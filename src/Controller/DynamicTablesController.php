<?php
declare(strict_types=1);

namespace App\Controller;

use RuntimeException;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException; 
use Cake\Http\Exception\ForbiddenException; 
use Cake\Http\Exception\UnauthorizedException; 
use Cake\Http\UploadedFile; 
use Cake\Log\Log; 
use Cake\Utility\Text;
use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\Database\Schema\TableSchema;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\I18n\FrozenTime;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * DynamicTables Controller
 */
class DynamicTablesController extends AppController
{
    protected $UserTables; 
    protected $ColumnAliases;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Flash'); // Đảm bảo Flash component được load
        $this->UserTables = $this->fetchTable('UserTables');
        $this->ColumnAliases = $this->fetchTable('ColumnAliases');
    }

    /**
     * Trang hiển thị danh sách các bảng do user tạo
     */
    public function index()
    {
        $this->request->allowMethod(['get']);
        $user = $this->Authentication->getIdentity();

        // Load ColumnAliases để hiển thị số cột, nếu cần
        $tables = $this->UserTables
            ->find()
            ->where(['user_id' => $user->id])
            ->all();

        $this->set(compact('tables'));

        if ($tables->isEmpty()) {
            $this->set('message', 'Bạn chưa tạo bảng nào.');
        }

        $this->viewBuilder()->setOption('serialize', ['tables', 'message']);
    }

    /**
     * Trang tạo bảng mới
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $originalName = $this->request->getData('table_name');
            if (empty($originalName)) {
                $this->Flash->error('Tên bảng không được để trống.');
                return $this->redirect(['action' => 'add']);
            }

            $user = $this->Authentication->getIdentity();
            $userId = $user?->get('id');
            $tableName = $this->standardizeTableName($originalName);

            $connection = ConnectionManager::get('default');
            
            try {
                $connection->begin();

                // Kiểm tra bảng đã tồn tại chưa
                $schemaCollection = $connection->getSchemaCollection(); // Đổi tên biến để tránh nhầm lẫn
                if (in_array($tableName, $schemaCollection->listTables())) {
                    throw new RuntimeException('Bảng đã tồn tại. Vui lòng chọn tên khác.');
                }

                // Tạo bảng với các cột mặc định VÀ cột sort_order cho dòng
                $sql = "
                    CREATE TABLE `{$tableName}` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `user_id` INT UNSIGNED NOT NULL,
                        `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
                        `modified` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `sort_order` INT NULL DEFAULT NULL, -- THÊM DÒNG NÀY ĐỂ TẠO CỘT SORT_ORDER CHO DÒNG
                        INDEX `user_id_idx` (`user_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ";
                $connection->execute($sql);

                // Ghi lại vào bảng user_tables
                $entity = $this->UserTables->newEntity([
                    'user_id' => $userId,
                    'table_name' => $tableName,
                    'original_name' => $originalName,
                    'status' => 0,
                    'is_active' => 1
                ]);
                
                if (!$this->UserTables->save($entity)) {
                    // Nếu lưu UserTables thất bại, Rollback việc tạo bảng
                    $connection->rollback(); // Đảm bảo rollback nếu có lỗi ở đây
                    throw new RuntimeException('Không thể lưu thông tin bảng vào hệ thống: ' . json_encode($entity->getErrors()));
                }

                $connection->commit();
                
                $this->Flash->success(__('Đã tạo bảng "{0}" thành công.', $originalName));
                return $this->redirect(['action' => 'index']);

            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('Lỗi khi tạo bảng: {0}', $e->getMessage()));
                return $this->redirect(['action' => 'index']);
            }
        }

    }
    public function view($tableName = null)
    {
        $user = $this->Authentication->getIdentity();
        $userTable = $this->UserTables->find()
            ->where([
                'user_id' => $user->id,
                'table_name' => $tableName
            ])
            ->first();

        if (!$userTable) {
            throw new NotFoundException(__d('cake', 'Không tìm thấy bảng.'));
        }

        // Lấy danh sách cột để biết alias và kiểu dữ liệu
        $columnAliases = $this->ColumnAliases->find()
            ->where(['user_table_id' => $userTable->id])
            ->order(['sort_order' => 'ASC'])
            ->all();

        // Lấy thể hiện của bảng động
        $dynamicTable = $this->_getDynamicTableInstance($tableName);

        // Định nghĩa các trường có thể sắp xếp
        $sortableFields = [];
        foreach ($columnAliases as $col) {
            // Đảm bảo $col->column_name tồn tại và loại trừ các kiểu dữ liệu không nên sắp xếp trực tiếp
            if (isset($col->column_name) && !in_array($col->data_type, ['file', 'text'])) {
                $sortableFields[] = $col->column_name;
            }
        }

        // --- ĐÂY LÀ PHẦN THAY ĐỔI QUAN TRỌNG NHẤT ---
        // PaginatorComponent đã bị loại bỏ.
        // Không còn sử dụng $this->Paginator->setSettings() nữa.
        // Thay vào đó, truyền các thiết lập phân trang trực tiếp vào đối số thứ hai của $this->paginate().

        $paginationSettings = [
            'limit' => 10,
            'maxLimit' => 50,
            'sortableFields' => $sortableFields, // Chỉ cho phép sắp xếp các cột này
            'order' => ['id' => 'ASC'], // Mặc định sắp xếp theo ID
            // 'finder' => 'all', // 'all' là finder mặc định khi truyền một Table instance, thường không cần thiết phải ghi rõ
        ];

        // Gọi $this->paginate() trực tiếp trên Table instance ($dynamicTable)
        // và truyền các thiết lập vào đối số thứ hai.
        $tableData = $this->paginate($dynamicTable, $paginationSettings);

        $this->set(compact('userTable', 'columnAliases', 'tableData', 'tableName'));
        $this->set('columnTypeMapping', Configure::read('ColumnTypeMapping'));
    }

    /**
     * Trang chỉnh sửa bảng (hiển thị cột, thêm/xóa/sửa/di chuyển cột)
     */
    public function edit(string $tableName)
    {
        $user = $this->Authentication->getIdentity();
        $table = $this->UserTables->find()
            ->where([
                'user_id' => $user->id,
                'table_name' => $tableName
            ])
            ->first();

        if (!$table) {
            throw new NotFoundException("Không tìm thấy bảng.");
        }

        $connection = ConnectionManager::get('default');
        $schema = $connection->getSchemaCollection();

        try {
            $columns = $schema->describe($tableName);
        } catch (\Exception $e) {
            throw new RuntimeException("Không thể lấy thông tin bảng: " . $e->getMessage());
        }

        // Mapping kiểu dữ liệu để hiển thị tiếng Việt (nếu cần)
        $typeMapping = Configure::read('ColumnTypeMapping');

        // Phân trang dữ liệu cột
        $query = $this->ColumnAliases->find()
            ->where(['user_table_id' => $table->id])
            ->order(['sort_order' => 'ASC']);

        $columnAliases = $this->paginate($query, [
            'limit' => 10,
            'maxLimit' => 50,
        ]);

        // Mảng thông tin đã xử lý để hiển thị
        $processedColumnDetails = [];
        foreach ($columnAliases as $col) {
            $processedColumnDetails[] = [
                'id' => $col->id,
                'column_name' => $col->column_name,
                'original_name' => $col->original_name,
                'original_type' => $col->original_type,
                'data_type' => $col->data_type,
                'null' => $col->null,
                'default' => $col->default,
                'sort_order' => $col->sort_order,
            ];
        }

        $this->set(compact(
            'table',
            'processedColumnDetails',
            'typeMapping',
            'columnAliases' // dùng cho phân trang
        ));

        $this->set('STATUS_OPTIONS', Configure::read('STATUS_OPTIONS'));
    }

    /**
     * Thêm cột mới vào bảng
     */
    public function addColumn($tableName)
    {
        // Lấy thông tin bảng chính
        $table = $this->UserTables->find()
            ->where(['table_name' => $tableName])
            ->firstOrFail();

        // Đọc ánh xạ kiểu cột từ cấu hình
        $typeMapping = Configure::read('ColumnTypeMapping'); 
        
        // Khởi tạo một entity ColumnAlias mới
        $columnAlias = $this->ColumnAliases->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_table_id'] = $table->id; 
            
            // Tìm sort_order lớn nhất cho ColumnAliases để gán giá trị mới
            $maxSortOrder = $this->ColumnAliases->find()
                ->where(['user_table_id' => $table->id])
                ->select(['sort_order'])
                ->order(['sort_order' => 'DESC'])
                ->first();

            $data['sort_order'] = ($maxSortOrder ? $maxSortOrder->sort_order : 0) + 1;
            
            // Chuẩn hóa tên cột hệ thống từ tên hiển thị
            $data['column_name'] = $this->normalizeColumnName($data['original_name']);

            // Patch entity với toàn bộ dữ liệu từ request
            $columnAlias = $this->ColumnAliases->patchEntity($columnAlias, $data);

            // Kiểm tra lỗi validation của entity
            if ($columnAlias->hasErrors()) {
                $this->Flash->error('Dữ liệu cột không hợp lệ. Vui lòng kiểm tra lại.');
                foreach ($columnAlias->getErrors() as $field => $errors) {
                    foreach ($errors as $error) {
                        $this->Flash->error("Lỗi trường '{$field}': {$error}");
                    }
                }
                return $this->redirect($this->referer());
            }

            // Kiểm tra logic nghiệp vụ bổ sung cho tên hiển thị và tên cột hệ thống
            if (empty(trim($columnAlias->original_name))) { 
                $this->Flash->error('Vui lòng nhập tên hiển thị.');
                return $this->redirect($this->referer());
            }

            if (strlen($columnAlias->column_name) < 3) { 
                $this->Flash->error('Tên cột hệ thống quá ngắn (tối thiểu 3 ký tự).');
                return $this->redirect($this->referer());
            }

            // Lấy kết nối cơ sở dữ liệu và driver
            $connection = ConnectionManager::get('default');
            $driver = $connection->getDriver();
            $alterSql = ''; // Biến để lưu câu lệnh SQL phục vụ ghi log khi có lỗi

            try {
                $connection->begin(); // Bắt đầu transaction

                // Kiểm tra xem cột vật lý đã tồn tại trong bảng dữ liệu chưa
                if ($this->columnExists($tableName, $columnAlias->column_name)) {
                    throw new RuntimeException('Tên cột hệ thống đã tồn tại trong bảng dữ liệu. Vui lòng chọn tên khác.');
                }

                // Lấy thông tin kiểu dữ liệu và thuộc tính từ entity
                $columnDataType = $columnAlias->data_type; 
                $sqlDataTypeDefinition = ''; // Định nghĩa kiểu dữ liệu trong SQL
                $length = null;
                $isNullable = (bool)$columnAlias->null;
                $defaultValue = $columnAlias->default;

                // Xác định kiểu dữ liệu SQL và độ dài (nếu có)
                switch ($columnDataType) {
                    case 'varchar':
                    case 'email':
                    case 'file': // Ví dụ: file được lưu tên dưới dạng varchar
                        $sqlDataTypeDefinition = 'VARCHAR';
                        $length = 255;
                        break;
                    case 'text':
                        $sqlDataTypeDefinition = 'TEXT';
                        break;
                    case 'integer':
                    case 'tinyint':
                        $sqlDataTypeDefinition = 'INT';
                        break;
                    case 'float':
                        $sqlDataTypeDefinition = 'FLOAT';
                        break;
                    case 'date':
                        $sqlDataTypeDefinition = 'DATE';
                        break;
                    case 'datetime':
                        $sqlDataTypeDefinition = 'DATETIME';
                        break;
                    default:
                        $sqlDataTypeDefinition = 'VARCHAR';
                        $length = 255; // Mặc định an toàn cho các kiểu không xác định
                        break;
                }

                // Bắt đầu xây dựng câu lệnh ALTER TABLE
                $quotedTable = $driver->quoteIdentifier($tableName);
                $quotedColumn = $driver->quoteIdentifier($columnAlias->column_name);
                
                $sqlBuilder = ["ALTER TABLE {$quotedTable} ADD COLUMN {$quotedColumn}"];

                // Thêm kiểu dữ liệu và độ dài (nếu có)
                if ($length) {
                    $sqlBuilder[] = "{$sqlDataTypeDefinition}({$length})";
                } else {
                    $sqlBuilder[] = "{$sqlDataTypeDefinition}";
                }

                // Thêm thuộc tính NULL / NOT NULL
                $sqlBuilder[] = $isNullable ? "NULL" : "NOT NULL";

                // Thêm giá trị mặc định (DEFAULT)
                // Cần xử lý cẩn thận vì MariaDB/MySQL có hạn chế cho TEXT/BLOB NOT NULL
                if ($defaultValue !== null && (string)$defaultValue !== '') { // Nếu người dùng có nhập giá trị mặc định
                    if ($columnDataType === 'text' && !$isNullable) {
                        // Cột TEXT NOT NULL không thể có DEFAULT trong MariaDB/MySQL
                        $this->Flash->warning('Cột TEXT NOT NULL không thể có giá trị mặc định trong MySQL/MariaDB. Giá trị mặc định sẽ bị bỏ qua.');
                    } else {
                        // Xử lý giá trị đặc biệt CURRENT_TIMESTAMP cho DATETIME
                        if (strtolower((string)$defaultValue) === 'current_timestamp' && $columnDataType === 'datetime') {
                            $sqlBuilder[] = "DEFAULT CURRENT_TIMESTAMP";
                        } else {
                            // Trích dẫn giá trị nếu là chuỗi, không trích dẫn nếu là số
                            if (in_array($columnDataType, ['varchar', 'text', 'email', 'file', 'date', 'datetime'])) {
                                $sqlBuilder[] = "DEFAULT " . $connection->quote((string)$defaultValue);
                            } else { // integer, float, tinyint - là các kiểu số
                                $sqlBuilder[] = "DEFAULT " . (is_numeric($defaultValue) ? $defaultValue : $connection->quote((string)$defaultValue)); 
                            }
                        }
                    }
                } elseif (!$isNullable) { 
                    // Nếu cột là NOT NULL nhưng người dùng không cung cấp giá trị mặc định
                    // Và không phải kiểu TEXT (vì TEXT NOT NULL đã được xử lý riêng)
                    if ($columnDataType !== 'text') { 
                        switch ($sqlDataTypeDefinition) {
                            case 'VARCHAR':
                                $sqlBuilder[] = "DEFAULT ''"; // Mặc định rỗng cho VARCHAR NOT NULL
                                break;
                            case 'INT':
                            case 'FLOAT':
                                $sqlBuilder[] = "DEFAULT 0"; // Mặc định 0 cho số NOT NULL
                                break;
                            // Đối với DATE/DATETIME NOT NULL không có DEFAULT, DB sẽ quyết định
                            // (thường sẽ là lỗi nếu strict mode bật và không có giá trị được cung cấp khi INSERT)
                        }
                    }
                }
                
                // Ghép nối các phần để tạo câu lệnh SQL hoàn chỉnh
                $alterSql = implode(' ', $sqlBuilder) . ";";

                // Thực thi câu lệnh ALTER TABLE để thêm cột vật lý vào database
                $connection->execute($alterSql);

                // Lưu thông tin cột vào bảng column_aliases (entity đã được patch ở trên)
                if (!$this->ColumnAliases->save($columnAlias)) {
                    $connection->rollback(); // Rollback thay đổi DB nếu lưu entity thất bại
                    throw new RuntimeException('Lỗi khi lưu thông tin cột vào hệ thống: ' . json_encode($columnAlias->getErrors()));
                }

                // Xóa cache schema của bảng để CakePHP đọc lại cấu trúc mới nhất
                Cache::delete($tableName, '_cake_model_');
                // Tùy chọn: Buộc làm mới schema trong bộ nhớ của connection (CakePHP 4+)
                $connection->getSchemaCollection()->describe($tableName, ['forceRefresh' => true]);
                
                $connection->commit(); // Hoàn tất transaction

                $this->Flash->success('Cột mới đã được thêm.');
                return $this->redirect(['action' => 'edit', $tableName]);

            } catch (\Exception $e) {
                $connection->rollback(); // Rollback tất cả thay đổi nếu có lỗi
                // Ghi log chi tiết lỗi, bao gồm cả câu lệnh SQL gây lỗi
                Log::error("Lỗi khi thêm cột vào bảng '{$tableName}': " . $e->getMessage() . "\nSQL: {$alterSql}");
                $this->Flash->error('Lỗi khi thêm cột: ' . $e->getMessage() . ' Vui lòng kiểm tra log để biết chi tiết.');
                return $this->redirect($this->referer());
            }
        }

        // Đặt biến cho view khi lần đầu truy cập (GET request)
        $this->set(compact('table', 'typeMapping', 'columnAlias')); 
    }




    /**
     * Xóa một cột khỏi bảng vật lý và alias
     */
    public function deleteColumnAlias($columnAliasId = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try {
            $columnAlias = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
            $userTable = $columnAlias->user_table;

            if (!$userTable) {
                throw new NotFoundException('Không tìm thấy bảng gốc.');
            }

            $connection = ConnectionManager::get('default');
            $driver = $connection->getDriver();
            $quotedTableName = $driver->quoteIdentifier($userTable->table_name);
            $quotedColumnName = $driver->quoteIdentifier($columnAlias->column_name);

            $connection->begin();

            // 1. Xóa cột khỏi bảng vật lý
            $sql = "ALTER TABLE {$quotedTableName} DROP COLUMN {$quotedColumnName}";
            $connection->execute($sql);

            // 2. Lưu lại sort_order trước khi xóa
            $deletedSortOrder = $columnAlias->sort_order;

            // 3. Xóa bản ghi alias
            if (!$this->ColumnAliases->delete($columnAlias)) {
                throw new RuntimeException('Không thể xóa thông tin cột khỏi hệ thống.');
            }

            // 4. Cập nhật sort_order cho các cột còn lại (SỬA LỖI Ở ĐÂY)
            $this->ColumnAliases->updateAll(
                ['sort_order' => $this->ColumnAliases->query()->newExpr('sort_order - 1')],
                [
                    'user_table_id' => $userTable->id,
                    'sort_order >' => $deletedSortOrder
                ]
            );

            // 5. Clear cache
            Cache::delete($userTable->table_name, '_cake_model_');
            // Force refresh the schema to verify
            $schema = $connection->getSchemaCollection();
            $tableSchema = $schema->describe($userTable->table_name, ['forceRefresh' => true]);
            
            $this->Flash->success('Cột đã được xóa thành công.');
        } catch (NotFoundException $e) {
            $this->Flash->error('Không tìm thấy cột hoặc bảng: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (isset($connection)) {
                $connection->rollback();
            }
            $this->Flash->error('Có lỗi xảy ra khi xóa cột: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'edit', $userTable->table_name ?? '']);
    }

    /**
     * Chỉnh sửa tên hiển thị và kiểu hiển thị của cột alias
     */
    public function editColumnAlias($columnAliasId = null)
    {
        // Lấy ColumnAlias entity dựa trên ID, bao gồm thông tin UserTable liên quan
        $columnAlias = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
        if (!$columnAlias) {
            throw new NotFoundException('Không tìm thấy cột để chỉnh sửa.');
        }

        // Lấy ánh xạ kiểu cột để hiển thị trong form
        $typeMapping = Configure::read('ColumnTypeMapping'); 
        
        // Chỉ lấy các key để làm options cho select box (varchar, int, text...)
        $dbTypeOptions = array_combine(array_keys($typeMapping), array_keys($typeMapping));

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Lấy các giá trị cũ của cột trước khi patch để so sánh thay đổi
            $oldColumnName = $columnAlias->column_name;
            $oldOriginalName = $columnAlias->original_name;
            $oldDataType = $columnAlias->data_type;
            $oldIsNullable = $columnAlias->null;
            $oldDefaultValue = $columnAlias->default;

            // Patch entity với dữ liệu mới từ form
            // Cho phép patch tất cả các trường liên quan đến cấu hình cột
            $columnAlias = $this->ColumnAliases->patchEntity($columnAlias, $data);

            // Kiểm tra validator của entity
            if ($columnAlias->hasErrors()) {
                $this->Flash->error('Dữ liệu cột không hợp lệ. Vui lòng kiểm tra lại.');
                foreach ($columnAlias->getErrors() as $field => $errors) {
                    foreach ($errors as $error) {
                        $this->Flash->error("Lỗi trường '{$field}': {$error}");
                    }
                }
                // Giữ lại columnAlias để form hiển thị lại dữ liệu đã nhập
                $this->set(compact('columnAlias', 'typeMapping', 'dbTypeOptions'));
                return; // Không redirect, hiển thị lại form
            }

            // Kiểm tra logic nghiệp vụ cho tên hiển thị
            if (empty(trim($columnAlias->original_name))) {
                $this->Flash->error('Vui lòng nhập tên hiển thị.');
                $this->set(compact('columnAlias', 'typeMapping', 'dbTypeOptions'));
                return;
            }

            $connection = ConnectionManager::get('default');
            $driver = $connection->getDriver();
            $tableName = $columnAlias->user_table->table_name;
            $alterSql = ''; // Biến để lưu câu lệnh SQL phục vụ ghi log khi có lỗi

            try {
                $connection->begin(); // Bắt đầu transaction

                // Cập nhật tên cột hệ thống nếu original_name thay đổi
                $newColumnName = $this->normalizeColumnName($columnAlias->original_name);
                if ($newColumnName !== $columnAlias->column_name) {
                    // Kiểm tra tên cột mới có bị trùng không
                    if ($this->columnExists($tableName, $newColumnName)) {
                        throw new RuntimeException("Tên cột hệ thống '{$newColumnName}' đã tồn tại trong bảng dữ liệu. Vui lòng chọn tên khác.");
                    }
                    // Cập nhật column_name trong entity
                    $columnAlias->column_name = $newColumnName;
                }

                // Kiểm tra xem có cần ALTER TABLE không
                $needsAlterTable = (
                    $columnAlias->data_type !== $oldDataType ||
                    $columnAlias->null !== $oldIsNullable ||
                    $columnAlias->default !== $oldDefaultValue ||
                    $columnAlias->column_name !== $oldColumnName // Đổi tên cột
                );

                if ($needsAlterTable) {
                    // Xây dựng câu lệnh ALTER TABLE để MODIFY COLUMN
                    $quotedTable = $driver->quoteIdentifier($tableName);
                    $quotedOldColumn = $driver->quoteIdentifier($oldColumnName);
                    $quotedNewColumn = $driver->quoteIdentifier($columnAlias->column_name);

                    $sqlDataTypeDefinition = '';
                    $length = null;

                    // Xác định kiểu dữ liệu SQL và độ dài (tương tự addColumn)
                    switch ($columnAlias->data_type) {
                        case 'varchar':
                        case 'email':
                        case 'file':
                            $sqlDataTypeDefinition = 'VARCHAR';
                            $length = 255;
                            break;
                        case 'text':
                            $sqlDataTypeDefinition = 'TEXT';
                            break;
                        case 'integer':
                        case 'tinyint':
                            $sqlDataTypeDefinition = 'INT';
                            break;
                        case 'float':
                            $sqlDataTypeDefinition = 'FLOAT';
                            break;
                        case 'date':
                            $sqlDataTypeDefinition = 'DATE';
                            break;
                        case 'datetime':
                            $sqlDataTypeDefinition = 'DATETIME';
                            break;
                        default:
                            $sqlDataTypeDefinition = 'VARCHAR';
                            $length = 255; 
                            break;
                    }

                    $columnDefinitionParts = [];
                    if ($columnAlias->column_name !== $oldColumnName) {
                         // Sử dụng CHANGE COLUMN nếu đổi tên cột
                        $columnDefinitionParts[] = "CHANGE COLUMN {$quotedOldColumn} {$quotedNewColumn}";
                    } else {
                        // Sử dụng MODIFY COLUMN nếu không đổi tên cột
                        $columnDefinitionParts[] = "MODIFY COLUMN {$quotedNewColumn}";
                    }

                    if ($length) {
                        $columnDefinitionParts[] = "{$sqlDataTypeDefinition}({$length})";
                    } else {
                        $columnDefinitionParts[] = "{$sqlDataTypeDefinition}";
                    }

                    $columnDefinitionParts[] = ($columnAlias->null) ? "NULL" : "NOT NULL";

                    // Thêm giá trị mặc định
                    if ($columnAlias->default !== null && (string)$columnAlias->default !== '') { 
                        if ($columnAlias->data_type === 'text' && !$columnAlias->null) {
                            $this->Flash->warning('Cột TEXT NOT NULL không thể có giá trị mặc định trong MySQL/MariaDB khi sửa đổi. Giá trị mặc định sẽ bị bỏ qua.');
                            // Không thêm DEFAULT vào SQL
                        } else {
                            if (strtolower((string)$columnAlias->default) === 'current_timestamp' && $columnAlias->data_type === 'datetime') {
                                $columnDefinitionParts[] = "DEFAULT CURRENT_TIMESTAMP";
                            } else {
                                if (in_array($columnAlias->data_type, ['varchar', 'text', 'email', 'file', 'date', 'datetime'])) {
                                    $columnDefinitionParts[] = "DEFAULT " . $connection->quote((string)$columnAlias->default);
                                } else {
                                    $columnDefinitionParts[] = "DEFAULT " . (is_numeric($columnAlias->default) ? $columnAlias->default : $connection->quote((string)$columnAlias->default)); 
                                }
                            }
                        }
                    } elseif (!$columnAlias->null) { 
                        // Nếu NOT NULL và không có default được cung cấp từ form, và không phải TEXT
                        if ($columnAlias->data_type !== 'text') { 
                            switch ($sqlDataTypeDefinition) {
                                case 'VARCHAR':
                                    $columnDefinitionParts[] = "DEFAULT ''"; 
                                    break;
                                case 'INT':
                                case 'FLOAT':
                                    $columnDefinitionParts[] = "DEFAULT 0"; 
                                    break;
                            }
                        }
                    } else { // Nếu là NULL và không có default được cung cấp, hãy đảm bảo không có DEFAULT
                        $columnDefinitionParts[] = "DEFAULT NULL"; // Đảm bảo rõ ràng DEFAULT NULL cho cột NULLable không có default
                    }
                    
                    $alterSql = "ALTER TABLE {$quotedTable} " . implode(' ', $columnDefinitionParts) . ";";
                    $connection->execute($alterSql);
                } // End if needsAlterTable

                // Lưu thông tin ColumnAlias vào database
                if ($this->ColumnAliases->save($columnAlias)) {
                    // Chỉ xóa cache nếu có thay đổi cấu trúc bảng
                    if ($needsAlterTable) {
                        Cache::delete($tableName, '_cake_model_');
                        $connection->getSchemaCollection()->describe($tableName, ['forceRefresh' => true]);
                    }
                    $connection->commit(); // Hoàn tất transaction
                    $this->Flash->success('Thông tin cột đã được cập nhật.');
                    return $this->redirect(['action' => 'edit', $tableName]);
                } else {
                    $connection->rollback(); // Rollback nếu lưu ColumnAlias thất bại
                    throw new RuntimeException('Lỗi khi lưu thông tin cột vào hệ thống: ' . json_encode($columnAlias->getErrors()));
                }

            } catch (\Exception $e) {
                $connection->rollback(); // Rollback mọi thứ nếu có lỗi
                Log::error("Lỗi khi cập nhật cột '{$columnAlias->original_name}' trong bảng '{$tableName}': " . $e->getMessage() . "\nSQL: {$alterSql}");
                $this->Flash->error('Lỗi khi cập nhật cột: ' . $e->getMessage() . ' Vui lòng kiểm tra log để biết chi tiết.');
                // Giữ lại columnAlias để form hiển thị lại dữ liệu đã nhập
                $this->set(compact('columnAlias', 'typeMapping', 'dbTypeOptions'));
                return; // Không redirect, hiển thị lại form
            }
        }

        // Đặt biến cho view (cho GET request hoặc POST/PUT thất bại)
        $this->set(compact('columnAlias', 'typeMapping', 'dbTypeOptions'));
    }




    /**
     * Xem chi tiết cột (có thể dùng trong modal)
     */
    public function viewColumnAlias($columnAliasId = null)
    {
        $columnAlias = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
        if (!$columnAlias) {
            throw new NotFoundException('Không tìm thấy cột.');
        }

        // Lấy tên hiển thị đầy đủ của kiểu dữ liệu
        $typeMapping = Configure::read('ColumnTypeMapping');
        $columnAlias->original_type_display = $typeMapping[$columnAlias->original_type] ?? $columnAlias->original_type;

        if ($this->request->is('ajax')) {
            $this->set(compact('columnAlias'));
            $this->viewBuilder()->setLayout('ajax'); // Sử dụng layout ajax cho modal
            $this->render('/Element/view_column_alias_modal'); // Render một element cho modal
        } else {
            // Nếu truy cập trực tiếp, chuyển hướng về trang edit
            return $this->redirect(['action' => 'edit', $columnAlias->user_table->table_name]);
        }
    }

    /**
     * Di chuyển cột lên trong danh sách (thay đổi sort_order)
     */
    public function moveColumnUp($columnAliasId = null)
    {
        $this->request->allowMethod(['post']);
        $connection = ConnectionManager::get('default'); // Lấy connection ở đây để có thể begin/rollback

        try {
            $connection->begin(); // Bắt đầu transaction

            $currentColumn = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
            $userTable = $currentColumn->user_table;
            $userTableId = $currentColumn->user_table_id;

            // Tìm cột ngay phía trên (có sort_order nhỏ hơn nhưng lớn nhất có thể)
            $prevColumn = $this->ColumnAliases->find()
                ->where([
                    'user_table_id' => $userTableId,
                    'sort_order <' => $currentColumn->sort_order
                ])
                ->order(['sort_order' => 'DESC'])
                ->first();

            if ($prevColumn) {
                // Hoán đổi sort_order
                $tempOrder = $currentColumn->sort_order;
                $currentColumn->sort_order = $prevColumn->sort_order;
                $prevColumn->sort_order = $tempOrder;

                // Lưu thay đổi
                $this->ColumnAliases->save($currentColumn);
                $this->ColumnAliases->save($prevColumn);

                $connection->commit(); // Hoàn tất transaction
                $this->Flash->success('Đã di chuyển cột lên thành công.');
            } else {
                $connection->rollback(); // Rollback nếu không có gì để di chuyển
                $this->Flash->error('Cột đã ở vị trí đầu tiên.');
            }
        } catch (NotFoundException $e) { // Đảm bảo bắt đúng NotFoundException
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error('Không tìm thấy cột: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            Log::error("Move column up error: " . $e->getMessage());
            $this->Flash->error('Có lỗi xảy ra khi di chuyển cột: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'edit', $userTable->table_name ?? '']);
    }

    /**
     * Di chuyển cột xuống trong danh sách (thay đổi sort_order)
     */
    public function moveColumnDown($columnAliasId = null)
    {
        $this->request->allowMethod(['post']);
        $connection = ConnectionManager::get('default'); // Lấy connection ở đây để có thể begin/rollback

        try {
            $connection->begin(); // Bắt đầu transaction

            $currentColumn = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
            $userTable = $currentColumn->user_table;
            $userTableId = $currentColumn->user_table_id;

            // Tìm cột ngay phía dưới (có sort_order lớn hơn nhưng nhỏ nhất có thể)
            $nextColumn = $this->ColumnAliases->find()
                ->where([
                    'user_table_id' => $userTableId,
                    'sort_order >' => $currentColumn->sort_order
                ])
                ->order(['sort_order' => 'ASC'])
                ->first();

            if ($nextColumn) {
                // Hoán đổi sort_order
                $tempOrder = $currentColumn->sort_order;
                $currentColumn->sort_order = $nextColumn->sort_order;
                $nextColumn->sort_order = $tempOrder;

                // Lưu thay đổi
                $this->ColumnAliases->save($currentColumn);
                $this->ColumnAliases->save($nextColumn);

                $connection->commit(); // Hoàn tất transaction
                $this->Flash->success('Đã di chuyển cột xuống thành công.');
            } else {
                $connection->rollback(); // Rollback nếu không có gì để di chuyển
                $this->Flash->error('Cột đã ở vị trí cuối cùng.');
            }
        } catch (NotFoundException $e) { // Đảm bảo bắt đúng NotFoundException
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error('Không tìm thấy cột: ' . $e->getMessage());
        } catch (\Exception $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            Log::error("Move column down error: " . $e->getMessage());
            $this->Flash->error('Có lỗi xảy ra khi di chuyển cột: ' . $e->getMessage());
        }

        return $this->redirect(['action' => 'edit', $userTable->table_name ?? '']);
    }

    /**
     * Kiểm tra xem cột có tồn tại trong bảng vật lý không
     */
    private function columnExists($tableName, $columnName): bool
    {
        $connection = ConnectionManager::get('default');
        $schema = $connection->getSchemaCollection();
        
        // Đảm bảo lấy schema mới nhất (forceRefresh)
        $tableSchema = $schema->describe($tableName, ['forceRefresh' => true]);
        return in_array($columnName, $tableSchema->columns());
    }

    /**
     * Chuẩn hóa tên cột để dùng trong database
     */
    private function normalizeColumnName(string $name): string
    {
        $name = mb_strtolower($name, 'UTF-8');
        $name = Text::slug($name, ['replacement' => '_']); // Sử dụng Text::slug cho hiệu quả tốt hơn
        
        // Đảm bảo không bắt đầu bằng số
        if (preg_match('/^[0-9]/', $name)) {
            $name = '_' . $name;
        }

        // Cắt ngắn nếu quá dài, hoặc tạo tên viết tắt
        if (strlen($name) > 60) { // Giới hạn hợp lý cho tên cột MySQL
            // Thử rút gọn bằng cách lấy chữ cái đầu của mỗi từ
            $words = explode('_', $name);
            $shortName = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $shortName .= substr($word, 0, 1);
                }
            }
            if (strlen($shortName) < 3) { // Đảm bảo tên rút gọn không quá ngắn
                 $shortName = substr($name, 0, 60); // Nếu rút gọn quá ngắn, cắt từ đầu
            }
            $name = $shortName;
        }
        
        return $name;
    }

    public function addRow(string $tableName)
    {
        $user = $this->Authentication->getIdentity();
        if (!$user) {
            // Đảm bảo user đăng nhập, nếu không thì không thể gán user_id
            $this->Flash->error(__('Bạn cần đăng nhập để thêm dòng.'));
            return $this->redirect(['action' => 'index']);
        }

        $table = $this->UserTables->find()
            ->where(['user_id' => $user->id, 'table_name' => $tableName])
            ->firstOrFail();

        // Lấy dynamicTable instance
        $dynamicTable = $this->_getDynamicTableInstance($tableName);
        $entity = $dynamicTable->newEmptyEntity(); // Tạo entity rỗng cho bảng động

        $columns = $this->ColumnAliases->find()
            ->where(['user_table_id' => $table->id])
            ->orderAsc('sort_order')
            ->all();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Xử lý chuyển đổi định dạng ngày tháng
            foreach ($columns as $col) {
                if (in_array($col->data_type, ['date', 'datetime'])) {
                    $field = $col->column_name;
                    if (!empty($data[$field])) {
                        if ($col->data_type === 'date') {
                            $date = \DateTime::createFromFormat('d/m/Y', $data[$field]);
                            $data[$field] = $date ? $date->format('Y-m-d') : null;
                        } else {
                            $datetime = \DateTime::createFromFormat('d/m/Y H:i', $data[$field]);
                            $data[$field] = $datetime ? $datetime->format('Y-m-d H:i:s') : null;
                        }
                    }
                }
            }

            // Xử lý file upload
            foreach ($columns as $col) {
                $name = $col->column_name;
                if ($col->data_type === 'file') {
                    $file = $this->request->getData($name); // Lấy UploadedFileInterface
                    if ($file instanceof UploadedFileInterface && $file->getError() === UPLOAD_ERR_OK) {
                        $filename = uniqid() . '_' . $file->getClientFilename();
                        $path = WWW_ROOT . 'uploads' . DS . $filename;
                        $file->moveTo($path);
                        $data[$name] = $filename;
                    } else {
                        $data[$name] = null;
                    }
                }
            }

            // GÁN user_id của người dùng hiện tại
            $data['user_id'] = $user->id;

            // Tính toán và gán sort_order mới
            $maxSortOrder = $dynamicTable->find()->select(['sort_order'])->order(['sort_order' => 'DESC'])->first();
            $data['sort_order'] = ($maxSortOrder && $maxSortOrder->sort_order !== null ? $maxSortOrder->sort_order : 0) + 1;


            // Patch dữ liệu vào entity
            $entity = $dynamicTable->patchEntity($entity, $data);

            // Lưu entity
            if ($dynamicTable->save($entity)) {
                $this->Flash->success('Đã thêm dòng mới.');
                return $this->redirect(['action' => 'view', $tableName]);
            } else {
                // Lấy lỗi từ entity để hiển thị chi tiết hơn nếu có lỗi validation
                $errors = $entity->getErrors();
                $errorMessage = 'Có lỗi khi lưu dữ liệu vào bảng.';
                if (!empty($errors)) {
                    foreach ($errors as $field => $fieldErrors) {
                        foreach ($fieldErrors as $error) {
                            $errorMessage .= " Lỗi trường '{$field}': {$error}.";
                        }
                    }
                }
                $this->Flash->error($errorMessage);
            }
        }

        // Chuẩn bị dữ liệu cho view
        $preparedCols = [];
        foreach ($columns as $col) {
            $preparedCols[] = [
                'name' => $col->column_name,
                'label' => $col->original_name,
                'type' => $this->mapInputType($col->data_type),
            ];
        }

        $this->set(compact('table', 'columns', 'entity')); // entity ở đây là entity rỗng hoặc entity đã patch nhưng không lưu được
        $this->set('columns', $preparedCols);
        $this->set('entity', $entity); // Gán entity đã patch (nếu có lỗi save) để form hiển thị lại dữ liệu
    }

    public function editRow(string $tableName, $id)
    {
        // 1. Xác thực người dùng
        $user = $this->Authentication->getIdentity();
        if (!$user) {
            throw new UnauthorizedException('Bạn cần đăng nhập để thực hiện thao tác này.');
        }

        // 2. Lấy thông tin bảng từ UserTables
        $table = $this->UserTables->find()
            ->where([
                'user_id' => $user->id,
                'table_name' => $tableName
            ])
            ->firstOrFail();

        // 3. Lấy danh sách cột từ ColumnAliases (để hiển thị label, kiểu input và xử lý file/date)
        $columnAliases = $this->ColumnAliases->find()
            ->where(['user_table_id' => $table->id])
            ->orderAsc('sort_order')
            ->all();

        // 4. Lấy Table Object của bảng động (RẤT QUAN TRỌNG CHO FORM HELPER)
        // Dùng TableRegistry::getTableLocator()->get() với className và table để làm việc với bảng động
        $DynamicTable = TableRegistry::getTableLocator()->get($tableName, [
            'className' => \Cake\ORM\Table::class, // Sử dụng Table cơ bản
            'table' => $tableName // Chỉ định tên bảng vật lý
        ]);

        // 5. Lấy dữ liệu hiện tại (entity) của bản ghi cần chỉnh sửa
        try {
            // Dùng get() của Table Object để lấy entity
            $entity = $DynamicTable->get($id);
        } catch (NotFoundException $e) {
            throw new NotFoundException("Không tìm thấy bản ghi với ID {$id} trong bảng {$tableName}.");
        } catch (\Exception $e) {
            Log::error("Lỗi khi lấy bản ghi từ bảng động '{$tableName}': " . $e->getMessage());
            throw new InternalErrorException('Lỗi hệ thống khi tải dữ liệu bản ghi.');
        }

        // Lưu trữ giá trị ban đầu của các trường file để xóa file cũ nếu có file mới
        $originalFileValues = [];
        foreach ($columnAliases as $col) {
            if ($this->mapInputType($col->data_type) === 'file') {
                $originalFileValues[$col->column_name] = $entity->get($col->column_name);
            }
        }

        // 6. Xử lý khi submit form
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData(); // Lấy tất cả dữ liệu được gửi đi
            $uploadErrors = [];

            // Xử lý các file được upload trước
            foreach ($columnAliases as $col) {
                $field = $col->column_name;
                if ($this->mapInputType($col->data_type) === 'file') {
                    $file = $this->request->getData($field); // Lấy đối tượng UploadedFile (hoặc null nếu trường không tồn tại)

                    // QUAN TRỌNG: Đảm bảo $data[$field] luôn là một chuỗi hoặc null sau khi xử lý
                    // Mặc định gán giá trị hiện tại của entity nếu không có file mới được tải lên
                    $data[$field] = $entity->get($field); // Bắt đầu với tên file hiện có

                    if ($file instanceof UploadedFile) { // Sử dụng class UploadedFile đã được import
                        if ($file->getError() === UPLOAD_ERR_OK) {
                            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                            $maxSize = 2 * 1024 * 1024; // 2MB

                            if (in_array($file->getClientMediaType(), $allowedTypes)) {
                                if ($file->getSize() <= $maxSize) {
                                    $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                                    $filename = uniqid() . '.' . $extension;
                                    $uploadPath = WWW_ROOT . 'uploads' . DS . $filename;

                                    // Xóa file cũ nếu có file mới được tải lên thành công và file cũ tồn tại
                                    if (!empty($originalFileValues[$field]) && file_exists(WWW_ROOT . 'uploads' . DS . $originalFileValues[$field])) {
                                        @unlink(WWW_ROOT . 'uploads' . DS . $originalFileValues[$field]);
                                    }

                                    $file->moveTo($uploadPath);
                                    $data[$field] = $filename; // Cập nhật $data với tên file mới (chuỗi)
                                } else {
                                    $uploadErrors[] = sprintf(
                                        "File '%s' vượt quá kích thước cho phép (2MB). Kích thước hiện tại: %.2fMB",
                                        $file->getClientFilename(),
                                        $file->getSize() / (1024 * 1024)
                                    );
                                    // Giữ nguyên giá trị cũ trong $data[$field] nếu có lỗi kích thước
                                }
                            } else {
                                $uploadErrors[] = sprintf(
                                    "File '%s' không đúng định dạng. Định dạng nhận được: %s",
                                    $file->getClientFilename(),
                                    $file->getClientMediaType()
                                );
                                // Giữ nguyên giá trị cũ trong $data[$field] nếu có lỗi định dạng
                            }
                        } else if ($file->getError() === UPLOAD_ERR_NO_FILE) {
                            // Không có file nào được tải lên cho trường này, giữ nguyên giá trị hiện tại
                            // (đã được gán ở đầu vòng lặp: $data[$field] = $entity->get($field);).
                            // Nếu bạn có checkbox "xóa file", bạn sẽ cần thêm logic ở đây để đặt $data[$field] thành null.
                        } else {
                            // Các lỗi upload khác (ví dụ: UPLOAD_ERR_PARTIAL, UPLOAD_ERR_INI_SIZE,...)
                            $uploadErrors[] = sprintf("Lỗi upload file '%s': Mã lỗi %d", $file->getClientFilename(), $file->getError());
                            // Giữ nguyên giá trị cũ trong $data[$field] nếu có lỗi upload khác
                        }
                    }
                    // Nếu $file không phải là UploadedFile object (ví dụ: trường input không phải file, hoặc bị null),
                    // thì $data[$field] đã được gán giá trị cũ của entity, không cần thay đổi.
                }
            }

            // 7. Thực hiện patch entity với dữ liệu đã xử lý
            // Lúc này $data[$field] cho các trường file đã là chuỗi tên file hoặc null,
            // không còn là đối tượng UploadedFile nữa.
            $entity = $DynamicTable->patchEntity($entity, $data);

            // 8. Thực hiện update nếu không có lỗi upload và không có lỗi validation của entity
            if (empty($uploadErrors)) {
                if ($DynamicTable->save($entity)) { // Dùng save() của Table Object
                    $this->Flash->success('Cập nhật dữ liệu thành công!');
                    return $this->redirect(['action' => 'view', $tableName]);
                } else {
                    // Lỗi validation từ entity (ví dụ: trường required bị thiếu) sẽ được hiển thị
                    $this->Flash->error('Không thể cập nhật dữ liệu. Vui lòng kiểm tra các lỗi.');
                }
            } else {
                // Hiển thị các lỗi liên quan đến upload file
                foreach ($uploadErrors as $error) {
                    $this->Flash->error($error);
                }
            }
        }

        // 9. Chuẩn bị dữ liệu cho view (map DB type sang kiểu input HTML)
        $columns = [];
        foreach ($columnAliases as $col) {
            $columns[] = [
                'name' => $col->column_name,
                'label' => $col->original_name,
                'type' => $this->mapInputType($col->data_type),
                'required' => !$col->null, // Nếu cột không được phép NULL thì mặc định là required
                'options' => $col->options ? json_decode($col->options, true) : null
            ];
        }

        // 10. Truyền dữ liệu xuống view
        $this->set(compact('table', 'columns', 'entity'));
        $this->set('isEdit', true);
    }

    public function moveRowUp($tableName = null, $id = null)
    {
        $this->request->allowMethod(['post']);
        $this->_checkTablePermission($tableName);

        $connection = ConnectionManager::get('default'); // Lấy connection ở đây để có thể begin/rollback

        try {
            $connection->begin(); // Bắt đầu transaction
            
            $dynamicTable = $this->_getDynamicTableInstance($tableName);
            $currentRow = $dynamicTable->get($id, ['fields' => ['id', 'sort_order']]);
            
            $prevRow = $dynamicTable->find()
                ->select(['id', 'sort_order'])
                ->where(['sort_order <' => $currentRow->sort_order])
                ->order(['sort_order' => 'DESC'])
                ->first();
                
            if ($prevRow) {
                $this->_swapSortOrders($dynamicTable, $currentRow, $prevRow);
                $connection->commit();
                $this->Flash->success(__('Dòng đã được di chuyển lên.'));
            } else {
                $connection->rollback(); // Rollback nếu không có gì để di chuyển
                $this->Flash->info(__('Dòng đã ở vị trí đầu tiên.'));
            }
        } catch (NotFoundException $e) { // Sửa từ RecordNotFoundException
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error(__('Không tìm thấy dòng.'));
        } catch (\Exception $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            Log::error("Move row up error: " . $e->getMessage());
            $this->Flash->error(__('Đã xảy ra lỗi khi di chuyển dòng.'));
        }

        return $this->redirect(['action' => 'view', $tableName]);
    }

    public function moveRowDown($tableName = null, $id = null)
    {
        $this->request->allowMethod(['post']);
        $this->_checkTablePermission($tableName);

        $connection = ConnectionManager::get('default'); // Lấy connection ở đây để có thể begin/rollback

        try {
            $connection->begin(); // Bắt đầu transaction
            
            $dynamicTable = $this->_getDynamicTableInstance($tableName);
            $currentRow = $dynamicTable->get($id, ['fields' => ['id', 'sort_order']]);
            
            $nextRow = $dynamicTable->find()
                ->select(['id', 'sort_order'])
                ->where(['sort_order >' => $currentRow->sort_order])
                ->order(['sort_order' => 'ASC'])
                ->first();
                
            if ($nextRow) {
                $this->_swapSortOrders($dynamicTable, $currentRow, $nextRow);
                $connection->commit();
                $this->Flash->success(__('Dòng đã được di chuyển xuống.'));
            } else {
                $connection->rollback(); // Rollback nếu không có gì để di chuyển
                $this->Flash->info(__('Dòng đã ở vị trí cuối cùng.'));
            }
        } catch (NotFoundException $e) { // Sửa từ RecordNotFoundException
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error(__('Không tìm thấy dòng.'));
        } catch (\Exception $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            Log::error("Move row down error: " . $e->getMessage());
            $this->Flash->error(__('Đã xảy ra lỗi khi di chuyển dòng.'));
        }

        return $this->redirect(['action' => 'view', $tableName]);
    }

    public function deleteRow($tableName = null, $id = null)
    {
        $this->request->allowMethod(['post', 'delete']); // Chỉ cho phép method POST hoặc DELETE

        // 1. Kiểm tra quyền truy cập bảng
        $user = $this->Authentication->getIdentity();
        if (!$user) {
            throw new UnauthorizedException('Bạn cần đăng nhập để thực hiện thao tác này.');
        }

        try {
            // Lấy thông tin bảng từ UserTables để kiểm tra quyền
            $table = $this->UserTables->find()
                ->where([
                    'user_id' => $user->id,
                    'table_name' => $tableName
                ])
                ->firstOrFail();

            // Lấy dynamicTable instance
            $dynamicTable = $this->_getDynamicTableInstance($tableName);
            $connection = $dynamicTable->getConnection();

            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $connection->begin();

            // 2. Lấy dòng cần xóa và lưu lại sort_order của nó
            $rowToDelete = $dynamicTable->get($id, ['fields' => ['id', 'sort_order']]);
            $deletedSortOrder = $rowToDelete->sort_order;

            // 3. Xóa dòng khỏi database
            if (!$dynamicTable->delete($rowToDelete)) {
                throw new RuntimeException('Không thể xóa dòng khỏi bảng.');
            }

            // 4. Cập nhật sort_order của các dòng còn lại
            // Giảm sort_order của tất cả các dòng có sort_order lớn hơn dòng vừa xóa đi 1
            $dynamicTable->updateAll(
                ['sort_order' => $dynamicTable->query()->newExpr('sort_order - 1')],
                [
                    'sort_order >' => $deletedSortOrder
                ]
            );

            $connection->commit(); // Hoàn tất transaction
            $this->Flash->success(__('Dòng đã được xóa thành công.'));

        } catch (RecordNotFoundException $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error(__('Không tìm thấy dòng để xóa.'));
        } catch (ForbiddenException $e) { // Nếu _checkTablePermission ném ra ForbiddenException
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->Flash->error($e->getMessage());
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            if (isset($connection) && $connection->inTransaction()) {
                $connection->rollback();
            }
            $this->log("Delete row error for {$tableName} (ID: {$id}): " . $e->getMessage(), 'error');
            $this->Flash->error(__('Đã xảy ra lỗi khi xóa dòng: ') . $e->getMessage());
        }

        return $this->redirect(['action' => 'view', $tableName]);
    }

    // Phương thức hỗ trợ
    protected function _swapSortOrders($table, $row1, $row2)
    {
        $temp = $row1->sort_order;
        $row1->sort_order = $row2->sort_order;
        $row2->sort_order = $temp;
        
        $table->save($row1);
        $table->save($row2);
    }

    protected function _checkTablePermission($tableName)
    {
        $user = $this->Authentication->getIdentity();
        if (!$user) {
            throw new UnauthorizedException('Bạn cần đăng nhập để thực hiện thao tác này.');
        }
        
        $userTable = $this->UserTables->find()
            ->where([
                'user_id' => $user->id,
                'table_name' => $tableName
            ])
            ->first();
            
        if (!$userTable) {
            throw new ForbiddenException('Bạn không có quyền truy cập bảng này.');
        }
    }



    /**
     * Hàm hỗ trợ mapInputType
     */
    private function mapInputType(string $dataType): string
    {
        $mapping = [
            'varchar' => 'text',
            'text' => 'textarea',
            'int' => 'number',
            'float' => 'number',
            'date' => 'date',
            'datetime' => 'datetime',
            'file' => 'file',
            'tinyint' => 'select'
        ];
        
        return $mapping[strtolower($dataType)] ?? 'text';
    }








    /**
     * Chuẩn hóa tên bảng
     */
    private function standardizeTableName(string $name): string
    {
        $name = mb_strtolower($name, 'UTF-8');
        $name = Text::slug($name, ['replacement' => '_']); // Dùng Text::slug
        
        // Loại bỏ ký tự không phải chữ cái và số sau slug
        $name = preg_replace('/[^a-z0-9_]/', '', $name);

        // Đảm bảo không bắt đầu bằng số
        if (preg_match('/^[0-9]/', $name)) {
            $name = '_' . $name;
        }

        // Đảm bảo kết thúc bằng 's' nếu có thể (quy ước số nhiều của CakePHP)
        if (!empty($name) && !str_ends_with($name, 's')) {
            $name .= 's';
        }

        return substr($name, 0, 60); // Giới hạn độ dài tên bảng
    }

    /**
     * Helper function to get a dynamic table instance.
     * Use this function in addRow, editRow, deleteRow actions as well.
     */
    protected function _getDynamicTableInstance($tableName)
    {
        $tableLocator = TableRegistry::getTableLocator();
        
        if ($tableLocator->exists($tableName)) {
            return $tableLocator->get($tableName);
        }

        $connection = ConnectionManager::get('default');
        
        try {
            // Sửa lại cách kiểm tra bảng tồn tại
            $tables = $connection->getSchemaCollection()->listTables();
            if (!in_array($tableName, $tables)) {
                throw new RuntimeException("Bảng {$tableName} không tồn tại");
            }

            $schema = $connection->getSchemaCollection()->describe($tableName);
            
            $dynamicTable = new Table([
                'table' => $tableName,
                'connection' => $connection,
                'schema' => $schema
            ]);
            
            $tableLocator->set($tableName, $dynamicTable);
            
            return $dynamicTable;
        } catch (\Exception $e) {
            Log::error("Dynamic table error [{$tableName}]: " . $e->getMessage());
            throw new RuntimeException("Không thể khởi tạo bảng '{$tableName}'.", 0, $e);
        }
    }
}
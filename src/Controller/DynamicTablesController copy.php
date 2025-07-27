<?php
declare(strict_types=1);

namespace App\Controller;

use RuntimeException;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Text;
use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\Database\Schema\TableSchema;
use Cake\Database\Expression\QueryExpression;

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
                throw new BadRequestException('Tên bảng không được để trống');
            }

            $user = $this->Authentication->getIdentity();
            $userId = $user?->get('id');
            $tableName = $this->standardizeTableName($originalName);

            $connection = ConnectionManager::get('default');
            
            try {
                $connection->begin();

                // Kiểm tra bảng đã tồn tại chưa
                $schema = $connection->getSchemaCollection();
                if (in_array($tableName, $schema->listTables())) {
                    throw new RuntimeException('Bảng đã tồn tại');
                }

                // Tạo bảng với các cột mặc định
                $sql = "
                    CREATE TABLE `$tableName` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `user_id` INT UNSIGNED NOT NULL,
                        `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
                        `modified` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
                    throw new RuntimeException('Không thể lưu thông tin bảng');
                }

                $connection->commit();
                
                $this->Flash->success(__('Đã tạo bảng {0} thành công', $tableName));
                return $this->redirect(['action' => 'index']);

            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('Lỗi khi tạo bảng: {0}', $e->getMessage()));
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->render('add');
    }

    public function view(string $tableName)
    {
        $user = $this->Authentication->getIdentity();

        $table = $this->UserTables->find()
            ->where([
                'user_id' => $user->id,
                'table_name' => $tableName
            ])
            ->first();

        if (!$table) {
            throw new NotFoundException('Không tìm thấy bảng.');
        }

        $this->set(compact('table'));
    }


    public function edit(string $tableName)
    {
        $table = $this->UserTables->find()
            ->where(['table_name' => $tableName])
            ->firstOrFail();

        $typeMapping = Configure::read('ColumnTypeMapping');

        $connection = ConnectionManager::get('default');
        $schema = $connection->getSchemaCollection();
        
        // Lấy schema bảng với cache bị clear
        $tableSchema = $schema->describe($tableName);

        $primaryColumns = $tableSchema->getPrimaryKey();

        // Lấy danh sách alias từ database
        $aliases = $this->ColumnAliases->find()
            ->where(['user_table_id' => $table->id])
            ->all()
            ->combine('column_name', function ($entity) {
                return $entity;
            })
            ->toArray();

        $columnDetails = [];
        foreach ($tableSchema->columns() as $columnName) {
            if (in_array($columnName, ['id', 'user_id', 'created', 'modified'])) {
                continue;
            }

            $columnType = $tableSchema->getColumnType($columnName);
            $isNullable = $tableSchema->isNullable($columnName);
            $alias = $aliases[$columnName] ?? null;

            $columnDetails[] = [
                'name' => $columnName,
                'original_name' => $alias->original_name ?? $columnName,
                'original_type' => $alias->original_type ?? ($typeMapping[$columnType] ?? $columnType),
                'type' => $columnType,
                'null' => $isNullable,
                'primary' => in_array($columnName, $primaryColumns),
            ];
        }

        $this->set(compact('table', 'columnDetails', 'tableSchema'));
    }

    /**
     * Thêm cột mới vào bảng
     */
    public function addColumn($tableName)
    {
        $table = $this->UserTables->find()
            ->where(['table_name' => $tableName])
            ->firstOrFail();

        $typeMapping = Configure::read('ColumnTypeMapping'); // Lấy mapping từ config

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Validate input (giữ nguyên)
            if (empty(trim($data['original_name']))) {
                $this->Flash->error('Vui lòng nhập tên hiển thị');
                return $this->redirect($this->referer());
            }

            $columnName = $this->normalizeColumnName($data['original_name']);
            
            if (strlen($columnName) < 3) {
                $this->Flash->error('Tên cột quá ngắn (tối thiểu 3 ký tự)');
                return $this->redirect($this->referer());
            }

            $connection = ConnectionManager::get('default');
            $driver = $connection->getDriver();
            
            try {
                $connection->begin();

                // Check if column already exists (giữ nguyên)
                if ($this->columnExists($tableName, $columnName)) {
                    throw new RuntimeException('Tên cột đã tồn tại trong bảng này');
                }

                // Determine actual database type and display type
                $inputType = $data['type']; // Ví dụ: 'file', 'varchar', 'int'

                // === PHẦN CẦN THAY ĐỔI ĐỂ KHẮC PHỤC LỖI VALIDATION ===
                $dbType = $inputType; // Mặc định dbType là inputType
                $length = null; // Khởi tạo length

                // Lấy displayType TRỰC TIẾP từ typeMapping.
                // Điều này đảm bảo nó khớp với các giá trị hợp lệ trong validation.
                $displayType = $typeMapping[$inputType] ?? $inputType; 

                // Xử lý các trường hợp đặc biệt cho DB TYPE và LENGTH
                if ($inputType === 'file') {
                    $dbType = 'varchar'; // Trong DB, file vẫn là varchar để lưu đường dẫn
                    $length = 255;
                } elseif ($inputType === 'varchar') {
                    $length = 255;
                } 
                // Thêm các trường hợp khác nếu bạn có các inputType cần mapping khác cho $dbType
                // Ví dụ: 'boolean' có thể cần $dbType = 'tinyint(1)' hoặc 'boolean' tùy DB
                // ==========================================================

                // Build SQL query (giữ nguyên)
                $quotedTableName = $driver->quoteIdentifier($tableName);
                $quotedColumnName = $driver->quoteIdentifier($columnName);
                $sql = "ALTER TABLE {$quotedTableName} ADD COLUMN {$quotedColumnName} {$dbType}";

                // Add length if applicable
                if (isset($length)) {
                    $sql .= "({$length})";
                }

                // Handle NULL/NOT NULL (giữ nguyên)
                $isNullable = !empty($data['null']);
                $sql .= $isNullable ? " NULL" : " NOT NULL";

                // Handle default values (giữ nguyên)
                if (!empty($data['default']) && $inputType !== 'file') {
                    if (in_array($dbType, ['varchar', 'text', 'date', 'datetime'])) {
                        $defaultValue = $driver->quote($data['default']);
                    } else {
                        $defaultValue = $data['default'];
                    }
                    $sql .= " DEFAULT {$defaultValue}";
                } elseif (!$isNullable) {
                    // Set appropriate default for NOT NULL columns
                    switch ($dbType) {
                        case 'varchar':
                        case 'text':
                            $sql .= " DEFAULT ''";
                            break;
                        case 'integer':
                        case 'float':
                            $sql .= " DEFAULT 0";
                            break;
                        case 'date':
                        case 'datetime':
                            break;
                    }
                }

                // Execute the query (giữ nguyên)
                $connection->execute($sql);

                // Clear schema cache (giữ nguyên)
                Cache::delete($tableName, '_cake_model_');
                // Force refresh the schema to verify
                $schema = $connection->getSchemaCollection();
                $tableSchema = $schema->describe($tableName, ['forceRefresh' => true]);


                // Verify column was added (giữ nguyên)
                if (!in_array($columnName, $tableSchema->columns())) {
                    throw new RuntimeException('Không thể xác nhận cột mới đã được thêm');
                }

                // Save to column_aliases (giữ nguyên)
                $aliasData = [
                    'user_table_id' => $table->id,
                    'column_name' => $columnName,
                    'original_name' => $data['original_name'],
                    'data_type' => $dbType,
                    'original_type' => $displayType
                ];

                $alias = $this->ColumnAliases->newEntity($aliasData);
                if (!$this->ColumnAliases->save($alias)) {
                    throw new RuntimeException('Không thể lưu thông tin cột: ' . json_encode($alias->getErrors()));
                }

                $connection->commit();
                
                $this->Flash->success('Thêm cột thành công');
                return $this->redirect(['action' => 'edit', $tableName, '#' => 'columns']);
                
            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error('Lỗi: ' . $e->getMessage());
                return $this->redirect($this->referer());
            }
        }

        $this->set(compact('table', 'typeMapping'));
    }

    public function deleteColumnAlias($columnAliasId = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $columnAlias = $this->ColumnAliases->get($columnAliasId, ['contain' => ['UserTables']]);
        if (!$columnAlias) {
            $this->Flash->error(__('Không tìm thấy cột để xóa.'));
            return $this->redirect($this->referer()); // Hoặc redirect về action 'index'
        }

        // Lưu lại thông tin cần thiết trước khi xóa
        $userTableId = $columnAlias->user_table_id;
        $deletedSortOrder = $columnAlias->sort_order;
        $tableName = $columnAlias->user_table->table_name; // Lấy tên bảng để redirect

        if ($this->ColumnAliases->delete($columnAlias)) {
            $this->Flash->success(__('Cột đã được xóa thành công.'));

            // Cập nhật lại sort_order cho các cột còn lại trong cùng bảng
            // Những cột có sort_order lớn hơn cột vừa xóa sẽ giảm đi 1
            $this->ColumnAliases->updateAll(
                ['sort_order' => new QueryExpression('sort_order - 1')], // Cú pháp đúng để giảm giá trị
                [
                    'user_table_id' => $userTableId,
                    'sort_order >' => $deletedSortOrder
                ]
            );

            return $this->redirect(['action' => 'edit', $tableName]);
        } else {
            $this->Flash->error(__('Không thể xóa cột. Vui lòng thử lại.'));
        }

        return $this->redirect($this->referer());
    }


    private function columnExists($tableName, $columnName): bool
    {
        $connection = ConnectionManager::get('default');
        $schema = $connection->getSchemaCollection();
        
        // Đảm bảo lấy schema mới nhất
        $tableSchema = $schema->describe($tableName);
        return in_array($columnName, $tableSchema->columns());
    }

    private function normalizeColumnName(string $name): string
    {
        $name = mb_strtolower($name, 'UTF-8');
        $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
        
        $name = preg_replace('/[^a-z0-9\s]/', '', $name);
        $name = preg_replace('/\s+/', '_', trim($name));
        
        if (strlen($name) > 20) {
            $words = explode('_', $name);
            $name = '';
            foreach ($words as $word) {
                $name .= substr($word, 0, 1);
            }
        }
        
        return $name;
    }

    private function standardizeTableName(string $name): string
    {
        $name = mb_strtolower($name, 'UTF-8');
        $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);

        $name = preg_replace('/[^a-z\s]/', '', $name);
        $name = trim($name);

        if (str_word_count($name) === 1) {
            $result = preg_replace('/\s+/', '_', $name);
        } else {
            $words = explode(' ', $name);
            $result = implode('', array_map(fn($w) => mb_substr($w, 0, 1), $words));
        }

        if (!str_ends_with($result, 's')) {
            $result .= 's';
        }

        return substr($result, 0, 20);
    }
}
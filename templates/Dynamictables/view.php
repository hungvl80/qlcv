<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UserTable $userTable
 * @var \Cake\Datasource\ResultSetInterface $columnAliases
 * @var \Cake\Datasource\ResultSetInterface $tableData
 * @var string|null $tableName
 * @var array $filterValues // Biến mới để giữ giá trị lọc
 */

use Cake\Core\Configure;

$tableName = $tableName ?? $userTable->table_name ?? null;

// Lấy thông tin sắp xếp hiện tại từ request
$currentSortColumn = $this->getRequest()->getQuery('sort');
$currentSortDirection = $this->getRequest()->getQuery('direction');

// Lấy giá trị lọc hiện tại (hoặc mảng rỗng nếu không có)
$filterValues = $filterValues ?? [];
?>

<div class="container mt-4">
    <?= $this->Html->link(
        '<i class="bi bi-arrow-left-circle"></i> Quay lại danh sách',
        ['action' => 'index'],
        ['class' => 'btn btn-secondary mb-3', 'escape' => false]
    ) ?>

    <h4 class="d-flex align-items-center gap-2">
        <i class="bi bi-table"></i>
        Xem bảng: <span style="color: #FF69B4;"><?= h($userTable->original_name ?? $tableName) ?></span>

        <?php if ($tableName): ?>
            <?= $this->Html->link(
                '<i class="bi bi-plus-circle"></i> Thêm dòng',
                ['action' => 'addRow', $tableName],
                ['class' => 'btn btn-success ms-auto', 'escape' => false]
            ) ?>
        <?php endif; ?>
    </h4>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dữ liệu bảng</h5>
        </div>
        <div class="card-body">
            <?php if ($tableData->items()->isEmpty()): ?>
                <p class="text-center">Chưa có dòng dữ liệu nào trong bảng này.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <?php foreach ($columnAliases as $column): ?>
                                    <th>
                                        <?php
                                        // Kiểm tra nếu cột này có thể sắp xếp
                                        if (!in_array($column->data_type, ['file', 'text'])) {
                                            // Tạo link sắp xếp
                                            echo $this->Paginator->sort($column->column_name, h($column->original_name));

                                            // Hiển thị mũi tên trong THẻ TH dựa trên trạng thái sắp xếp hiện tại
                                            if ($currentSortColumn === $column->column_name) {
                                                echo ($currentSortDirection === 'asc') ? ' <i class="bi bi-arrow-up"></i>' : ' <i class="bi bi-arrow-down"></i>';
                                            }
                                        } else {
                                            // Nếu không thể sắp xếp, chỉ hiển thị tên cột
                                            echo h($column->original_name);
                                        }
                                        ?>
                                    </th>
                                <?php endforeach; ?>
                                <th class="actions"><?= __('Thao tác') ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel"></i> Lọc</button>
                                </td>
                                <?php
                                // Mở form lọc tại đây
                                echo $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'view', $tableName]]);
                                ?>
                                <?php foreach ($columnAliases as $column): ?>
                                    <td>
                                        <?php
                                        $filterInputName = 'filter_' . $column->column_name;
                                        $currentFilterValue = $filterValues[$filterInputName] ?? '';

                                        if (!in_array($column->data_type, ['file', 'text'])) {
                                            // Input cho các cột có thể lọc
                                            echo $this->Form->control($filterInputName, [
                                                'type' => 'text',
                                                'label' => false,
                                                'class' => 'form-control form-control-sm',
                                                'placeholder' => 'Lọc ' . h($column->original_name),
                                                'value' => $currentFilterValue,
                                                'title' => 'Nhập giá trị để lọc theo cột này'
                                            ]);
                                        } else {
                                            // Cột không lọc được
                                            echo '&nbsp;'; // Giữ khoảng trắng để căn chỉnh
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="bi bi-x-circle"></i> Xóa lọc',
                                        ['action' => 'view', $tableName],
                                        ['class' => 'btn btn-sm btn-warning w-100', 'escape' => false, 'title' => 'Xóa tất cả các tiêu chí lọc']
                                    ) ?>
                                </td>
                                <?php echo $this->Form->end(); // Đóng form lọc ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Lấy thông tin trang hiện tại để tính STT động
                            $page = $this->Paginator->current();
                            $limit = $this->Paginator->param('perPage');
                            $startSno = (($page - 1) * $limit);
                            $sno = 0; // Bộ đếm cho từng dòng trên trang
                            ?>
                            <?php foreach ($tableData as $row): ?>
                                <tr>
                                    <td><?= ++$sno + $startSno ?></td>
                                    <?php foreach ($columnAliases as $column): ?>
                                        <td>
                                            <?php
                                            $columnName = $column->column_name;
                                            $dataType = $column->data_type;
                                            $value = $row->$columnName;

                                            echo $this->DynamicTables->formatCellValue($value, $dataType);
                                            ?>
                                            <?php
                                            if ($column->data_type === 'file' && !empty($value)) {
                                                $filePath = 'uploads' . DS . $value;
                                                if (file_exists(WWW_ROOT . $filePath)) {
                                                    $fileExtension = pathinfo($value, PATHINFO_EXTENSION);
                                                    if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        echo $this->Html->image($filePath, ['class' => 'img-thumbnail', 'style' => 'max-width: 100px; max-height: 100px;']);
                                                    }
                                                    echo $this->Html->link(
                                                        '<i class="bi bi-download"></i> Tải xuống',
                                                        $this->Url->build('/' . str_replace(DS, '/', $filePath)),
                                                        ['target' => '_blank', 'escape' => false, 'class' => 'btn btn-sm btn-info ms-2']
                                                    );
                                                } else {
                                                    echo 'Không tìm thấy file';
                                                }
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="actions text-nowrap">
                                        <?php if ($tableName): ?>
                                            <?php if (property_exists($row, 'sort_order')): ?>
                                                <?= $this->Html->link(
                                                    '<i class="bi bi-arrow-up-circle"></i>',
                                                    ['action' => 'moveRow', $tableName, $row->id, 'up'],
                                                    ['class' => 'btn btn-sm btn-outline-secondary me-1', 'escape' => false, 'data-bs-toggle' => 'tooltip', 'title' => 'Di chuyển lên']
                                                ) ?>
                                                <?= $this->Html->link(
                                                    '<i class="bi bi-arrow-down-circle"></i>',
                                                    ['action' => 'moveRow', $tableName, $row->id, 'down'],
                                                    ['class' => 'btn btn-sm btn-outline-secondary me-2', 'escape' => false, 'data-bs-toggle' => 'tooltip', 'title' => 'Di chuyển xuống']
                                                ) ?>
                                            <?php endif; ?>
                                            <?= $this->Html->link(
                                                '<i class="bi bi-pencil-square"></i>',
                                                ['action' => 'editRow', $tableName, $row->id],
                                                ['class' => 'btn btn-primary btn-sm me-2', 'escape' => false, 'data-bs-toggle' => 'tooltip', 'title' => 'Sửa']
                                            ) ?>
                                            <?= $this->Form->postLink(
                                                '<i class="bi bi-trash"></i>',
                                                ['action' => 'deleteRow', $tableName, $row->id],
                                                [
                                                    'confirm' => 'Bạn có chắc chắn muốn xóa dòng này không?',
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'escape' => false,
                                                    'data-bs-toggle' => 'tooltip', 'title' => 'Xóa'
                                                ]
                                            ) ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-info">
                    <?= $this->Paginator->counter('Trang {{page}} của {{pages}}, tổng {{count}} dòng') ?>
                </div>
                <ul class="pagination pagination-sm m-0">
                    <?php
                    $this->Paginator->setTemplates([
                        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'current' => '<li class="page-item active"><a class="page-link bg-primary" href="{{url}}">{{text}}</a></li>',
                        'first' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</li>',
                        'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'prevDisabled' => '<li class="page-item disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
                        'nextActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'nextDisabled' => '<li class="page-item disabled"><a class="page-link" href="" onclick="return false;">{{text}}</a></li>',
                        'last' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                    ]);
                    ?>
                    <?= $this->Paginator->first('<< Đầu') ?>
                    <?= $this->Paginator->prev('< Trước') ?>
                    <?= $this->Paginator->numbers(['modulus' => 4]) ?>
                    <?= $this->Paginator->next('Sau >') ?>
                    <?= $this->Paginator->last('Cuối >>') ?>
                </ul>
            </div>
        </div>
    </div>
</div>
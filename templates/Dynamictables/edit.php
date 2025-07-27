<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UserTable $table
 * @var \Cake\ORM\ResultSet|\App\Model\Entity\ColumnAlias[] $columnAliases
 */

use Cake\Core\Configure;

// Lấy mapping để hiển thị tên kiểu dữ liệu tiếng Việt
$columnTypeMapping = Configure::read('ColumnTypeMapping'); //
?>

<div class="container mt-4">
    <?= $this->Html->link(
        '<i class="bi bi-arrow-left-circle"></i> Quay lại danh sách',
        ['action' => 'index'],
        ['class' => 'btn btn-secondary mb-3', 'escape' => false]
    ) ?>
    <h4 class="d-flex align-items-center gap-2">
        Chỉnh sửa bảng: <?= h($table->original_name ?? $table->table_name) ?>
        <?php 
        $status = $table->status ?? 0;
        // Đảm bảo STATUS_OPTIONS được định nghĩa ở đâu đó (ví dụ: bootstrap.php hoặc config file)
        // Ví dụ: define('STATUS_OPTIONS', [0 => 'Chưa phê duyệt', 1 => 'Đã phê duyệt']);
        $statusText = STATUS_OPTIONS[$status] ?? 'Không xác định';
        $badgeClass = match($status) {
            1 => 'bg-success',      // Đã phê duyệt
            0 => 'bg-warning',      // Chưa phê duyệt
            default => 'bg-secondary' // Trạng thái khác
        };
        ?>
        <span class="badge <?= $badgeClass ?> ms-2"><?= h($statusText) ?></span>
    </h4>
</div>

<div class="container mb-5">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Các cột dữ liệu</h5>
                <div>
                    <?= $this->Html->link(
                        '<i class="bi bi-plus-circle"></i> Thêm cột',
                        ['action' => 'addColumn', $table->table_name],
                        ['class' => 'btn btn-sm btn-primary', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="30%"><?= $this->Paginator->sort('original_name', 'Tên hiển thị') ?></th>
                            <th width="20%"><?= $this->Paginator->sort('data_type', 'Kiểu dữ liệu') ?></th>
                            <th width="15%"><?= $this->Paginator->sort('null', 'Cho phép NULL') ?></th>
                            <th width="35%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($columnAliases) && $columnAliases->count() > 0): ?>
                            <?php 
                            // $columnCount = count($columnAliases); // Không dùng count() trực tiếp trên ResultSet
                            // $currentIndex = 0; // Không cần currentIndex nếu không có Paginator trên cột
                            ?>
                            <?php foreach ($columnAliases as $col): ?>
                                <tr>
                                    <td><?= h($col->original_name) ?></td>
                                    <td>
                                       
                                        <?= h($columnTypeMapping[$col->data_type] ?? $col->data_type) ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $col->null ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                            <?= $col->null ? 'Có' : 'Không' ?>
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <?= $this->Html->link('<i class="bi bi-pencil-square"></i>', 
                                            ['action' => 'editColumnAlias', $col->id],
                                            ['escape' => false, 'class' => 'btn btn-sm btn-warning me-1', 'title' => 'Chỉnh sửa']) ?>

                                        <?= $this->Form->postLink('<i class="bi bi-trash"></i>', 
                                            ['action' => 'deleteColumnAlias', $col->id],
                                            [
                                                'escape' => false,
                                                'class' => 'btn btn-sm btn-danger me-1',
                                                'confirm' => 'Bạn có chắc muốn xóa cột "' . h($col->original_name) . '" này? Thao tác này sẽ xóa cột khỏi cả database vật lý và không thể hoàn tác!'
                                            ]) ?>
                                        
                                        <?php 
                                        // Giả sử $col->sort_order đã có và bạn đã có maxSortOrder từ controller
                                        // Hoặc bạn sẽ cần fetch lại min/max sort_order ở đây (không khuyến khích trong view)
                                        // Tốt nhất là truyền 'firstColumnSortOrder' và 'lastColumnSortOrder' từ controller
                                        
                                        // Ví dụ đơn giản (có thể không hoàn hảo với pagination):
                                        // Để nút mũi tên lên hoạt động, sort_order của cột phải > min_sort_order của bảng
                                        // Để nút mũi tên xuống hoạt động, sort_order của cột phải < max_sort_order của bảng
                                        // Bạn cần truyền $minSortOrder và $maxSortOrder từ controller xuống view
                                        // Ví dụ: $this->set(compact('minSortOrder', 'maxSortOrder'));

                                        $isFirstColumn = (isset($minSortOrder) && $col->sort_order == $minSortOrder);
                                        $isLastColumn = (isset($maxSortOrder) && $col->sort_order == $maxSortOrder);
                                        
                                        ?>

                                        <?php if (!$isFirstColumn): ?>
                                            <?= $this->Form->postLink(
                                                '<i class="bi bi-arrow-up-circle"></i>',
                                                ['action' => 'moveColumnUp', $col->id],
                                                ['class' => 'btn btn-sm btn-secondary me-1', 'escapeTitle' => false, 'title' => 'Di chuyển lên']
                                            ) ?>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary me-1" disabled title="Đã ở vị trí đầu tiên"><i class="bi bi-arrow-up-circle"></i></button>
                                        <?php endif; ?>

                                        <?php if (!$isLastColumn): ?>
                                            <?= $this->Form->postLink(
                                                '<i class="bi bi-arrow-down-circle"></i>',
                                                ['action' => 'moveColumnDown', $col->id],
                                                ['class' => 'btn btn-sm btn-secondary', 'escapeTitle' => false, 'title' => 'Di chuyển xuống']
                                            ) ?>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled title="Đã ở vị trí cuối cùng"><i class="bi bi-arrow-down-circle"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Chưa có cột nào</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-info text-muted">
                    <?= $this->Paginator->counter('Trang {{page}} của {{pages}}, tổng {{count}} cột') ?>
                </div>
                <ul class="pagination pagination-sm m-0">
                    <?php
                    // Đặt template tùy chỉnh cho Paginator để áp dụng class badge cho trang hiện tại
                    $this->Paginator->setTemplates([
                        'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'current' => '<li class="page-item active"><a class="page-link bg-primary" href="{{url}}">{{text}}</a></li>',
                        'first' => '<li class="page-item{{disabled}}"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'prevDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'nextActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'nextDisabled' => '<li class="page-item disabled"><a class="page-link" href="{{url}}">{{text}}</a></li>',
                        'last' => '<li class="page-item{{disabled}}"><a class="page-link" href="{{url}}">{{text}}</a></li>',
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
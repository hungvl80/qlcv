<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CatTable $catTable
 */
?>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar với thao tác -->
        <aside class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= __('Thao tác') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-edit me-2"></i>' . __('Sửa thông tin'),
                            ['action' => 'edit', $catTable->id],
                            ['class' => 'btn btn-warning btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa lĩnh vực'),
                            ['action' => 'delete', $catTable->id],
                            [
                                'confirm' => __('Bạn có chắc chắn muốn xóa lĩnh vực {0}? Thao tác này không thể hoàn tác.', $catTable->name),
                                'class' => 'btn btn-danger btn-sm',
                                'escape' => false
                            ]
                        ) ?>
                        <hr>
                        <?= $this->Html->link(
                            '<i class="fas fa-list me-2"></i>' . __('Danh sách lĩnh vực'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus me-2"></i>' . __('Thêm lĩnh vực'),
                            ['action' => 'add'],
                            ['class' => 'btn btn-outline-success btn-sm', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Nội dung chính -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= h($catTable->name) ?></h4>
                    <div>
                        <span class="badge bg-light text-dark">
                            ID: <?= h($catTable->id) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <th class="table-header">ID</th>
                                <td><?= $this->Number->format($catTable->id) ?></td>
                            </tr>
                            <tr>
                                <th class="table-header">Tên lĩnh vực</th>
                                <td><?= h($catTable->name) ?></td>
                            </tr>
                            <tr>
                                <th class="table-header">Đơn vị quản lý</th>
                                <td><?= h($catTable->unit->name ?? __('Chưa có')) ?></td>
                            </tr>
                            <tr>
                                <th class="table-header">Ngày tạo</th>
                                <td><?= h($catTable->created) ?></td>
                            </tr>
                            <tr>
                                <th class="table-header">Ngày sửa</th>
                                <td><?= h($catTable->modified) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-header {
    width: 35%; 
    background-color: #e9ecef; 
    vertical-align: middle; 
    padding: 0.8rem 1.2rem; 
    font-weight: 600; 
    color: #495057; 
    border-bottom: 1px solid #dee2e6;
}
.table td {
    vertical-align: middle; 
    padding: 0.8rem 1.2rem; 
    border-bottom: 1px solid #dee2e6;
}
.table tr:last-child th,
.table tr:last-child td {
    border-bottom: none;
}
</style>

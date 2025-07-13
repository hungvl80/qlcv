<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar with actions -->
        <aside class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= __('Thao tác') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-edit me-2"></i>' . __('Sửa thông tin'),
                            ['action' => 'edit', $position->id],
                            ['class' => 'btn btn-warning btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa chức vụ'),
                            ['action' => 'delete', $position->id],
                            [
                                'confirm' => __('Bạn có chắc chắn muốn xóa chức vụ {0}? Thao tác này không thể hoàn tác.', $position->name),
                                'class' => 'btn btn-danger btn-sm',
                                'escape' => false
                            ]
                        ) ?>
                        <hr>
                        <?= $this->Html->link(
                            '<i class="fas fa-list me-2"></i>' . __('Danh sách chức vụ'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus me-2"></i>' . __('Thêm chức vụ'),
                            ['action' => 'add'],
                            ['class' => 'btn btn-outline-success btn-sm', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= h($position->name) ?></h4>
                    <div>
                        <span class="badge bg-light text-dark">
                            ID: <?= h($position->id) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <table class="table table-hover table-sm">
                            <tbody>
                                <tr>
                                    <th class="table-header">ID</th>
                                    <td><?= $this->Number->format($position->id) ?></td>
                                </tr>
                                <tr>
                                    <th class="table-header">Tên chức vụ</th>
                                    <td><?= h($position->name) ?></td>
                                </tr>
                                <tr>
                                    <th class="table-header">Mã chức vụ</th>
                                    <td><?= h($position->code) ?></td>
                                </tr>
                                <tr>
                                    <th class="table-header">Cấp bậc</th>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= $this->Number->format($position->level) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="table-header">Ngày tạo</th>
                                    <td><?= h($position->created) ?></td>
                                </tr>
                                <tr>
                                    <th class="table-header">Ngày sửa</th>
                                    <td><?= h($position->modified) ?></td>
                                </tr>
                             </tbody>
                        </table>
                    </div>
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
    
    .table tr:last-child th {
        border-bottom: none;
    }
    
    .table tr:last-child td {
        border-bottom: none;
    }
    
    .badge a {
        text-decoration: none;
    }
</style>
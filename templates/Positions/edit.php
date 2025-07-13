<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<div class="container mt-4">
    <div class="row">
        <aside class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= __('Thao tác') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-eye me-2"></i>' . __('Xem chi tiết'),
                            ['action' => 'view', $position->id],
                            ['class' => 'btn btn-info btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa chức vụ'),
                            ['action' => 'delete', $position->id],
                            [
                                'confirm' => __('Bạn có chắc chắn muốn xóa chức vụ {0}?', $position->name),
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
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= __('Chỉnh sửa Chức vụ') ?></h4>
                    <div>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-id-card me-1"></i>ID: <?= h($position->id) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <?= $this->Form->create($position) ?>
                    
                    <div class="mb-3">
                        <?= $this->Form->label('name', __('Tên chức vụ'), ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->text('name', [
                            'class' => 'form-control',
                            'required' => true,
                            'placeholder' => __('Nhập tên chức vụ')
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <?= $this->Form->label('code', __('Mã chức vụ'), ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->text('code', [
                            'class' => 'form-control',
                            'placeholder' => __('Nhập mã chức vụ (tùy chọn)')
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <?= $this->Form->label('level', __('Cấp bậc'), ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->number('level', [
                            'class' => 'form-control',
                            'required' => true,
                            'min' => 0,
                            'placeholder' => __('Nhập cấp bậc')
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <?= $this->Form->button(__('Lưu thay đổi'), [
                            'class' => 'btn btn-success',
                            'escape' => false
                        ]) ?>
                    </div>
                    
                    <?= $this->Form->end() ?>
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
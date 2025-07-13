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
                            '<i class="fas fa-list me-2"></i>' . __('Danh sách đơn vị'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa Đơn vị'),
                            ['action' => 'delete', $unit->id],
                            [
                                'confirm' => __('Bạn có chắc chắn muốn xóa đơn vị {0}?', $unit->name),
                                'class' => 'btn btn-danger btn-sm',
                                'escape' => false
                            ]
                        ) ?>
                        <hr>
                        <?= $this->Html->link(
                            '<i class="fas fa-eye me-2"></i>' . __('Xem chi tiết'),
                            ['action' => 'view', $unit->id],
                            ['class' => 'btn btn-outline-info btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus me-2"></i>' . __('Thêm đơn vị'),
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
                    <h4 class="mb-0"><?= __('Sửa Đơn vị') ?></h4>
                    <div>
                        <span class="badge bg-light text-dark">
                            ID: <?= h($unit->id) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <?= $this->Form->create($unit) ?>
                    
                    <div class="mb-3">
                        <?= $this->Form->label('name', 'Tên đơn vị', ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->control('name', [
                            'class' => 'form-control', 
                            'label' => false,
                            'placeholder' => 'Nhập tên đơn vị'
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <?= $this->Form->label('code', 'Mã đơn vị', ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->control('code', [
                            'class' => 'form-control', 
                            'label' => false,
                            'placeholder' => 'Nhập mã đơn vị'
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <?= $this->Form->label('parent_id', 'Đơn vị cha', ['class' => 'form-label fw-bold']) ?>
                        <?= $this->Form->control('parent_id', [
                            'options' => $parentUnits,
                            'empty' => '-- Chọn đơn vị cha --',
                            'class' => 'form-select',
                            'label' => false
                        ]) ?>
                    </div>

                    <div class="d-flex justify-content-between">
                        <?= $this->Form->button(__('Lưu thay đổi'), ['class' => 'btn btn-success mt-3']) ?>
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
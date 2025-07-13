<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Unit $unit
 */
?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar with actions -->
        <aside class="col-lg-3 col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i><?= __('Thao tác') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-edit me-2"></i>' . __('Sửa thông tin'),
                            ['action' => 'edit', $unit->id],
                            ['class' => 'btn btn-warning btn-sm text-start', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa Đơn vị'),
                            ['action' => 'delete', $unit->id],
                            [
                                'confirm' => __('Bạn có chắc chắn muốn xóa đơn vị {0}? Thao tác này không thể hoàn tác.', $unit->name),
                                'class' => 'btn btn-danger btn-sm text-start',
                                'escape' => false
                            ]
                        ) ?>
                        <hr>
                        <?= $this->Html->link(
                            '<i class="fas fa-list me-2"></i>' . __('Danh sách đơn vị'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm text-start', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus me-2"></i>' . __('Thêm đơn vị'),
                            ['action' => 'add'],
                            ['class' => 'btn btn-outline-success btn-sm text-start', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
            
            <!-- Unit hierarchy info -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i><?= __('Cấu trúc đơn vị') ?></h5>
                </div>
                <div class="card-body">
                    <?php if ($unit->has('parent_unit')): ?>
                        <h6 class="fw-bold"><?= __('Đơn vị cha') ?></h6>
                        <p>
                            <?= $this->Html->link(
                                h($unit->parent_unit->name),
                                ['action' => 'view', $unit->parent_unit->id],
                                ['class' => 'text-primary']
                            ) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($unit->child_units)): ?>
                        <h6 class="fw-bold"><?= __('Đơn vị con') ?></h6>
                        <ul class="list-unstyled">
                            <?php foreach ($unit->child_units as $child): ?>
                                <li>
                                    <?= $this->Html->link(
                                        h($child->name),
                                        ['action' => 'view', $child->id],
                                        ['class' => 'text-primary']
                                    ) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="col-lg-9 col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fas fa-building me-2"></i><?= h($unit->name) ?></h4>
                        <small class="opacity-75"><?= h($unit->code) ?></small>
                    </div>
                    <div>
                        <span class="badge bg-light text-dark fs-6">
                            ID: <?= h($unit->id) ?>
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <th class="table-header">ID</th>
                                        <td><?= $this->Number->format($unit->id) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-header">Tên đơn vị</th>
                                        <td><?= h($unit->name) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-header">Mã đơn vị</th>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= h($unit->code) ?>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <th class="table-header">Đơn vị cha</th>
                                        <td>
                                            <?php if ($unit->has('parent_unit')): ?>
                                                <?= $this->Html->link(
                                                    h($unit->parent_unit->name), 
                                                    ['action' => 'view', $unit->parent_unit->id],
                                                    ['class' => 'text-decoration-none text-primary fw-bold']
                                                ) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không có</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="table-header">Ngày tạo</th>
                                        <td><?= h($unit->created->format('d/m/Y H:i')) ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-header">Ngày sửa</th>
                                        <td><?= h($unit->modified->format('d/m/Y H:i')) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($unit->description)): ?>
                        <div class="mt-4">
                            <h5 class="fw-bold border-bottom pb-2"><?= __('Mô tả') ?></h5>
                            <div class="p-3 bg-light rounded">
                                <?= $this->Text->autoParagraph(h($unit->description)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-header {
        width: 40%; 
        font-weight: 600; 
        color: #495057;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
</style>
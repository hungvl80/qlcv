<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-building me-2"></i>Quản lý Đơn vị
                </h4>
                <div>
                    <?= $this->Html->link(
                        '<i class="bi bi-plus-lg me-1"></i> Thêm mới',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="70" class="text-center"><?= $this->Paginator->sort('id', '#ID') ?></th>
                            <th><?= $this->Paginator->sort('name', 'Tên đơn vị') ?></th>
                            <th><?= $this->Paginator->sort('code', 'Mã đơn vị') ?></th>
                            <th><?= $this->Paginator->sort('parent_id', 'Đơn vị cha') ?></th>
                            <th width="140" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($units as $unit): ?>
                        <tr>
                            <td class="text-center text-muted"><?= $this->Number->format($unit->id) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <span class="text-dark fw-bold"><?= substr(h($unit->name), 0, 1) ?></span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= h($unit->name) ?></div>
                                        <?php if (!empty($unit->description)): ?>
                                        <small class="text-muted"><?= h($unit->description) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= h($unit->code) ?></td>
                            <td>
                                <?= h($unit->parent_unit->name ?? __('Không có')) ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?= $this->Html->link(
                                        '<i class="bi bi-eye"></i>',
                                        ['action' => 'view', $unit->id],
                                        [
                                            'class' => 'btn btn-outline-info',
                                            'escape' => false,
                                            'title' => 'Xem chi tiết',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                    
                                    <?= $this->Html->link(
                                        '<i class="bi bi-pencil"></i>',
                                        ['action' => 'edit', $unit->id],
                                        [
                                            'class' => 'btn btn-outline-warning',
                                            'escape' => false,
                                            'title' => 'Chỉnh sửa',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="bi bi-trash"></i>',
                                        ['action' => 'delete', $unit->id],
                                        [
                                            'confirm' => 'Bạn có chắc chắn muốn xóa đơn vị ' . h($unit->name) . '?',
                                            'class' => 'btn btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Xóa',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    <?= $this->Paginator->counter('Hiển thị <b>{:start}-{:end}</b> trong <b>{:count}</b> đơn vị') ?>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <?= $this->Paginator->first('<i class="bi bi-chevron-double-left"></i>', ['escape' => false]) ?>
                        <?= $this->Paginator->prev('<i class="bi bi-chevron-left"></i>', ['escape' => false]) ?>
                        <?= $this->Paginator->numbers(['modulus' => 2]) ?>
                        <?= $this->Paginator->next('<i class="bi bi-chevron-right"></i>', ['escape' => false]) ?>
                        <?= $this->Paginator->last('<i class="bi bi-chevron-double-right"></i>', ['escape' => false]) ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php $this->append('css'); ?>
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem !important;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
    }
</style>
<?php $this->end(); ?>

<?php $this->append('script'); ?>
<script>
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
<?php $this->end(); ?>
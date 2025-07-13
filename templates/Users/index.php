<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Quản lý Người dùng
                </h4>
                <div>
                    <?= $this->Html->link(
                        '<i class="bi bi-plus-lg me-1"></i> Thêm mới',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<i class="bi bi-download me-1"></i> Xuất Excel',
                        ['action' => 'export'],
                        ['class' => 'btn btn-success ms-2', 'escape' => false]
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
                            <th><?= $this->Paginator->sort('username', 'Tên đăng nhập') ?></th>
                            <th><?= $this->Paginator->sort('full_name', 'Họ tên') ?></th>
                            <th><?= $this->Paginator->sort('email', 'Email') ?></th>
                            <th>Đơn vị</th>
                            <th width="140" class="text-center">Trạng thái</th>
                            <th width="140" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center text-muted"><?= $this->Number->format($user->id) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <span class="text-dark fw-bold"><?= substr(h($user->username), 0, 1) ?></span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= h($user->username) ?></div>
                                        <small class="text-muted"><?= h($user->is_admin ? 'Quản trị' : 'Người dùng') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= h($user->full_name) ?></td>
                            <td><?= h($user->email) ?></td>
                            <td><?= h($user->unit->name ?? '--') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $user->is_active ? 'bg-success' : 'bg-secondary' ?>">
                                    <i class="bi <?= $user->is_active ? 'bi-check-circle' : 'bi-x-circle' ?> me-1"></i>
                                    <?= $user->is_active ? 'Hoạt động' : 'Vô hiệu' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?= $this->Html->link(
                                        '<i class="bi bi-eye"></i>',
                                        ['action' => 'view', $user->id],
                                        [
                                            'class' => 'btn btn-outline-info',
                                            'escape' => false,
                                            'title' => 'Xem chi tiết',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                    
                                    <?= $this->Html->link(
                                        '<i class="bi bi-pencil"></i>',
                                        ['action' => 'edit', $user->id],
                                        [
                                            'class' => 'btn btn-outline-warning',
                                            'escape' => false,
                                            'title' => 'Chỉnh sửa',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    ) ?>
                                    
                                    <?= $this->Form->postLink(
                                        '<i class="bi bi-trash"></i>',
                                        ['action' => 'delete', $user->id],
                                        [
                                            'confirm' => 'Bạn có chắc chắn muốn xóa '.h($user->full_name).'?',
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
                    <?= $this->Paginator->counter('Hiển thị <b>{:start}-{:end}</b> trong <b>{:count}</b> người dùng') ?>
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
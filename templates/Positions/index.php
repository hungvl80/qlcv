<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Position> $positions
 */
?>
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-briefcase-fill me-2"></i>Quản lý Chức vụ
                </h4>
                <div>
                    <?= $this->Html->link(
                        '<i class="bi bi-plus-lg me-1"></i> Thêm mới',
                        ['action' => 'add'],
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <div class="dropdown d-inline-block ms-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i> Tùy chọn
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
                            <li><?= $this->Html->link(
                                '<i class="bi bi-download me-2"></i>' . __('Xuất Excel'),
                                ['action' => 'export'],
                                ['class' => 'dropdown-item', 'escape' => false]
                            ) ?></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><?= $this->Html->link(
                                '<i class="bi bi-funnel me-2"></i>' . __('Lọc nâng cao'),
                                '#',
                                ['class' => 'dropdown-item', 'escape' => false, 'data-bs-toggle' => 'modal', 'data-bs-target' => '#filterModal']
                            ) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="70" class="text-center"><?= $this->Paginator->sort('id', '#ID') ?></th>
                            <th><?= $this->Paginator->sort('name', 'Tên chức vụ') ?></th>
                            <th width="250px" class="text-center"><?= $this->Paginator->sort('code', 'Mã chức vụ') ?></th>
                            <th width="250px" class="text-center"><?= $this->Paginator->sort('level', 'Cấp độ') ?></th>
                            <th width="140" class="text-center"><?= $this->Paginator->sort('created', 'Ngày tạo') ?></th>
                            <th width="140" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($positions->toArray())): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-2"></i><?= __('Không tìm thấy chức vụ nào') ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($positions as $position): ?>
                            <tr>
                                <td class="text-center text-muted"><?= $this->Number->format($position->id) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <span class="text-dark fw-bold"><?= substr(h($position->name), 0, 1) ?></span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= h($position->name) ?></div>
                                            <?php if (!empty($position->description)): ?>
                                            <small class="text-muted"><?= h($position->description) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary"><?= h($position->code) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php
                                    $level = $position->level;
                                    $maxStars = 5; // Cấp bậc tối đa 5 sao
                                    for ($i = 0; $i < $maxStars; $i++) {
                                        if ($i < $level) {
                                            echo '<i class="bi bi-star-fill text-warning me-1"></i>'; // Sao đã điền
                                        } else {
                                            echo '<i class="bi bi-star text-muted me-1"></i>'; // Sao trống
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <span class="small text-muted" title="<?= $position->created->format('H:i:s') ?>" data-bs-toggle="tooltip">
                                        <?= $position->created->format('d/m/Y') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?= $this->Html->link(
                                            '<i class="bi bi-eye"></i>',
                                            ['action' => 'view', $position->id],
                                            [
                                                'class' => 'btn btn-outline-info',
                                                'escape' => false,
                                                'title' => 'Xem chi tiết',
                                                'data-bs-toggle' => 'tooltip'
                                            ]
                                        ) ?>
                                        
                                        <?= $this->Html->link(
                                            '<i class="bi bi-pencil"></i>',
                                            ['action' => 'edit', $position->id],
                                            [
                                                'class' => 'btn btn-outline-warning',
                                                'escape' => false,
                                                'title' => 'Chỉnh sửa',
                                                'data-bs-toggle' => 'tooltip'
                                            ]
                                        ) ?>
                                        
                                        <?= $this->Form->postLink(
                                            '<i class="bi bi-trash"></i>',
                                            ['action' => 'delete', $position->id],
                                            [
                                                'confirm' => __('Bạn có chắc chắn muốn xóa chức vụ "{0}"?', $position->name),
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
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    <?= $this->Paginator->counter('Hiển thị <b>{:start}-{:end}</b> trong <b>{:count}</b> chức vụ') ?>
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

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel"><?= __('Lọc chức vụ') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= $this->Form->create(null, ['type' => 'get']) ?>
            <div class="modal-body">
                <div class="mb-3">
                    <?= $this->Form->control('search', [
                        'label' => __('Tìm kiếm'),
                        'class' => 'form-control',
                        'placeholder' => __('Nhập tên hoặc mã chức vụ...')
                    ]) ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $this->Form->control('level', [
                            'label' => __('Cấp độ từ'),
                            'type' => 'number',
                            'min' => 1,
                            'max' => 5, // Cấp độ tối đa 5
                            'class' => 'form-control'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('status', [
                            'label' => __('Trạng thái'),
                            'options' => [
                                '1' => __('Hoạt động'),
                                '0' => __('Không hoạt động')
                            ],
                            'empty' => __('Tất cả'),
                            'class' => 'form-select'
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Đóng') ?></button>
                <?= $this->Form->button(__('Áp dụng bộ lọc'), ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link(__('Đặt lại'), ['action' => 'index'], ['class' => 'btn btn-outline-danger']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->append('css'); ?>
<style>
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .table th {
        border-top: none;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    .btn-group .btn {
        border-radius: 0.25rem !important;
        padding: 0.25rem 0.5rem;
    }
    
    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .page-link {
        color: #4e73df;
    }

    /* Avatar small for table */
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
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
$(document).ready(function() {
    // Enable tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});

// Bootstrap 5 Tooltip initialization (đảm bảo nó được gọi sau khi DOM sẵn sàng)
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
<?php $this->end(); ?>
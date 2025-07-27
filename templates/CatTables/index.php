<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-success">
                    <i class="bi bi-tags me-2"></i>Quản lý Lĩnh vực (CatTables)
                </h4>
                <div>
                    <?= $this->Html->link(
                        '<i class="bi bi-plus-lg me-1"></i> Thêm mới',
                        ['action' => 'add'],
                        ['class' => 'btn btn-success', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center"><?= $this->Paginator->sort('id', '#ID') ?></th>
                            <th><?= $this->Paginator->sort('name', 'Tên lĩnh vực') ?></th>
                            <th><?= $this->Paginator->sort('unit_id', 'Đơn vị quản lý') ?></th>
                            <th width="140" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($catTables as $table): ?>
                        <tr>
                            <td class="text-center text-muted"><?= $this->Number->format($table->id) ?></td>
                            <td>
                                <div class="fw-semibold"><?= h($table->name) ?></div>
                            </td>
                            <td><?= h($table->unit->name ?? 'Không rõ') ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?= $this->Html->link(
                                        '<i class="bi bi-eye"></i>',
                                        ['action' => 'view', $table->id],
                                        ['class' => 'btn btn-outline-info', 'escape' => false, 'title' => 'Xem']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="bi bi-pencil"></i>',
                                        ['action' => 'edit', $table->id],
                                        ['class' => 'btn btn-outline-warning', 'escape' => false, 'title' => 'Sửa']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="bi bi-trash"></i>',
                                        ['action' => 'delete', $table->id],
                                        [
                                            'confirm' => 'Bạn có chắc muốn xóa lĩnh vực "' . h($table->name) . '"?',
                                            'class' => 'btn btn-outline-danger',
                                            'escape' => false,
                                            'title' => 'Xóa'
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
                    <?= $this->Paginator->counter('Hiển thị <b>{:start}-{:end}</b> trong <b>{:count}</b> thẻ lĩnh vực') ?>
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
</style>
<?php $this->end(); ?>

<?php $this->append('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>
<?php $this->end(); ?>

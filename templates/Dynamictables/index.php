<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-table"></i> Danh sách bảng đã tạo</h3>
        <?= $this->Html->link('<i class="bi bi-plus-circle me-1"></i> Tạo bảng mới', ['action' => 'add'], [
            'class' => 'btn btn-success',
            'escape' => false
        ]) ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i><?= h($message) ?>
        </div>
    <?php elseif ($tables->isEmpty()): ?>
        <div class="alert alert-warning shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Chưa có bảng nào được tạo.
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-file-earmark-text me-1"></i> Tên bảng</th>
                                <th><i class="bi bi-clock-history me-1"></i> Ngày tạo</th>
                                <th><i class="bi bi-check2-circle me-1"></i> Tình trạng</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i> Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tables as $table): ?>
                                <tr>
                                    <td><?= h($table->original_name) ?></td>
                                    <td><?= h($table->created->format('d/m/Y H:i')) ?></td>
                                    <td>
                                        <?php
                                            $statusText = STATUS_OPTIONS[$table->status] ?? 'Không xác định';
                                            $statusClass = $table->status == 1 ? 'success' : 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <i class="bi <?= $table->status == 1 ? 'bi-check-circle' : 'bi-clock' ?> me-1"></i>
                                            <?= h($statusText) ?>
                                        </span>
                                    </td>
                                    <td class="text-center d-flex flex-wrap justify-content-center gap-2">
                                        <?= $this->Html->link('<i class="bi bi-eye me-1"></i>Xem', [
                                            'controller' => 'DynamicTables',
                                            'action' => 'view',
                                            $table->table_name
                                        ], [
                                            'class' => 'btn btn-sm btn-secondary',
                                            'escape' => false
                                        ]) ?>

                                        <?= $this->Html->link('<i class="bi bi-pencil-square me-1"></i>Sửa', [
                                            'controller' => 'DynamicTables',
                                            'action' => 'edit',
                                            $table->table_name
                                        ], [
                                            'class' => 'btn btn-sm btn-primary',
                                            'escape' => false
                                        ]) ?>

                                        <?= $this->Form->postLink('<i class="bi bi-trash me-1"></i>Xóa', [
                                            'controller' => 'DynamicTables',
                                            'action' => 'delete',
                                            $table->table_name
                                        ], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'escape' => false,
                                            'confirm' => 'Bạn có chắc chắn muốn xóa bảng này?'
                                        ]) ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

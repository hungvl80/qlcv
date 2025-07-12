<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Lịch sử thao tác (Audit Logs)</h2>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Người thao tác</th>
                <th>Hành động</th>
                <th>Model</th>
                <th>ID Bản ghi</th>
                <th>Thời gian</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($auditLogs as $log): ?>
            <tr>
                <td><?= h($log->id) ?></td>
                <td>
                    <?= $log->user ? h($log->user->full_name) : 'Unknown' ?>
                </td>
                <td><?= h($log->action) ?></td>
                <td><?= h($log->model) ?></td>
                <td><?= h($log->model_id) ?></td>
                <td><?= $log->created ? $log->created->format('d/m/Y H:i') : '' ?></td>
                <td>
                    <?= $this->Html->link('Xem', ['action' => 'view', $log->id], [
                        'class' => 'btn btn-info btn-sm'
                    ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-center">
            <?= $this->Paginator->prev('«', [
                'tag' => 'li',
                'class' => 'page-item',
                'disabledTag' => 'span',
                'disabledClass' => 'page-item disabled',
                'linkAttributes' => ['class' => 'page-link'],
            ]) ?>

            <?= $this->Paginator->numbers([
                'tag' => 'li',
                'class' => 'page-item',
                'currentTag' => 'span',
                'currentClass' => 'page-item active',
                'currentLinkClass' => 'page-link',
                'linkAttributes' => ['class' => 'page-link'],
            ]) ?>

            <?= $this->Paginator->next('»', [
                'tag' => 'li',
                'class' => 'page-item',
                'disabledTag' => 'span',
                'disabledClass' => 'page-item disabled',
                'linkAttributes' => ['class' => 'page-link'],
            ]) ?>
        </ul>
    </nav>


</div>

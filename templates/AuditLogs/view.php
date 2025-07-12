<div class="container mt-5">
    <h2>Chi tiết Audit Log #<?= h($auditLog->id) ?></h2>

    <table class="table table-bordered mt-3">
        <tr>
            <th>Người thao tác</th>
            <td><?= $auditLog->user ? h($auditLog->user->full_name) : 'Unknown' ?></td>
        </tr>
        <tr>
            <th>Hành động</th>
            <td><?= h($auditLog->action) ?></td>
        </tr>
        <tr>
            <th>Model</th>
            <td><?= h($auditLog->model) ?></td>
        </tr>
        <tr>
            <th>ID Bản ghi</th>
            <td><?= h($auditLog->model_id) ?></td>
        </tr>
        <tr>
            <th>Thời gian</th>
            <td><?= $auditLog->created ? $auditLog->created->format('d/m/Y H:i') : '' ?></td>
        </tr>
        <tr>
            <th>Dữ liệu chi tiết</th>
            <td>
                <pre><?= h(json_encode(json_decode($auditLog->data, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
            </td>
        </tr>
    </table>

    <?= $this->Html->link('Quay lại danh sách', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
</div>

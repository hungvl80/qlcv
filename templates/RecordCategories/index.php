<div class="card">
    <div class="card-header">
        <h5 class="card-title">Danh mục Đề mục Hồ sơ</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đề mục</th>
                    <th>Ký hiệu</th>
                    <th>Level</th>
                    <th>Thuộc đề mục</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $cat->id ?></td>
                    <td><?= h($cat->name) ?></td>
                    <td><?= h($cat->code) ?></td>
                    <td><?= h($cat->level) ?></td>
                    <td><?= h($cat->parent_id) ?></td>
                    <td>
                        <?= $this->Html->link('Xem', ['action' => 'view', $cat->id], ['class' => 'btn btn-sm btn-primary']) ?>
                        <?= $this->Html->link('Sửa', ['action' => 'edit', $cat->id], ['class' => 'btn btn-sm btn-warning']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $this->Paginator->numbers() ?>
    </div>
</div>

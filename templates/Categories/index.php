<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách Lĩnh vực</h2>
        <?= $this->Html->link('Thêm mới', ['action' => 'add'], ['class' => 'btn btn-success']) ?>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên lĩnh vực</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= h($category->id) ?></td>
                <td><?= h($category->name) ?></td>
                <td><?= h($category->description) ?></td>
                <td>
                    <?= $this->Html->link('Sửa', ['action' => 'edit', $category->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= $this->Form->postLink(
                        'Xóa',
                        ['action' => 'delete', $category->id],
                        ['confirm' => 'Bạn có chắc chắn muốn xóa?', 'class' => 'btn btn-danger btn-sm']
                    ) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

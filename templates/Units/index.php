<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Danh sách Đơn vị</h2>
        <?= $this->Html->link('Thêm mới', ['action' => 'add'], ['class' => 'btn btn-success']) ?>
    </div>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên đơn vị</th>
                <th>Mã đơn vị</th>
                <th>Đơn vị cha</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $unit): ?>
            <tr>
                <td><?= h($unit['id']) ?></td>
                <td><?= h($unit['name']) ?></td>
                <td><?= h($unit['code']) ?></td>
                <td><?= !empty($unit['ParentUnit']['name']) ? h($unit['ParentUnit']['name']) : '-' ?></td>
                <td>
                    <?= $this->Html->link(
                        '<i class="bi bi-eye"></i>', // Icon mắt
                        ['action' => 'view', $unit->id],
                        [
                            'class' => 'btn btn-info btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Xem chi tiết')
                        ]
                    ) ?>

                    <?= $this->Html->link(
                        '<i class="bi bi-pencil"></i>', // Icon bút chì
                        ['action' => 'edit', $unit->id],
                        [
                            'class' => 'btn btn-warning btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Sửa')
                        ]
                    ) ?>

                    <?= $this->Form->postLink(
                        '<i class="bi bi-trash"></i>', // Icon thùng rác
                        [
                            'controller' => 'Units',
                            'action' => 'delete',
                            $unit->id
                        ],
                        [
                            'confirm' => 'Bạn có chắc chắn muốn xóa người dùng ' . h($unit->full_name) . ' không?',
                            'class' => 'btn btn-danger btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Xóa')
                        ]
                    ) ?>
                </td>
            </tr>
            <?php
                if (!empty($unit['children'])):
                    foreach ($unit['children'] as $child):
            ?>
                <tr>
                    <td><?= h($child['id']) ?></td>
                    <td>— <?= h($child['name']) ?></td>
                    <td><?= h($child['code']) ?></td>
                    <td><?= h($unit['name']) ?></td>
                    <td>
                        <?= $this->Html->link('Sửa', ['action' => 'edit', $child['id']], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= $this->Form->postLink(
                            'Xóa',
                            ['action' => 'delete', $child['id']],
                            ['confirm' => 'Bạn có chắc chắn muốn xóa?', 'class' => 'btn btn-danger btn-sm']
                        ) ?>
                    </td>
                </tr>
            <?php
                    endforeach;
                endif;
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách người dùng</h2>
        <?= $this->Html->link('Thêm mới', ['action' => 'add'], ['class' => 'btn btn-success']) ?>
    </div>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Quyền tài khoản</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->id) ?></td>
                <td><?= h($user->username) ?></td>
                <td><?= h($user->full_name) ?></td>
                <td><?= h($user->email) ?></td>
                <td>
                    <?= $user->is_admin ? 'Tài khoản quản trị' : 'Tài khoản thường' ?>
                </td>
                <td>
                    <?= $this->Html->link(
                        '<i class="bi bi-eye"></i>', // Icon mắt
                        ['action' => 'view', $user->id],
                        [
                            'class' => 'btn btn-info btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Xem chi tiết')
                        ]
                    ) ?>

                    <?= $this->Html->link(
                        '<i class="bi bi-pencil"></i>', // Icon bút chì
                        ['action' => 'edit', $user->id],
                        [
                            'class' => 'btn btn-warning btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Sửa')
                        ]
                    ) ?>

                    <?= $this->Form->postLink(
                        '<i class="fas fa-trash-alt"></i>', // Icon thùng rác
                        [
                            'controller' => 'Users',
                            'action' => 'delete',
                            $user->id
                        ],
                        [
                            'confirm' => 'Bạn có chắc chắn muốn xóa người dùng ' . h($user->full_name) . ' không?',
                            'class' => 'btn btn-danger btn-action-icon', // Thêm class tùy chỉnh 'btn-action-icon'
                            'escape' => false,
                            'title' => __('Xóa')
                        ]
                    ) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
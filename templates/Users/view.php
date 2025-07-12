<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="container mt-4">
    <div class="row">
        <aside class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= __('Thao tác') ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-edit me-2"></i>' . __('Sửa Người dùng'),
                            ['action' => 'edit', $user->id],
                            ['class' => 'btn btn-warning btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-trash-alt me-2"></i>' . __('Xóa Người dùng'),
                            ['action' => 'delete', $user->id],
                            ['confirm' => __('Bạn có chắc chắn muốn xóa {0} không?', $user->username), 'class' => 'btn btn-danger btn-sm', 'escape' => false]
                        ) ?>
                        <hr>
                        <?= $this->Html->link(
                            '<i class="fas fa-list me-2"></i>' . __('Danh sách Người dùng'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-outline-secondary btn-sm', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus me-2"></i>' . __('Thêm mới Người dùng'),
                            ['action' => 'add'],
                            ['class' => 'btn btn-outline-success btn-sm', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>
        </aside>
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><?= h($user->full_name) ?></h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('ID') ?></th>
                                <td><?= $this->Number->format($user->id) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Tên đăng nhập') ?></th>
                                <td><?= h($user->username) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Họ tên') ?></th>
                                <td><?= h($user->full_name) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Email') ?></th>
                                <td><?= h($user->email) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Điện thoại') ?></th>
                                <td><?= h($user->phone) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Đơn vị công tác') ?></th>
                                <td>
                                    <?php if ($user->has('unit') && $user->unit->name !== null): ?>
                                        <?= $this->Html->link(h($user->unit->name), ['controller' => 'Units', 'action' => 'view', $user->unit->id]) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?= __('Chưa có') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Chức vụ') ?></th>
                                <td>
                                    <?php if ($user->has('position') && $user->position->title !== null): ?>
                                        <?= $this->Html->link(h($user->position->title), ['controller' => 'Positions', 'action' => 'view', $user->position->id]) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?= __('Chưa có') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Kích hoạt') ?></th>
                                <td>
                                    <?php if ($user->is_active): ?>
                                        <span class="badge bg-success badge-custom"><?= __('Có') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger badge-custom"><?= __('Không') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Quyền tài khoản') ?></th>
                                <td>
                                    <?php if ($user->is_admin): ?>
                                        <span class="badge bg-primary badge-custom"><?= __('Tài khoản quản trị') ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary badge-custom"><?= __('Tài khoản thường') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Ngày tạo') ?></th>
                                <td><?= h($user->created) ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-nowrap"><?= __('Ngày sửa') ?></th>
                                <td><?= h($user->modified) ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php if (!empty($user->avatar)): ?>
                    <div class="text-center mt-4 p-3 border rounded bg-light">
                        <h4><?= __('Ảnh đại diện') ?></h4>
                        <?= $this->Html->image($user->avatar, ['class' => 'img-fluid rounded-circle shadow-sm avatar-display', 'style' => 'max-width: 150px; height: auto;']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
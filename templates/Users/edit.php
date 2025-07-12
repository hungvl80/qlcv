<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|string[] $units
 * @var \Cake\Collection\CollectionInterface|string[] $positions
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend class="mb-4"><?= __('Chỉnh sửa Người dùng') ?></legend>
                <div class="form-group mb-3">
                    <?= $this->Form->control('username', [
                        'class' => 'form-control',
                        'label' => __('Tên đăng nhập'),
                        'required' => true,
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('full_name', [
                        'class' => 'form-control',
                        'label' => __('Họ và tên'),
                        'required' => true,
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('email', [
                        'class' => 'form-control',
                        'label' => __('Email'),
                        'required' => true,
                        'type' => 'email'
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('password', [
                        'class' => 'form-control',
                        'label' => __('Mật khẩu (Để trống nếu không muốn thay đổi)'),
                        'type' => 'password',
                        'value' => '' // Đặt trống để không hiển thị mật khẩu cũ
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('unit_id', [
                        'options' => $units,
                        'empty' => '--- Chọn Đơn vị ---',
                        'class' => 'form-control',
                        'label' => __('Đơn vị công tác'),
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('position_id', [
                        'options' => $positions,
                        'empty' => '--- Chọn Chức vụ ---',
                        'class' => 'form-control',
                        'label' => __('Chức vụ'),
                    ]) ?>
                </div>
                </fieldset>
            <?= $this->Form->button(__('Lưu Chỉnh sửa'), ['class' => 'btn btn-success mt-3']) ?>
            <?= $this->Html->link(
                __('Trở về trang trước'),
                ['action' => 'index'],
                ['class' => 'side-nav-item btn btn-outline-secondary btn-block mt-3']
            ) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
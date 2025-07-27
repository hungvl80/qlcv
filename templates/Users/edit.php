<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|string[] $units
 * @var \Cake\Collection\CollectionInterface|string[] $positions
 * @var array $canAssignTasksOptions
 */

$loggedInUser = $this->request->getAttribute('identity');
$isLoggedInUserAdmin = $loggedInUser && $loggedInUser->is_admin;
$isEditingOwnProfile = $loggedInUser && $loggedInUser->id === $user->id;
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i><?= __('Tài khoản') ?></h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-eye me-2"></i>' . __('Xem hồ sơ'),
                            ['action' => 'view', $user->id],
                            ['class' => 'btn btn-outline-primary text-start', 'escape' => false]
                        ) ?>
                        
                        <?php if ($isLoggedInUserAdmin): ?>
                            <?= $this->Form->postLink(
                                '<i class="fas fa-user-times me-2"></i>' . __('Xóa tài khoản'),
                                ['action' => 'delete', $user->id],
                                [
                                    'confirm' => __('Bạn có chắc chắn muốn xóa {0}?', $user->username),
                                    'class' => 'btn btn-outline-danger text-start',
                                    'escape' => false
                                ]
                            ) ?>
                        <?php endif; ?>
                        
                        <?php if ($isEditingOwnProfile): ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-key me-2"></i>' . __('Đổi mật khẩu'),
                                ['action' => 'changePassword', $user->id],
                                ['class' => 'btn btn-outline-warning text-start', 'escape' => false]
                            ) ?>
                        <?php endif; ?>
                        
                        <hr class="my-2">
                        
                        <?php if ($isLoggedInUserAdmin): ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-users me-2"></i>' . __('Danh sách người dùng'),
                                ['action' => 'index'],
                                ['class' => 'btn btn-outline-secondary text-start', 'escape' => false]
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-user-plus me-2"></i>' . __('Thêm người dùng'),
                                ['action' => 'add'],
                                ['class' => 'btn btn-outline-success text-start', 'escape' => false]
                            ) ?>
                        <?php else: ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-tasks me-2"></i>' . __('Công việc của tôi'),
                                ['controller' => 'Tasks', 'action' => 'index'],
                                ['class' => 'btn btn-outline-info text-start', 'escape' => false]
                            ) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- User Status Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i><?= __('Trạng thái') ?></h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted"><?= __('Tình trạng tài khoản') ?></small>
                        <div class="mt-1">
                            <?php if ($user->is_active): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i><?= __('Đang hoạt động') ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i><?= __('Đã khóa') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted"><?= __('Quyền hạn') ?></small>
                        <div class="mt-1">
                            <?php if ($user->is_admin): ?>
                                <span class="badge bg-primary">
                                    <i class="fas fa-user-shield me-1"></i><?= __('Quản trị viên') ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user me-1"></i><?= __('Người dùng') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <small class="text-muted"><?= __('Quyền giao việc') ?></small>
                        <div class="mt-1">
                            <?php
                                $assignPermissionValue = $user->can_assign_tasks ?? 0;
                                $permissionText = $canAssignTasksOptions[$assignPermissionValue] ?? __('Không xác định');
                                
                                $badgeClass = 'bg-secondary';
                                if ($assignPermissionValue === 1) {
                                    $badgeClass = 'bg-info';
                                } elseif ($assignPermissionValue === 2) {
                                    $badgeClass = 'bg-primary';
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>">
                                <i class="fas fa-tasks me-1"></i><?= h($permissionText) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            <?= __('Chỉnh sửa thông tin') ?>
                        </h4>
                        <?php if ($isEditingOwnProfile): ?>
                            <span class="badge bg-success">
                                <i class="fas fa-user me-1"></i><?= __('Hồ sơ của bạn') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body">
                    <?= $this->Form->create($user, ['class' => 'needs-validation', 'novalidate' => true]) ?>
                    
                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?= $this->Form->text('username', [
                                    'class' => 'form-control' . (!$isLoggedInUserAdmin && !$isEditingOwnProfile ? ' bg-light' : ''),
                                    'placeholder' => __('Tên đăng nhập'),
                                    'disabled' => !$isLoggedInUserAdmin && !$isEditingOwnProfile,
                                    'readonly' => !$isLoggedInUserAdmin && $isEditingOwnProfile,
                                    'required' => true
                                ]) ?>
                                <label for="username"><?= __('Tên đăng nhập') ?></label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?= $this->Form->text('full_name', [
                                    'class' => 'form-control' . (!$isLoggedInUserAdmin && !$isEditingOwnProfile ? ' bg-light' : ''),
                                    'placeholder' => __('Họ và tên'),
                                    'disabled' => !$isLoggedInUserAdmin && !$isEditingOwnProfile,
                                    'readonly' => !$isLoggedInUserAdmin && $isEditingOwnProfile,
                                    'required' => true
                                ]) ?>
                                <label for="full-name"><?= __('Họ và tên') ?></label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?= $this->Form->email('email', [
                                    'class' => 'form-control',
                                    'placeholder' => __('Email'),
                                    'required' => true
                                ]) ?>
                                <label for="email"><?= __('Email') ?></label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?= $this->Form->tel('phone', [
                                    'class' => 'form-control',
                                    'placeholder' => __('Số điện thoại')
                                ]) ?>
                                <label for="phone"><?= __('Số điện thoại') ?></label>
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <?= $this->Form->password('password', [
                                    'class' => 'form-control',
                                    'placeholder' => __('Mật khẩu'),
                                    'value' => '',
                                    'aria-describedby' => 'passwordHelp'
                                ]) ?>
                                <label for="password"><?= __('Mật khẩu mới') ?></label>
                                <small id="passwordHelp" class="form-text text-muted">
                                    <?= __('Để trống nếu không muốn thay đổi mật khẩu') ?>
                                </small>
                            </div>
                        </div>
                        
                        <!-- Organization Information -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?php if ($isLoggedInUserAdmin): ?>
                                    <?= $this->Form->select('unit_id', $units, [
                                        'class' => 'form-select',
                                        'empty' => __('--- Chọn Đơn vị ---'),
                                        'value' => $user->unit_id
                                    ]) ?>
                                    <label for="unit-id"><?= __('Đơn vị công tác') ?></label>
                                <?php else: ?>
                                    <?= $this->Form->text('unit_display', [
                                        'class' => 'form-control bg-light',
                                        'value' => $user->has('unit') ? h($user->unit->name) : __('Chưa có'),
                                        'disabled' => true
                                    ]) ?>
                                    <label for="unit-display"><?= __('Đơn vị công tác') ?></label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <?php if ($isLoggedInUserAdmin): ?>
                                    <?= $this->Form->select('position_id', $positions, [
                                        'class' => 'form-select',
                                        'empty' => __('--- Chọn Chức vụ ---'),
                                        'value' => $user->position_id
                                    ]) ?>
                                    <label for="position-id"><?= __('Chức vụ') ?></label>
                                <?php else: ?>
                                    <?= $this->Form->text('position_display', [
                                        'class' => 'form-control bg-light',
                                        'value' => $user->has('position') ? h($user->position->name) : __('Chưa có'),
                                        'disabled' => true
                                    ]) ?>
                                    <label for="position-display"><?= __('Chức vụ') ?></label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Admin Only Fields -->
                        <?php if ($isLoggedInUserAdmin): ?>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <?= $this->Form->checkbox('is_active', [
                                        'class' => 'form-check-input',
                                        'id' => 'is-active',
                                        'checked' => $user->is_active
                                    ]) ?>
                                    <label class="form-check-label" for="is-active">
                                        <i class="fas fa-power-off me-1"></i><?= __('Kích hoạt tài khoản') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <?= $this->Form->checkbox('is_admin', [
                                        'class' => 'form-check-input',
                                        'id' => 'is-admin',
                                        'checked' => $user->is_admin
                                    ]) ?>
                                    <label class="form-check-label" for="is-admin">
                                        <i class="fas fa-user-shield me-1"></i><?= __('Quyền quản trị') ?>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <?= $this->Form->select('can_assign_tasks', $canAssignTasksOptions, [
                                        'class' => 'form-select',
                                        'value' => $user->can_assign_tasks ?? 0
                                    ]) ?>
                                    <label for="can-assign-tasks"><?= __('Quyền giao việc') ?></label>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <?= $this->Html->link(
                            __('<i class="fas fa-arrow-left me-1"></i> Hủy bỏ'),
                            ['action' => 'view', $user->id],
                            ['class' => 'btn btn-outline-secondary', 'escape' => false]
                        ) ?>
                        
                        <?= $this->Form->button(
                            __('Lưu thay đổi'),
                            ['class' => 'btn btn-primary px-4', 'escape' => false]
                        ) ?>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->append('css'); ?>
<style>
    .form-floating > label {
        padding: 0.5rem 0.75rem;
    }
    
    .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }
    
    .form-switch .form-check-input {
        width: 2.5em;
        margin-left: -2.5em;
    }
    
    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
</style>
<?php $this->end(); ?>

<?php $this->append('script'); ?>
<script>
// Enable Bootstrap 5 validation
(function () {
    'use strict'
    
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
<?php $this->end(); ?>
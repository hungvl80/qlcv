<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|string[] $units
 * @var \Cake\Collection\CollectionInterface|string[] $positions
 * @var array $canAssignTasksOptions
 */
?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i><?= __('Thêm Người Dùng Mới') ?></h2>
                        <?= $this->Html->link(
                            __('<i class="bi bi-arrow-left-circle me-1"></i> Quay lại danh sách'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-sm btn-light', 'escape' => false]
                        ) ?>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <?= $this->Form->create($user, [
                        'class' => 'needs-validation',
                        'novalidate' => true,
                        'id' => 'user-form',
                        'autocomplete' => 'off'
                    ]) ?>
                    
                    <div class="row g-4 mb-4">
                        <!-- Thông tin cơ bản -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('username', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'username',
                                    'placeholder' => __('Tên đăng nhập'),
                                    'required' => true
                                ]) ?>
                                <label for="username"><?= __('Tên đăng nhập') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập tên đăng nhập') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('full_name', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'full_name',
                                    'placeholder' => __('Họ và tên'),
                                    'required' => true
                                ]) ?>
                                <label for="full_name"><?= __('Họ và tên') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập họ và tên') ?></div>
                            </div>
                        </div>
                        
                        <!-- Thông tin liên hệ -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->email('email', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'email',
                                    'placeholder' => __('Email'),
                                    'required' => true
                                ]) ?>
                                <label for="email"><?= __('Email') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập email hợp lệ') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->tel('phone', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'phone',
                                    'placeholder' => __('Số điện thoại')
                                ]) ?>
                                <label for="phone"><?= __('Số điện thoại') ?></label>
                            </div>
                        </div>
                        
                        <!-- Mật khẩu -->
                        <div class="col-12">
                            <div class="form-floating">
                                <?= $this->Form->password('password', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'password',
                                    'placeholder' => __('Mật khẩu'),
                                    'required' => true,
                                    'data-toggle' => 'password'
                                ]) ?>
                                <label for="password"><?= __('Mật khẩu') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập mật khẩu') ?></div>
                                <small class="form-text text-muted">
                                    <?= __('Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt') ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <!-- Đơn vị & Chức vụ -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->select('unit_id', $units, [
                                    'class' => 'form-select form-select-lg',
                                    'id' => 'unit_id',
                                    'empty' => __('-- Chọn đơn vị --')
                                ]) ?>
                                <label for="unit_id"><?= __('Đơn vị công tác') ?></label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->select('position_id', $positions, [
                                    'class' => 'form-select form-select-lg',
                                    'id' => 'position_id',
                                    'empty' => __('-- Chọn chức vụ --')
                                ]) ?>
                                <label for="position_id"><?= __('Chức vụ') ?></label>
                            </div>
                        </div>
                        
                        <!-- Quyền hạn -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->select('can_assign_tasks', $canAssignTasksOptions, [
                                    'class' => 'form-select form-select-lg',
                                    'id' => 'can_assign_tasks'
                                ]) ?>
                                <label for="can_assign_tasks"><?= __('Quyền giao việc') ?></label>
                            </div>
                        </div>
                        
                        <!-- Trạng thái & Quyền admin -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <?= $this->Form->checkbox('is_active', [
                                            'class' => 'form-check-input',
                                            'id' => 'is_active',
                                            'checked' => true
                                        ]) ?>
                                        <label class="form-check-label fw-bold" for="is_active"><?= __('Kích hoạt tài khoản') ?></label>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <?= $this->Form->checkbox('is_admin', [
                                            'class' => 'form-check-input',
                                            'id' => 'is_admin'
                                        ]) ?>
                                        <label class="form-check-label fw-bold" for="is_admin"><?= __('Quyền quản trị viên') ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between border-top pt-4">
                        <button type="reset" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-counterclockwise me-2"></i><?= __('Đặt lại') ?>
                        </button>
                        
                        <div>
                            <?= $this->Html->link(
                                __('<i class="bi bi-x-circle me-2"></i> Hủy bỏ'),
                                ['action' => 'index'],
                                ['class' => 'btn btn-outline-danger me-2', 'escape' => false]
                            ) ?>
                            
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-2"></i><?= __('Lưu người dùng') ?>
                            </button>
                        </div>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->append('script'); ?>
<script>
$(document).ready(function() {
    // Enable form validation
    (function() {
        'use strict';
        
        var form = document.getElementById('user-form');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    })();
    
    // Password strength indicator
    $('#password').on('input', function() {
        // Implement password strength logic here
    });
});
</script>
<?php $this->end(); ?>

<?php $this->append('css'); ?>
<style>
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-floating>label {
        padding: 1rem 1.25rem;
    }
    
    .form-control-lg, .form-select-lg {
        height: calc(3.5rem + 2px);
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
    }
    
    .form-check-input {
        width: 3em;
        margin-left: -1.5em;
        height: 1.5em;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
<?php $this->end(); ?>
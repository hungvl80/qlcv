<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $canAssignTasksOptions
 */

$loggedInUser = $this->request->getAttribute('identity');
$isLoggedInUserAdmin = $loggedInUser && $loggedInUser->is_admin;
$isCurrentUser = $this->Identity->get('id') === $user->id;

if (!isset($canAssignTasksOptions)) {
    $canAssignTasksOptions = [
        0 => __('Không có quyền giao việc'),
        1 => __('Giao việc nội bộ'),
        2 => __('Giao việc liên đơn vị'),
    ];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i><?= __('Tài khoản') ?></h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <?= $this->Html->link(
                            '<i class="fas fa-user-edit me-2"></i>' . __('Cập nhật thông tin'),
                            ['action' => 'edit', $user->id],
                            ['class' => 'btn btn-outline-primary text-start', 'escape' => false]
                        ) ?>
                        
                        <?php if ($isCurrentUser): ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-key me-2"></i>' . __('Đổi mật khẩu'),
                                ['action' => 'changePassword', $user->id],
                                ['class' => 'btn btn-outline-warning text-start', 'escape' => false]
                            ) ?>
                        <?php endif; ?>
                        
                        <?php if ($isLoggedInUserAdmin): ?>
                            <?= $this->Form->postLink(
                                '<i class="fas fa-user-times me-2"></i>' . __('Xóa tài khoản'),
                                ['action' => 'delete', $user->id],
                                [
                                    'confirm' => __('Bạn có chắc chắn muốn xóa tài khoản {0}?', $user->username),
                                    'class' => 'btn btn-outline-danger text-start',
                                    'escape' => false
                                ]
                            ) ?>
                        <?php endif; ?>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-grid gap-2">
                        <?php if ($isLoggedInUserAdmin): ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-users me-2"></i>' . __('Quản lý người dùng'),
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
        </div>
        
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <?= h($user->full_name) ?>
                            <?php if ($isCurrentUser): ?>
                                <small class="badge bg-success ms-2"><?= __('Đây là bạn') ?></small>
                            <?php endif; ?>
                        </h4>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-id-card me-1"></i>ID: <?= h($user->id) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <div class="position-relative">
                                <?php if (!empty($user->avatar)): ?>
                                    <div class="avatar-container mx-auto">
                                        <?= $this->Html->image('/uploads/avatars/' . basename($user->avatar), [
                                            'class' => 'img-fluid rounded-circle shadow avatar-display',
                                            'alt' => __('Ảnh đại diện của ') . h($user->full_name)
                                        ]) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-placeholder mx-auto">
                                        <?php
                                            $initials = 'NA';
                                            if (!empty($user->full_name)) {
                                                $parts = explode(' ', $user->full_name);
                                                $initials = mb_strtoupper(mb_substr($parts[0], 0, 1));
                                                if (count($parts) > 1) {
                                                    $initials .= mb_strtoupper(mb_substr(end($parts), 0, 1));
                                                }
                                            } elseif (!empty($user->username)) {
                                                $initials = mb_strtoupper(mb_substr($user->username, 0, 2));
                                            }
                                        ?>
                                        <span><?= h($initials) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($isCurrentUser): ?>
                                    <div class="mt-3">
                                        <div class="btn-group" role="group">
                                            <?= $this->Html->link(
                                                '<i class="fas fa-camera"></i> ' . __('Đổi ảnh đại diện'), // Added text
                                                ['action' => 'uploadAvatar', $user->id],
                                                [
                                                    'class' => 'btn btn-sm btn-primary',
                                                    'escape' => false,
                                                    'title' => __('Đổi ảnh đại diện'),
                                                    'data-bs-toggle' => 'tooltip'
                                                ]
                                            ) ?>
                                            <?php if (!empty($user->avatar)): ?>
                                                <?= $this->Form->postLink(
                                                    '<i class="fas fa-trash-alt"></i> ' . __('Xóa ảnh'), // Added text
                                                    ['action' => 'delete-avatar', $user->id],
                                                    [
                                                        'confirm' => __('Bạn có chắc chắn muốn xóa ảnh đại diện?'),
                                                        'class' => 'btn btn-sm btn-outline-danger',
                                                        'escape' => false,
                                                        'title' => __('Xóa ảnh'),
                                                        'data-bs-toggle' => 'tooltip'
                                                    ]
                                                ) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4">
                                <?php if ($user->is_admin): ?>
                                    <span class="badge bg-primary mb-2">
                                        <i class="fas fa-user-shield me-1"></i><?= __('Quản trị viên') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary mb-2">
                                        <i class="fas fa-user me-1"></i><?= __('Người dùng') ?>
                                    </span>
                                <?php endif; ?>
                                
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
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted text-uppercase mb-3"><?= __('Thông tin cá nhân') ?></h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-user-tag me-2 text-muted"></i>
                                                <strong><?= __('Tên đăng nhập') ?>:</strong>
                                                <span class="float-end"><?= h($user->username) ?></span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-envelope me-2 text-muted"></i>
                                                <strong><?= __('Email') ?>:</strong>
                                                <span class="float-end">
                                                    <?= h($user->email) ?>
                                                    <?php if ($user->email): ?>
                                                        <a href="mailto:<?= h($user->email) ?>" class="ms-2 text-decoration-none">
                                                            <i class="fas fa-paper-plane text-primary"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-phone me-2 text-muted"></i>
                                                <strong><?= __('Điện thoại') ?>:</strong>
                                                <span class="float-end">
                                                    <?= h($user->phone) ?>
                                                    <?php if ($user->phone): ?>
                                                        <a href="tel:<?= h($user->phone) ?>" class="ms-2 text-decoration-none">
                                                            <i class="fas fa-phone-volume text-primary"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted text-uppercase mb-3"><?= __('Thông tin công việc') ?></h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-building me-2 text-muted"></i>
                                                <strong><?= __('Đơn vị') ?>:</strong>
                                                <span class="float-end">
                                                    <?php if ($user->has('unit')): ?>
                                                        <?= h($user->unit->name) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted"><?= __('Chưa có') ?></span>
                                                    <?php endif; ?>
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-briefcase me-2 text-muted"></i>
                                                <strong><?= __('Chức vụ') ?>:</strong>
                                                <span class="float-end">
                                                    <?= h($user->position->name ?? __('Chưa có')) ?>
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-tasks me-2 text-muted"></i>
                                                <strong><?= __('Quyền giao việc') ?>:</strong>
                                                <span class="float-end">
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
                                                    <span class="badge <?= $badgeClass ?>"><?= h($permissionText) ?></span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted text-uppercase mb-3"><?= __('Thời gian') ?></h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-calendar-plus me-2 text-muted"></i>
                                                <strong><?= __('Ngày tạo') ?>:</strong>
                                                <span class="float-end">
                                                    <?= $user->created ? $user->created->format('d/m/Y H:i') : 'N/A' ?>
                                                </span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-calendar-check me-2 text-muted"></i>
                                                <strong><?= __('Ngày cập nhật') ?>:</strong>
                                                <span class="float-end">
                                                    <?= $user->modified ? $user->modified->format('d/m/Y H:i') : 'N/A' ?>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <?php if ($isLoggedInUserAdmin): ?>
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning bg-opacity-10 py-2">
                                                <h6 class="mb-0"><i class="fas fa-user-shield me-2"></i><?= __('Quản trị') ?></h6>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="form-check form-switch mb-2">
                                                    <?= $this->Form->control('is_active', [
                                                        'type' => 'checkbox',
                                                        'class' => 'form-check-input',
                                                        'label' => ['text' => __('Kích hoạt tài khoản'), 'class' => 'form-check-label'],
                                                        'disabled' => true,
                                                        'checked' => $user->is_active
                                                    ]) ?>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <?= $this->Form->control('is_admin', [
                                                        'type' => 'checkbox',
                                                        'class' => 'form-check-input',
                                                        'label' => ['text' => __('Quyền quản trị'), 'class' => 'form-check-label'],
                                                        'disabled' => true,
                                                        'checked' => $user->is_admin
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->append('css'); ?>
<style>
    /* Avatar Styles */
    .avatar-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .avatar-display {
        width: 100%; /* Đảm bảo ảnh lấp đầy chiều rộng của container */
        height: 100%; /* Đảm bảo ảnh lấp đầy chiều cao của container */
        object-fit: cover; /* Quan trọng: Cắt ảnh để vừa với khung mà không làm biến dạng */
        object-position: center; /* Đảm bảo phần trung tâm của ảnh hiển thị */
        display: block; /* Loại bỏ khoảng trắng dưới ảnh */
    }
    .avatar-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6e8efb, #a777e3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        font-weight: bold;
        border: 3px solid #fff;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 50px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .timeline-content {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 6px;
        position: relative;
    }
    
    .timeline-content:after {
        content: '';
        position: absolute;
        left: -10px;
        top: 15px;
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right: 10px solid #f8f9fa;
    }
    
    .timeline-date {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 5px;
        display: block;
    }
    
    /* Custom Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .float-end {
            float: none !important;
            display: block;
        }
    }
</style>
<?php $this->end(); ?>

<?php $this->append('script'); ?>
<script>
$(document).ready(function() {
    // Enable tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize any other JS plugins if needed
});
</script>
<?php $this->end(); ?>
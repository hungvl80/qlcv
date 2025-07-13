<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Quản lý Công việc');
?>

<style>
    /* Custom styling for compact icon tabs */
    .compact-icon-tabs {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
        border-bottom: 1px solid #dee2e6;
        gap: 5px;
    }
    
    .compact-icon-tabs .nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        min-width: 80px;
        border: none;
        color: #495057;
        background: transparent;
        border-radius: 0;
        text-decoration: none;
    }
    
    .compact-icon-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }
    
    .compact-icon-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    .compact-icon-tabs .nav-icon {
        font-size: 1.25rem;
        margin-bottom: 0.25rem;
    }
    
    .compact-icon-tabs .nav-label {
        font-size: 0.75rem;
        line-height: 1.2;
        text-align: center;
    }
    
    /* Hide scrollbar but keep functionality */
    .compact-icon-tabs::-webkit-scrollbar {
        display: none;
    }
</style>

<div class="row mt-3">
    <div class="col-12">
        <!-- Compact Navigation Tabs -->
        <div class="compact-icon-tabs mb-3" id="mainNav">
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-list-task nav-icon"></i><span class="nav-label">Công việc</span>',
                    ['controller' => 'Tasks', 'action' => 'index'],
                    ['class' => 'nav-link active', 'escape' => false, 'aria-current' => 'page']
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-arrow-repeat nav-icon"></i><span class="nav-label">Lặp lại</span>',
                    ['controller' => 'Tasks', 'action' => 'recurring'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-diagram-3 nav-icon"></i><span class="nav-label">Quy trình</span>',
                    ['controller' => 'BusinessProcesses', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-lightbulb nav-icon"></i><span class="nav-label">Sáng kiến</span>',
                    ['controller' => 'Innovations', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-folder nav-icon"></i><span class="nav-label">Hồ sơ</span>',
                    ['controller' => 'TaskFiles', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-alarm nav-icon"></i><span class="nav-label">Nhắc nhở</span>',
                    ['controller' => 'Reminders', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-journal-text nav-icon"></i><span class="nav-label">Ghi chú</span>',
                    ['controller' => 'Notes', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
            <div class="nav-item">
                <?= $this->Html->link(
                    '<i class="bi bi-bell nav-icon"></i><span class="nav-label">Thông báo</span>',
                    ['controller' => 'Notifications', 'action' => 'index'],
                    ['class' => 'nav-link', 'escape' => false]
                ) ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="card p-3">
            <h4 class="mb-3">Danh sách công việc</h4>
            
            <!-- Compact Task Table -->
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="30%">Tên công việc</th>
                            <th width="15%">Trạng thái</th>
                            <th width="15%">Hạn chót</th>
                            <th width="15%">Người giao</th>
                            <th width="20%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Hoàn thành báo cáo tháng</td>
                            <td><span class="badge bg-warning text-dark">Đang thực hiện</span></td>
                            <td>31/07/2025</td>
                            <td>Nguyễn Văn A</td>
                            <td>
                                <?= $this->Html->link(
                                    '<i class="bi bi-eye-fill"></i>',
                                    ['action' => 'view', 1],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Xem chi tiết']
                                ) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Lên kế hoạch dự án X</td>
                            <td><span class="badge bg-info text-white">Mới</span></td>
                            <td>15/08/2025</td>
                            <td>Trần Thị B</td>
                            <td>
                                <?= $this->Html->link(
                                    '<i class="bi bi-eye-fill"></i>',
                                    ['action' => 'view', 2],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false, 'title' => 'Xem chi tiết']
                                ) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(['block' => true]); ?>
<script>
    // Highlight current tab based on URL
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const links = document.querySelectorAll('#mainNav .nav-link');
        
        links.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            } else {
                link.classList.remove('active');
                link.removeAttribute('aria-current');
            }
        });
    });
</script>
<?php $this->Html->scriptEnd(); ?>
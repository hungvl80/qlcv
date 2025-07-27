<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface|\Cake\Collection\CollectionInterface $tasks
 * @var \Cake\Collection\CollectionInterface $categories
 */
$this->assign('title', 'Quản lý Công việc');

// Lấy tên tab từ query parameter, mặc định là 'tasks'
$activeTab = $this->getRequest()->getQuery('tab', 'tasks');

// Ánh xạ tab ID sang tên tab để dễ dàng kiểm tra
$tabMap = [
    'tasks' => 'tab-tasks',
    'recurring' => 'tab-recurring',
    'process' => 'tab-process',
    'innovations' => 'tab-innovations',
    'ho-so' => 'tab-ho-so',
    'reminders' => 'tab-reminders',
    'notes' => 'tab-notes',
    'notifications' => 'tab-notifications',
];

// Hàm để kiểm tra nếu một tab là active
$isActive = function ($tabName) use ($activeTab) {
    return $tabName === $activeTab ? 'active' : '';
};

// Hàm để kiểm tra nếu một tab pane là active
$isPaneActive = function ($tabName) use ($activeTab) {
    return $tabName === $activeTab ? 'show active' : '';
};

?>

<style>
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
        transition: color 0.2s ease-in-out, border-bottom-color 0.2s ease-in-out; /* Thêm transition */
    }

    .compact-icon-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }

    .compact-icon-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd; /* Giữ màu xanh khi hover */
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
</style>

<div class="row mt-3">
    <div class="col-12">
        <!-- Compact Navigation Tabs -->
        <div class="compact-icon-tabs mb-3" id="mainNav" role="tablist">
            <div class="nav-item">
                <a class="nav-link <?= $isActive('tasks') ?>" id="tasks-tab" href="<?= $this->Url->build(['?' => ['tab' => 'tasks']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-tasks" type="button" role="tab" aria-controls="tab-tasks" aria-selected="<?= $isActive('tasks') ? 'true' : 'false' ?>">
                    <i class="bi bi-list-task nav-icon"></i>
                    <span class="nav-label">Công việc</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('recurring') ?>" id="recurring-tab" href="<?= $this->Url->build(['?' => ['tab' => 'recurring']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-recurring" type="button" role="tab" aria-controls="tab-recurring" aria-selected="<?= $isActive('recurring') ? 'true' : 'false' ?>">
                    <i class="bi bi-arrow-repeat nav-icon"></i>
                    <span class="nav-label">Lặp lại</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('process') ?>" id="process-tab" href="<?= $this->Url->build(['?' => ['tab' => 'process']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-process" type="button" role="tab" aria-controls="tab-process" aria-selected="<?= $isActive('process') ? 'true' : 'false' ?>">
                    <i class="bi bi-diagram-3 nav-icon"></i>
                    <span class="nav-label">Quy trình</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('innovations') ?>" id="innovations-tab" href="<?= $this->Url->build(['?' => ['tab' => 'innovations']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-innovations" type="button" role="tab" aria-controls="tab-innovations" aria-selected="<?= $isActive('innovations') ? 'true' : 'false' ?>">
                    <i class="bi bi-lightbulb nav-icon"></i>
                    <span class="nav-label">Sáng kiến</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('ho-so') ?>" id="ho-so-tab" href="<?= $this->Url->build(['?' => ['tab' => 'ho-so']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-ho-so" type="button" role="tab" aria-controls="tab-ho-so" aria-selected="<?= $isActive('ho-so') ? 'true' : 'false' ?>">
                    <i class="bi bi-folder nav-icon"></i>
                    <span class="nav-label">Hồ sơ</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('reminders') ?>" id="reminders-tab" href="<?= $this->Url->build(['?' => ['tab' => 'reminders']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-reminders" type="button" role="tab" aria-controls="tab-reminders" aria-selected="<?= $isActive('reminders') ? 'true' : 'false' ?>">
                    <i class="bi bi-alarm nav-icon"></i>
                    <span class="nav-label">Nhắc nhở</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('notes') ?>" id="notes-tab" href="<?= $this->Url->build(['?' => ['tab' => 'notes']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-notes" type="button" role="tab" aria-controls="tab-notes" aria-selected="<?= $isActive('notes') ? 'true' : 'false' ?>">
                    <i class="bi bi-journal-text nav-icon"></i>
                    <span class="nav-label">Ghi chú</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link <?= $isActive('notifications') ?>" id="notifications-tab" href="<?= $this->Url->build(['?' => ['tab' => 'notifications']]) ?>" data-bs-toggle="tab" data-bs-target="#tab-notifications" type="button" role="tab" aria-controls="tab-notifications" aria-selected="<?= $isActive('notifications') ? 'true' : 'false' ?>">
                    <i class="bi bi-bell nav-icon"></i>
                    <span class="nav-label">Thông báo</span>
                </a>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Tab: Công việc -->
            <div class="tab-pane fade <?= $isPaneActive('tasks') ?>" id="tab-tasks" role="tabpanel" aria-labelledby="tasks-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Danh sách Công việc</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Mô tả</th>
                                    <th>Công việc cha</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td><?= h($task->id) ?></td>
                                        <td><?= h($task->title) ?></td>
                                        <td><?= h($task->description) ?></td>
                                        <td><?= h($task->parent_task->title ?? '-') ?></td>
                                        <td>
                                            <?= $this->Html->link('Xem', ['action' => 'view', $task->id], ['class' => 'btn btn-sm btn-primary']) ?>
                                            <?= $this->Html->link('Sửa', ['action' => 'edit', $task->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                            <?= $this->Form->postLink(
                                                'Xóa',
                                                ['action' => 'delete', $task->id],
                                                ['confirm' => 'Bạn chắc chắn muốn xóa?', 'class' => 'btn btn-sm btn-danger']
                                            ) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?= $this->Paginator->numbers() ?>
                    </div>
                </div>
            </div>

            <!-- Tab: Lặp lại -->
            <div class="tab-pane fade <?= $isPaneActive('recurring') ?>" id="tab-recurring" role="tabpanel" aria-labelledby="recurring-tab">
                <p>Nội dung tab Lặp lại...</p>
            </div>

            <!-- Tab: Quy trình -->
            <div class="tab-pane fade <?= $isPaneActive('process') ?>" id="tab-process" role="tabpanel" aria-labelledby="process-tab">
                <p>Nội dung tab Quy trình...</p>
            </div>

            <!-- Tab: Sáng kiến -->
            <div class="tab-pane fade <?= $isPaneActive('innovations') ?>" id="tab-innovations" role="tabpanel" aria-labelledby="innovations-tab">
                <p>Nội dung tab Sáng kiến...</p>
            </div>

            <!-- Tab: Hồ sơ -->
            <div class="tab-pane fade <?= $isPaneActive('ho-so') ?>" id="tab-ho-so" role="tabpanel" aria-labelledby="ho-so-tab">
                <?= $this->element('RecordCategories/index_content'); ?>
            </div>

            <!-- Các tab còn lại -->
            <div class="tab-pane fade <?= $isPaneActive('reminders') ?>" id="tab-reminders" role="tabpanel" aria-labelledby="reminders-tab">
                <p>Nội dung tab Nhắc nhở...</p>
            </div>
            <div class="tab-pane fade <?= $isPaneActive('notes') ?>" id="tab-notes" role="tabpanel" aria-labelledby="notes-tab">
                <p>Nội dung tab Ghi chú...</p>
            </div>
            <div class="tab-pane fade <?= $isPaneActive('notifications') ?>" id="tab-notifications" role="tabpanel" aria-labelledby="notifications-tab">
                <p>Nội dung tab Thông báo...</p>
            </div>
        </div>
    </div>
</div>

<?php $this->append('script'); ?>
<script>
// JavaScript để xử lý Active Tab trên URL và Bootstrap Tabs
document.addEventListener('DOMContentLoaded', function() {
    // Lấy hash từ URL (ví dụ: #tab-ho-so) hoặc query parameter (ví dụ: ?tab=ho-so)
    let activeTabId = new URLSearchParams(window.location.search).get('tab');
    let targetTabElement;

    if (activeTabId) {
        // Nếu có query parameter 'tab', tìm tab link và tab pane tương ứng
        targetTabElement = document.getElementById(activeTabId + '-tab'); // Ví dụ: ho-so-tab
    } else {
        // Mặc định tab 'tasks' là active nếu không có query parameter
        targetTabElement = document.getElementById('tasks-tab');
    }

    if (targetTabElement) {
        // Kích hoạt tab bằng Bootstrap's JS API
        let tab = new bootstrap.Tab(targetTabElement);
        tab.show();
    }

    // Cập nhật URL khi click vào tab (không tải lại trang)
    const tabLinks = document.querySelectorAll('.compact-icon-tabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Ngăn chặn hành vi mặc định của thẻ <a> khi dùng data-bs-toggle="tab"
            // Bootstrap đã xử lý việc hiển thị tab, chúng ta chỉ cần cập nhật URL
            event.preventDefault(); 

            const tabName = this.id.replace('-tab', ''); // Lấy tên tab từ ID (e.g., 'tasks' from 'tasks-tab')
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('tab', tabName);
            window.history.pushState({ path: newUrl.href }, '', newUrl.href);

            // Kích hoạt tab bằng Bootstrap's JS API (đảm bảo tab được active đúng)
            let tab = new bootstrap.Tab(this);
            tab.show();
        });
    });
});
</script>
<?php $this->end(); ?>
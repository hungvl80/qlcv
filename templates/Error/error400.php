<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Routing\Router;

// You can keep your layout for error pages if you have one, or set to 'default' or 'blank'
$this->layout = 'default'; // Or 'error' if you have a specific error layout

if (Configure::read('debug')) :
    $this->layout = 'dev_error'; // CakePHP's default debug error layout
    if (in_array('Debugger', Router::extensions())) :
        $this->plugin = null;
        // Only show detailed error in debug mode
        Debugger::printVar($error->getMessage());
        Debugger::printVar($error->getTrace());
    endif;
endif;
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="error-container text-center">
                <h1 class="display-1 text-danger">404</h1>
                <h2 class="display-5 text-muted mb-4">Trang không tìm thấy hoặc bạn không có quyền truy cập.</h2>
                <p class="lead mb-4">Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc bạn không có quyền để xem nội dung này.</p>
                <?= $this->Html->link(
                    __('<i class="bi bi-arrow-left-circle me-2"></i> Quay về trang quản trị'),
                    // **QUAN TRỌNG:** Đảm bảo đường dẫn này trỏ đến trang quản trị thực tế của bạn
                    // Ví dụ phổ biến:
                    // - Nếu admin dashboard của bạn là /admin/dashboards/index: ['controller' => 'Dashboards', 'action' => 'index', 'prefix' => 'Admin']
                    // - Nếu admin dashboard của bạn là /users/index (cho admin): ['controller' => 'Users', 'action' => 'index']
                    // - Nếu bạn có một trang "home" dành cho admin: ['controller' => 'Pages', 'action' => 'display', 'admin_home']
                    // Tôi sẽ dùng một ví dụ phổ biến là UsersController/index. Bạn hãy điều chỉnh nếu cần.
                    ['controller' => 'Users', 'action' => 'index'],
                    ['class' => 'btn btn-primary btn-lg', 'escape' => false]
                ) ?>
            </div>
        </div>
    </div>
</div>

<?php $this->append('css'); ?>
<style>
    .error-container {
        padding: 50px 0;
    }
    .error-container h1 {
        font-size: 8rem;
        font-weight: bold;
    }
    .error-container h2 {
        font-weight: 300;
    }
    .error-container .btn {
        margin-top: 20px;
    }
</style>
<?php $this->end(); ?>
<style>
    /* CSS Tùy chỉnh để tạo thẻ vuông và cân đối nội dung */
    .card-square {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 110px; /* Giảm nhẹ min-height */
        padding: 0.6rem; /* Giảm padding tổng thể của thẻ */
        border-radius: 0.5rem;
        transition: transform 0.3s ease;
    }

    .card-square:hover {
        transform: scale(1.05);
    }

    .card-square .card-body {
        padding: 0.2rem; /* Giảm padding của body bên trong thẻ */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
    }

    .card-square .card-title {
        font-size: 0.95rem; /* Giảm kích thước tiêu đề để giữ trên 1 hàng */
        margin-bottom: 0.15rem; /* Giảm khoảng cách dưới tiêu đề */
        white-space: nowrap; /* Đảm bảo tiêu đề không xuống dòng */
        overflow: hidden; /* Ẩn phần tràn */
        text-overflow: ellipsis; /* Thêm dấu ... nếu tràn */
        font-weight: 600; /* Có thể làm đậm hơn chút cho dễ đọc */
    }

    .card-square .card-title i {
        font-size: 1.1rem; /* Giảm kích thước icon */
        margin-right: 0.15rem; /* Giảm khoảng cách giữa icon và chữ */
    }

    .card-square .card-text {
        font-size: 0.85rem; /* Giảm kích thước chữ mô tả */
        margin-bottom: 0.5rem; /* Giảm khoảng cách dưới mô tả */
        font-weight: 500;
        white-space: nowrap; /* Đảm bảo mô tả không xuống dòng */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card.bg-warning .card-text {
        color: #212529; /* Đảm bảo màu chữ tối cho nền warning */
    }

    .card-square .btn {
        font-size: 0.75rem; /* Giảm kích thước nút */
        padding: 0.25rem 0.8rem; /* Giảm padding nút */
    }

    /* Điều chỉnh cho màn hình nhỏ hơn (mobile) */
    @media (max-width: 767.98px) {
        .card-square {
            min-height: 90px; /* Thẻ nhỏ hơn trên mobile */
            padding: 0.3rem;
        }
        .card-square .card-title {
            font-size: 0.85rem;
        }
        .card-square .card-title i {
            font-size: 1rem;
        }
        .card-square .card-text {
            font-size: 0.75rem;
        }
        .card-square .btn {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
        }
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">Trang quản trị hệ thống</h2>

    <div class="row g-4 justify-content-center">
        <div class="col-md-2 col-sm-4 col-6">
            <div class="card text-white bg-primary h-100 card-square">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-people"></i> Người dùng
                    </h5>
                    <p class="card-text"><?= h($usersCount) ?> tài khoản</p>
                    <?= $this->Html->link('Quản lý', ['controller' => 'Users', 'action' => 'index'], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <div class="card text-white bg-success h-100 card-square">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-building"></i> Đơn vị
                    </h5>
                    <p class="card-text"><?= h($unitsCount) ?> đơn vị</p>
                    <?= $this->Html->link('Quản lý', ['controller' => 'Units', 'action' => 'index'], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <div class="card text-white bg-info h-100 card-square">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-person-badge"></i> Chức vụ
                    </h5>
                    <p class="card-text">Có <?= h($positionsCount) ?> chức vụ</p>
                    <?= $this->Html->link('Quản lý', ['controller' => 'Positions', 'action' => 'index'], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <div class="card text-dark bg-warning h-100 card-square">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-gear"></i> Giao việc
                    </h5>
                    <p class="card-text"><?= h($assignmentPermissionsCount) ?> cấu hình giao việc</p>
                    <?= $this->Html->link('Quản lý', ['controller' => 'AssignmentPermissions', 'action' => 'index'], ['class' => 'btn btn-outline-dark']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-4 col-6">
            <div class="card text-white bg-danger h-100 card-square">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-journal-text"></i> Audit Logs
                    </h5>
                    <p class="card-text"><?= h($logsCount) ?> logs</p>
                    <?= $this->Html->link('Xem Logs', ['controller' => 'AuditLogs', 'action' => 'index'], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
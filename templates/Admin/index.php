<style>
    .dashboard-card-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin: 0 auto;
        max-width: 1200px;
    }

    .dashboard-card {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1.5rem 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: none; /* bỏ hiệu ứng hover */
        min-height: 180px;
    }

    .dashboard-card .card-icon {
        font-size: 1.8rem;
        margin-bottom: 12px;
    }

    .dashboard-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .dashboard-card .card-value {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .dashboard-card .card-btn {
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        min-width: 100px;
        margin-top: auto;
    }

    .card-user {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
    }

    .card-unit {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        color: white;
    }

    .card-position {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
        color: white;
    }

    .card-linhvuc {
        background: linear-gradient(135deg, #f6c23e 0%, #e0a800 100%);
        color: white;
    }

    .card-log {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
        color: white;
    }

    @media (max-width: 992px) {
        .dashboard-card-container {
            flex-wrap: wrap;
        }

        .dashboard-card {
            flex: 0 0 calc(50% - 10px);
            min-height: 160px;
        }
    }

    @media (max-width: 576px) {
        .dashboard-card {
            flex: 0 0 100%;
        }
    }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-4">Trang quản trị hệ thống</h2>

    <div class="dashboard-card-container">
        <!-- Người dùng -->
        <div class="dashboard-card card-user">
            <i class="bi bi-people card-icon"></i>
            <h3 class="card-title">Người dùng</h3>
            <div class="card-value"><?= h($usersCount) ?></div>
            <?= $this->Html->link('Quản lý',
                ['controller' => 'Users', 'action' => 'index'],
                ['class' => 'btn btn-outline-light card-btn']) ?>
        </div>

        <!-- Đơn vị -->
        <div class="dashboard-card card-unit">
            <i class="bi bi-building card-icon"></i>
            <h3 class="card-title">Đơn vị</h3>
            <div class="card-value"><?= h($unitsCount) ?></div>
            <?= $this->Html->link('Quản lý',
                ['controller' => 'Units', 'action' => 'index'],
                ['class' => 'btn btn-outline-light card-btn']) ?>
        </div>

        <!-- Chức vụ -->
        <div class="dashboard-card card-position">
            <i class="bi bi-person-badge card-icon"></i>
            <h3 class="card-title">Chức vụ</h3>
            <div class="card-value"><?= h($positionsCount) ?></div>
            <?= $this->Html->link('Quản lý',
                ['controller' => 'Positions', 'action' => 'index'],
                ['class' => 'btn btn-outline-light card-btn']) ?>
        </div>

        <!-- Lĩnh vực -->
        <div class="dashboard-card card-linhvuc">
            <i class="bi bi-tags card-icon"></i>
            <h3 class="card-title">Lĩnh vực</h3>
            <div class="card-value"><?= h($catTablesCount) ?></div>
            <?= $this->Html->link('Quản lý',
                ['controller' => 'CatTables', 'action' => 'index'],
                ['class' => 'btn btn-outline-light card-btn']) ?>
        </div>

        <!-- Nhật ký -->
        <div class="dashboard-card card-log">
            <i class="bi bi-journal-text card-icon"></i>
            <h3 class="card-title">Nhật ký</h3>
            <div class="card-value"><?= h($logsCount) ?></div>
            <?= $this->Html->link('Xem logs',
                ['controller' => 'AuditLogs', 'action' => 'index'],
                ['class' => 'btn btn-outline-light card-btn']) ?>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?: 'HỆ THỐNG QLCV' ?></title>

    <?= $this->Html->meta('icon') ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css') ?>
    <?= $this->Html->css(['normalize.min', 'fonts']) ?>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <?= $this->Html->link(
            '<i class="bi bi-speedometer2 me-2"></i> HỆ THỐNG QLCV',
            ['controller' => 'Admin', 'action' => 'index'],
            ['class' => 'navbar-brand d-flex align-items-center fw-bold', 'escape' => false]
            ) ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <!-- Bổ sung menu nếu cần -->
                </ul>

                <?php
                    $identity = $this->request->getAttribute('identity');
                    if ($identity):
                ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle d-flex align-items-center"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="text-transform: none;">
                            <i class="bi bi-person-circle me-2"></i>
                            <?= h($identity->full_name) ?>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <?= $this->Html->link(
                                    '<i class="bi bi-person-lines-fill me-2"></i> Thông tin tài khoản',
                                    ['controller' => 'Users', 'action' => 'view', $identity->id],
                                    ['escape' => false, 'class' => 'dropdown-item']
                                ) ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <?= $this->Html->link(
                                    '<i class="bi bi-box-arrow-right me-2"></i> Đăng xuất',
                                    ['controller' => 'Login', 'action' => 'logout'],
                                    ['escape' => false, 'class' => 'dropdown-item']
                                ) ?>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>
                    <span class="navbar-text text-white fw-semibold d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        Khách
                    </span>
                <?php endif; ?>
            </div>

        </div>
    </nav>



    <main class="main">
        <div class="container">
            <?php if (!empty($this->Breadcrumbs->getcrumbs())): ?>
            <nav aria-label="breadcrumb" class="mt-3 mb-3">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <?= $this->Breadcrumbs->render(
                        ['class' => 'breadcrumb-item'], // Class cho các item (ngoại trừ item cuối)
                        ['class' => 'breadcrumb-item active', 'aria-current' => 'page'] // Class cho item cuối cùng
                    ) ?>
                </ol>
            </nav>
            <?php endif; ?>
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
    <?= $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') ?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ?: 'HỆ THỐNG QLCV' ?></title>

    <?= $this->Html->meta('icon') ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?= $this->Html->css(['normalize.min', 'fonts']) ?>
    <?= $this->Html->css('style.css') ?> 
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <main class="main">
        <div class="container-fluid"> 
            <div class="mx-3">
                <?= $this->Flash->render() ?> 
            </div>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
    <?= $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') ?>
</body>
</html>
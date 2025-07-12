<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center mb-4">Đăng nhập Hệ thống</h3>
            <?= $this->Form->create() ?>

            <div class="mb-3">
                <?= $this->Form->control('username', [
                    'label' => 'Tên đăng nhập',
                    'class' => 'form-control',
                    'required' => true
                ]) ?>
            </div>

            <div class="mb-3">
                <?= $this->Form->control('password', [
                    'label' => 'Mật khẩu',
                    'class' => 'form-control',
                    'required' => true
                ]) ?>
            </div>

            <?= $this->Form->button('Đăng nhập', ['class' => 'btn btn-primary w-100']) ?>
            <?= $this->Form->end() ?>

            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>

<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Đăng nhập hệ thống');
?>

<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        align-items: center;
        padding: 20px;
    }
    
    .login-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .login-header {
        background: #4e73df;
        color: white;
        padding: 20px;
        text-align: center;
    }
    
    .login-body {
        padding: 30px;
        background: white;
    }
    
    .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding-left: 15px;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-color: #4e73df;
    }
    
    .login-btn {
        background: #4e73df;
        border: none;
        height: 45px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }
    
    .login-btn:hover {
        background: #2e59d9;
    }
    
    .forgot-password {
        text-align: center;
        margin-top: 15px;
    }
    
    .forgot-password a {
        color: #6c757d;
        text-decoration: none;
    }
    
    .forgot-password a:hover {
        color: #4e73df;
    }
</style>
<?= $this->Flash->render() ?>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h3><i class="bi bi-shield-lock"></i> ĐĂNG NHẬP</h3>
            <p class="mb-0">Vui lòng nhập thông tin tài khoản</p>
        </div>
        
        <div class="login-body">
            <?= $this->Form->create() ?>
                <div class="mb-4">
                    <?= $this->Form->control('username', [
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => 'Tên đăng nhập',
                        'required' => true
                    ]) ?>
                </div>

                <div class="mb-4">
                    <?= $this->Form->control('password', [
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => 'Mật khẩu',
                        'required' => true
                    ]) ?>
                </div>

                <div class="mb-3">
                        <?= $this->Form->button(
                        'Đăng nhập',
                        [
                            'class' => 'btn btn-primary login-btn w-100',
                            'escape' => false
                        ]
                    ) ?>

                </div>
                
                <div class="forgot-password">
                    <?= $this->Html->link('Quên mật khẩu?', ['action' => 'forgotPassword']) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
<script defer>
    // Tự động focus vào ô username
    const usernameField = document.querySelector('input[name="username"]');
    if (usernameField) usernameField.focus();
</script>
<?php $this->Html->scriptEnd(); ?>
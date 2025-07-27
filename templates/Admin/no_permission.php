<?php $this->layout = 'auth'; ?>
<div class="unauthorized-container">
    <div class="unauthorized-card">
        <div class="unauthorized-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#dc3545" viewBox="0 0 16 16">
                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
            </svg>
        </div>
        
        <h1 class="unauthorized-title"><?= __('Truy cập bị từ chối') ?></h1>
        
        <div class="unauthorized-message">
            <p><?= __('Xin lỗi, bạn không có quyền truy cập trang này.') ?></p>
            <p class="text-muted"><?= __('Nếu bạn cho rằng đây là lỗi, vui lòng liên hệ quản trị hệ thống.') ?></p>
        </div>
        
        <div class="unauthorized-actions">
            <?= $this->Html->link(
                '<i class="bi bi-arrow-left me-2"></i>' . __('Quay lại trang phù hợp'),
                $backUrl,
                ['class' => 'btn btn-primary', 'escape' => false]
            ) ?>
        </div>
    </div>
</div>

<style>
    .unauthorized-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }
    
    .unauthorized-card {
        max-width: 500px;
        width: 100%;
        padding: 2.5rem;
        text-align: center;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .unauthorized-icon {
        margin-bottom: 1.5rem;
    }
    
    .unauthorized-title {
        color: #dc3545;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .unauthorized-message {
        margin-bottom: 2rem;
    }
    
    .unauthorized-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    @media (min-width: 576px) {
        .unauthorized-actions {
            flex-direction: row;
            justify-content: center;
        }
    }
</style>
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0 fw-bold"><i class="bi bi-briefcase-fill me-2"></i>Thêm Chức Vụ Mới</h2>
                        <?= $this->Html->link(
                            __('<i class="bi bi-arrow-left-circle me-1"></i> Quay lại danh sách'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-sm btn-light', 'escape' => false]
                        ) ?>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <?= $this->Form->create($position, [
                        'class' => 'needs-validation',
                        'novalidate' => true,
                        'id' => 'position-form', // Đổi ID form
                        'autocomplete' => 'off'
                    ]) ?>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('name', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'name',
                                    'placeholder' => __('Tên chức vụ'),
                                    'required' => true
                                ]) ?>
                                <label for="name"><?= __('Tên chức vụ') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập tên chức vụ') ?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('code', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'code',
                                    'placeholder' => __('Mã chức vụ'),
                                    // 'required' => true // Mã chức vụ thường không bắt buộc, tùy theo logic của bạn
                                ]) ?>
                                <label for="code"><?= __('Mã chức vụ (tùy chọn)') ?></label>
                                </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <?= $this->Form->control('level', [
                                    'type' => 'number',
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'level',
                                    'placeholder' => __('Cấp bậc'),
                                    'required' => true,
                                    'min' => 1, // Cấp bậc tối thiểu 1
                                    'max' => 5, // Cấp bậc tối đa 5 sao
                                ]) ?>
                                <label for="level"><?= __('Cấp bậc') ?> <span class="text-danger">*</span></label>
                                <div class="invalid-feedback"><?= __('Vui lòng nhập cấp bậc (1-5)') ?></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <?= $this->Form->textarea('description', [
                                    'class' => 'form-control',
                                    'id' => 'description',
                                    'placeholder' => __('Mô tả chức vụ'),
                                    'style' => 'height: 100px;',
                                    'rows' => 3 // Số hàng mặc định
                                ]) ?>
                                <label for="description"><?= __('Mô tả chức vụ') ?></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between border-top pt-4">
                        <button type="reset" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-counterclockwise me-2"></i><?= __('Đặt lại') ?>
                        </button>
                        
                        <div>
                            <?= $this->Html->link(
                                __('<i class="bi bi-x-circle me-2"></i> Hủy bỏ'),
                                ['action' => 'index'],
                                ['class' => 'btn btn-outline-danger me-2', 'escape' => false]
                            ) ?>
                            
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-2"></i><?= __('Lưu chức vụ') ?>
                            </button>
                        </div>
                    </div>
                    
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->append('script'); ?>
<script>
$(document).ready(function() {
    // Enable form validation
    (function() {
        'use strict';
        
        var form = document.getElementById('position-form'); // Đổi ID form
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    })();
});
</script>
<?php $this->end(); ?>

<?php $this->append('css'); ?>
<style>
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .form-floating>label {
        padding: 1rem 1.25rem;
    }
    
    .form-control-lg, .form-select-lg {
        height: calc(3.5rem + 2px);
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
<?php $this->end(); ?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0 fw-bold"><i class="bi bi-building me-2"></i>Thêm Đơn Vị Mới</h2>
                        <?= $this->Html->link(
                            __('<i class="bi bi-arrow-left-circle me-1"></i> Quay lại danh sách'),
                            ['action' => 'index'],
                            ['class' => 'btn btn-sm btn-light', 'escape' => false]
                        ) ?>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <?= $this->Form->create($unit, [
                        'class' => 'needs-validation',
                        'novalidate' => true,
                        'id' => 'unit-form',
                        'autocomplete' => 'off'
                    ]) ?>
                    
                    <div class="row g-4 mb-4">
                        <!-- Tên đơn vị -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('name', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'name',
                                    'placeholder' => 'Tên đơn vị',
                                    'required' => true
                                ]) ?>
                                <label for="name">Tên đơn vị <span class="text-danger">*</span></label>
                                <div class="invalid-feedback">Vui lòng nhập tên đơn vị</div>
                            </div>
                        </div>
                        
                        <!-- Mã đơn vị -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <?= $this->Form->text('code', [
                                    'class' => 'form-control form-control-lg',
                                    'id' => 'code',
                                    'placeholder' => 'Mã đơn vị',
                                    'required' => true
                                ]) ?>
                                <label for="code">Mã đơn vị <span class="text-danger">*</span></label>
                                <div class="invalid-feedback">Vui lòng nhập mã đơn vị</div>
                            </div>
                        </div>
                        
                        <!-- Đơn vị cha -->
                        <div class="col-12">
                            <div class="form-floating">
                                <?= $this->Form->select('parent_id', $parentUnits, [
                                    'class' => 'form-select form-select-lg',
                                    'id' => 'parent_id',
                                    'empty' => '-- Chọn đơn vị cha --'
                                ]) ?>
                                <label for="parent_id">Đơn vị cha</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between border-top pt-4">
                        <button type="reset" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Đặt lại
                        </button>
                        
                        <div>
                            <?= $this->Html->link(
                                __('<i class="bi bi-x-circle me-2"></i> Hủy bỏ'),
                                ['action' => 'index'],
                                ['class' => 'btn btn-outline-danger me-2', 'escape' => false]
                            ) ?>
                            
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-2"></i>Lưu đơn vị
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
        
        var form = document.getElementById('unit-form');
        
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
<?php
/**
 * Element Form chung để thêm và chỉnh sửa cột
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ColumnAlias $columnAlias Đối tượng ColumnAlias (chỉ có khi edit)
 * @var \App\Model\Entity\UserTable $table Đối tượng UserTable (chỉ có khi add)
 * @var array $typeMapping Mảng ánh xạ kiểu dữ liệu (từ Configure::read('ColumnTypeMapping'))
 * @var array $dbTypeOptions Mảng tùy chọn cho select box kiểu dữ liệu (ví dụ: ['varchar' => 'varchar'])
 * @var bool $isEdit Biến cờ để biết đang ở chế độ sửa hay thêm
 */

use Cake\Core\Configure;

// Đảm bảo $typeMapping được truyền từ controller
if (!isset($typeMapping)) {
    $typeMapping = Configure::read('ColumnTypeMapping');
}

// KHÔNG CẦN $dbTypeOptions NỮA, CHÚNG TA DÙNG TRỰC TIẾP $typeMapping CHO OPTIONS CỦA SELECT BOX
// if (!isset($dbTypeOptions)) {
//     $dbTypeOptions = array_combine(array_keys($typeMapping), array_keys($typeMapping));
// }

// Xác định entity để bind form (null cho add, $columnAlias cho edit)
$entity = $isEdit ? $columnAlias : (isset($columnAlias) ? $columnAlias : $this->Form->create(null)->entity());

// Xác định action URL cho form
$formAction = $isEdit ? ['action' => 'editColumnAlias', $columnAlias->id] : ['action' => 'addColumn', $table->table_name];

// Xác định tiêu đề form và văn bản nút submit
$formTitle = $isEdit ? 'Chỉnh sửa Cột: ' . h($columnAlias->original_name) : 'Thêm cột mới cho bảng: ' . h($table->original_name);
$buttonText = $isEdit ? '<i class="bi bi-save"></i> Cập nhật' : '<i class="bi bi-plus-circle"></i> Thêm cột';

// Xác định link hủy bỏ
$cancelLink = $isEdit ? ['action' => 'edit', $columnAlias->user_table->table_name] : $this->request->referer(); 
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?= $formTitle ?></h4>
                </div>
                <div class="card-body">
                    <?= $this->Form->create($entity, ['url' => $formAction, 'type' => 'post']) ?>
                    <fieldset>
                        <legend class="h5 mb-3 text-secondary">Thông tin cơ bản</legend>
                        
                        <div class="form-group required">
                            <?= $this->Form->control('original_name', [
                                'label' => 'Tên cột', 
                                'class' => 'form-control',
                                'required' => true,
                                'placeholder' => 'Tên sẽ hiển thị cho người dùng'
                            ]) ?>
                            <small class="text-muted">Tên sẽ hiển thị cho người dùng</small>
                        </div>
                        
                        <div class="form-group required">
                            <?= $this->Form->control('data_type', [ // Đảm bảo đây là 'data_type'
                                'label' => 'Kiểu dữ liệu', 
                                'class' => 'form-control',
                                'options' => $typeMapping, // SỬ DỤNG TRỰC TIẾP $typeMapping ở đây
                                'empty' => 'Chọn kiểu dữ liệu',
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="form-group form-check">
                            <?= $this->Form->checkbox('null', [
                                'class' => 'form-check-input',
                                'id' => 'nullable'
                            ]) ?>
                            <label class="form-check-label" for="nullable">Cho phép giá trị NULL</label>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('default', [
                                'label' => 'Giá trị mặc định',
                                'class' => 'form-control',
                                'placeholder' => 'Không bắt buộc'
                            ]) ?>
                        </div>
                    </fieldset>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <?= $this->Form->button($buttonText, ['class' => 'btn btn-success', 'escapeTitle' => false]) ?>
                        <?= $this->Html->link(
                            '<i class="bi bi-x-circle"></i> Hủy bỏ',
                            $cancelLink,
                            ['class' => 'btn btn-outline-secondary', 'escape' => false]
                        ) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
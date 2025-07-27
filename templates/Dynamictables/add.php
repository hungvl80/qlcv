<?php
/**
 * Form tạo bảng mới
 * @var \Cake\View\View $this
 */
?>

<div class="container mt-5">
    <h2>Tạo bảng dữ liệu mới</h2>

    <?= $this->Form->create(null) ?>
        <div class="mb-3">
            <?= $this->Form->control('table_name', [
                'label' => 'Tên bảng',
                'class' => 'form-control',
                'required' => true
            ]) ?>
        </div>

        <?= $this->Form->button('<i class="bi bi-plus-circle"></i> Tạo bảng', [
            'class' => 'btn btn-primary',
            'escapeTitle' => false // Quan trọng: Cho phép HTML trong title của button
        ]) ?>
        <?= $this->Html->link('⬅️ Quay lại', ['action' => 'index'], ['class' => 'btn btn-secondary ms-2']) ?>
    <?= $this->Form->end() ?>
</div>

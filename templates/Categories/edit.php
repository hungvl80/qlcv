<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>

<div class="container mt-5">
    <div class="col-md-8 offset-md-2">
        <h2>Sửa Lĩnh vực</h2>

        <?= $this->Form->create($category) ?>

        <div class="mb-3">
            <?= $this->Form->label('name', 'Tên lĩnh vực', ['class' => 'form-label']) ?>
            <?= $this->Form->control('name', ['class' => 'form-control', 'label' => false]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('description', 'Mô tả', ['class' => 'form-label']) ?>
            <?= $this->Form->control('description', ['type' => 'textarea', 'class' => 'form-control', 'label' => false]) ?>
        </div>

        <?= $this->Form->button('Cập nhật', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Hủy', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>

        <?= $this->Form->end() ?>
    </div>
</div>

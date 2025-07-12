<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="positions form content">
            <?= $this->Form->create($position) ?>
            <fieldset>
                <legend class="mb-4"><?= __('Chỉnh sửa Chức vụ') ?></legend>
                <div class="form-group mb-3">
                    <?= $this->Form->control('name', [
                        'class' => 'form-control',
                        'label' => __('Tên chức vụ'),
                        'required' => true,
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('code', [
                        'class' => 'form-control',
                        'label' => __('Mã chức vụ (tùy chọn)'),
                    ]) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $this->Form->control('level', [
                        'type' => 'number',
                        'class' => 'form-control',
                        'label' => __('Cấp bậc'),
                        'required' => true,
                        'min' => 0, // Đảm bảo cấp bậc không âm
                    ]) ?>
                </div>
            </fieldset>
            <?= $this->Form->button(__('Lưu Chỉnh sửa'), ['class' => 'btn btn-success mt-3']) ?>
            <?= $this->Html->link(
                __('Trở về trang trước'),
                ['action' => 'index'],
                ['class' => 'side-nav-item btn btn-outline-secondary btn-block mt-3']
            ) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="col-md-8 offset-md-2">
        <h2>Thêm Đơn vị</h2>

        <?= $this->Form->create($unit) ?>

        <div class="mb-3">
            <?= $this->Form->label('name', 'Tên đơn vị', ['class' => 'form-label']) ?>
            <?= $this->Form->control('name', ['class' => 'form-control', 'label' => false]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('code', 'Mã đơn vị', ['class' => 'form-label']) ?>
            <?= $this->Form->control('code', ['class' => 'form-control', 'label' => false]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('parent_id', 'Đơn vị cha', ['class' => 'form-label']) ?>
            <?= $this->Form->control('parent_id', [
                'options' => $parentUnits,
                'empty' => 'Chọn đơn vị cha',
                'class' => 'form-select',
                'label' => false
            ]) ?>
        </div>

        <?= $this->Form->button('Lưu', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Hủy', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>

        <?= $this->Form->end() ?>
    </div>
</div>

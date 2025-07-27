<div class="card-body p-4">
    <?= $this->Form->create($catTable, [
        'class' => 'needs-validation',
        'novalidate' => true,
        'id' => 'cat-table-form',
        'autocomplete' => 'off'
    ]) ?>
    <div class="row g-4 mb-4">
        <!-- Tên lĩnh vực -->
        <div class="col-md-6">
            <div class="form-floating">
                <?= $this->Form->text('name', [
                    'class' => 'form-control form-control-lg',
                    'id' => 'name',
                    'placeholder' => 'Tên lĩnh vực',
                    'required' => true
                ]) ?>
                <label for="name">Tên lĩnh vực <span class="text-danger">*</span></label>
                <div class="invalid-feedback">Vui lòng nhập tên lĩnh vực</div>
            </div>
        </div>

        <!-- Đơn vị quản lý -->
        <div class="col-md-6">
            <div class="form-floating mb-3">
                <?= $this->Form->select('unit_id', $units ?? [], [
                    'class' => 'form-select',
                    'empty' => __('-- Chọn đơn vị --'),
                    'required' => true,
                    'id' => 'unit-id',
                    'value' => $catTable->unit_id ?? null
                ]) ?>
                <label for="unit-id"><?= __('Đơn vị quản lý') ?> <span class="text-danger">*</span></label>
                <div class="invalid-feedback">Vui lòng chọn đơn vị</div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between border-top pt-4">
        <button type="reset" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-counterclockwise me-2"></i>Đặt lại
        </button>

        <div>
            <?= $this->Html->link(
                '<i class="bi bi-x-circle me-2"></i> Hủy bỏ',
                ['action' => 'index'],
                ['class' => 'btn btn-outline-danger me-2', 'escape' => false]
            ) ?>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save2 me-2"></i>Lưu lĩnh vực
            </button>
        </div>
    </div>

    <?= $this->Form->end() ?>
</div>

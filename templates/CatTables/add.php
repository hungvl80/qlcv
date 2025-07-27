<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0 fw-bold">
                            <i class="bi bi-tags me-2"></i>Thêm Lĩnh vực mới
                        </h2>
                        <?= $this->Html->link(
                            '<i class="bi bi-arrow-left-circle me-1"></i> Quay lại danh sách',
                            ['action' => 'index'],
                            ['class' => 'btn btn-sm btn-light', 'escape' => false]
                        ) ?>
                    </div>
                </div>
                <?= $this->element('CatTables/formaddedit') ?>
            </div>
        </div>
    </div>
</div>
<?= $this->element('CatTables/scriptcssaddedit') ?>
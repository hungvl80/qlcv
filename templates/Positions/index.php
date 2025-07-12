<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Position> $positions
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><?= __('Danh sách Chức vụ') ?></h3>
        <?= $this->Html->link(
            __('Thêm mới Chức vụ'),
            ['action' => 'add'],
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-light">
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('code') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('level') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                    <th scope="col" class="actions text-center"><?= __('Thao tác') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($positions->toArray())): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có chức vụ nào được tìm thấy.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($positions as $position): ?>
                    <tr>
                        <td><?= $this->Number->format($position->id) ?></td>
                        <td><?= h($position->name) ?></td>
                        <td><?= h($position->code) ?></td>
                        <td><?= $this->Number->format($position->level) ?></td>
                        <td><?= h($position->created->format('d-m-Y H:i:s')) ?></td>
                        <td><?= h($position->modified->format('d-m-Y H:i:s')) ?></td>
                        <td class="actions text-center text-nowrap">
                            <?= $this->Html->link(
                                '<i class="bi bi-eye"></i>', // Icon cho Xem
                                ['action' => 'view', $position->id],
                                ['class' => 'btn btn-info btn-sm me-1', 'escape' => false, 'title' => __('Xem chi tiết')]
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="bi bi-pencil"></i>', // Icon cho Sửa
                                ['action' => 'edit', $position->id],
                                ['class' => 'btn btn-warning btn-sm me-1', 'escape' => false, 'title' => __('Chỉnh sửa')]
                            ) ?>
                            <?= $this->Form->postLink(
                                '<i class="bi bi-trash"></i>', // Icon cho Xóa
                                ['action' => 'delete', $position->id],
                                [
                                    'confirm' => __('Bạn có chắc chắn muốn xóa chức vụ "{0}" không?', $position->name),
                                    'class' => 'btn btn-danger btn-sm',
                                    'escape' => false,
                                    'title' => __('Xóa')
                                ]
                            ) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="paginator d-flex justify-content-center align-items-center mt-3">
        <ul class="pagination mb-0">
            <?= $this->Paginator->first('<i class="bi bi-chevron-double-left"></i>', ['escape' => false, 'class' => 'page-item']) ?>
            <?= $this->Paginator->prev('<i class="bi bi-chevron-left"></i>', ['escape' => false, 'class' => 'page-item']) ?>
            <?= $this->Paginator->numbers(['class' => 'page-item', 'currentClass' => 'page-item active']) ?>
            <?= $this->Paginator->next('<i class="bi bi-chevron-right"></i>', ['escape' => false, 'class' => 'page-item']) ?>
            <?= $this->Paginator->last('<i class="bi bi-chevron-double-right"></i>', ['escape' => false, 'class' => 'page-item']) ?>
        </ul>
        <p class="mb-0 ms-3 text-muted small">
            <?= $this->Paginator->counter(
                __('Trang {{page}} của {{pages}}, hiển thị {{current}} bản ghi trong tổng số {{count}}')
            ) ?>
        </p>
    </div>
</div>
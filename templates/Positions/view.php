<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Position $position
 */
?>
<div class="row">
    <div class="column-responsive column-80">
        <div class="positions view content">
            <h3 class="mb-4"><?= h($position->name) ?></h3>
            <table class="table table-bordered table-striped">
                <tr>
                    <th scope="row"><?= __('ID') ?></th>
                    <td><?= $this->Number->format($position->id) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Tên chức vụ') ?></th>
                    <td><?= h($position->name) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Mã chức vụ') ?></th>
                    <td><?= h($position->code) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Cấp bậc') ?></th>
                    <td><?= $this->Number->format($position->level) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Ngày tạo') ?></th>
                    <td><?= h($position->created) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Ngày chỉnh sửa') ?></th>
                    <td><?= h($position->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <aside class="column">
        <div class="side-nav">
            <?= $this->Html->link(
                __('Trở về trang trước'),
                ['action' => 'index'],
                ['class' => 'side-nav-item btn btn-outline-secondary btn-block mb-2']
            ) ?>
        </div>
    </aside>
</div>
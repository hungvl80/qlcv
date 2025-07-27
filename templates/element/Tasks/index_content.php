<div class="tab-content mt-3">
    <!-- TAB: Công việc -->
    <div class="tab-pane fade show active" id="tab-tasks">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Danh sách Công việc</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th>Công việc cha</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= h($task->id) ?></td>
                                <td><?= h($task->title) ?></td>
                                <td><?= h($task->description) ?></td>
                                <td><?= h($task->parent_task->title ?? '-') ?></td>
                                <td>
                                    <?= $this->Html->link('Xem', ['action' => 'view', $task->id], ['class' => 'btn btn-sm btn-primary']) ?>
                                    <?= $this->Html->link('Sửa', ['action' => 'edit', $task->id], ['class' => 'btn btn-sm btn-warning']) ?>
                                    <?= $this->Form->postLink(
                                        'Xóa',
                                        ['action' => 'delete', $task->id],
                                        [
                                            'confirm' => 'Bạn chắc chắn muốn xóa?',
                                            'class' => 'btn btn-sm btn-danger'
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?= $this->Paginator->numbers() ?>
            </div>
        </div>
    </div>

    <!-- TAB: Hồ sơ -->
    <div class="tab-pane fade" id="tab-ho-so">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Danh mục Hồ sơ</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đề mục</th>
                            <th>Ký hiệu</th>
                            <th>Level</th>
                            <th>Thuộc đề mục</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= h($cat->id) ?></td>
                                <td><?= h($cat->name) ?></td>
                                <td><?= h($cat->code) ?></td>
                                <td><?= h($cat->level) ?></td>
                                <td><?= h($cat->parent_id) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        'Xem',
                                        ['controller' => 'RecordCategories', 'action' => 'view', $cat->id],
                                        ['class' => 'btn btn-sm btn-primary']
                                    ) ?>
                                    <?= $this->Html->link(
                                        'Sửa',
                                        ['controller' => 'RecordCategories', 'action' => 'edit', $cat->id],
                                        ['class' => 'btn btn-sm btn-warning']
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

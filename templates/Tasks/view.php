<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tasks view content">
            <h3><?= h($task->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($task->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Parent Task') ?></th>
                    <td><?= $task->hasValue('parent_task') ? $this->Html->link($task->parent_task->title, ['controller' => 'Tasks', 'action' => 'view', $task->parent_task->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($task->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Type') ?></th>
                    <td><?= h($task->repeat_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($task->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Assigned By') ?></th>
                    <td><?= $this->Number->format($task->assigned_by) ?></td>
                </tr>
                <tr>
                    <th><?= __('Assigned To') ?></th>
                    <td><?= $task->assigned_to === null ? '' : $this->Number->format($task->assigned_to) ?></td>
                </tr>
                <tr>
                    <th><?= __('Priority') ?></th>
                    <td><?= $this->Number->format($task->priority) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deadline') ?></th>
                    <td><?= h($task->deadline) ?></td>
                </tr>
                <tr>
                    <th><?= __('Repeat Until') ?></th>
                    <td><?= h($task->repeat_until) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($task->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($task->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Is Repeat') ?></th>
                    <td><?= $task->is_repeat ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($task->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Tasks') ?></h4>
                <?php if (!empty($task->child_tasks)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('Assigned By') ?></th>
                            <th><?= __('Assigned To') ?></th>
                            <th><?= __('Deadline') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Priority') ?></th>
                            <th><?= __('Is Repeat') ?></th>
                            <th><?= __('Repeat Type') ?></th>
                            <th><?= __('Repeat Until') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($task->child_tasks as $childTask) : ?>
                        <tr>
                            <td><?= h($childTask->id) ?></td>
                            <td><?= h($childTask->title) ?></td>
                            <td><?= h($childTask->description) ?></td>
                            <td><?= h($childTask->parent_id) ?></td>
                            <td><?= h($childTask->assigned_by) ?></td>
                            <td><?= h($childTask->assigned_to) ?></td>
                            <td><?= h($childTask->deadline) ?></td>
                            <td><?= h($childTask->status) ?></td>
                            <td><?= h($childTask->priority) ?></td>
                            <td><?= h($childTask->is_repeat) ?></td>
                            <td><?= h($childTask->repeat_type) ?></td>
                            <td><?= h($childTask->repeat_until) ?></td>
                            <td><?= h($childTask->created) ?></td>
                            <td><?= h($childTask->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Tasks', 'action' => 'view', $childTask->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $childTask->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Tasks', 'action' => 'delete', $childTask->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $childTask->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
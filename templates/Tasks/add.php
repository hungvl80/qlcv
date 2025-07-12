<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $parentTasks
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tasks form content">
            <?= $this->Form->create($task) ?>
            <fieldset>
                <legend><?= __('Add Task') ?></legend>
                <?php
                    echo $this->Form->control('title');
                    echo $this->Form->control('description');
                    echo $this->Form->control('parent_id', ['options' => $parentTasks, 'empty' => true]);
                    echo $this->Form->control('assigned_by');
                    echo $this->Form->control('assigned_to');
                    echo $this->Form->control('deadline', ['empty' => true]);
                    echo $this->Form->control('status');
                    echo $this->Form->control('priority');
                    echo $this->Form->control('is_repeat');
                    echo $this->Form->control('repeat_type');
                    echo $this->Form->control('repeat_until', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateTasks extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $this->table('tasks')
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('parent_id', 'integer', ['null' => true])
            ->addColumn('assigned_by', 'integer')
            ->addColumn('assigned_to', 'integer', ['null' => true])
            ->addColumn('deadline', 'datetime', ['null' => true])
            ->addColumn('status', 'string', ['limit' => 50])
            ->addColumn('priority', 'integer', ['default' => 0])
            ->addColumn('is_repeat', 'boolean', ['default' => false])
            ->addColumn('repeat_type', 'string', ['null' => true])
            ->addColumn('repeat_until', 'datetime', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();

    }
}

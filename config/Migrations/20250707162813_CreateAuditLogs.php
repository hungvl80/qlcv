<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAuditLogs extends BaseMigration
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
        $this->table('audit_logs')
            ->addColumn('user_id', 'integer')
            ->addColumn('action', 'string', ['limit' => 255])
            ->addColumn('model', 'string', ['limit' => 255])
            ->addColumn('model_id', 'integer', ['null' => true])
            ->addColumn('data', 'text', ['null' => true])
            ->addColumn('created', 'datetime')
            ->create();
    }
}

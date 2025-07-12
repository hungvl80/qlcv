<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUnits extends BaseMigration
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
        $this->table('units')
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('code', 'string', ['limit' => 50])
            ->addColumn('parent_id', 'integer', ['null' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }
}

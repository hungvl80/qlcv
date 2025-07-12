<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddIsAdminToUsers extends AbstractMigration
{
    public function change(): void
    {
        $this->table('users')
            ->addColumn('is_admin', 'boolean', [
                'default' => 0,
                'null' => false,
                'after' => 'is_active',
            ])
            ->update();
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class UserTablesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('user_tables'); // tên thật trong DB
        $this->setPrimaryKey('id');
    }
}

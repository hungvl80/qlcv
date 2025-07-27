<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RowTablesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable(''); // không cần bảng thật vì thao tác với bảng động
    }
}
?>
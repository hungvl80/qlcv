<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class CatTablesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('cat_tables');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        // Nếu có quan hệ với Units
        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'LEFT',
        ]);
    }
}

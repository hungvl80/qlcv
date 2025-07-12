<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class UnitsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('units');
        $this->setPrimaryKey('id');
        $this->addBehavior('Tree');


        $this->hasMany('ChildUnits', [
            'className' => 'Units',
            'foreignKey' => 'parent_id',
        ]);

        $this->belongsTo('ParentUnit', [
            'className' => 'Units',
            'foreignKey' => 'parent_id',
        ]);

        $this->addBehavior('Timestamp');
    }
}

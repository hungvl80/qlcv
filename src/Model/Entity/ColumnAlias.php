<?php
// src/Model/Entity/ColumnAlias.php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ColumnAlias extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected array $_accessible = [
        'user_table_id' => true,
        'column_name' => true,
        'original_name' => true,
        'data_type' => true,
        'original_type' => true,
        'sort_order' => true, 
        'created' => true,
        'modified' => true,
        '*' => false,
    ];
}
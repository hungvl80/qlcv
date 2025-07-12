<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Unit extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'code' => true,
        'parent_id' => true,
        'created' => true,
        'modified' => true,
        'parent_unit' => true,
        'child_units' => true,
    ];
}

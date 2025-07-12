<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class AuditLog extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'action' => true,
        'model' => true,
        'model_id' => true,
        'data' => true,
        'created' => true,
    ];
}

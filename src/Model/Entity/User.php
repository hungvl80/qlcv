<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class User extends Entity
{
    protected array $_accessible = [
        'username' => true,
        'password' => true,
        'full_name' => true,
        'email' => true,
        'phone' => true,
        'unit_id' => true,
        'position_id' => true,
        'avatar' => true,
        'is_active' => true,
        'level' => true,
        'is_admin' => true,
        'created' => true,
        'modified' => true,
        'unit' => true,
    ];

    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(?string $password): ?string
    {
        if (!empty($password)) {
            return password_hash($password, PASSWORD_DEFAULT);
        }
        return null;
    }
}

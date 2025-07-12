<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Position Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property int $level
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\AssignmentPermission[] $assignment_permissions_assigner
 * @property \App\Model\Entity\AssignmentPermission[] $assignment_permissions_target
 */
class Position extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'code' => true,
        'level' => true,
        'created' => true,
        'modified' => true,
        'users' => true,
        'assignment_permissions_assigner' => true,
        'assignment_permissions_target' => true,
    ];
}
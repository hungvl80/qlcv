<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AssignmentPermission Entity
 *
 * @property int $id
 * @property int|null $assigner_user_id
 * @property int $assigner_position_id
 * @property int $assigner_unit_id
 * @property int|null $target_unit_id
 * @property int|null $target_position_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $assigner_user
 * @property \App\Model\Entity\Position $assigner_position
 * @property \App\Model\Entity\Unit $assigner_unit
 * @property \App\Model\Entity\Unit $target_unit
 * @property \App\Model\Entity\Position $target_position
 */
class AssignmentPermission extends Entity
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
        'assigner_user_id' => true,
        'assigner_position_id' => true,
        'assigner_unit_id' => true,
        'target_unit_id' => true,
        'target_position_id' => true,
        'created' => true,
        'modified' => true,
        'assigner_user' => true,
        'assigner_position' => true,
        'assigner_unit' => true,
        'target_unit' => true,
        'target_position' => true,
    ];
}
<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $assigned_by
 * @property int|null $assigned_to
 * @property \Cake\I18n\DateTime|null $deadline
 * @property string $status
 * @property int $priority
 * @property bool $is_repeat
 * @property string|null $repeat_type
 * @property \Cake\I18n\DateTime|null $repeat_until
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\ParentTask $parent_task
 * @property \App\Model\Entity\ChildTask[] $child_tasks
 */
class Task extends Entity
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
        'title' => true,
        'description' => true,
        'parent_id' => true,
        'assigned_by' => true,
        'assigned_to' => true,
        'deadline' => true,
        'status' => true,
        'priority' => true,
        'is_repeat' => true,
        'repeat_type' => true,
        'repeat_until' => true,
        'created' => true,
        'modified' => true,
        'parent_task' => true,
        'child_tasks' => true,
    ];
}

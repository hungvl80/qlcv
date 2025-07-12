<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TasksFixture
 */
class TasksFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'parent_id' => 1,
                'assigned_by' => 1,
                'assigned_to' => 1,
                'deadline' => '2025-07-07 16:30:37',
                'status' => 'Lorem ipsum dolor sit amet',
                'priority' => 1,
                'is_repeat' => 1,
                'repeat_type' => 'Lorem ipsum dolor sit amet',
                'repeat_until' => '2025-07-07 16:30:37',
                'created' => '2025-07-07 16:30:37',
                'modified' => '2025-07-07 16:30:37',
            ],
        ];
        parent::init();
    }
}

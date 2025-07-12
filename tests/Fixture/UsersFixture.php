<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'full_name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'phone' => 'Lorem ipsum dolor ',
                'avatar' => 'Lorem ipsum dolor sit amet',
                'unit_id' => 1,
                'position' => 'Lorem ipsum dolor sit amet',
                'level' => 1,
                'is_active' => 1,
                'created' => '2025-07-07 16:30:28',
                'modified' => '2025-07-07 16:30:28',
            ],
        ];
        parent::init();
    }
}

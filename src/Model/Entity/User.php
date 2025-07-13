<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;
use Cake\I18n\DateTime;

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
        'can_assign_tasks' => true,
        'created' => true,
        'modified' => true,
        'unit' => true,
        'position' => true,
    ];

    protected array $_hidden = [
        'password',
    ];

    protected array $_virtual = [
        'avatar_url',
        'initials',
    ];

    /**
     * Hash password khi set
     */
    protected function _setPassword(?string $password): ?string
    {
        if (!empty($password)) {
            return password_hash($password, PASSWORD_DEFAULT);
        }
        return null;
    }

    /**
     * Xử lý ngày tạo nếu null
     */
    protected function _setCreated(DateTime $created): DateTime // <-- SỬA KIỂU Ở ĐÂY
    {
        return $created;
    }

    /**
     * Xử lý ngày cập nhật nếu null
     */
    protected function _setModified(DateTime $modified): DateTime // <-- VÀ CẢ Ở ĐÂY
    {
        return $modified;
    }

    /**
     * Virtual field: URL avatar đầy đủ
     */
    protected function _getAvatarUrl(): string
    {
        if (!empty($this->avatar)) {
            return $this->avatar;
        }
        return '/img/default-avatar.png';
    }

    /**
     * Virtual field: Chữ cái đầu cho avatar mặc định
     */
    protected function _getInitials(): string
    {
        if (empty($this->full_name)) {
            return 'NA';
        }

        $names = explode(' ', $this->full_name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (strlen($initials) >= 2) break;
        }

        return $initials;
    }

    /**
     * Xác thực email
     */
    protected function _setEmail(?string $email): ?string
    {
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email không hợp lệ');
        }
        return $email;
    }

    /**
     * Xử lý tên người dùng
     */
    protected function _setUsername(string $username): string
    {
        return Text::slug(strtolower($username));
    }
}
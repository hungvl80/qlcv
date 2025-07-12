<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController; // Đảm bảo AppController được import đúng
use Cake\ORM\TableRegistry; // Cần import TableRegistry để lấy các bảng

/**
 * Admin Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\UnitsTable $Units
 * @property \App\Model\Table\PositionsTable $Positions // Thêm property hint
 * @property \App\Model\Table\AssignmentPermissionsTable $AssignmentPermissions // Thêm property hint
 * @property \App\Model\Table\AuditLogsTable $AuditLogs
 */
class AdminController extends AppController
{
    /**
     * Index method
     *
     * Đây là hành động hiển thị trang tổng quan quản trị.
     * Nó lấy số lượng người dùng, đơn vị, chức vụ, cấu hình giao việc và nhật ký kiểm toán.
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Lấy TableLocator để truy cập các bảng
        $tableLocator = TableRegistry::getTableLocator();

        // Lấy số lượng người dùng
        $usersCount = $tableLocator->get('Users')->find()->count();

        // Lấy số lượng đơn vị
        $unitsCount = $tableLocator->get('Units')->find()->count();

        // Lấy số lượng chức vụ (Positions)
        $positionsCount = $tableLocator->get('Positions')->find()->count();

        // Lấy số lượng cấu hình giao việc (AssignmentPermissions)
        $assignmentPermissionsCount = $tableLocator->get('AssignmentPermissions')->find()->count();

        // Lấy số lượng nhật ký kiểm toán (AuditLogs)
        $logsCount = $tableLocator->get('AuditLogs')->find()->count();

        // Truyền các biến đếm vào view
        $this->set(compact(
            'usersCount',
            'unitsCount',
            'positionsCount', // Thêm vào compact
            'assignmentPermissionsCount', // Thêm vào compact
            'logsCount'
        ));
    }
}
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
    public function noPermission()
    {
        $role = $this->request->getQuery('role');

        $backUrl = [];
        if ($role === 'admin') {
            $backUrl = ['controller' => 'Admin', 'action' => 'index'];
        } else {
            $backUrl = ['controller' => 'Pages', 'action' => 'home'];
        }

        $this->set(compact('role', 'backUrl'));
    }
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

        $usersCount = $tableLocator->get('Users')->find()->count();
        $unitsCount = $tableLocator->get('Units')->find()->count();
        $positionsCount = $tableLocator->get('Positions')->find()->count();
        $assignmentPermissionsCount = $tableLocator->get('AssignmentPermissions')->find()->count();
        $logsCount = $tableLocator->get('AuditLogs')->find()->count();
        $catTablesCount = $tableLocator->get('CatTables')->find()->count(); // ✅ Mới

        $this->set(compact(
            'usersCount',
            'unitsCount',
            'positionsCount',
            'assignmentPermissionsCount',
            'logsCount',
            'catTablesCount' // ✅ Mới
        ));
    }

}
<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication', [
            'logoutRedirect' => '/login',
        ]);
        $this->loadComponent('Authorization.Authorization');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $controller = $this->getRequest()->getParam('controller');
        $action = $this->getRequest()->getParam('action');

        // Bỏ qua phân quyền cho trang no-permission
        if ($controller === 'Admin' && $action === 'noPermission') {
            return;
        }

        if ($controller === 'Login' && $action === 'index') {
            $this->Authentication->addUnauthenticatedActions(['index']);
            return;
        }

        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            return $this->redirect('/login');
        }

        /** @var \Authentication\IdentityInterface $user */
        $user = $this->Authentication->getIdentity();

        $userRole = $user->get('is_admin') ? 'admin' : 'user';

        $adminControllers = [
            'Admin',
            'Users',
            'Positions',
            'Units',
            'CatTables',
            'AuditLogs',
        ];

        if ($userRole === 'admin' && !in_array($controller, $adminControllers)) {
            return $this->redirect([
                'controller' => 'Admin',
                'action' => 'noPermission',
                '?' => ['role' => 'admin']
            ]);
        }

        if ($userRole === 'user') {
            $allowedUserActions = [
                'Users' => ['view', 'edit'],
            ];

            if (in_array($controller, $adminControllers)) {
                if (
                    $controller === 'Users' &&
                    isset($allowedUserActions['Users']) &&
                    in_array($action, $allowedUserActions['Users'])
                ) {
                    // Lấy userId từ URL
                    $paramId = $this->getRequest()->getParam('pass.0');

                    if ($paramId && (int)$paramId !== $user->get('id')) {
                        return $this->redirect([
                            'controller' => 'Admin',
                            'action' => 'noPermission',
                            '?' => ['role' => 'user']
                        ]);
                    }
                    // Nếu ID trùng, cho phép tiếp tục
                } else {
                    return $this->redirect([
                        'controller' => 'Admin',
                        'action' => 'noPermission',
                        '?' => ['role' => 'user']
                    ]);
                }
            }
        }

        // $this->Authorization->authorize($this->request);
    }




    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        $canAssignTasksOptions = [
            0 => __('Không có quyền giao việc'),
            1 => __('Giao việc nội bộ'),
            2 => __('Giao việc liên đơn vị'),
        ];
        $this->set(compact('canAssignTasksOptions'));
    }
}

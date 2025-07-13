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
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $controller = $this->getRequest()->getParam('controller');

        if ($controller === 'Login') {
            $this->Authentication->addUnauthenticatedActions(['index']);
            return;
        }

        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            return $this->redirect('/login');
        }
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        // Định nghĩa các tùy chọn cho quyền giao việc
        $canAssignTasksOptions = [
            0 => __('Không có quyền giao việc'),
            1 => __('Giao việc nội bộ'),
            2 => __('Giao việc liên đơn vị'),
        ];
        $this->set(compact('canAssignTasksOptions')); // Gửi biến này đến tất cả các view
    }    
}

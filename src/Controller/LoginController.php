<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Response;

class LoginController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setLayout('auth'); 
    }

    public function index()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        // Xử lý khi đã đăng nhập
        if ($result->isValid()) {
            return $this->redirectAfterLogin();
        }

        // Xử lý khi submit form đăng nhập thất bại
        if ($this->request->is('post') && !$result->isValid()) {
            $this->handleFailedLogin();
        }
    }

    public function logout()
    {
        // Chỉ cho phép POST request
        $this->request->allowMethod(['post']);
        
        $this->Authentication->logout();
        $this->Flash->success(__('Đã đăng xuất thành công.'));
        return $this->redirect(['controller' => 'Login', 'action' => 'index']);
    }

    /**
     * Xử lý chuyển hướng sau khi đăng nhập thành công
     */
    protected function redirectAfterLogin(): Response
    {
        $user = $this->Authentication->getIdentity();
            $redirect = [
            'controller' => $user->is_admin ? 'Admin' : 'Pages',
            'action' => $user->is_admin ? 'index' : 'home'
        ];

        $this->Flash->success(__('Đăng nhập thành công! Chào mừng {0}', $user->username));
        return $this->redirect($redirect);
    }

    /**
     * Xử lý khi đăng nhập thất bại
     */
    protected function handleFailedLogin(): void
    {
        $this->Flash->error(__('Tên đăng nhập hoặc mật khẩu không đúng.'), [
            'key' => 'auth_error',
            'escape' => false
        ]);

        // Bảo mật: không tiết lộ thông tin cụ thể về lỗi
        $this->log('Failed login attempt from IP: ' . $this->request->clientIp(), 'warning');
    }
}
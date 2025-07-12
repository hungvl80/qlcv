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
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
        return $this;
    }

    public function index(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $user = $this->request->getAttribute('identity');

            if ($user->is_admin) {
                return $this->redirect(['controller' => 'Admin', 'action' => 'index']);
            } else {
                return $this->redirect(['controller' => 'Tasks', 'action' => 'index']);
            }
        }

        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error(__('Tên đăng nhập hoặc mật khẩu không đúng.'));
        }

        return null;
    }

    public function logout(): Response
    {
        $this->Authentication->logout();
        $this->Flash->success(__('Bạn đã đăng xuất thành công.'));
        return $this->redirect(['action' => 'index']);
    }
}

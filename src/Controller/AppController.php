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
}

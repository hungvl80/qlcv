<?php
declare(strict_types=1);

namespace App\Controller;

class AuditLogsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function index()
    {
        $query = $this->AuditLogs->find()
            ->contain(['Users'])
            ->order(['AuditLogs.created' => 'DESC']);

        $auditLogs = $this->paginate($query, [
            'limit' => 20,
            'order' => [
                'AuditLogs.created' => 'DESC'
            ]
        ]);

        $this->set(compact('auditLogs'));
    }

    public function view($id = null)
    {
        $auditLog = $this->AuditLogs->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('auditLog'));
    }
}

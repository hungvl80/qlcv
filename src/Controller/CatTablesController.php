<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * CatTables Controller
 *
 * Quản lý danh mục Lĩnh vực
 */
class CatTablesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // Lấy TableLocator để truy cập các bảng
        $tableLocator = TableRegistry::getTableLocator();
    }

    /**
     * Danh sách các lĩnh vực
     */
    public function index()
    {
        $catTables = $this->paginate(
            $this->CatTables->find()
                ->contain(['Units'])
                ->order(['CatTables.id' => 'DESC'])
        );

        $this->set(compact('catTables'));
    }

    /**
     * Xem chi tiết 1 lĩnh vực
     */
    public function view($id = null)
    {
        $catTable = $this->CatTables->get($id, [
            'contain' => ['Units']
        ]);

        $this->set(compact('catTable'));
    }

    /**
     * Thêm lĩnh vực mới
     */
    public function add()
    {
        $catTable = $this->CatTables->newEmptyEntity();

        if ($this->request->is('post')) {
            $catTable = $this->CatTables->patchEntity($catTable, $this->request->getData());

            if ($this->CatTables->save($catTable)) {
                $this->Flash->success(__('Đã thêm lĩnh vực thành công.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Không thể lưu lĩnh vực. Vui lòng thử lại.'));
        }

        $unitTable = TableRegistry::getTableLocator()->get('Units');
        $units = $unitTable->find('list')->toArray();
        $this->set(compact('catTable', 'units'));
    }

    /**
     * Sửa lĩnh vực
     */
    public function edit($id = null)
    {
        $catTable = $this->CatTables->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $catTable = $this->CatTables->patchEntity($catTable, $this->request->getData());

            if ($this->CatTables->save($catTable)) {
                $this->Flash->success(__('Đã cập nhật lĩnh vực thành công.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Không thể cập nhật. Vui lòng thử lại.'));
        }

        $unitTable = TableRegistry::getTableLocator()->get('Units');
        $units = $unitTable->find('list')->toArray();
        $this->set(compact('catTable', 'units'));
    }

    /**
     * Xóa lĩnh vực
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $catTable = $this->CatTables->get($id);

        if ($this->CatTables->delete($catTable)) {
            $this->Flash->success(__('Đã xóa lĩnh vực.'));
        } else {
            $this->Flash->error(__('Không thể xóa. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

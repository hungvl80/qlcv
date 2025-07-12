<?php
declare(strict_types=1);

namespace App\Controller;

class CategoriesController extends AppController
{
    public function index()
    {
        $categories = $this->Categories->find();
        $this->set(compact('categories'));
    }

    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('Đã thêm lĩnh vực.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Không thể lưu.'));
        }
        $this->set(compact('category'));
    }

    public function edit($id = null)
    {
        $category = $this->Categories->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('Đã cập nhật lĩnh vực.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Không thể lưu.'));
        }
        $this->set(compact('category'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('Đã xóa lĩnh vực.'));
        } else {
            $this->Flash->error(__('Không thể xóa.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}

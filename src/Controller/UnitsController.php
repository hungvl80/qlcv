<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Response; // Sử dụng để trả về Response từ các action

/**
 * Units Controller
 *
 * @property \App\Model\Table\UnitsTable $Units
 */
class UnitsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(): void
    {
        // find('threaded') hoạt động vì TreeBehavior đã được gắn trong UnitsTable
        $units = $this->Units->find('threaded')->toArray();
        $this->set(compact('units'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
    {
        $unit = $this->Units->newEmptyEntity();
        if ($this->request->is('post')) {
            $unit = $this->Units->patchEntity($unit, $this->request->getData());
            if ($this->Units->save($unit)) {
                $this->Flash->success(__('Đơn vị đã được lưu.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Không thể lưu đơn vị. Vui lòng thử lại.'));
        }

        // Sửa lỗi 'treeList' bằng cách sử dụng find('list') với closure cho valueField
        // Điều này sẽ tạo ra danh sách có thụt lề cho dropdown 'parent_id'
        $parentUnits = $this->Units->find('list', keyField: 'id', valueField: function ($u) {
            // Đảm bảo trường 'level' tồn tại (được tạo bởi TreeBehavior)
            // 'level' của root node là 0, nên thụt lề sẽ bắt đầu từ đó.
            $prefix = str_repeat('--', $u->level ?? 0);
            return $prefix . ' ' . $u->name;
        })
        ->order(['lft' => 'ASC']) // Quan trọng: Đảm bảo thứ tự đúng của cây
        ->toArray();

        $this->set(compact('unit', 'parentUnits'));
        return null; // Trả về null nếu không có redirect, để render view
    }

    /**
     * Edit method
     *
     * @param string|null $id Unit id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null): ?Response
    {
        $unit = $this->Units->get($id, [
            'contain' => [], // Nếu cần load thêm mối quan hệ khi edit
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $unit = $this->Units->patchEntity($unit, $this->request->getData());
            if ($this->Units->save($unit)) {
                $this->Flash->success(__('Đơn vị đã được cập nhật.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Không thể lưu đơn vị. Vui lòng thử lại.'));
        }

        // Sửa lỗi 'treeList' tương tự như add()
        $parentUnits = $this->Units->find('list', keyField: 'id', valueField: function ($u) {
            $prefix = str_repeat('--', $u->level ?? 0);
            return $prefix . ' ' . $u->name;
        })
        ->order(['lft' => 'ASC']) // Quan trọng: Đảm bảo thứ tự đúng của cây
        ->toArray();

        $this->set(compact('unit', 'parentUnits'));
        return null; // Trả về null nếu không có redirect, để render view
    }

    /**
     * Delete method
     *
     * @param string|null $id Unit id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']); // Chỉ cho phép POST hoặc DELETE

        // Kiểm tra ID có hợp lệ không
        if (!$id) {
            $this->Flash->error(__('Không tìm thấy ID đơn vị để xóa.'));
            return $this->redirect(['action' => 'index']);
        }

        $unit = $this->Units->get($id); // Lấy đơn vị cần xóa

        if ($this->Units->delete($unit)) {
            $this->Flash->success(__('Đơn vị đã được xóa.'));
        } else {
            // Xử lý trường hợp không thể xóa (ví dụ: có đơn vị con)
            // TreeBehavior sẽ ngăn xóa nếu có con, trừ khi cascadeCallbacks được bật.
            // Hoặc bạn có thể kiểm tra trước khi xóa.
            $this->Flash->error(__('Không thể xóa đơn vị. Có thể có đơn vị con hoặc lỗi khác.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Response;
use Cake\Event\EventInterface; 

/**
 * Users Controller
 */
class UsersController extends AppController
{
    
    /**
     * @var \App\Model\Table\AuditLogsTable
     */
    public $AuditLogs;
    /**
     * Initialize method
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('FormProtection');
        $this->AuditLogs = $this->fetchTable('AuditLogs'); // Sử dụng fetchTable thay cho loadModel
    }

    /**
     * Index method
     */
    public function index(): void
    {
        $users = $this->Users->find(
            all: [
                'contain' => ['Units'],
                'order' => ['Users.created' => 'DESC']
            ]
        );
        $this->set(compact('users'));
    }

    /**
     * Add method
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Người dùng đã được lưu.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Người dùng không thể được lưu. Vui lòng thử lại.'));
        }

        // Lấy danh sách Units và Positions để đổ vào dropdown
        $units = $this->Users->Units->find('list', limit: 200)->all(); // Lấy tất cả đơn vị
        $positions = $this->Users->Positions->find('list', limit: 200)->all(); // Lấy tất cả chức vụ

        $this->set(compact('user', 'units', 'positions')); // Truyền các biến này sang view
    }

    public function view($id = null)
    {
        try {
            $user = $this->Users->get($id, [
                'contain' => ['Units', 'Positions'], // Lấy thông tin Unit và Position liên quan
            ]);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Người dùng không tồn tại hoặc đã bị xóa.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('user'));
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData(); // Lấy toàn bộ dữ liệu request vào một biến tạm

            // RẤT QUAN TRỌNG: Nếu trường mật khẩu được gửi lên là rỗng, loại bỏ nó khỏi dữ liệu
            // để patchEntity không ghi đè mật khẩu cũ bằng null/rỗng.
            if (isset($data['password']) && $data['password'] === '') {
                unset($data['password']);
            }

            $user = $this->Users->patchEntity($user, $data); // Sử dụng $data đã được xử lý

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Người dùng đã được lưu.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Người dùng không thể được lưu. Vui lòng thử lại.'));
        }
        // Lấy danh sách Units và Positions cho form edit
        $units = $this->Users->Units->find('list', limit: 200)->all();
        $positions = $this->Users->Positions->find('list', limit: 200)->all();

        $this->set(compact('user', 'units', 'positions'));
    }

    /**
     * Delete method
     */
    // Trong UsersController.php
    public function delete($id = null) // Chú ý: $id đã trở lại làm tham số
    {
        // Đảm bảo chỉ chấp nhận yêu cầu POST hoặc DELETE
        $this->request->allowMethod(['post', 'delete']);

        // Kiểm tra xem ID có được truyền vào không
        if (!$id) {
            $this->Flash->error(__('Không tìm thấy ID người dùng để xóa.'));
            return $this->redirect(['action' => 'index']);
        }

        // Lấy người dùng theo ID từ tham số URL
        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Người dùng đã được xóa.'));
        } else {
            $this->Flash->error(__('Không thể xóa người dùng. Vui lòng thử lại.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function isValidId($id)
    {
        return !empty($id) && is_numeric($id);
    }





    /**
     * Ghi log audit
     */
    private function _auditLog(string $action, string $model, int $modelId, ?array $data = null): void
    {
        $this->AuditLogs->save($this->AuditLogs->newEntity([
            'user_id' => $this->request->getAttribute('identity')->id ?? null,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'data' => $data, // CakePHP tự động encode JSON
        ]));
    }
}
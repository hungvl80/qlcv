<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Response;
use Cake\Event\EventInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\DateTime;

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
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('FormProtection');
        $this->AuditLogs = $this->fetchTable('AuditLogs');
    }

    /**
     * Index method
     */
    public function index(): void
    {
        $this->paginate = [
            'finder' => 'withUnits', // Use our custom finder
            'limit' => 15
        ];
        
        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }

    /**
     * View method
     */
    public function view($id = null): ?Response
    {
        if (!$this->isValidId($id)) {
            $this->Flash->error(__('ID không hợp lệ.'));
            return $this->redirect(['action' => 'index']);
        }

        try {
            $user = $this->Users->get($id, contain: ['Units', 'Positions']);
            
            // Đảm bảo created/modified không null
            if ($user->created === null) {
                $user->created = new DateTime();
            }
            if ($user->modified === null) {
                $user->modified = new DateTime();
            }
            
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Người dùng không tồn tại hoặc đã bị xóa.'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id, contain: ['Units', 'Positions']);

        $this->set(compact('user'));
        return null;
    }

    /**
     * Add method
     */
    public function add(): ?Response
    {
        $user = $this->Users->newEmptyEntity();
        $user->created = new DateTime();
        $user->modified = new DateTime();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            $user = $this->Users->patchEntity($user, $data);
            
            if ($this->Users->save($user)) {
                $this->_auditLog('create', 'Users', $user->id, $user->toArray());
                $this->Flash->success(__('Người dùng đã được lưu.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Người dùng không thể được lưu. Vui lòng thử lại.'));
        }

        $units = $this->Users->Units->find('list', limit: 200)->all();
        $positions = $this->Users->Positions->find('list', limit: 200)->all();
        $this->set(compact('user', 'units', 'positions'));
        return null;
    }

    /**
     * Edit method
     */
    public function edit($id = null)
    {
        // Lấy thông tin user hiện tại từ request
        $identity = $this->request->getAttribute('identity');
        $currentUserId = $identity ? $identity->getIdentifier() : null;
        $isAdmin = $identity ? $identity->get('is_admin') : false;

        // Kiểm tra quyền chỉnh sửa
        if ($currentUserId != $id && !$isAdmin) {
            $this->Flash->error(__('Bạn chỉ có thể chỉnh sửa thông tin của chính mình.'));
            return $this->redirect($identity ? ['action' => 'view', $currentUserId] : ['action' => 'login']);
        }

        try {
            $user = $this->Users->get($id, contain: ['Units', 'Positions']);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Người dùng không tồn tại.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            // Xử lý mật khẩu
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user = $this->Users->patchEntity($user, $data);
            
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Thông tin người dùng đã được cập nhật.'));
                
                // Ghi log audit
                $this->_auditLog('update', 'Users', $user->id, [
                    'fields_changed' => array_keys($data)
                ]);
                
                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error(__('Không thể cập nhật thông tin. Vui lòng kiểm tra lại các trường.'));
        }

        // Lấy danh sách đơn vị và chức vụ
        $units = $this->Users->Units->find('list', limit: 200)->all();
        $positions = $this->Users->Positions->find('list', limit: 200)->all();

        $this->set(compact('user', 'units', 'positions'));
    }

    /**
     * Delete method
     */
    public function delete($id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);

        if (!$this->isValidId($id)) {
            $this->Flash->error(__('ID không hợp lệ.'));
            return $this->redirect(['action' => 'index']);
        }

        try {
            $user = $this->Users->get($id);
            
            // Xóa avatar nếu có
            if ($user->avatar_path) {
                $this->deleteAvatarFile($user->avatar_path);
            }
            
            if ($this->Users->delete($user)) {
                $this->_auditLog('delete', 'Users', $user->id);
                $this->Flash->success(__('Người dùng đã được xóa.'));
            } else {
                $this->Flash->error(__('Không thể xóa người dùng. Vui lòng thử lại.'));
            }
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Người dùng không tồn tại.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function uploadAvatar($id = null)
    {
        $user = $this->Users->get($id);
        
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            
            if (!empty($data['avatar']->getClientFilename())) {
                $uploadResult = $this->handleAvatarUpload($data['avatar'], $user);
                
                if ($uploadResult) {
                    $user->avatar = $uploadResult;
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('Ảnh đại diện đã được cập nhật.'));
                        return $this->redirect(['action' => 'view', $id]);
                    }
                }
                $this->Flash->error(__('Không thể tải lên ảnh.'));
            }
        }
        
        $this->set(compact('user'));
    }

    /**
     * Kiểm tra ID hợp lệ
     */
    private function isValidId($id): bool
    {
        return !empty($id) && is_numeric($id);
    }

    /**
     * Xử lý upload avatar
     */
    private function handleAvatarUpload($file, $user)
    {
        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getClientMediaType(), $allowedTypes)) {
            return false;
        }

        // Kiểm tra kích thước file (tối đa 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            return false;
        }

        $uploadPath = WWW_ROOT . 'uploads' . DS . 'avatars';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        // Tạo tên file an toàn
        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = $user->id . '_' . time() . '.' . $extension;
        $filePath = $uploadPath . DS . $filename;

        try {
            $file->moveTo($filePath);
            return 'uploads/avatars/' . $filename;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Xóa file avatar
     * 
     * @param string $avatarPath Đường dẫn avatar
     * @return bool
     */
    private function deleteAvatarFile($avatarPath)
    {
        $filePath = WWW_ROOT . $avatarPath;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Action xóa avatar
     * 
     * @param string|null $id User id
     * @return \Cake\Http\Response|null
     */
    public function deleteAvatar($id = null)
    {
        $this->request->allowMethod(['post', 'delete']); // Chỉ cho phép POST/DELETE
        
        // Lấy thông tin user hiện tại từ request
        $identity = $this->request->getAttribute('identity');
        
        // Kiểm tra quyền
        if (!$identity || ($identity->getIdentifier() != $id && !$identity->get('is_admin'))) {
            $this->Flash->error(__('Bạn không có quyền thực hiện thao tác này.'));
            return $this->redirect(['action' => 'view', $id]);
        }

        try {
            $user = $this->Users->get($id);
            
            if (empty($user->avatar)) {
                $this->Flash->error(__('Người dùng không có ảnh đại diện.'));
                return $this->redirect(['action' => 'view', $id]);
            }

            // Xóa file vật lý
            $filePath = WWW_ROOT . $user->avatar;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Cập nhật database
            $user->avatar = null;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Đã xóa ảnh đại diện thành công.'));
            } else {
                $this->Flash->error(__('Đã xóa file ảnh nhưng không thể cập nhật database.'));
            }
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('Người dùng không tồn tại.'));
        }

        return $this->redirect(['action' => 'view', $id]);
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
            'data' => $data,
        ]));
    }
}


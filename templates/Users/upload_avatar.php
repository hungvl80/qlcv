<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="text-center font-weight-bold text-primary"><?= __('Cập nhật ảnh đại diện') ?></h3>
                </div>
                <div class="card-body px-5 pb-5">
                    <?= $this->Form->create($user, ['type' => 'file', 'class' => 'avatar-form']) ?>
                    
                    <div class="avatar-upload-area mb-4 text-center p-4 border rounded-lg" id="dropzone">
                        <div class="avatar-preview mb-3" id="avatarPreview">
                            <?php if (!empty($user->avatar)): ?>
                                <?= $this->Html->image($user->avatar, ['class' => 'rounded-circle', 'width' => '120', 'height' => '120', 'id' => 'imagePreview']) ?>
                            <?php else: ?>
                                <div class="default-avatar rounded-circle d-flex align-items-center justify-content-center bg-light" style="width:120px; height:120px; margin: 0 auto;">
                                    <i class="fas fa-user text-muted" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="custom-file">
                            <?= $this->Form->control('avatar', [
                                'type' => 'file',
                                'label' => false,
                                'required' => true,
                                'class' => 'custom-file-input',
                                'id' => 'avatarInput',
                                'accept' => 'image/*'
                            ]) ?>
                            <label class="custom-file-label text-left" for="avatarInput" id="fileLabel">
                                <?= __('Chọn ảnh từ máy tính') ?>
                            </label>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <?= __('Định dạng hỗ trợ: JPG, PNG. Kích thước tối đa: 5MB') ?>
                        </small>
                    </div>
                    
                    <div class="form-group text-center mt-4">
                        <?= $this->Form->button(__('Lưu thay đổi'), [
                            'class' => 'btn btn-primary px-4 py-2',
                            'style' => 'min-width: 150px;'
                        ]) ?>
                        <?= $this->Html->link(__('Hủy bỏ'), 
                            ['action' => 'view', $user->id], 
                            ['class' => 'btn btn-outline-secondary px-4 py-2 ml-2', 'style' => 'min-width: 150px;']
                        ) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-upload-area {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .avatar-upload-area:hover {
        border-color: #4e73df !important;
        background-color: #f8f9fa;
    }
    
    .custom-file-label::after {
        content: "Duyệt";
    }
    
    .avatar-preview img {
        object-fit: cover;
        border: 3px solid #e3e6f0;
    }
    
    .default-avatar {
        border: 3px dashed #d1d3e2;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image before upload
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const fileLabel = document.getElementById('fileLabel');
    const dropzone = document.getElementById('dropzone');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(event) {
                if (avatarPreview.querySelector('img')) {
                    avatarPreview.querySelector('img').src = event.target.result;
                } else {
                    const defaultAvatar = avatarPreview.querySelector('.default-avatar');
                    if (defaultAvatar) {
                        avatarPreview.removeChild(defaultAvatar);
                    }
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'rounded-circle';
                    img.width = 120;
                    img.height = 120;
                    img.id = 'imagePreview';
                    img.style.objectFit = 'cover';
                    avatarPreview.appendChild(img);
                }
            }
            
            reader.readAsDataURL(file);
            fileLabel.textContent = file.name;
        }
    });
    
    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropzone.classList.add('border-primary');
        dropzone.style.backgroundColor = '#f8f9fa';
    }
    
    function unhighlight() {
        dropzone.classList.remove('border-primary');
        dropzone.style.backgroundColor = '';
    }
    
    dropzone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        avatarInput.files = files;
        
        // Trigger the change event
        const event = new Event('change');
        avatarInput.dispatchEvent(event);
    }
});
</script>
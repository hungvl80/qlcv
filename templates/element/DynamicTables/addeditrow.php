<div class="container mt-4">
    <h4>
        <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' ?>"></i>
        <?= $isEdit ? 'Chỉnh sửa dòng' : 'Thêm dòng' ?> vào bảng: <code><?= h($table->original_name) ?></code>
    </h4>

    <?= $this->Form->create($entity, [
        'type' => 'file',
        'context' => ['schema' => $columns],
        'enctype' => 'multipart/form-data'
    ]) ?>

    <!-- Thêm trường ẩn ID nếu là chỉnh sửa -->
    <?php if ($isEdit && isset($entity->id)): ?>
        <?= $this->Form->hidden('id') ?>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($columns as $col): 
            if(in_array($col['name'], ['id', 'user_id', 'created', 'modified'])) continue;
            
            $field = $col['name'];
            $label = $col['label'] ?? ucfirst(str_replace(['_', 'cot'], ' ', $field));
            $type = $col['type'] ?? 'text';
            $value = $entity->{$field} ?? '';
            
            $options = [
                'label' => $label,
                'required' => false,
                'class' => 'form-control',
                'default' => ''
            ];

            // Xử lý đặc biệt cho từng loại trường
            switch ($type) {
                case 'textarea':
                    $options['type'] = 'textarea';
                    $options['class'] .= ' form-control-textarea';
                    $options['value'] = $value;
                    break;
                    
                case 'file':
                    $options['type'] = 'file';
                    if ($isEdit && !empty($value)) {
                        echo '<div class="mb-2">';
                        if (strpos($value, 'jpg') !== false || strpos($value, 'png') !== false) {
                            echo $this->Html->image('uploads/' . $value, ['width' => '100']);
                        }
                        echo '<p class="small">' . h($value) . '</p>';
                        echo '</div>';
                    }
                    break;
                    
                case 'select':
                    $options['type'] = 'select';
                    $options['options'] = ['0' => 'Không', '1' => 'Có'];
                    $options['value'] = $value;
                    break;
                    
                case 'date':
                    $options['type'] = 'text'; // Đổi thành text để dùng picker
                    $options['class'] .= ' date-picker';
                    if ($value instanceof \Cake\I18n\Date || $value instanceof \Cake\I18n\Time) {
                        $options['value'] = $value->format('d/m/Y');
                    } elseif (!empty($value)) {
                        $options['value'] = (new \Cake\I18n\Date($value))->format('d/m/Y');
                    }
                    break;
                    
                case 'datetime':
                    $options['type'] = 'text'; // Đổi thành text để dùng picker
                    $options['class'] .= ' datetime-picker';
                    if ($value instanceof \Cake\I18n\Time) {
                        $options['value'] = $value->format('d/m/Y H:i');
                    } elseif (!empty($value)) {
                        $options['value'] = (new \Cake\I18n\Time($value))->format('d/m/Y H:i');
                    }
                    break;
                    
                case 'number':
                    $options['type'] = 'number';
                    $options['step'] = 'any';
                    $options['value'] = $value;
                    break;
                    
                default:
                    $options['type'] = 'text';
                    $options['value'] = $value;
            }
            
            // Tự động nhận diện trường ngày giờ theo tên
            if (strpos($field, 'datetime') !== false || $field === 'cot_16') {
                $options['type'] = 'text';
                $options['class'] .= ' datetime-picker';
                if (!empty($value)) {
                    $options['value'] = (new \Cake\I18n\Time($value))->format('d/m/Y H:i');
                }
            }
            
            if (strpos($field, 'date') !== false || $field === 'cot_thu_17') {
                $options['type'] = 'text';
                $options['class'] .= ' date-picker';
                if (!empty($value)) {
                    $options['value'] = (new \Cake\I18n\Date($value))->format('d/m/Y');
                }
            }
            echo $this->Form->control($field, $options);
        endforeach; ?>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> <?= $isEdit ? 'Cập nhật' : 'Lưu' ?>
        </button>
        <?= $this->Html->link('<i class="bi bi-arrow-left-circle"></i> Quay lại',
            ['action' => 'view', $table->table_name],
            ['class' => 'btn btn-secondary', 'escape' => false]) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<!-- Thêm thư viện jQuery và datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

<script>
$(document).ready(function() {
    // Date picker định dạng dd/mm/yyyy
    $('.date-picker').datetimepicker({
        format: 'd/m/Y',
        timepicker: false,
        mask: true
    });
    
    // Datetime picker định dạng dd/mm/yyyy H:i
    $('.datetime-picker').datetimepicker({
        format: 'd/m/Y H:i',
        mask: true
    });
});
</script>

<style>
    .form-control-textarea {
        min-height: 120px;
    }
    .xdsoft_datetimepicker {
        z-index: 99999 !important;
    }
</style>
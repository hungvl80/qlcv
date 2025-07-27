<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ColumnAlias $columnAlias
 * @var array $typeMapping
 */
?>
<style>
    /* CSS trực tiếp cho trang editColumnAlias (nếu có khác biệt CSS, nếu không có thể xóa) */
    .form-group.required label::after {
        content: " *"; /* Thêm dấu * sau nhãn, có khoảng cách */
        color: red;    /* Đặt màu đỏ cho dấu * */
        font-weight: bold; /* Tùy chọn: in đậm dấu * */
        margin-left: 2px; /* Tùy chọn: thêm khoảng cách nhỏ giữa nhãn và dấu * */
    }
</style>

<?= $this->element('DynamicTables/addeditcolumn', [
    'columnAlias' => $columnAlias,
    'typeMapping' => $typeMapping,
    'isEdit' => true // Báo cho element biết đây là chế độ sửa
]) ?>
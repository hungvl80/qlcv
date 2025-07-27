<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UserTable $table
 * @var array $typeMapping
 */
?>
<style>
    /* CSS trực tiếp cho trang addColumn */
    .form-group.required label::after {
        content: " *"; /* Thêm dấu * sau nhãn, có khoảng cách */
        color: red;    /* Đặt màu đỏ cho dấu * */
        font-weight: bold; /* Tùy chọn: in đậm dấu * */
        margin-left: 2px; /* Tùy chọn: thêm khoảng cách nhỏ giữa nhãn và dấu * */
    }
</style>

<?= $this->element('DynamicTables/addeditcolumn', [
    'table' => $table,
    'typeMapping' => $typeMapping,
    'isEdit' => false // Báo cho element biết đây là chế độ thêm
]) ?>
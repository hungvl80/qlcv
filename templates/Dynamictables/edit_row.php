<?php
$this->assign('title', 'Chỉnh sửa dòng');
$isEdit = true;
echo $this->element('DynamicTables/addeditrow', compact('table', 'columns', 'entity', 'isEdit'));
?>

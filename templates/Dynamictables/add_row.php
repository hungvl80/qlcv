<?php
$this->assign('title', 'Thêm dòng');
$isEdit = false;
echo $this->element('DynamicTables/addeditrow', compact('table', 'columns', 'entity', 'isEdit'));
?>

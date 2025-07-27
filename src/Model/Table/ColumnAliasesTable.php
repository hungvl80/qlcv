<?php
// src/Model/Table/ColumnAliasesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class ColumnAliasesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        
        $this->setTable('column_aliases');
        $this->setDisplayField('original_name');
        $this->setPrimaryKey('id');
        
        $this->belongsTo('UserTables', [
            'foreignKey' => 'user_table_id',
            'joinType' => 'INNER'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $allowedKeys = array_keys(Configure::read('ColumnTypeMapping'));

        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('column_name')
            ->maxLength('column_name', 255, 'Tên cột hệ thống không được vượt quá 255 ký tự.')
            ->requirePresence('column_name', 'create', 'Tên cột hệ thống là bắt buộc.') // THAY ĐỔI DÒNG NÀY ĐỂ CHỈ YÊU CẦU KHI TẠO
            ->notEmptyString('column_name', 'Tên cột hệ thống không được để trống khi tạo.', 'create') // THAY ĐỔI DÒNG NÀY ĐỂ CHỈ YÊU CẦU KHI TẠO
            // Quy tắc cho phép chữ cái, số và dấu gạch dưới vẫn giữ nguyên
            ->add('column_name', 'validChars', [
                'rule' => function ($value, $context) {
                    return (bool)preg_match('/^[a-zA-Z0-9_]+$/', $value);
                },
                'message' => 'Tên cột hệ thống chỉ được chứa chữ cái, số và dấu gạch dưới.'
            ]);
        
        $validator
            ->scalar('original_name')
            ->maxLength('original_name', 255, 'Tên hiển thị không được vượt quá 255 ký tự.')
            ->requirePresence('original_name', 'create', 'Tên hiển thị là bắt buộc.')
            ->notEmptyString('original_name', 'Tên hiển thị không được để trống.'); // Cái này có thể áp dụng cho cả tạo và sửa

        $validator
            ->scalar('data_type')
            ->maxLength('data_type', 50, 'Kiểu dữ liệu không được vượt quá 50 ký tự.')
            ->requirePresence('data_type', 'create', 'Kiểu dữ liệu là bắt buộc.')
            ->notEmptyString('data_type', 'Kiểu dữ liệu không được để trống.');
        
        $validator
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->notEmptyString('sort_order', 'Thứ tự cột không được để trống.');


        return $validator;
    }
}
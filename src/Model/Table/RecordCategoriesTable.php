<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RecordCategoriesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('record_categories');
        $this->setPrimaryKey('id');

        $this->hasMany('RecordFiles', [
            'foreignKey' => 'record_category_id',
        ]);

        $this->belongsTo('ParentCategories', [
            'className' => 'RecordCategories',
            'foreignKey' => 'parent_id',
        ]);

        $this->hasMany('ChildCategories', [
            'className' => 'RecordCategories',
            'foreignKey' => 'parent_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('name', 'Tên đề mục không được để trống')
            ->maxLength('code', 50);

        return $validator;
    }
}

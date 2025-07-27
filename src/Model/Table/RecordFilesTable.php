<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RecordFilesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('record_files');
        $this->setPrimaryKey('id');

        $this->belongsTo('RecordCategories', [
            'foreignKey' => 'record_category_id',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'creator_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title', 'Tiêu đề hồ sơ không được để trống')
            ->notEmptyString('number', 'Số hồ sơ không được để trống');

        return $validator;
    }
}

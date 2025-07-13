<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UnitsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Cấu hình bảng
        $this->setTable('units');
        $this->setDisplayField('name'); // Trường hiển thị mặc định
        $this->setPrimaryKey('id');

        // Thêm behaviors
        $this->addBehavior('Tree', [
            'parent' => 'parent_id',
            'left' => 'lft',
            'right' => 'rght',
            'scope' => [] // Điều kiện scope nếu cần
        ]);
        $this->addBehavior('Timestamp');

        // Quan hệ
        $this->hasMany('ChildUnits', [
            'className' => 'Units',
            'foreignKey' => 'parent_id',
            'dependent' => true, // Xóa các đơn vị con khi xóa đơn vị cha
            'cascadeCallbacks' => true
        ]);

        $this->belongsTo('ParentUnit', [
            'className' => 'Units',
            'foreignKey' => 'parent_id',
            'joinType' => 'LEFT' // LEFT JOIN để bao gồm cả đơn vị gốc
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name', 'Vui lòng nhập tên đơn vị');

        $validator
            ->scalar('code')
            ->maxLength('code', 50)
            ->requirePresence('code', 'create')
            ->notEmptyString('code', 'Vui lòng nhập mã đơn vị')
            ->add('code', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Mã đơn vị đã tồn tại'
            ]);

        $validator
            ->integer('parent_id')
            ->allowEmptyString('parent_id');

        return $validator;
    }

    /**
     * Custom finder để lấy đơn vị với thông tin đơn vị cha
     */
    public function findWithUnits(Query $query, array $options): Query
    {
        return $query
            ->contain(['ParentUnit' => function ($q) {
                return $q->select(['id', 'name', 'code']);
            }])
            ->order(['Units.created' => 'DESC']);
    }

    /**
     * Finder cho danh sách phân trang dạng cây
     */
    public function findThreadedList(Query $query, array $options): Query
    {
        return $query
            ->find('threaded')
            ->order(['lft' => 'ASC']);
    }

    /**
     * Lấy danh sách đơn vị dạng phẳng cho select box
     */
    public function findFlatList(Query $query, array $options): Query
    {
        return $query
            ->find('list')
            ->order(['lft' => 'ASC']);
    }

    public function findForIndex(Query $query, array $options)
    {
        return $query
            ->contain(['ParentUnits'])
            ->order(['Units.created' => 'DESC']);
    }

    /**
     * Trước khi lưu, tự động sinh mã nếu không có
     */
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && empty($entity->code)) {
            $entity->code = $this->generateUnitCode($entity->name);
        }
    }

    /**
     * Tạo mã đơn vị tự động từ tên
     */
    protected function generateUnitCode(string $name): string
    {
        $code = mb_substr($name, 0, 3);
        $code = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $code));
        
        $i = 1;
        $originalCode = $code;
        while ($this->exists(['code' => $code])) {
            $code = $originalCode . $i++;
        }
        
        return $code;
    }

}
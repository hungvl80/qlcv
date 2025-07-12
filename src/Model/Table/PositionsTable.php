<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Positions Model
 *
 * @method \App\Model\Entity\Position newEmptyEntity()
 * @method \App\Model\Entity\Position newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Position[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Position get($primaryKey, array $options = [])
 * @method \App\Model\Entity\Position findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Position patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Position[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Position|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Position saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Position[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Position[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Position[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Position[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PositionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('positions'); // Tên bảng trong database
        $this->setDisplayField('name'); // Trường hiển thị mặc định cho Position (ví dụ trong dropdown)
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp'); // Tự động cập nhật created và modified

        // Thiết lập mối quan hệ nếu có
        // Ví dụ: Một chức vụ có thể có nhiều người dùng
        $this->hasMany('Users', [
            'foreignKey' => 'position_id',
        ]);
        // Hoặc mối quan hệ với AssignmentPermissions nếu cần
        $this->hasMany('AssignmentPermissionsAssigner', [
            'className' => 'AssignmentPermissions',
            'foreignKey' => 'assigner_position_id',
        ]);
        $this->hasMany('AssignmentPermissionsTarget', [
            'className' => 'AssignmentPermissions',
            'foreignKey' => 'target_position_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name', 'Vui lòng nhập tên chức vụ.');

        $validator
            ->scalar('code')
            ->maxLength('code', 50)
            ->allowEmptyString('code');

        $validator
            ->integer('level')
            ->requirePresence('level', 'create')
            ->notEmptyString('level', 'Vui lòng nhập cấp bậc.')
            ->add('level', 'validValue', [
                'rule' => function ($value, $context) {
                    return $value >= 0; // Đảm bảo level là số không âm
                },
                'message' => 'Cấp bậc phải là số không âm.'
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used to encapsulate
     * application rules.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name', 'message' => 'Tên chức vụ đã tồn tại.']);

        return $rules;
    }
}
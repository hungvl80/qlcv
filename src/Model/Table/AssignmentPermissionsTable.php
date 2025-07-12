<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssignmentPermissions Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $AssignerUsers
 * @property \App\Model\Table\PositionsTable&\Cake\ORM\Association\BelongsTo $AssignerPositions
 * @property \App\Model\Table\UnitsTable&\Cake\ORM\Association\BelongsTo $AssignerUnits
 * @property \App\Model\Table\UnitsTable&\Cake\ORM\Association\BelongsTo $TargetUnits
 * @property \App\Model\Table\PositionsTable&\Cake\ORM\Association\BelongsTo $TargetPositions
 *
 * @method \App\Model\Entity\AssignmentPermission newEmptyEntity()
 * @method \App\Model\Entity\AssignmentPermission newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AssignmentPermission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssignmentPermission get($primaryKey, array $options = [])
 * @method \App\Model\Entity\AssignmentPermission findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AssignmentPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssignmentPermission[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssignmentPermission|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssignmentPermission saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssignmentPermission[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssignmentPermission[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssignmentPermission[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AssignmentPermission[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AssignmentPermissionsTable extends Table
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

        $this->setTable('assignment_permissions'); // Tên bảng trong database
        $this->setDisplayField('id'); // Hoặc một trường nào đó có ý nghĩa hơn nếu có
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp'); // Tự động cập nhật created và modified

        // Định nghĩa các mối quan hệ BelongsTo
        $this->belongsTo('AssignerUsers', [
            'className' => 'Users',
            'foreignKey' => 'assigner_user_id',
        ]);
        $this->belongsTo('AssignerPositions', [
            'className' => 'Positions',
            'foreignKey' => 'assigner_position_id',
            'joinType' => 'INNER', // Có thể là INNER nếu luôn cần có chức vụ người giao
        ]);
        $this->belongsTo('AssignerUnits', [
            'className' => 'Units',
            'foreignKey' => 'assigner_unit_id',
            'joinType' => 'INNER', // Có thể là INNER nếu luôn cần có đơn vị người giao
        ]);
        $this->belongsTo('TargetUnits', [
            'className' => 'Units',
            'foreignKey' => 'target_unit_id',
        ]);
        $this->belongsTo('TargetPositions', [
            'className' => 'Positions',
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
            ->integer('assigner_user_id')
            ->allowEmptyString('assigner_user_id');

        $validator
            ->integer('assigner_position_id')
            ->requirePresence('assigner_position_id', 'create')
            ->notEmptyString('assigner_position_id', 'Vui lòng chọn chức vụ người giao.');

        $validator
            ->integer('assigner_unit_id')
            ->requirePresence('assigner_unit_id', 'create')
            ->notEmptyString('assigner_unit_id', 'Vui lòng chọn đơn vị người giao.');

        $validator
            ->integer('target_unit_id')
            ->allowEmptyString('target_unit_id');

        $validator
            ->integer('target_position_id')
            ->allowEmptyString('target_position_id');

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
        $rules->add($rules->existsIn(['assigner_user_id'], 'AssignerUsers'), ['errorField' => 'assigner_user_id', 'message' => 'Người dùng không hợp lệ.']);
        $rules->add($rules->existsIn(['assigner_position_id'], 'AssignerPositions'), ['errorField' => 'assigner_position_id', 'message' => 'Chức vụ người giao không hợp lệ.']);
        $rules->add($rules->existsIn(['assigner_unit_id'], 'AssignerUnits'), ['errorField' => 'assigner_unit_id', 'message' => 'Đơn vị người giao không hợp lệ.']);
        $rules->add($rules->existsIn(['target_unit_id'], 'TargetUnits'), ['errorField' => 'target_unit_id', 'message' => 'Đơn vị mục tiêu không hợp lệ.']);
        $rules->add($rules->existsIn(['target_position_id'], 'TargetPositions'), ['errorField' => 'target_position_id', 'message' => 'Chức vụ mục tiêu không hợp lệ.']);

        // Bạn có thể thêm các quy tắc duy nhất hoặc phức tạp hơn ở đây nếu cần
        // Ví dụ: Đảm bảo không có cấu hình trùng lặp cho cùng một người giao đến cùng một mục tiêu
        /*
        $rules->add(
            $rules->isUnique(
                ['assigner_position_id', 'assigner_unit_id', 'target_unit_id', 'target_position_id'],
                'Cấu hình phân quyền này đã tồn tại.'
            ),
            ['errorField' => 'assigner_position_id']
        );
        */

        return $rules;
    }
}
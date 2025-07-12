<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\UnitsTable&\Cake\ORM\Association\BelongsTo $Units
 * @property \App\Model\Table\PositionsTable&\Cake\ORM\Association\BelongsTo $Positions
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\User> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $callable = null)
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\User> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, array $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, array $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, array $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField(''); // Hoặc 'email', tùy theo bạn muốn hiển thị gì
        $this->setPrimaryKey('id');

        // Thêm BelongsTo cho Units
        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'LEFT', // Hoặc 'INNER' nếu bạn yêu cầu người dùng luôn có đơn vị
        ]);
        // Thêm BelongsTo cho Positions
        $this->belongsTo('Positions', [
            'foreignKey' => 'position_id',
            'joinType' => 'LEFT', // Hoặc 'INNER' nếu bạn yêu cầu người dùng luôn có chức vụ
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
            ->scalar('username') // Thêm validation cho username
            ->maxLength('username', 100) // Khớp với độ dài trong DB
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'Vui lòng nhập tên đăng nhập.');

        $validator
            ->scalar('full_name') // Thêm validation cho full_name (tên người dùng đầy đủ)
            ->maxLength('full_name', 255) // Khớp với độ dài trong DB
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name', 'Vui lòng nhập họ và tên.');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

       $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create') // Chỉ bắt buộc khi 'create'
            ->notEmptyString('password', 'Mật khẩu không được để trống.', 'create'); // Chỉ bắt buộc khi 'create'

        $validator
            ->integer('unit_id')
            ->allowEmptyString('unit_id');

        $validator
            ->integer('position_id')
            ->allowEmptyString('position_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used to ensure an entity is valid.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username', 'message' => 'Tên đăng nhập đã tồn tại.']);
        $rules->add($rules->existsIn(['unit_id'], 'Units'), ['errorField' => 'unit_id']);
        $rules->add($rules->existsIn(['position_id'], 'Positions'), ['errorField' => 'position_id']);

        return $rules;
    }
}
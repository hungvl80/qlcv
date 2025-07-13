<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 */
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('full_name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Units', [
            'foreignKey' => 'unit_id',
            'joinType' => 'LEFT',
        ]);
        
        $this->belongsTo('Positions', [
            'foreignKey' => 'position_id',
            'joinType' => 'LEFT',
        ]);

        // Thêm behavior Timestamp tự động
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('username')
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username', 'Vui lòng nhập tên đăng nhập')
            ->add('username', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Tên đăng nhập đã được sử dụng'
            ]);

        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 255)
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name', 'Vui lòng nhập họ tên đầy đủ');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'Vui lòng nhập email')
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Email đã được sử dụng'
            ]);
        $validator
            ->scalar('password')
            //->minLength('password', 8, 'Mật khẩu phải có ít nhất 8 ký tự')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Vui lòng nhập mật khẩu', 'create');
            /*
            ->add('password', 'custom', [
                'rule' => function ($value, $context) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        return 'Mật khẩu phải chứa ít nhất 1 chữ hoa';
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        return 'Mật khẩu phải chứa ít nhất 1 chữ thường';
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        return 'Mật khẩu phải chứa ít nhất 1 số';
                    }
                    return true;
                }
            ]);
            */
        $validator
            ->integer('unit_id')
            ->allowEmptyString('unit_id');

        $validator
            ->integer('position_id')
            ->allowEmptyString('position_id');

        $validator
            ->boolean('is_active')
            ->allowEmptyString('is_active');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['unit_id'], 'Units'));
        $rules->add($rules->existsIn(['position_id'], 'Positions'));

        return $rules;
    }

    /**
     * Finder method for authentication
     */
    public function findAuth(Query $query, array $options)
    {
        return $query
            ->select(['id', 'username', 'password', 'email', 'full_name', 'is_active', 'unit_id', 'position_id', 'is_admin'])
            ->contain(['Units', 'Positions'])
            ->where(['Users.is_active' => true]);
    }

    /**
     * Hash password before saving
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

    public function findWithUnits(Query $query, array $options)
    {
        return $query->contain(['Units'])
                    ->order(['Users.created' => 'DESC']);
    }
}
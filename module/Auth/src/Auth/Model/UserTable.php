<?php

declare(strict_types=1);

/**
 * This file contains the Model class to manage user table
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Model;

use Auth\Form\Filter\User;
use Zend\Db\TableGateway\TableGateway;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\ResultSet\ResultSet;

class UserTable
{
    /** @var TableGateway $tableGateway */
    private $tableGateway;

    /**
     * Constructor to initialize variable
     * 
     * @param Zend\Db\TableGateway\TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        //$this->initialize();
    }

    /**
     * Used to fetch all user
     * 
     * @return Zend\Db\ResultSet\ResultSet $resultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Used to fetch user by email id
     * 
     * @param string $emailId An user id
     * @return array|\ArrayObject|null
     */
    public function getUser($emailId)
    {
        $rowset = $this->tableGateway->select(['email_id' => $emailId]);
        $row = $rowset->current();
        return $row;
    }

    /**
     * Used to register new user
     * 
     * @param User $user
     */
    public function saveUser(User $user)
    {
        $bcrypt = new Bcrypt();
        $datetime = date('Y-m-d H:i:s');
        $data = [
            'user_name' => $user->userName,
            'role_id'  => $user->roleId,
            'email_id'  => $user->emailId,
            'password'  => $bcrypt->create($user->password),
            'created_at' => $datetime,
            'updated_at' => $datetime,
        ];
        $this->tableGateway->insert($data);
    }

    /**
     * Used to update user info
     * 
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $data = [
            'user_name' => $user->userName,
            'email_id'  => $user->emailId,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        if ($this->getUser((int) $user->id)) {
            $this->tableGateway->update($data, ['id' => (int) $user->id]);
        } else {
            throw new \Exception('User id does not exist');
        }
    }

    /**
     * Used to delete user by id
     * 
     * @param int $id An user id
     */
    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

    /**
     * Used to fetch user by where condition and given columns
     * 
     * @param array $where An where condition columns
     * @param array $columns An required columns names
     * @return array|\ArrayObject|null
     */
    public function getUsers($where = array(), $columns = array())
    {
        try {
            $select = $this->tableGateway->getSql()->select();
            
            if (count($where) > 0) {
                $select->where($where);
            }
            
            if (count($columns) > 0) {
                $select->columns($columns);
            }
            
            $select->join(array('userRole' => 'user_role'), 'userRole.user_id = user.id', array('role_id'), 'LEFT');
            $select->join(array('role' => 'role'), 'userRole.role_id = role.rid', array('role_name'), 'LEFT');
            
            $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
            $users = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
            return $users[0];
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
}

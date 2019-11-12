<?php

declare(strict_types=1);

/**
 * This file contains the Model class to manage Role Permission table
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class RolePermissionTable extends AbstractTableGateway
{
    /** @var string $table */
    public $table = 'role_permission';

    /**
     * Constructor to initialize variable
     * 
     * @param Zend\Db\Adapter\Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }

    /**
     * Used to get role permissions
     * 
     * @return mixed
     */
    public function getRolePermissions()
    {
        $sql = new Sql($this->getAdapter());

        $select = $sql->select()
            ->from(array(
                't1' => 'role'
            ))
            ->columns(array(
                'role_name'
            ))
            ->join(array(
                't2' => $this->table
            ), 't1.rid = t2.role_id', array(), 'left')
            ->join(array(
                't3' => 'permission'
            ), 't3.id = t2.permission_id', array(
                'permission_name'
            ), 'left')
            ->join(array(
                't4' => 'resource'
            ), 't4.id = t3.resource_id', array(
                'resource_name'
            ), 'left')
            ->where('t3.permission_name is not null and t4.resource_name is not null')
            ->order('t1.rid');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
            ->toArray();
        return $result;
    }
}

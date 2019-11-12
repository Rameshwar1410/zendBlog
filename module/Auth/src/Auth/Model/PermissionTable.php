<?php

declare(strict_types=1);

/**
 * This file contains the Model class to manage Permission table
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

class PermissionTable extends AbstractTableGateway
{
    /** @var string $table */
    public $table = 'permission';

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
     * Used to fetch all Resource Permissions by role id
     * 
     * @param int $roleId
     * @return mixed
     */
    public function getResourcePermissions($roleId)
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'p' => $this->table
            ));
            $select->columns(array(
                'resid'
            ));

            $select->join(array(
                "r" => "resource"
            ), "p.resid = r.resid", array(
                "name",
                "route"
            ));
            $select->where(array(
                'p.rid' => $roleId
            ));
            $select->order(array(
                'menu_order'
            ));

            $statement = $sql->prepareStatementForSqlObject($select);
            $resources = $this->resultSetPrototype->initialize($statement->execute())
                ->toArray();
            return $resources;
        } catch (\Exception $err) {
            throw $err;
        }
    }
}

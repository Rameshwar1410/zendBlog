<?php

declare(strict_types=1);

/**
 * This file contains the Model class to manage User Role table
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;

class UserRole extends AbstractTableGateway
{
    /** @var string $table */
    public $table = 'user_role';

    /**
     * Constructor to initialize variable
     * 
     * @param Zend\Db\Adapter\Adapter $adapter
     */
    public function __construct()
    {
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }

    /**
     * Used to get role permissions
     * 
     * @param array $where An where condition
     * @param array $columns An required columns to get data
     * @param string $orderBy For sotring
     * @param bool $paging For pagination
     * @return mixed
     */
    public function getUserRoles($where = array(), $columns = array(), $orderBy = '', $paging = false)
    {
        try {
            $sql = new Sql($this->getAdapter());
            $select = $sql->select()->from(array(
                'sa' => $this->table
            ));

            if (count($where) > 0) {
                $select->where($where);
            }

            $select->where($where);

            if (count($columns) > 0) {
                $select->columns($columns);
            }

            if (!empty($orderBy)) {
                $select->order($orderBy);
            }

            if ($paging) {

                $dbAdapter = new DbSelect($select, $this->getAdapter());
                $paginator = new Paginator($dbAdapter);

                return $paginator;
            } else {
                $statement = $sql->prepareStatementForSqlObject($select);

                $clients = $this->resultSetPrototype->initialize($statement->execute())
                    ->toArray();

                return $clients;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getPrevious()->getMessage());
        }
    }
}

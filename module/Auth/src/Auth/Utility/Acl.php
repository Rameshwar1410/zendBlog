<?php

declare(strict_types=1);

/**
 * This file contains the Utility class to manage ACL (access control list)
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Utility;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Acl extends ZendAcl implements ServiceLocatorAwareInterface
{
/** @var const DEFAULT_ROLE */
    const DEFAULT_ROLE = 'User';

    /** @var ServiceLocatorInterface $serviceLocator */
    private $serviceLocator;

    /** @var mixed An roles data */
    private $roles;

    /** @var mixed rolePermission An role permission data */
    private $rolePermission;

    /** @var mixed commonPermission An common permission data */
    private $commonPermission;

    /**
     * Set service locator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        
        return $this;
    }

    /**
     * Get service locator
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Initialize acl
     */
    public function initAcl()
    {
        $this->roles = $this->_getAllRoles();
        $this->resources = $this->_getAllResources();
        $this->rolePermission = $this->_getRolePermissions();
        // we are not putting these resource & permission in table bcz it is
        // common to all user
        $this->commonPermission = array(
            'Auth\Controller\Login' => array(
                'logout',
                'index'                
            )
        );
        $this->_addRoles()
            ->_addResources()
            ->_addRoleResources();
    }

    /**
     * Checks permission for access screen
     */
    public function isAccessAllowed($role, $resource, $permission)
    {
        if (! $this->hasResource($resource)) {
            return false;
        }
        if ($this->isAllowed($role, $resource, $permission)) {
            return true;
        }
        return false;
    }

    /**
     * Adding roles
     */
    protected function _addRoles()
    {
        $this->addRole(new Role(self::DEFAULT_ROLE));
        
        if (! empty($this->roles)) {
            foreach ($this->roles as $role) {
                $roleName = $role['role_name'];
                if (! $this->hasRole($roleName)) {
                    $this->addRole(new Role($roleName), self::DEFAULT_ROLE);
                }
            }
        }
        return $this;
    }

    /**
     * Adding resources
     */
    protected function _addResources()
    {
        if (! empty($this->resources)) {
            foreach ($this->resources as $resource) {
                if (! $this->hasResource($resource['resource_name'])) {
                    $this->addResource(new Resource($resource['resource_name']));
                }
            }
        }
        
        // add common resources
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                if (! $this->hasResource($resource)) {
                    $this->addResource(new Resource($resource));
                }
            }
        }
        
        return $this;
    }

    /**
     * Adding role resources
     */
    protected function _addRoleResources()
    {
        // allow common resource/permission to guest user
        if (! empty($this->commonPermission)) {
            foreach ($this->commonPermission as $resource => $permissions) {
                foreach ($permissions as $permission) {
                    $this->allow(self::DEFAULT_ROLE, $resource, $permission);
                }
            }
        }
        
        if (! empty($this->rolePermission)) {
            foreach ($this->rolePermission as $rolePermissions) {
                $this->allow($rolePermissions['role_name'], $rolePermissions['resource_name'], $rolePermissions['permission_name']);
            }
        }
        
        return $this;
    }

    /**
     * Geting all roles
     */
    protected function _getAllRoles()
    {
        $roleTable = $this->getServiceLocator()->get("RoleTable");
        return $roleTable->getUserRoles();
    }

    /**
     * Geting all role resources
     */
    protected function _getAllResources()
    {
        $resourceTable = $this->getServiceLocator()->get("ResourceTable");
        return $resourceTable->getAllResources();
    }

    /**
     * Geting all role permissions
     */
    protected function _getRolePermissions()
    {
        $rolePermissionTable = $this->getServiceLocator()->get("RolePermissionTable");
        return $rolePermissionTable->getRolePermissions();
    }
}

<?php

declare(strict_types=1);

/**
 * This file contains the Module class for Service Provider to auth module
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth;

use Auth\Form\Filter\User;
use Auth\Model\PermissionTable;
use Auth\Model\ResourceTable;
use Auth\Model\Role;
use Auth\Model\RolePermissionTable;
use Auth\Model\UserRole;
use Auth\Model\UserTable;
use Auth\Service\Authenticator;
use Auth\Service\UserCreator;
use Auth\Utility\Acl;
use Zend\ModuleManager\Feature\{AutoloaderProviderInterface, ConfigProviderInterface};
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an instance of requested service.
     *
     * @return instance
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Auth\Model\UserTable' =>  function ($serviceManager) {
                    $tableGateway = $serviceManager->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'AuthService' => function ($serviceManager) {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter($adapter, 'user', 'email_id', 'password');

                    $auth = new AuthenticationService();
                    $auth->setAdapter($dbAuthAdapter);
                    return $auth;
                },
                'Auth\Service\UserCreatorService' =>  function ($serviceManager) {
                    $userTable = $serviceManager->get('Auth\Model\UserTable');
                    $flashMessenger = new FlashMessenger();
                    return new UserCreator($userTable, $flashMessenger);
                },
                'Auth\Service\AuthenticatorService' =>  function ($serviceManager) {
                    $userTable = $serviceManager->get('Auth\Model\UserTable');
                    $authService = $serviceManager->get('AuthService');
                    $flashMessenger = new FlashMessenger();
                    return new Authenticator($userTable, $flashMessenger, $authService);
                },
                'Acl' => function ($serviceManager) {
                    return new Acl();
                },
                'RoleTable' => function ($serviceManager) {
                    return new Role($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'UserRoleTable' => function ($serviceManager) {
                    return new UserRole($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'PermissionTable' => function ($serviceManager) {
                    return new PermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'ResourceTable' => function ($serviceManager) {
                    return new ResourceTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RolePermissionTable' => function ($serviceManager) {
                    return new RolePermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                }
            ],
        ];
    }

    /**
     * Bootstrap auth module.
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager   = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [
            $this,
            'boforeDispatch'
        ], 100);
    }

    /**
     * Called before any controller action called.
     */
    function boforeDispatch(MvcEvent $event)
    {
        $response = $event->getResponse();

        /* Offline pages not needed authentication */
        $whiteList = [
            'Auth\Controller\Login-index',
            'Auth\Controller\Registration-index'
        ];

        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');

        $requestedResourse = $controller . "-" . $action;
        $session = new Container('User');
        if ($session->offsetExists('emailId')) {
            if ($requestedResourse == 'Auth\Controller\Login-index' || in_array($requestedResourse, $whiteList)) {
                $url = '/';
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            } else {

                $serviceManager = $event->getApplication()->getServiceManager();
                $userRole = $session->offsetGet('roleName');

                $acl = $serviceManager->get('Acl');
                $acl->initAcl();

                $status = $acl->isAccessAllowed($userRole, $controller, $action);
                if (!$status) {
                    die('Permission denied');
                }
            }
        } else {
            if ($requestedResourse != 'Auth\Controller\Login-index' && !in_array($requestedResourse, $whiteList)) {
                $url = ($requestedResourse == 'Auth\Controller\Registration-index') ? '/registration' : '/login';
                $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
                $response->setStatusCode(302);
            }
            $response->sendHeaders();
        }
    }
}

<?php

declare(strict_types=1);

/**
 * This file contains the Module class for Service Provider to blog module
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog;

use Blog\Form\Filter\Post;
use Blog\Model\PostTable;
use Blog\Service\PostHelperService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    /**
     * Bootstrap blog module.
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Return an instance of requested service.
     *
     * @return instance
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Blog\Model\PostTable' =>  function ($serviceManager) {
                    $tableGateway = $serviceManager->get('PostTableGateway');
                    $table = new PostTable($tableGateway);
                    return $table;
                },
                'PostTableGateway' => function ($serviceManager) {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Post());
                    return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
                },
                'Blog\Service\PostHelperService' =>  function ($serviceManager) {
                    return new PostHelperService($serviceManager->get('Blog\Model\PostTable'));
                },
            ),
        );
    }
}

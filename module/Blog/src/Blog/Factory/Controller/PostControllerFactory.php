<?php

declare(strict_types=1);

/**
 * This file contains the factory class to post controller
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Factory\Controller;

use Blog\Controller\PostController;
use Zend\ServiceManager\{FactoryInterface, ServiceLocatorInterface};

class PostControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $postHelperService = $serviceLocator->getServiceLocator()
            ->get('Blog\Service\PostHelperService');

        return new PostController($postHelperService);
    }
}

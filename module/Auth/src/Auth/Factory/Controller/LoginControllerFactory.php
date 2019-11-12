<?php

declare(strict_types=1);

/**
 * This file contains the factory class to login controller
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Factory\Controller;

use Auth\Controller\LoginController;
use Zend\ServiceManager\{FactoryInterface, ServiceLocatorInterface};

class LoginControllerFactory implements FactoryInterface
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
        $authenticatorService = $serviceLocator->getServiceLocator()
            ->get('Auth\Service\AuthenticatorService');

        return new LoginController($authenticatorService);
    }
}

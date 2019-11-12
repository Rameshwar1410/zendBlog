<?php

declare(strict_types=1);

/**
 * This file contains the factory class to registration controller
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Factory\Controller;

use Auth\Controller\RegistrationController;
use Zend\ServiceManager\{FactoryInterface, ServiceLocatorInterface};

class RegistrationControllerFactory implements FactoryInterface
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
        $userCreatorService = $serviceLocator->getServiceLocator()
            ->get('Auth\Service\UserCreatorService');

        return new RegistrationController($userCreatorService);
    }
}

<?php

declare(strict_types=1);

/**
 * This file contains the service class to manage user registration
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Service;

use Auth\Model\UserTable;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

class UserCreator
{
    /** @var UserTable $userTable */
    private $userTable;

    /** @var Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger */
    private $flashMessenger;

    /**
     * Constructor to initialize variable
     * 
     * @param Auth\Model\UserTable $userTable
     * @param Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     */
    public function __construct(
        UserTable $userTable,
        FlashMessenger $flashMessenger
    )
    {
        $this->userTable = $userTable;
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * Used to register new user
     * 
     * @param mixed $data User data for registration
     */
    public function save($data)
    {
        $this->userTable->saveUser($data);
        $this->flashMessenger->addSuccessMessage('Your account has been created successfully 
                and is ready to use.');
    }
}

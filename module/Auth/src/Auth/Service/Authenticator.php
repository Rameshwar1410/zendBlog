<?php

declare(strict_types=1);

/**
 * This file contains the service class to manage user login|logout functionality
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Service;

use Auth\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Session\Container;

class Authenticator
{
    /** @var UserTable $userTable */
    private $userTable;

    /** @var Zend\Authentication\AuthenticationService $authService */
    private $authService;

    /** @var FlashMessenger $flashMessenger */
    private $flashMessenger;

    /**
     * Constructor to initialize variable
     * 
     * @param Auth\Model\UserTable $userTable
     * @param Zend\Mvc\Controller\Plugin\FlashMessenger $flashMessenger
     * @param Zend\Authentication\AuthenticationService $authService
     */
    public function __construct(
        UserTable $userTable,
        FlashMessenger $flashMessenger,
        AuthenticationService $authService
    ) {
        $this->userTable = $userTable;
        $this->flashMessenger = $flashMessenger;
        $this->authService = $authService;
    }

    /**
     * Used to authenticate given credential for login
     *
     * @param array $data An contain email and password
     * @return bool
     */
    public function authenticateLogin($data)
    {
        $bcrypt = new Bcrypt();
        $dbCredential = (array) $this->userTable->getUsers(array(
            'email_id' => $data['emailId']
        ), array(
            'id', 'password', 'user_name'
        ));//print_r($dbCredential);exit;
        if (
            !isset($dbCredential['password']) ||
            !$bcrypt->verify($data['password'], $dbCredential['password'])
        ) {
            $this->flashMessenger->addErrorMessage($data['emailId'] . ' is not registered');

            return false;
        }
        $this->authService
            ->getAdapter()
            ->setIdentity($data['emailId'])
            ->setCredential($dbCredential['password']);
        $result = $this->authService->authenticate();
        if ($result->isValid()) {
            $session = new Container('User');
            $session->offsetSet('emailId', $data['emailId']);
            $session->offsetSet('userId', $dbCredential['id']);
            $session->offsetSet('roleId', $dbCredential['role_id']);
            $session->offsetSet('roleName', $dbCredential['role_name']);
            $this->flashMessenger->addSuccessMessage('Welcome back ' . ucfirst($dbCredential['user_name']) . ' .');

            return true;
        } else {
            $this->flashMessenger->addErrorMessage('Invalid credentials.');

            return false;
        }
    }

    /**
     * Used to handle user logout functionality
     * 
     * @return void
     */
    public function authLogout()
    {
        $session = new Container('User');
        $session->getManager()->destroy();
        $this->authService->clearIdentity();
    }
}

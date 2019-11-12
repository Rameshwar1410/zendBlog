<?php

declare(strict_types=1);

/**
 * This file contains the controller class to manage user login|logout functionality
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Controller;

use Auth\Form\LoginForm;
use Auth\Form\Filter\LoginFilter;
use Auth\Service\Authenticator;
use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController
{
    /** @var Auth\Service\Authenticator $authenticator */
    private $authenticator;

    /**
     * Constructor to initialize variable
     * 
     * @param Auth\Service\Authenticator $authenticator
     */
    public function __construct(Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * Used to handle user login functionality
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $loginForm = new LoginForm('loginForm');
        $loginForm->setInputFilter(new LoginFilter());
        if ($request->isPost()) {
            $loginForm->setData($request->getPost());
            if (
                $loginForm->isValid() &&
                $this->authenticator->authenticateLogin($loginForm->getData())
            ) {
                return $this->redirect()->tourl('/');
            }
        }

        return ['loginForm' => $loginForm];
    }

    /**
     * Used to handle user logout functionality
     * 
     * @return Zend\Mvc\Controller\Plugin\Redirect
     */
    public function logoutAction()
    {
        $this->authenticator->authLogout();

        return $this->redirect()->toUrl('/login');
    }
}

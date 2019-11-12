<?php

declare(strict_types=1);

/**
 * This file contains the controller class to manage user registration
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Controller;

use Auth\Form\RegistrationForm;
use Auth\Form\Filter\User;
use Auth\Service\UserCreator;
use Zend\Mvc\Controller\AbstractActionController;

class RegistrationController extends AbstractActionController
{
    /** @var Auth\Service\UserCreator $userCreator */
    private $userCreator;

    /**
     * Constructor to initialize variable
     * 
     * @param Auth\Service\UserCreator $userCreator
     */
    public function __construct(UserCreator $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    /**
     * Used to register new user
     * 
     * @return void
     */
    public function indexAction()
    {
        $form = new RegistrationForm();
        $form->get('submit')->setValue('Register');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $regstration = new User();
            $form->setInputFilter($regstration->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $regstration->exchangeArray($form->getData());
                $this->userCreator->save($regstration);

                return $this->redirect()->tourl('/login');
            }
        }

        return ['form' => $form];
    }
}

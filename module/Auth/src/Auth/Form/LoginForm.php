<?php

declare(strict_types=1);

/**
 * This file contains the Form class to create login form
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    /**
     * Constructor to initialize variable
     * 
     * @param mixed|null $name
     * @return void
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'emailId',
            'type' => 'Email',
            'options' => [
                'label' => 'Email Id',
            ],
            'attributes' => [
                'placeholder' => 'Email Id',
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'placeholder' => 'Password',
                'class' => 'form-control',
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'loginCsrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600
                ]
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Login',
                'id' => 'submitbutton',
                'class' => 'btn btn-success btn-block',
            ],
        ]);
    }
}

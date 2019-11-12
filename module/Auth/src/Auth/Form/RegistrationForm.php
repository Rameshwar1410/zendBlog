<?php

declare(strict_types=1);

/**
 * This file contains the Form class to create registration form
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Form;

use Zend\Form\Form;

class RegistrationForm extends Form
{
    /**
     * Constructor to initialize variable
     * 
     * @param mixed|null $name
     * @return void
     */
    public function __construct($name = null)
    {
        parent::__construct('registration');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);
        $this->add([
            'name' => 'user_name',
            'type' => 'Text',
            'options' => [
                'label' => 'User Name',
            ],
            'attributes' => [
                'placeholder' => 'User Name',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'email_id',
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
            'name' => 'confirmPassword',
            'type' => 'Password',
            'options' => [
                'label' => 'Confirm Password',
            ],
            'attributes' => [
                'placeholder' => 'Confirm Password',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success btn-block',
            ],
        ]);
    }
}

<?php

declare(strict_types=1);

/**
 * This file contains the Filter class to filter login form data
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Auth\Form\Filter;

use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    /**
     * Constructor to initialize variable
     */
    public function __construct()
    {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;

        $this->add([
            'name' => 'emailId',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Email can not be empty.'
                        ]
                    ],
                    'break_chain_on_failure' => true
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'messages' => [
                            $invalidEmail => 'Enter Valid Email Address.'
                        ]
                    ]
                ]
            ],
        ]);

        $this->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            $isEmpty => 'Password can not be empty.'
                        ]
                    ]
                ]
            ]
        ]);
    }
}

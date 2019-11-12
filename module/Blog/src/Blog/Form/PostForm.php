<?php

declare(strict_types=1);

/**
 * This file contains the Form class to create post form
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Form;

use Zend\Form\Form;

class PostForm extends Form
{
    /**
     * Constructor to initialize variable
     * 
     * @param mixed|null $name
     * @return void
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('album');
        
        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'Text',
            'options' => [
                'label' => 'Title',
            ],
            'attributes' => [
                'placeholder' => 'Title',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'description',
            'type' => 'Text',
            'options' => [
                'label' => 'Description',
            ],
            'attributes' => [
                'placeholder' => 'Description',
                'class' => 'form-control content',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success',
            ],
        ]);
    }
}

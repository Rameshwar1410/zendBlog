<?php

declare(strict_types=1);

/**
 * This file contains the controller class to blog home page that shows all posts here
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    /** @var PostTable $postTable */
    private $postTable;

    /**
     * Used to show all posts
     * 
     * @return void
     */
    public function indexAction()
    {
        $content = 'sample content you want to convert';
        $converter = $this->getServiceLocator()->get('convertercontent');
        return [
            'posts' => $this->getPostTable()->fetchAll(),
        ];
    }

    /**
     * Used to show selected post
     * 
     * @return void
     */
    public function showAction()
    {
        return [
            'post' => $this->getPostTable()->getPost($this->params()->fromRoute('id', 0)),
        ];
    }

    /**
     * Used to create an instance of UserTable
     * 
     * @return Blog\Controller\PostTable
     */
    public function getPostTable()
    {
        if (!$this->postTable) {
            $sm = $this->getServiceLocator();
            $this->postTable = $sm->get('Blog\Model\PostTable');
        }

        return $this->postTable;
    }
}

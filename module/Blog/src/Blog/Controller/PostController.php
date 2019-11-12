<?php

declare(strict_types=1);

/**
 * This file contains the controller class to manage post
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Controller;

use Blog\Form\Filter\Post;
use Blog\Form\PostForm;
use Blog\Service\PostHelperService;
use Zend\Mvc\Controller\AbstractActionController;

class PostController extends AbstractActionController
{
    /** @var PostTable $postTable */
    private $postTable;

    /** @var Blog\Service\PostHelperService $postHelperService */
    private $postHelperService;

    /**
     * Constructor to initialize variable
     * 
     * @param Blog\Service\PostHelperService $postHelperService
     */
    public function __construct(PostHelperService $postHelperService)
    {
        $this->postHelperService = $postHelperService;
    }

    /**
     * Used to show user posted all posts list
     */
    public function indexAction()
    {
        return [
            'posts' => $this->postHelperService->getAllPost(),
        ];
    }

    /**
     * Used to show added post
     */
    public function showAction()
    {
        return [
            'post' => $this->postHelperService->getPostById((int)$this->params()->fromRoute('id', 0)),
        ];
    }

    /**
     * Used to add new post
     * 
     * @return Zend\Mvc\Controller\Plugin\Redirect
     */
    public function addAction()
    {
        $form = new PostForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = new Post();
            $form->setInputFilter($post->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $post->exchangeArray($form->getData());
                $this->postHelperService->save($post);

                return $this->redirect()->toRoute('post');
            }
        }

        return ['form' => $form];
    }

    /**
     * Used to edit post by post id
     * 
     * @return Zend\Mvc\Controller\Plugin\Redirect
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post', [
                'action' => 'add'
            ]);
        }

        // Get the post with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $post = $this->postHelperService->getPostById($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('post', [
                'action' => 'index'
            ]);
        }

        $form  = new PostForm();
        $form->bind($post);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($post->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->postHelperService->update($post);

                return $this->redirect()->toRoute('post');
            }
        }

        return [
            'id' => $id,
            'form' => $form,
        ];
    }

    /**
     * Used to delete post by post id
     * 
     * @return Zend\Mvc\Controller\Plugin\Redirect
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('post');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->postHelperService->delete($id);
            }

            return $this->redirect()->toRoute('post');
        }

        return [
            'id'    => $id,
            'post' => $this->postHelperService->getPostById($id)
        ];
    }
}

<?php

declare(strict_types=1);

/**
 * This file contains the service class to manage post
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Service;

use Blog\Form\Filter\Post;
use Blog\Model\PostTable;

class PostHelperService
{
    /** @var Blog\Model\PostTable $postTable */
    private $postTable;

    /**
     * Constructor to initialize variable
     * 
     * @param Blog\Model\PostTable $postTable
     */
    public function __construct(PostTable $postTable)
    {
        $this->postTable = $postTable;
    }

    /**
     * Used to return user posted all posts list
     */
    public function getAllPost()
    {
        return $this->postTable->fetchAll();
    }

    /**
     * Used to return post by given post id
     * 
     * @param int $id Post id
     */
    public function getPostById(int $id)
    {
        return $this->postTable->getPost($id);
    }

    /**
     * Used to add new post
     * 
     * @param mixed $data Post data for add
     */
    public function save($data)
    {
        $this->postTable->savePost($data);
    }

    /**
     * Used to update post
     * 
     * @param Blog\Form\Filter\Post $data Post data for update
     */
    public function update(Post $data)
    {
        $this->postTable->updatePost($data);
    }

    /**
     * Used to delete post by post id
     * 
     * @param int $id Post id
     */
    public function delete(int $id)
    {
        $this->postTable->deletePost($id);
    }
}

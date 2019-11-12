<?php

declare(strict_types=1);

/**
 * This file contains the Model class to manage post
 *
 * PHP version 7
 *
 * @author Rameshwar Birajdar <r.birajdar@easternenterprise.com>
 */

namespace Blog\Model;

use Blog\Form\Filter\Post;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

class PostTable
{
    /** @var TableGateway $tableGateway An instance of TableGateway */
    protected $tableGateway;

    /** @var Container $container An instance of Container */
    protected $container;

    /**
     * @param Zend\Db\TableGateway\TableGateway $tableGateway An instance of TableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->container = new Container('User');
    }

    /**
     * Used to fetch all post
     * 
     * @return Zend\Db\ResultSet\ResultSet $resultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Used to fetch post by post id
     * 
     * @param string $id An post id
     * @return array|\ArrayObject|null
     */
    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     * Used to add new Post
     * 
     * @param Post $post An instance of Post
     */
    public function savePost(Post $post)
    {
        $datetime = date('Y-m-d H:i:s');
        $data = [
            'user_id' => $this->container->offsetGet('userId'),
            'description' => $post->description,
            'title'  => $post->title,
            'created_at' => $datetime,
            'updated_at' => $datetime,
        ];
        $this->tableGateway->insert($data);
    }

    /**
     * Used to update post data
     * 
     * @param Post $post An instance of Post
     */
    public function updatePost(Post $post)
    {
        $data = [
            'description' => $post->description,
            'title'  => $post->title,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        if ($this->getPost((int) $post->id)) {
            $this->tableGateway->update($data, ['id' => (int) $post->id]);
        } else {
            throw new \Exception('User id does not exist');
        }
    }

    /**
     * Used to delete post by id
     * 
     * @param int $id An post id
     */
    public function deletePost($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

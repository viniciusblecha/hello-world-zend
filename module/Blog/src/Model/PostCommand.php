<?php

namespace Blog\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class PostCommand implements PostCommandInterface
{
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function insertPost(Post $post)
    {
        $insert = new Insert('posts');
        $insert->values([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);

        $sql = new Sql($this->db);
        $stmt = $sql->prepareStatementForSqlObject($insert);
        $result = $stmt->execute();

        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error ocurred during blog post insert operation'
            );
        }

        $id = $result->getGeneratedValue();

        return new Post (
            $post->getTitle(),
            $post->getText(),
            $id
        );
    }

    public function updatePost(Post $post)
    {

    }
    public function deletePost(Post $post)
    {

    }
}

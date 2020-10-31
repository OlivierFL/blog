<?php

namespace App\Managers;

use App\Core\Manager;
use PDO;

final class CommentManager extends Manager
{
    /**
     * @param null|int $limit
     *
     * @return array
     */
    public function findAllWithAuthor(int $limit = null): array
    {
        $queryLimit = $limit ? ' LIMIT '.$limit : '';

        $query = $this->db->query('SELECT c.id, c.content, c.status, c.user_id, c.post_id, c.created_at, c.updated_at, u.first_name, u.last_name FROM comment c LEFT JOIN user u on c.user_id = u.id ORDER BY c.updated_at DESC'.$queryLimit);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function findOneWithAuthor(int $id): array
    {
        $query = $this->db->prepare('SELECT c.id, c.content, c.status, c.user_id, c.post_id, c.created_at, c.updated_at, u.first_name, u.last_name FROM comment c LEFT JOIN user u on c.user_id = u.id WHERE c.id = :id');

        $query->bindParam(':id', $id);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}

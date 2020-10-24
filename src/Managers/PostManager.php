<?php

namespace App\Managers;

use App\Core\Manager;
use PDO;

final class PostManager extends Manager
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function findOneWithAuthor(int $id): array
    {
        $query = $this->db->prepare('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.created_at, p.updated_at, a.id as author_id, u.first_name, u.last_name FROM post p LEFT JOIN admin a on p.admin_id = a.id LEFT JOIN user u on a.user_id = u.id WHERE p.id = :id');

        $query->bindParam(':id', $id);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * @param string $slug
     *
     * @return array
     */
    public function findOneWithAuthorBySlug(string $slug): array
    {
        $query = $this->db->prepare('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.created_at, p.updated_at, a.id as author_id, u.first_name, u.last_name FROM post p LEFT JOIN admin a on p.admin_id = a.id LEFT JOIN user u on a.user_id = u.id WHERE p.slug = :slug');

        $query->bindParam(':slug', $slug);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * @param null|int $limit
     *
     * @return array
     */
    public function findAllWithAuthor(int $limit = null): array
    {
        $queryLimit = $limit ? ' LIMIT '.$limit : '';

        $query = $this->db->query('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.created_at, p.updated_at, u.first_name, u.last_name FROM post p LEFT JOIN admin a on p.admin_id = a.id LEFT JOIN user u on a.user_id = u.id ORDER BY p.updated_at DESC'.$queryLimit);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

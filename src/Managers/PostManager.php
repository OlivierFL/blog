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
    public function findOneWithAuthorAndCommentsBySlug(string $slug): array
    {
        $query = $this->db->prepare('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.updated_at, c.id as comment_id, c.content as comment_content, c.status as comment_status, c.updated_at as comment_updated_at, u.user_name as comment_author, u2.user_name as author FROM comment c LEFT JOIN post p ON c.post_id = p.id LEFT JOIN user u ON c.user_id = u.id LEFT JOIN admin a ON p.admin_id = a.id LEFT JOIN user u2 ON a.user_id = u2.id WHERE c.status != \'En attente de modération\' AND p.slug = :slug GROUP BY p.id, c.id, c.updated_at ORDER BY c.updated_at');

        $query->bindParam(':slug', $slug);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param null|int $limit
     * @param null|int $offset
     *
     * @return array
     */
    public function findAllWithAuthor(int $limit = null, int $offset = null): array
    {
        $queryLimit = $limit ? ' LIMIT '.$limit : '';

        if (null !== $offset) {
            $queryLimit = ' LIMIT '.$offset.', '.$limit;
        }

        $query = $this->db->query('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.created_at, p.updated_at, u.first_name, u.last_name FROM post p LEFT JOIN admin a on p.admin_id = a.id LEFT JOIN user u on a.user_id = u.id ORDER BY p.updated_at DESC'.$queryLimit);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param null|int $limit
     * @param null|int $offset
     *
     * @return array
     */
    public function findAllWithAuthorPaginated(int $limit = null, int $offset = null): array
    {
        $result['data'] = $this->findAllWithAuthor($limit, $offset);
        $result['total'] = $this->getTotal();

        return $result;
    }

    private function getTotal()
    {
        return $this->db->query('SELECT COUNT(*) FROM post')->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
    }
}

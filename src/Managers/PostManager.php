<?php

namespace App\Managers;

use App\Core\Manager;
use PDO;

final class PostManager extends Manager
{
    public function findOneWithAuthor(array $data): array
    {
        $query = $this->db->prepare('SELECT p.id, p.title, p.content, p.slug, p.cover_img, p.alt_cover_img, p.created_at, p.updated_at, a.id as author_id, u.first_name, u.last_name FROM post p LEFT JOIN admin a on p.admin_id = a.id LEFT JOIN user u on a.user_id = u.id WHERE p.id = :id');

        $query->bindParam(':id', $data['id']);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}

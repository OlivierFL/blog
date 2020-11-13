<?php

namespace App\Managers;

use App\Core\Manager;
use PDO;

final class UserManager extends Manager
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function findUser(int $id): array
    {
        $query = $this->db->prepare('SELECT u.user_name, u.first_name, u.last_name, u.email, u.role, u.created_at, u.updated_at, a.description, a.url_avatar, a.alt_url_avatar, a.url_cv FROM user u LEFT JOIN admin a ON u.admin_id = a.id WHERE u.id = :id');

        $query->bindParam(':id', $id);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }
}

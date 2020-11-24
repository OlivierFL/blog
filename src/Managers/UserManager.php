<?php

namespace App\Managers;

use App\Core\Manager;
use App\Model\Admin;
use App\Model\User;
use PDO;
use ReflectionException;

final class UserManager extends Manager
{
    /**
     * @param int $id
     *
     * @return User
     */
    public function findUser(int $id): User
    {
        $query = $this->db->prepare('SELECT u.id as id, u.user_name, u.first_name, u.last_name, u.email, u.password, u.role, u.created_at, u.updated_at, u.admin_id as admin_id, a.description, a.url_avatar, a.alt_url_avatar, a.url_cv FROM user u LEFT JOIN admin a ON u.admin_id = a.id WHERE u.id = :id');

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC)[0];

        if ('admin' === $result['role']) {
            return new Admin($result);
        }

        return new User($result);
    }

    /**
     * @param User $user
     *
     * @throws ReflectionException
     *
     * @return bool
     */
    public function updateAdmin(User $user): bool
    {
        $columns = implode(' = ?, ', $this->getColumns($user));
        $columns .= ' = ?';

        $query = $this->db->prepare('UPDATE user u LEFT JOIN admin a ON u.admin_id = a.id SET '.$columns.' WHERE u.id = '.$user->getId());

        $query = $this->bindValues($query, $this->getValues($user));

        return $query->execute();
    }
}

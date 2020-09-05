<?php

namespace App\Managers;

use App\Core\Manager;
use PDO;

final class UserManager extends Manager
{
    /**
     * @return array
     */
    public function getLastThreeUsers(): array
    {
        $query = $this->db->query('SELECT * FROM '.$this->tableName.' ORDER BY `created_at` DESC LIMIT 3');

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

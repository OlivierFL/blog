<?php

namespace App\Core;

use PDO;
use ReflectionClass;
use ReflectionException;

abstract class Manager
{
    /** @var PDO */
    protected $db;
    /** @var string */
    protected $tableName;

    /**
     * Manager constructor.
     *
     * @param PDO $db
     *
     * @throws ReflectionException
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->tableName = $this->getTableName();
    }

    /**
     * @param array    $criteria
     * @param null|int $limit
     *
     * @return mixed
     */
    public function findBy(array $criteria, int $limit = null)
    {
        if (1 === \count($criteria)) {
            $query = $this->db->prepare('SELECT * FROM '.$this->tableName.
                ' WHERE :column = :criteria'.
                ($limit ? (' LIMIT '.$limit) : ''));

            $query->execute([
                'column' => key($criteria),
                'criteria' => $criteria,
            ]);

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        $query = $this->db->prepare('SELECT * FROM '.$this->tableName);

        $query->queryString .= ' WHERE ';

        foreach ($criteria as $key => $value) {
            if (array_key_last($criteria) === $key) {
                $query->queryString .= ':key = :value';
            } else {
                $query->queryString .= ':key = :value AND ';
            }
            $query->bindParam('key', $value);
        }

        if ($limit) {
            $query->queryString .= ' LIMIT '.$limit;
        }

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria, 1);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->db->query('SELECT * FROM '.$this->tableName)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data
     */
    public function create(array $data): void
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    /**
     * @throws ReflectionException
     *
     * @return null|string
     */
    private function getTableName(): ?string
    {
        $managerInstance = (new ReflectionClass($this))->getShortName();

        return strtolower(str_replace('Manager', '', $managerInstance));
    }
}

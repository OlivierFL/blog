<?php

namespace App\Core;

use PDO;
use PDOStatement;
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
     * @param array    $orderBy
     * @param null|int $limit
     * @param null|int $offset
     *
     * @return mixed
     */
    public function findBy(
        array $criteria,
        array $orderBy = [],
        int $limit = null,
        int $offset = null
    ) {
        $query = 'SELECT * FROM `'.$this->tableName.'` WHERE ';

        foreach ($criteria as $key => $value) {
            if (array_key_last($criteria) !== $key) {
                $query .= '`'.$key.'` = \''.$value.'\' AND ';
            } else {
                $query .= '`'.$key.'` = \''.$value.'\'';
            }
        }

        $query .= ' ORDER BY';

        if (empty($orderBy)) {
            $query .= ' \'id\' ASC';
        } else {
            foreach ($orderBy as $key => $value) {
                $query .= ' \''.$key.'\' \''.$value.'\'';
            }
        }

        if ($limit) {
            if ($offset) {
                $query .= ' LIMIT '.$offset.', '.$limit;
            } else {
                $query .= ' LIMIT '.$limit;
            }
        }

        $query = $this->db->query($query);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria, [], 1);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->db->query('SELECT * FROM '.$this->tableName)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return false|PDOStatement
     */
    public function create(Entity $entity)
    {
        $columns = $this->getColumns($entity);
        $values = $this->getValues($entity);

        $columns = implode(', ', $columns);
        $values = implode(', ', $values);

        return $this->db->query(sprintf('INSERT INTO '.$this->tableName.' (%s) VALUES (%s)', $columns, $values));
    }

    public function update()
    {
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        $query = $this->db->prepare('DELETE FROM '.$this->tableName.'WHERE id = :id');
        $query->bindParam(':id', $id);

        return $query->execute();
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

    /**
     * @param string $property
     *
     * @return string
     */
    private function camelCaseToSnakeCase(string $property): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return array
     */
    private function getColumns(Entity $entity): array
    {
        $columns = [];
        $properties = $this->getEntityProperties($entity);
        foreach ($properties as $property) {
            $columns[] = $this->camelCaseToSnakeCase($property->name);
        }

        return $columns;
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return array
     */
    private function getValues(Entity $entity): array
    {
        $properties = $this->getEntityProperties($entity);
        $values = [];
        foreach ($properties as $property) {
            $method = 'get'.ucfirst($property->name);
            if (method_exists($entity, $method)) {
                $values[] = '\''.$entity->{$method}().'\'';
            }
        }

        return $values;
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return array
     */
    private function getEntityProperties(Entity $entity): array
    {
        $reflectionClass = new ReflectionClass($entity);

        return $reflectionClass->getProperties();
    }
}

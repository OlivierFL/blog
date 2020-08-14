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
        $query = $this->db->prepare('SELECT * FROM '.$this->tableName.' WHERE ');

        foreach ($criteria as $key => $value) {
            if (array_key_last($criteria) !== $key) {
                $query->queryString .= $key.' = '.$value.' AND ';
            } else {
                $query->queryString .= $key.' = '.$value;
            }
        }

        $query->queryString .= ' ORDER BY';

        if (empty($orderBy)) {
            $query->queryString .= ' id ASC';
        } else {
            foreach ($orderBy as $key => $value) {
                $query->queryString .= ' '.$key.' '.$value;
            }
        }

        if ($limit) {
            if ($offset) {
                $query->queryString .= ' LIMIT '.$offset.', '.$limit;
            }
            $query->queryString .= ' LIMIT '.$limit;
        }

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
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return false|PDOStatement
     */
    public function create(Entity $entity)
    {
        $reflectionClass = new ReflectionClass($entity);

        $columns = $this->getColumns($reflectionClass);
        $values = $this->getValues($reflectionClass, $entity);

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
     * @param ReflectionClass $reflectionClass
     *
     * @return array
     */
    private function getColumns(ReflectionClass $reflectionClass): array
    {
        $columns = [];
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $property = $this->camelCaseToSnakeCase($property->name);
            $columns[] = $property;
        }

        return $columns;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param Entity          $entity
     *
     * @return array
     */
    private function getValues(ReflectionClass $reflectionClass, Entity $entity): array
    {
        $entityGetters = [];
        $values = [];

        $methods = $reflectionClass->getMethods();
        foreach ($methods as $key => $method) {
            if (preg_match('/^[g]/', $method->name)) {
                $entityGetters[] = $method->name;
            }
        }

        foreach ($entityGetters as $key => $getter) {
            if (null !== $entity->{$getter}()) {
                $values[] = '\''.$entity->{$getter}().'\'';
            }
        }

        return $values;
    }
}

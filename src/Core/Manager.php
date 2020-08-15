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
        $params = $this->getQueryParams($criteria);

        $queryOrder = $this->getQueryOrder($orderBy);

        $queryLimit = $this->getLimit($limit, $offset);

        $query = $this->db->prepare('SELECT * FROM `'.$this->tableName.'` WHERE '.$params.$queryOrder.$queryLimit);

        $i = 0;
        foreach ($criteria as $value) {
            ++$i;
            $query->bindValue($i, $value);
        }

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return mixed
     */
    public function findOneBy(array $criteria, array $orderBy)
    {
        return $this->findBy($criteria, $orderBy, 1);
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
        $columns = implode(', ', $this->getColumns($entity));
        $values = implode(', ', $this->getValues($entity));

        return $this->db->query(sprintf('INSERT INTO '.$this->tableName.' (%s) VALUES (%s)', $columns, $values));
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public function delete(Entity $entity): bool
    {
        $query = $this->db->prepare('DELETE FROM '.$this->tableName.'WHERE id = :id');
        $id = $entity->getId();
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
     * @param array $criteria
     *
     * @return string
     */
    private function getQueryParams(array $criteria): string
    {
        $params = [];
        foreach ($criteria as $key => $value) {
            if (array_key_last($criteria) !== $key) {
                $params[] = '`'.$key.'` = ? AND';
            } else {
                $params[] = '`'.$key.'` = ?';
            }
        }

        return implode(' ', $params);
    }

    /**
     * @param array $orderBy
     *
     * @return string
     */
    private function getQueryOrder(array $orderBy): string
    {
        $order = ' ORDER BY';

        if (empty($orderBy)) {
            $order .= ' id ASC';
        } else {
            foreach ($orderBy as $key => $value) {
                $order .= ' '.$key.' '.$value;
            }
        }

        return $order;
    }

    /**
     * @param null|int $limit
     * @param null|int $offset
     *
     * @return string
     */
    private function getLimit(?int $limit, ?int $offset): string
    {
        $queryLimit = ' LIMIT ';
        if ($limit) {
            if ($offset) {
                $queryLimit .= $offset.', '.$limit;
            } else {
                $queryLimit .= $limit;
            }
        }

        return $queryLimit;
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
        $properties = $entity->getProperties();
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
        $properties = $entity->getProperties();
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
     * @param string $property
     *
     * @return string
     */
    private function camelCaseToSnakeCase(string $property): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
    }
}

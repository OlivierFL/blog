<?php

namespace App\Core;

use App\Exceptions\DatabaseException;
use Exception;
use PDO;
use PDOStatement;
use ReflectionClass;
use ReflectionException;

abstract class Manager
{
    /** @var PDO */
    protected PDO $db;
    /** @var null|string */
    protected ?string $tableName;
    /** @var string */
    protected string $entity;

    /**
     * Manager constructor.
     *
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->db = (new PDOFactory())->getMysqlConnexion();
        $this->tableName = $this->getTableName();
        $this->entity = '\App\Model\\'.ucfirst($this->tableName);
    }

    /**
     * @param array    $criteria
     * @param array    $orderBy
     * @param null|int $limit
     * @param null|int $offset
     *
     * @throws DatabaseException
     *
     * @return mixed
     */
    public function findBy(
        array $criteria = [],
        array $orderBy = [],
        int $limit = null,
        int $offset = null
    ) {
        $params = $this->getQueryParams($criteria);

        $queryOrder = $this->getQueryOrder($orderBy);

        if ($limit) {
            $queryLimit = $this->getLimit($offset ? true : false);
        }

        $query = $this->db->prepare('SELECT * FROM `'.$this->tableName.'`'.$params.$queryOrder.$queryLimit);

        (null === $limit) ?: $criteria[] = $limit;
        (null === $offset) ?: $criteria[] = $offset;

        $query = $this->bindValues($query, $criteria);

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return $this->getEntities($results);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function findOneBy(array $criteria, array $orderBy = [])
    {
        return $this->findBy($criteria, $orderBy, 1)[0];
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $results = $this->db->query('SELECT * FROM '.$this->tableName)->fetchAll(PDO::FETCH_ASSOC);

        return $this->getEntities($results);
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @return bool
     */
    public function create(Entity $entity): bool
    {
        $columns = implode(', ', $this->getColumns($entity));
        $values = $this->getValues($entity);

        $valuesPlaceholder = $this->addPlaceholders($values);

        $query = $this->db->prepare('INSERT INTO '.$this->tableName.' ('.$columns.') VALUES ('.$valuesPlaceholder.')');

        $query = $this->bindValues($query, $values);

        return $query->execute();
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @return bool
     */
    public function update(Entity $entity): bool
    {
        $columns = implode(' = ?, ', $this->getColumns($entity));
        $columns .= ' = ?';

        $query = $this->db->prepare('UPDATE `'.$this->tableName.'` SET '.$columns.' WHERE id = '.$entity->getId());

        $query = $this->bindValues($query, $this->getValues($entity));

        return $query->execute();
    }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
    public function delete(Entity $entity): bool
    {
        $query = $this->db->prepare('DELETE FROM '.$this->tableName.' WHERE id = :id');
        $query->bindValue(':id', $entity->getId(), PDO::PARAM_INT);

        return $query->execute();
    }

    /**
     * @param array $criteria
     *
     * @throws Exception
     *
     * @return bool
     */
    public function preventReuse(array $criteria): bool
    {
        return empty($this->findOneBy($criteria));
    }

    /**
     * @param Entity $entity
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function getColumns(Entity $entity): array
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
    protected function getValues(Entity $entity): array
    {
        $properties = $entity->getProperties();
        $values = [];
        foreach ($properties as $property) {
            $method = 'get'.ucfirst($property->name);
            if (method_exists($entity, $method)) {
                $values[] = $entity->{$method}();
            }
        }

        return $values;
    }

    /**
     * @param PDOStatement $query
     * @param array        $values
     *
     * @return PDOStatement
     */
    protected function bindValues(PDOStatement $query, array $values): PDOStatement
    {
        $i = 0;
        foreach ($values as $value) {
            ++$i;
            $query->bindValue($i, $value, \is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        return $query;
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
        if (empty($criteria)) {
            return '';
        }

        $params = [];
        foreach ($criteria as $key => $value) {
            if (array_key_last($criteria) !== $key) {
                $params[] = '`'.$key.'` = ? AND';
            } else {
                $params[] = '`'.$key.'` = ?';
            }
        }

        return ' WHERE '.implode(' ', $params);
    }

    /**
     * @param array $orderBy
     *
     * @throws DatabaseException
     *
     * @return string
     */
    private function getQueryOrder(array $orderBy): string
    {
        $order = ' ORDER BY';

        if (empty($orderBy)) {
            return $order.' id ASC';
        }
        foreach ($orderBy as $key => $value) {
            if (!\in_array(strtoupper($value), ['ASC', 'DESC'], true)) {
                throw DatabaseException::invalidParameter('Order by', 'ASC or DESC', $value);
            }

            $order .= ' '.$key.' '.strtoupper($value);
        }

        return $order;
    }

    /**
     * @param bool $offset
     *
     * @return string
     */
    private function getLimit(bool $offset): string
    {
        $queryLimit = ' LIMIT ';
        if ($offset) {
            $queryLimit .= '?, ?';
        } else {
            $queryLimit .= '?';
        }

        return $queryLimit;
    }

    /**
     * @param array $values
     *
     * @return string
     */
    private function addPlaceholders(array $values): string
    {
        $valuesPlaceholder = [];
        $totalValues = \count($values);
        for ($i = 0; $i < $totalValues; ++$i) {
            $valuesPlaceholder[] = '?';
        }

        return implode(', ', $valuesPlaceholder);
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
     * @param array $results
     *
     * @return array
     */
    private function getEntities(array $results): array
    {
        $entities = [];

        foreach ($results as $result) {
            $entities[] = new $this->entity($result);
        }

        return $entities;
    }
}

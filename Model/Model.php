<?php
namespace BillyMVC\Model;

use BillyMVC\Configure;

abstract class Model {
    protected $table;
    private $_db;
    private $data = [];

    /**
     * Load mathing record into Model for a given id
     * @param  int $id  The id of the record.
     * @return bool     True if match found.
     */
    public function loadById($id)
    {
        $qb = $this->getDb()->createQueryBuilder();
        $qb->select('*')->from($this->table)->where('id = ?')->setParameter(0, $id);
        $result = $qb->execute();

        if ($data = $result->fetch()) {
            $this->setData($data);
            return true;
        }

        return false;
    }

    /**
     * Deletes current model row by 'id' if it is loaded
     * @return bool
     */
    public function delete()
    {
        if ($id = $this->getData('id')) {
            if ($this->getDB()->delete($this->table, array('id' => $id))) {
                $this->data = [];
                return true;
            }
        }

        return false;
    }

    /**
     * Load first match into model for the given data values (used as sql WHERE clause).
     * @param  array  $data The data to match (key is column, value is the value to match)
     * @return bool   True if match found.
     */
    public function loadByData(array $data)
    {
        $qb = $this->getDb()->createQueryBuilder();
        $qb->select('*')->from($this->table);

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $qb->where($key . ' = ' . $qb->createNamedParameter($value));
            } else {
                $qb->andWhere($key . ' = ' . $qb->createNamedParameter($value));
            }
            ++$i;
        }

        $result = $qb->execute();
        if ($data = $result->fetch()) {
            $this->setData($data);
            return true;
        }

        return false;
    }

    public function fetchModelsByData(array $data)
    {
        $qb = $this->getDb()->createQueryBuilder();
        $qb->select('*')->from($this->table);

        $i = 0;
        foreach ($data as $key => $value) {
            if ($i == 0) {
                $qb->where($key . ' = ' . $qb->createNamedParameter($value));
            } else {
                $qb->andWhere($key . ' = ' . $qb->createNamedParameter($value));
            }
            ++$i;
        }

        $model_name = get_class($this);

        $result = $qb->execute();
        $models = [];
        while ($data = $result->fetch()) {
            $model = new $model_name;
            $model->setData($data);
            $models[] = $model;
        }

        return $models;
    }

    public function setData($data, $value = null)
    {
        if ((is_int($data) || is_string($data)) && (is_scalar($value) || is_null($value))) {
            if (!$this->maySetData($data)) {
                throw new \Exception('You may not overwrite value for "' . $data . "'");
            }
            $this->data[$data] = $value;
        } else if (is_array($data)) {
            foreach($data as $key => $value) {
                $this->setData($key, $value);
            }
        } else {
            // ini_set('html_errors', false);
            // var_dump($data, $value); exit;
            throw new \Exception('Attempted to set invalid data type.');
        }

        return $this;
    }

    public function getData($key = null)
    {
        if ($key) {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }

        return $this->data;
    }

    private function maySetData($key)
    {
        if ($key == 'id' && $this->getData('id')) {
            return false;
        }
        return true;
    }

    protected function getDb()
    {
        if (!($this->_db instanceof \Doctrine\DBAL\Connection)) {
            $db = Configure::read('db');

            if (!($db instanceof \Doctrine\DBAL\Connection)) {
                throw new \Exception('"db" must be instance of class \Doctrine\DBAL\Connection');
            }

            $this->_db = $db;
        }

        return $this->_db;
    }

    protected function getTable()
    {
        if (!$this->table) {
            throw new Exception('The "table" property is required.');
        }
        return $this->table;
    }

    protected function add(array $records)
    {
        foreach($records as $record) {
            $qb = $this->getDb()->createQueryBuilder();

            $values = [];

            foreach ($record as $key => $value) {
                $values[$key] = $qb->createNamedParameter($value);
            }

            $qb->insert($this->getTable())->values($values);

            $qb->execute();
        }

        return $this->getDb()->lastInsertId();
    }

    protected function update(array $data, array $where)
    {
        $qb = $this->getDb()->createQueryBuilder();
        $qb->update($this->getTable());

        foreach ($data as $key => $value) {
            $qb->set($key, $qb->createNamedParameter($value));
        }

        foreach ($where as $key => $value) {
            $qb->andWhere($key . ' = ' . $qb->createNamedParameter($value));
        }

        return $qb->execute();
    }

    abstract protected function save();
}

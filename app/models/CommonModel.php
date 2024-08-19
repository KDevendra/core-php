<?php
require_once __DIR__ . '/../core/DB.php';

class CommonModel extends DB {

    // Select data with optional joins
    public function selectFromTable($table, $columns = '*', $conditions = [], $orderBy = '', $limit = '') {
        $this->table($table);
        $this->select($columns);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition['column'], $condition['value']);
            }
        }
        if ($orderBy) {
            $this->orderBy($orderBy);
        }
        if ($limit) {
            $this->limit($limit);
        }
        return $this->get();
    }

    // Insert data into a table
    public function insertIntoTable($table, $data) {
        $this->table($table);
        return $this->insert($data);
    }

    // Update data in a table
    public function updateTable($table, $data, $conditionStr, $params) {
        $this->table($table);
        return parent::updateTable($table, $data, $conditionStr, $params);
    }


    // Delete data from a table
    public function deleteFromTable($table, $conditions) {
        $this->table($table);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition['column'], $condition['value']);
            }
        }
        return $this->delete();
    }

    // Select data with left join
    public function selectWithLeftJoin($table, $columns = '*', $conditions = [], $leftJoins = [], $orderBy = '', $limit = '') {
        $this->table($table);
        $this->select($columns);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition['column'], $condition['value']);
            }
        }
        foreach ($leftJoins as $join) {
            $this->join($join['table'], $join['condition'], 'LEFT');
        }
        if ($orderBy) {
            $this->orderBy($orderBy);
        }
        if ($limit) {
            $this->limit($limit);
        }
        return $this->get();
    }

    // Select data with right join
    public function selectWithRightJoin($table, $columns = '*', $conditions = [], $rightJoins = [], $orderBy = '', $limit = '') {
        $this->table($table);
        $this->select($columns);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition['column'], $condition['value']);
            }
        }
        foreach ($rightJoins as $join) {
            $this->join($join['table'], $join['condition'], 'RIGHT');
        }
        if ($orderBy) {
            $this->orderBy($orderBy);
        }
        if ($limit) {
            $this->limit($limit);
        }
        return $this->get();
    }

    // Select data with inner join
    public function selectWithInnerJoin($table, $columns = '*', $conditions = [], $innerJoins = [], $orderBy = '', $limit = '') {
        $this->table($table);
        $this->select($columns);
        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $this->where($condition['column'], $condition['value']);
            }
        }
        foreach ($innerJoins as $join) {
            $this->join($join['table'], $join['condition'], 'INNER');
        }
        if ($orderBy) {
            $this->orderBy($orderBy);
        }
        if ($limit) {
            $this->limit($limit);
        }
        return $this->get();
    }
}

<?php
class DB {
    protected $conn;
    protected $table;
    protected $select = '*';
    protected $where = [];
    protected $join = [];
    protected $orderBy = '';
    protected $limit = '';
    public function __construct() {
        $this->conn = $this->getConnection();
    }
    // Get Database Connection
    protected function getConnection() {
        $dbConfig = require __DIR__ . '/../../config/database.php';
        $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname'], $dbConfig['port']);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $conn->set_charset($dbConfig['charset']);
        return $conn;
    }
    // Set Table
    public function table($table) {
        $this->table = $table;
        return $this;
    }
    // Set Select Columns
    public function select($columns) {
        $this->select = $columns;
        return $this;
    }
    // Add Where Condition
    public function where($column, $value) {
        $this->where[] = [$column, $value, '='];
        return $this;
    }
    // Add Join Condition
    public function join($table, $condition, $type = 'INNER') {
        $this->join[] = [$table, $condition, $type];
        return $this;
    }
    // Add Order By
    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }
    // Add Limit
    public function limit($limit, $offset = 0) {
        $this->limit = "LIMIT $offset, $limit";
        return $this;
    }
    // Fetch All Results
    public function get() {
        $query = $this->buildSelectQuery();
        $stmt = $this->executeQuery($query, $this->buildParams());
        return $this->fetchAll($stmt);
    }
    // Insert Data
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $this->executeQuery($query, array_values($data), str_repeat('s', count($data)));
        return $this->conn->insert_id;
    }
    // Update Data
    public function updateTable($table, $data, $condition, $params) {
        $set = implode(", ", array_map(function ($col) {
            return "$col = ?";
        }, array_keys($data)));
        $query = "UPDATE $table SET $set WHERE $condition";
        return $this->executeQuery($query, array_merge(array_values($data), $params), str_repeat('s', count($data)) . str_repeat('i', count($params)));
    }
    // Delete Data
    public function delete() {
        $query = "DELETE FROM {$this->table} " . $this->buildWhereClause();
        $this->executeQuery($query, $this->buildParams());
        return $this->conn->affected_rows;
    }
    // Execute Query
    protected function executeQuery($query, $params = [], $types = '') {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }
        if (!empty($params)) {
            $stmt->bind_param($types ? : str_repeat('s', count($params)), ...$params);
        }
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }
        return $stmt;
    }
    // Fetch All Records
    protected function fetchAll($stmt) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Build Select Query
    protected function buildSelectQuery() {
        $query = "SELECT {$this->select} FROM {$this->table}";
        foreach ($this->join as $join) {
            list($table, $condition, $type) = $join;
            $query.= " $type JOIN $table ON $condition";
        }
        $query.= $this->buildWhereClause();
        $query.= " $this->orderBy $this->limit";
        return $query;
    }
    // Build Where Clause
    protected function buildWhereClause() {
        if (empty($this->where)) {
            return '';
        }
        $where = array_map(function ($w) {
            return "{$w[0]} {$w[2]} ?";
        }, $this->where);
        return " WHERE " . implode(" AND ", $where);
    }
    // New method to delegate prepare calls
    public function prepare($query) {
        return $this->conn->prepare($query);
    }
    // Build Parameters Array
    protected function buildParams() {
        return array_map(function ($w) {
            return $w[1];
        }, $this->where);
    }
}

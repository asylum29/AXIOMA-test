<?php

defined('APP_INTERNAL') || die;

class DBConnection {

    private $pdo;
    
    public function __construct($dbname, $username = null, $password = null) {
        $this->pdo = new PDO("mysql:host=localhost:3306;dbname=$dbname", $username, $password);  
    }

    public function get_record($table, array $params = array()) {
        $keys = array_keys($params);
        $args = array_map(function($e) { return "$e = :$e"; }, $keys);
        $sql = "SELECT * FROM $table " . (count($args) > 0 ? 'WHERE ' . implode(' AND ', $args) : '');
        $sth = $this->pdo->prepare($sql);
        $sth->execute($params);
        $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($records) > 1) {
            die("Too many records");
        }
        return !empty($records) ? $records[0] : false;
    }

    public function get_records($table, array $params = array(), $orderby = false) {
        $keys = array_keys($params);
        $args = array_map(function($e) { return "$e = :$e"; }, $keys);
        $sql = "SELECT * FROM $table " . (count($args) > 0 ? 'WHERE ' . implode(' AND ', $args) : '');
        if ($orderby) {
            $sql .= (' ' . $orderby);
        }
        $sth = $this->pdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_record_sql($sql, array $params = array()) {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($params);
        $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($records) > 1) {
            die("Too many records");
        }
        return !empty($records) ? $records[0] : false;
    }

    public function get_records_sql($sql, array $params = array()) {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_record($table, array $params = array()) {
        $keys = array_keys($params);
        $values = array_map(function($e) { return ":$e"; }, $keys);
        $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (" . implode(',', $values) . ")";
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        return $result ? $this->pdo->lastInsertId() : 0;
    }

    public function update_record($table, $id, array $params = array()) {
        $keys = array_map(function($e) { return "$e = ?"; }, array_keys($params));
        $values = array_values($params);
        $values[] = $id;
        $sql = "UPDATE $table SET " . implode(',', $keys) . " WHERE id = ?";
        $sth =  $this->pdo->prepare($sql);
        return $sth->execute($values);
    }

    public function delete_record($table, $id) {
        $sql = "DELETE FROM $table WHERE id = ?";
        $sth = $this->pdo->prepare($sql);
        $sth->execute(array($id));
        return $sth->rowCount() > 0;
    }

    public function count_records($table, $params = array()) {
        $keys = array_keys($params);
        $args = array_map(function($e) { return "$e = :$e"; }, $keys);
        $sql = "SELECT COUNT(*) FROM $table " . (count($args) > 0 ? 'WHERE ' . implode(' AND ', $args) : '');
        $sth = $this->pdo->prepare($sql);
        $sth->execute($params);
        return $sth->fetchColumn();
    }

    public function execute($sql, $params) {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($params);		
    }

    public function quote($string) {
        return $this->pdo->quote($string);
    }

}

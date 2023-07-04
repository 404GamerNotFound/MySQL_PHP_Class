<?php
/**
 * Description of MySQLDatabase
 *
 * @author 404GamerNotFound / Tony
 */
class MySQLDatabase {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Verbindungsfehler: " . $this->connection->connect_error);
        }
    }

    public function select($table, $columns = "*", $condition = "") {
        $query = "SELECT $columns FROM $table";
        if ($condition != "") {
            $query .= " WHERE $condition";
        }
        return $this->executeQuery($query);
    }

    public function insert($table, $values) {
        $columns = implode(", ", array_keys($values));
        $placeholders = implode(", ", array_fill(0, count($values), "?"));
        $types = str_repeat("s", count($values));
        $bindValues = array_values($values);

        $stmt = $this->connection->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->bind_param($types, ...$bindValues);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        return $this->executeQuery($query);
    }

    public function drop($table) {
        $query = "DROP TABLE $table";
        return $this->executeQuery($query);
    }

    public function update($table, $values, $condition) {
        $set = "";
        $bindValues = [];
        foreach ($values as $column => $value) {
            $set .= "$column = ?, ";
            $bindValues[] = $value;
        }
        $set = rtrim($set, ", ");
        $types = str_repeat("s", count($bindValues));
        $bindValues[] = $condition;

        $stmt = $this->connection->prepare("UPDATE $table SET $set WHERE $condition");
        $stmt->bind_param($types, ...$bindValues);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function create($table, $columns) {
        $column_definitions = "";
        foreach ($columns as $column => $type) {
            $column_definitions .= "$column $type, ";
        }
        $column_definitions = rtrim($column_definitions, ", ");
        $query = "CREATE TABLE $table ($column_definitions)";
        return $this->executeQuery($query);
    }

    private function executeQuery($query) {
        $result = $this->connection->query($query);
        if ($result === false) {
            die("Abfragefehler: " . $this->connection->error);
        }
        return $result;
    }

    public function close() {
        $this->connection->close();
    }
}


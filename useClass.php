<?php

/**
 * Description of useClass
 *
 * @author 404GamerNotFound / Tony
 */


$host = "localhost";
$username = "root";
$password = "your_password";
$database = "your_database";

$database = new MySQLDatabase($host, $username, $password, $database);

// SELECT-Befehl ausführen
$result = $database->select("users", "*", "id = ?");
$id = 1;
$stmt = $result->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Verarbeiten Sie die Ergebnisdaten
    echo "Name: " . $row["name"] . "<br>";
}

// INSERT-Befehl ausführen
$values = array(
    "name" => "John Doe",
    "email" => "john@example.com"
);
$result = $database->insert("users", $values);
if ($result === true) {
    echo "Datensatz erfolgreich eingefügt.";
}

// UPDATE-Befehl
$database = new MySQLDatabase($host, $username, $password, $database);

// UPDATE-Befehl ausführen
$table = "users";
$values = array(
    "name" => "John Doe",
    "email" => "john.doe@example.com"
);
$condition = "id = 1";
$result = $database->update($table, $values, $condition);
if ($result === true) {
    echo "Datensatz erfolgreich aktualisiert.";
}

$database->close();

// Weitere Befehle ausführen...

$database->close();

<?php
$host = "localhost";
$db_name = "bucigno5dinf";
$username = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

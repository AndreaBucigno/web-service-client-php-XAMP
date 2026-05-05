<?php
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

// Determiniamo il metodo HTTP
$method = isset($_GET['method']) ? strtoupper($_GET['method']) : $_SERVER['REQUEST_METHOD'];
$method = strtoupper($method);

// Leggiamo il corpo della richiesta (per POST e PUT che inviano JSON)
$input = json_decode(file_get_contents("php://input"), true);

try {
    switch ($method) {
        // --- READ ---
        case 'GET':
            if (isset($_GET['id'])) {
                // Recupera un singolo utente per ID
                $stmt = $pdo->prepare('SELECT * FROM users WHERE ID = :id');
                $stmt->execute(['id' => $_GET['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } elseif (isset($_GET['nome'])) {
                // Ricerca per nome (es: qapi.php?nome=Andrea)
                $stmt = $pdo->prepare('SELECT * FROM users WHERE nome LIKE :nome');
                $stmt->execute(['nome' => "%" . $_GET['nome'] . "%"]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Recupera tutti gli utenti
                $stmt = $pdo->query('SELECT * FROM users');
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            echo json_encode($result ? $result : ["message" => "Nessun utente trovato"]);
            break;

        // --- CREATE ---
        case 'POST':
            if (isset($input['nome'], $input['email'])) {
                $stmt = $pdo->prepare('INSERT INTO users (nome, email) VALUES (:nome, :email)');
                $stmt->execute(['nome' => $input['nome'], 'email' => $input['email']]);
                http_response_code(201); 
                echo json_encode(["message" => "User creato con successo", "id" => $pdo->lastInsertId()]);
            } else {
                http_response_code(400);
                echo json_encode(["error" => "Dati mancanti: nome e email richiesti"]);
            }
            break;

        // --- UPDATE ---
        case 'PUT':
            if (isset($input['id'], $input['nome'], $input['email'])) {
                $stmt = $pdo->prepare('UPDATE users SET nome = :nome, email = :email WHERE ID = :id');
                $stmt->execute([
                    'id' => $input['id'],
                    'nome' => $input['nome'],
                    'email' => $input['email']
                ]);
                echo json_encode(["message" => "User aggiornato con successo"]);
            } else {
                http_response_code(400);

                echo json_encode(["error" => "Dati insufficienti per l'aggiornamento"]);
            }
            break;

        // --- DELETE ---
        case 'DELETE':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare('DELETE FROM users WHERE ID = :id');

                $stmt->execute(['id' => $_GET['id']]);

                echo json_encode(["message" => "User eliminato"]);
            } else {
                http_response_code(400);

                echo json_encode(["error" => "ID mancante"]);
            }
            break;

        default:
            http_response_code(405);

            echo json_encode(["error" => "Metodo $method non supportato"]);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode(["error" => "Errore del server: " . $e->getMessage()]);
}
?>
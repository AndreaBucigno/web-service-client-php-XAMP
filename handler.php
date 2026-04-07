<?php

require_once 'db.php';
//Bucigno gestione CRUD utenti 07-06-2024

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome'], $_POST['email']) && !isset($_POST['id'])) {
        $stmt = $pdo->prepare('INSERT INTO users (nome, email) VALUES (:nome, :email)');
        $stmt->execute([
            'nome' => $_POST['nome'],
            'email' => $_POST['email']
        ]);
    } elseif (isset($_POST['id'], $_POST['nuovo_nome'], $_POST['nuova_email'])) {
        $stmt = $pdo->prepare('UPDATE users SET nome = :nome, email = :email WHERE ID = :id');
        $stmt->execute([
            'id' => $_POST['id'],
            'nome' => $_POST['nuovo_nome'],
            'email' => $_POST['nuova_email']
        ]);
    } elseif (isset($_POST['id_elimina'])) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE ID = :id');
        $stmt->execute([
            'id' => $_POST['id_elimina']
        ]);
    }
    header("Location: index.php");
    exit;
}

// GESTIONE RICHIESTE GET (Ricerca)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // READ (Ricerca specifica): Se ricevo 'cerca_nome'
    if (isset($_GET['cerca_nome'])) {
        $nome_cercato = urlencode($_GET['cerca_nome']);
        header("Location: index.php?cerca_nome=" . $nome_cercato);
        exit;
    }
}

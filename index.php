<?php
require_once 'db.php';
$variabile = "";
if (isset($_GET['cerca_nome']) && !empty($_GET['cerca_nome'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE nome = :nome');
    $stmt->execute(['nome' => $_GET['cerca_nome']]);
} else {
    $stmt = $pdo->query('SELECT * FROM users');
}
$utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Costruiamo le righe della tabella
if ($utenti) {
    foreach ($utenti as $utente) {
        $variabile .= "<tr>";
        $variabile .= "<td>" . htmlspecialchars($utente['ID']) . "</td>";
        $variabile .= "<td>" . htmlspecialchars($utente['nome']) . "</td>";
        $variabile .= "<td>" . htmlspecialchars($utente['email']) . "</td>";
        $variabile .= "</tr>";
    }
} else {
    $variabile = "<tr><td colspan='3' class='text-center text-muted'>Nessun utente trovato</td></tr>";
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utenti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>

    <div class="container mt-4">
        <h2 class="mb-4 fw-bold">Dashboard Utenti (Client PHP)</h2>

        <div class="card mb-4 bg-white">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-4">Aggiungi Utente (POST)</h4>
                <form action="handler.php" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nome" placeholder="Nome" required>
                        </div>
                        <div class="col-md-4">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary px-4">Inserisci</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4 bg-white">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-4">Cerca Utenti (GET)</h4>

                <form class="mb-4" method="GET" action="index.php">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="cerca_nome" placeholder="Cerca per Nome esatto" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary px-3">Cerca</button>
                        </div>
                        <div class="col-md-auto">
                            <a href="index.php" class="btn btn-secondary px-3">Vedi Tutti</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 20%;">ID</th>
                                <th scope="col" style="width: 40%;">Nome</th>
                                <th scope="col" style="width: 40%;">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $variabile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4 bg-white">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-4">Modifica Utente (PUT)</h4>
                <form action="handler.php" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="id" placeholder="ID Utente" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nuovo_nome" placeholder="Nuovo Nome" required>
                        </div>
                        <div class="col-md-4">
                            <input type="email" class="form-control" name="nuova_email" placeholder="Nuova Email" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary px-4">Aggiorna</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4 bg-white">
            <div class="card-body">
                <h4 class="card-title fw-bold mb-4">Elimina Utente (DELETE)</h4>
                <form action="handler.php" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="id_elimina" placeholder="ID Utente da eliminare" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-danger px-4">Elimina</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
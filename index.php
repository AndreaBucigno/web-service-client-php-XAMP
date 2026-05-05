<?php
// URL del tuo Web Service
$base_url = "http://localhost/web-service-client-php-XAMP/api.php";

// 1. GESTIONE DELLE AZIONI
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'ignore_errors' => true
        ]
    ];

    if ($action === 'create') {
        $data = ['nome' => $_POST['nome'], 'email' => $_POST['email']];
        $options['http']['method'] = 'POST';
        $options['http']['content'] = json_encode($data);
        file_get_contents($base_url, false, stream_context_create($options));
    } 
    elseif ($action === 'update') {
        $data = ['id' => $_POST['id'], 'nome' => $_POST['nome'], 'email' => $_POST['email']];
        $options['http']['method'] = 'PUT';
        $options['http']['content'] = json_encode($data);
        file_get_contents($base_url, false, stream_context_create($options));
    } 
    elseif ($action === 'delete') {
        $id = $_POST['id_elimina'];
        $options['http']['method'] = 'DELETE';
        // Per il delete passi l'ID nell'URL come previsto dal tuo switch case 'DELETE'
        file_get_contents($base_url . "?id=" . $id, false, stream_context_create($options));
    }

    // Reindirizza per evitare il reinvio del form al refresh
    header("Location: index.php");
    exit;
}

// 2. LETTURA DATI - Chiamata GET al Web Service
$search = $_GET['cerca_nome'] ?? '';
$url = $base_url;
if (!empty($search)) {
    $url .= "?nome=" . urlencode($search);
}

$response = file_get_contents($url);
$utenti = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>PHP Client for Web Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Client PHP - Consumo Web Service JSON</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5>Gestione Utente</h5>
                <form method="POST">
                    <input type="hidden" name="action" value="create" id="formAction">
                    <div class="mb-2">
                        <label class="small">ID (solo per modifica)</label>
                        <input type="number" name="id" id="inputId" class="form-control form-control-sm">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="nome" id="inputNome" class="form-control" placeholder="Nome" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="btnSubmit">Crea Utente</button>
                    <button type="button" class="btn btn-link btn-sm w-100 mt-2" onclick="switchMode('create')">Reset</button>
                </form>
            </div>

            <div class="card shadow-sm p-3 border-danger">
                <h5>Elimina</h5>
                <form method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="number" name="id_elimina" class="form-control mb-2" placeholder="ID da eliminare" required>
                    <button type="submit" class="btn btn-danger btn-sm w-100">Elimina Definitivamente</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="cerca_nome" class="form-control" placeholder="Cerca..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-secondary">Cerca</button>
                    </div>
                </form>

                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Nome</th><th>Email</th><th>Azione</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($utenti) && is_array($utenti)): ?>
                            <?php foreach ($utenti as $u): ?>
                            <tr>
                                <td><?= $u['ID'] ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="prepareEdit(<?= $u['ID'] ?>, '<?= addslashes($u['nome']) ?>', '<?= addslashes($u['email']) ?>')">Carica</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Nessun dato o errore API</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Solo un po' di JS per aiutare la compilazione dei campi del form
    function prepareEdit(id, nome, email) {
        document.getElementById('formAction').value = 'update';
        document.getElementById('inputId').value = id;
        document.getElementById('inputNome').value = nome;
        document.getElementById('inputEmail').value = email;
        document.getElementById('btnSubmit').innerText = "Aggiorna Utente";
        document.getElementById('btnSubmit').className = "btn btn-warning w-100";
    }

    function switchMode(mode) {
        document.getElementById('formAction').value = mode;
        document.getElementById('inputId').value = '';
        document.getElementById('inputNome').value = '';
        document.getElementById('inputEmail').value = '';
        document.getElementById('btnSubmit').innerText = "Crea Utente";
        document.getElementById('btnSubmit').className = "btn btn-primary w-100";
    }
</script>
</body>
</html>
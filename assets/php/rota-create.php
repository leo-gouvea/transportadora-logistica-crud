<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Data no passado

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Rota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">🗺️ Cadastrar Rota
                    <a href="rotas.php" class="btn btn-outline-secondary btn-sm float-end">← Voltar</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="acoes.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Origem</label>
                            <input type="text" name="origem" class="form-control" placeholder="Ex: Santo André - SP"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Destino</label>
                            <input type="text" name="destino" class="form-control" placeholder="Ex: Jundiaí - SP"
                                required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Distância (km)</label>
                            <input type="number" step="0.1" name="distancia_km" class="form-control"
                                placeholder="Ex: 87.5">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Descrição / Observações</label>
                            <textarea name="descricao" class="form-control" rows="3"
                                placeholder="Ex: Rota pela Rodovia Anhanguera, pedágio em Louveira..."></textarea>
                        </div>
                    </div>
                    <button type="submit" name="create_rota" class="btn btn-dark">💾 Salvar</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('pageshow', function (event) {
            // Se o evento persistido for true, a página veio do cache do navegador
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Força o recarregamento da página para bater no PHP e ser redirecionado
                window.location.reload();
            }
        });
    </script>
</body>

</html>
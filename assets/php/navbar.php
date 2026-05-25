<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-md">
        <a class="navbar-brand fw-bold" href="index.php">🚚 Transportadora - Grupo 4</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">🏠 INÍCIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="motoristas.php">👤 MOTORISTAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="veiculos.php">🚛 VEÍCULOS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="entregas.php">📦 ENTREGAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rotas.php">🗺️ ROTAS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ocorrencias.php">⚠️ OCORRÊNCIAS</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-warning">👋 <?= isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : '' ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">🔒 SAIR</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

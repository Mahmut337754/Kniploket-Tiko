<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kniploket Tiko – Beheerpaneel</title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar .nav-link { color: rgba(255,255,255,.75); }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); border-radius: .375rem; }
        .card-stat { border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>

<?php if (!empty($_SESSION['gebruiker_id'])): ?>
<!-- Navigatiebalk (alleen ingelogd) -->
<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="/dashboard">
        <i class="bi bi-scissors me-2"></i>Kniploket Tiko
    </a>
    <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars($_SESSION['gebruiker_naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            <span class="badge bg-secondary ms-1">
                <?= htmlspecialchars($_SESSION['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </span>
        </span>
        <a href="/logout" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Uitloggen
        </a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Zijbalk -->
        <nav class="col-md-2 d-none d-md-block sidebar py-3">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : '' ?>"
                       href="/dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/klanten') !== false) ? 'active' : '' ?>"
                       href="/klanten">
                        <i class="bi bi-people me-2"></i>Klanten
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="#" title="Nog niet beschikbaar">
                        <i class="bi bi-box-seam me-2"></i>Producten
                        <span class="badge bg-secondary ms-1 small">binnenkort</span>
                    </a>
                </li>
                <hr class="border-secondary">
                <li class="nav-item">
                    <a class="nav-link" href="/wachtwoord-wijzigen">
                        <i class="bi bi-key me-2"></i>Wachtwoord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="/logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Uitloggen
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Hoofdinhoud -->
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
<?php else: ?>
<div class="container">
    <main class="py-4">
<?php endif; ?>

            <!-- Flash-berichten -->
            <?php if (!empty($flash)): ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <?= $flash['bericht'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
                </div>
            <?php endif; ?>

            <!-- View-inhoud -->
            <?= $inhoud ?>

<?php if (!empty($_SESSION['gebruiker_id'])): ?>
        </main>
    </div><!-- /row -->
</div><!-- /container-fluid -->
<?php else: ?>
    </main>
</div><!-- /container -->
<?php endif; ?>

<!-- Bootstrap 5 JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmKi69h56ECk3jM28efF/xWv1os0X"
    crossorigin="anonymous">
</script>

<!-- Globale client-side validatie -->
<script src="/js/validatie.js"></script>

</body>
</html>

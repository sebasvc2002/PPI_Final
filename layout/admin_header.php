<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand font-playfair fs-3" href="index.php">Admin - Las Delicias Hôrneadas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse align-items-end" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link"><i class="bi bi-speedometer2 fs-5 text-white"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php"><i class="bi bi-receipt fs-5 text-white"></i> Menú</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link"><i class="bi bi-tags fs-5 text-white"></i> Categorías</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="../php/logout.php" class="nav-link"><i class="bi bi-door-closed fs-5 text-white"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>
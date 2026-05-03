<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Redirecciones
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
// Walk up to the project root (PPI_Final)
while (basename($base) === 'products' || basename($base) === 'categories' || basename($base) === 'suppliers' || basename($base) === 'admin') {
    $base = dirname($base);
}
$base = rtrim($base, '/');

//Dashboard stats
if (isset($mysqli)) {
    $product_count    = (int)($mysqli->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c']    ?? 0);
    $total_categories = (int)($mysqli->query("SELECT COUNT(*) AS c FROM categories")->fetch_assoc()['c'] ?? 0);
    $total_suppliers  = (int)($mysqli->query("SELECT COUNT(*) AS c FROM suppliers")->fetch_assoc()['c']  ?? 0);
    $total_orders     = (int)($mysqli->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c']     ?? 0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?> – Las Delicias Horneadas</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= $base ?>/css/admin.css">
</head>
<body class="admin-body">

<nav class="navbar navbar-expand-lg admin-navbar sticky-top py-2">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand text-white font-playfair fs-5" href="<?= $base ?>/admin/index.php">
            <i class="bi bi-shop me-2"></i>Las Delicias Horneadas
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#adminNav"
                aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-white"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a href="<?= $base ?>/admin/index.php" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>/admin/products/index.php" class="nav-link">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>/admin/categories/index.php" class="nav-link">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>/admin/suppliers/index.php" class="nav-link">
                        <i class="bi bi-truck"></i> Proveedores
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="<?= $base ?>/index.php" class="nav-link">
                        <i class="bi bi-house-door"></i> Tienda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>/php/logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN -->
<main class="container-fluid px-3 px-md-4 py-3">
    <div class="container p-2">
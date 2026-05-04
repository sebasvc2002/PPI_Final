<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../index.php");
    exit();
}
$title = 'Dashboard';
require_once '../php/db.php';
require '../layout/admin_header.php';
?>

<div class="admin-page-title admin-fade-in">
    <h2 class="font-playfair"><i class="bi bi-speedometer2 me-2 text-muted"></i>Dashboard</h2>
    <p class="text-muted mb-0 mt-1">Resumen general</p>
</div>

<!-- Stats -->
<div class="row g-3 g-md-4 mb-4 mb-md-5 admin-fade-in mt-1">
    <div class="col-6 col-xl-3">
        <div class="card admin-stat-card h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Productos</h6>
                    <h3 class="mb-0"><?= $product_count ?></h3>
                </div>
                <div class="stat-icon bg-accent-soft">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-stat-card h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Categorías</h6>
                    <h3 class="mb-0"><?= $total_categories ?></h3>
                </div>
                <div class="stat-icon bg-success-soft">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-stat-card h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Proveedores</h6>
                    <h3 class="mb-0"><?= $total_suppliers ?></h3>
                </div>
                <div class="stat-icon bg-info-soft">
                    <i class="bi bi-truck"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card admin-stat-card h-100 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Pedidos</h6>
                    <h3 class="mb-0"><?= $total_orders ?></h3>
                </div>
                <div class="stat-icon bg-danger-soft">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paneles -->
<div class="row g-3 g-md-4 admin-fade-in">
    <div class="col-md-4">
        <div class="card admin-card h-100">
            <div class="card-body text-center py-4">
                <div class="stat-icon bg-accent-soft mx-auto mb-3">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5 class="font-playfair mb-2">Productos</h5>
                <p class="text-muted small mb-3">Administrar todo el catálogo de productos</p>
                <a href="products/index.php" class="btn btn-admin-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ir a Productos
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-card h-100">
            <div class="card-body text-center py-4">
                <div class="stat-icon bg-success-soft mx-auto mb-3">
                    <i class="bi bi-tags"></i>
                </div>
                <h5 class="font-playfair mb-2">Categorías</h5>
                <p class="text-muted small mb-3">Crear y editar las categorías de la tienda</p>
                <a href="categories/index.php" class="btn btn-admin-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ir a Categorías
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-card h-100">
            <div class="card-body text-center py-4">
                <div class="stat-icon bg-info-soft mx-auto mb-3">
                    <i class="bi bi-truck"></i>
                </div>
                <h5 class="font-playfair mb-2">Proveedores</h5>
                <p class="text-muted small mb-3">Gestionar los proveedores registrados</p>
                <a href="suppliers/index.php" class="btn btn-admin-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>Ir a Proveedores
                </a>
            </div>
        </div>
    </div>
</div>

<?php require '../layout/admin_footer.php'; ?>
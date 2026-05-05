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

// Load all orders with user and address info
$orders_result = $mysqli->query("
    SELECT o.id, o.total, o.placed_at,
           u.name AS user_name, u.email AS user_email,
           a.street, a.city, a.country, a.postal_code
    FROM orders o
    JOIN users u ON u.id = o.user_id
    LEFT JOIN user_addresses a ON a.id = o.address_id
    ORDER BY o.placed_at DESC
");
$all_orders = $orders_result ? $orders_result->fetch_all(MYSQLI_ASSOC) : [];

// Load order details for each order
$order_details = [];
if (!empty($all_orders)) {
    $order_ids = array_column($all_orders, 'id');
    $ids_str = implode(',', array_map('intval', $order_ids));
    $details_result = $mysqli->query("
        SELECT od.order_id, od.quantity, od.price, p.name AS product_name, p.image
        FROM order_details od
        JOIN products p ON p.id = od.product_id
        WHERE od.order_id IN ($ids_str)
        ORDER BY od.id ASC
    ");
    if ($details_result) {
        while ($row = $details_result->fetch_assoc()) {
            $order_details[$row['order_id']][] = $row;
        }
    }
}

// Revenue total
$total_revenue = array_sum(array_column($all_orders, 'total'));
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

<!-- Orders Table -->
<div class="row g-3 g-md-4 admin-fade-in mt-2">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="font-playfair mb-0"><i class="bi bi-receipt me-2"></i>Pedidos</h5>
                <span class="badge rounded-pill" style="background: var(--admin-accent); font-size: .8rem;">
                    <?= count($all_orders) ?> pedido<?= count($all_orders) !== 1 ? 's' : '' ?>
                </span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($all_orders)): ?>
                <div class="empty-state">
                    <i class="bi bi-bag-x d-block"></i>
                    <p>Aún no hay pedidos registrados.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;"></th>
                                <th># Pedido</th>
                                <th>Cliente</th>
                                <th class="d-none d-md-table-cell">Fecha</th>
                                <th class="d-none d-lg-table-cell">Dirección</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_orders as $ord):
                                $details = $order_details[$ord['id']] ?? [];
                                $item_count = array_sum(array_column($details, 'quantity'));
                            ?>
                            <tr>
                                <td>
                                    <button class="btn btn-action btn-edit"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#orderDetail<?= $ord['id'] ?>"
                                            aria-expanded="false"
                                            title="Ver detalles">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>
                                <td>
                                    <span class="fw-semibold">#<?= str_pad($ord['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                    <br><small class="text-muted"><?= $item_count ?> producto<?= $item_count !== 1 ? 's' : '' ?></small>
                                </td>
                                <td>
                                    <span class="fw-medium"><?= htmlspecialchars($ord['user_name']) ?></span>
                                    <br><small class="text-muted"><?= htmlspecialchars($ord['user_email']) ?></small>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <?= date('d/m/Y', strtotime($ord['placed_at'])) ?>
                                    <br><small class="text-muted"><?= date('H:i', strtotime($ord['placed_at'])) ?> hrs</small>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <?php if ($ord['street']): ?>
                                    <small><?= htmlspecialchars($ord['street']) ?>, <?= htmlspecialchars($ord['city']) ?></small>
                                    <?php else: ?>
                                    <small class="text-muted">—</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold" style="color: var(--admin-primary);">$<?= number_format($ord['total'], 2) ?></span>
                                </td>
                            </tr>
                            <!-- Collapsible detail row -->
                            <tr class="collapse-row">
                                <td colspan="6" class="p-0 border-0">
                                    <div class="collapse" id="orderDetail<?= $ord['id'] ?>">
                                        <div style="background: #faf7f3; padding: 1.25rem 1.5rem; border-bottom: 2px solid rgba(226,167,111,.2);">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 fw-semibold" style="color: var(--admin-primary);">
                                                    <i class="bi bi-bag me-1"></i>Detalle del pedido #<?= str_pad($ord['id'], 5, '0', STR_PAD_LEFT) ?>
                                                </h6>
                                                <?php if ($ord['street']): ?>
                                                <small class="text-muted d-none d-md-inline">
                                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($ord['street']) ?>, <?= htmlspecialchars($ord['city']) ?>, <?= htmlspecialchars($ord['country']) ?> C.P. <?= htmlspecialchars($ord['postal_code']) ?>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($details)): ?>
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0" style="background: #fff; border-radius: 10px; overflow: hidden;">
                                                    <thead>
                                                        <tr style="background: rgba(92,58,33,.06);">
                                                            <th style="font-size:.8rem; padding: .6rem .75rem;">Producto</th>
                                                            <th style="font-size:.8rem; padding: .6rem .75rem;" class="text-center">Cant.</th>
                                                            <th style="font-size:.8rem; padding: .6rem .75rem;" class="text-end">Precio Unit.</th>
                                                            <th style="font-size:.8rem; padding: .6rem .75rem;" class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($details as $d):
                                                            $thumb = 'data:image/jpeg;base64,' . base64_encode($d['image']);
                                                        ?>
                                                        <tr>
                                                            <td style="padding: .5rem .75rem;">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <img src="<?= $thumb ?>" class="product-thumb" alt="" style="width:36px; height:36px; border-radius:6px; object-fit:cover;">
                                                                    <span class="fw-medium" style="font-size:.9rem;"><?= htmlspecialchars($d['product_name']) ?></span>
                                                                </div>
                                                            </td>
                                                            <td class="text-center" style="padding: .5rem .75rem;"><?= $d['quantity'] ?></td>
                                                            <td class="text-end" style="padding: .5rem .75rem;">$<?= number_format($d['price'], 2) ?></td>
                                                            <td class="text-end fw-semibold" style="padding: .5rem .75rem;">$<?= number_format($d['price'] * $d['quantity'], 2) ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background: rgba(92,58,33,.04);">
                                                            <td colspan="3" class="text-end fw-bold" style="padding: .6rem .75rem; color: var(--admin-primary);">Total</td>
                                                            <td class="text-end fw-bold" style="padding: .6rem .75rem; color: var(--admin-primary);">$<?= number_format($ord['total'], 2) ?></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <?php else: ?>
                                            <p class="text-muted mb-0 small">Sin detalles disponibles.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Revenue summary -->
                <div class="d-flex justify-content-end p-3" style="background: rgba(92,58,33,.03); border-top: 1px solid rgba(0,0,0,.06);">
                    <div class="text-end">
                        <small class="text-muted text-uppercase" style="letter-spacing: 1px; font-size: .7rem;">Ingresos totales</small>
                        <h4 class="mb-0 font-playfair" style="color: var(--admin-primary);">$<?= number_format($total_revenue, 2) ?></h4>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .collapse-row td { padding: 0 !important; }
    .collapse-row:hover { background: transparent !important; }
    [data-bs-toggle="collapse"] i {
        transition: transform .25s ease;
    }
    [data-bs-toggle="collapse"][aria-expanded="true"] i {
        transform: rotate(180deg);
    }
</style>

<?php require '../layout/admin_footer.php'; ?>
<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../../index.php");
    exit();
}
$title = 'Productos';
require_once '../../php/db.php';
require '../../layout/admin_header.php';

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $msg = 'Producto eliminado correctamente.';
        $msg_type = 'success';
    } else {
        $msg = 'Error al eliminar el producto. Puede que tenga pedidos vinculados.';
        $msg_type = 'danger';
    }
    $stmt->close();
}

// Fetch products
$query = "SELECT p.id, p.name, p.description, p.price, p.stock, p.image,
                 c.name AS category_name, s.name AS supplier_name
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.id
          LEFT JOIN suppliers  s ON p.supplier_id = s.id
          ORDER BY p.id DESC";
$result = $mysqli->query($query);
$products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center admin-page-title admin-fade-in">
    <div>
        <h2 class="font-playfair"><i class="bi bi-box-seam me-2 text-muted"></i>Productos</h2>
        <p class="text-muted mb-0 mt-1"><?= count($products) ?> producto(s) registrado(s)</p>
    </div>
    <a href="create.php" class="btn btn-admin-accent mt-2 mt-sm-0">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Producto
    </a>
</div>

<!-- Alert -->
<?php if (!empty($msg)): ?>
<div class="alert admin-alert alert-<?= $msg_type ?> alert-dismissible fade show admin-fade-in" role="alert">
    <i class="bi bi-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-1"></i>
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card admin-card admin-fade-in mt-3">
    <div class="card-body p-0">
        <?php if (count($products) === 0): ?>
        <div class="empty-state">
            <i class="bi bi-box-seam d-block"></i>
            <p class="mb-0">No hay productos registrados aún.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover admin-table mb-0" id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th class="d-none d-lg-table-cell">Categoría</th>
                        <th class="d-none d-lg-table-cell">Proveedor</th>
                        <th>Precio</th>
                        <th class="d-none d-md-table-cell">Stock</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td><?= $prod['id'] ?></td>
                        <td>
                            <?php if (!empty($prod['image'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($prod['image']) ?>"
                                 class="product-thumb" alt="<?= htmlspecialchars($prod['name']) ?>">
                            <?php else: ?>
                            <div class="product-thumb d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="bi bi-image"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= htmlspecialchars($prod['name']) ?></td>
                        <td class="d-none d-lg-table-cell text-muted"><?= htmlspecialchars($prod['category_name'] ?? '—') ?></td>
                        <td class="d-none d-lg-table-cell text-muted"><?= htmlspecialchars($prod['supplier_name'] ?? '—') ?></td>
                        <td>$<?= number_format($prod['price'], 2) ?></td>
                        <td class="d-none d-md-table-cell">
                            <?php if ($prod['stock'] <= 5): ?>
                            <span class="badge bg-danger"><?= $prod['stock'] ?></span>
                            <?php else: ?>
                            <span class="badge bg-success"><?= $prod['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="edit.php?id=<?= $prod['id'] ?>" class="btn btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="index.php?delete=<?= $prod['id'] ?>" class="btn btn-action btn-delete ms-1"
                               title="Eliminar"
                               onclick="return confirm('¿Eliminar el producto «<?= htmlspecialchars(addslashes($prod['name'])) ?>»?')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require '../../layout/admin_footer.php'; ?>
<?php
$title = 'Proveedores';
require_once '../../php/db.php';
require '../../layout/admin_header.php';

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Check for linked products
    $check = $mysqli->prepare("SELECT COUNT(*) AS c FROM products WHERE supplier_id = ?");
    $check->bind_param('i', $id);
    $check->execute();
    $linked = $check->get_result()->fetch_assoc()['c'];
    $check->close();

    if ($linked > 0) {
        $msg = "No se puede eliminar: hay $linked producto(s) vinculado(s) a este proveedor.";
        $msg_type = 'danger';
    } else {
        $stmt = $mysqli->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $msg = 'Proveedor eliminado correctamente.';
            $msg_type = 'success';
        } else {
            $msg = 'Error al eliminar el proveedor.';
            $msg_type = 'danger';
        }
        $stmt->close();
    }
}

// Suppliers
$result = $mysqli->query("SELECT * FROM suppliers ORDER BY id DESC");
$suppliers = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center admin-page-title admin-fade-in">
    <div>
        <h2 class="font-playfair"><i class="bi bi-truck me-2 text-muted"></i>Proveedores</h2>
        <p class="text-muted mb-0 mt-1">Listado completo de proveedores</p>
    </div>
    <a href="create.php" class="btn btn-admin-accent mt-2 mt-sm-0">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Proveedor
    </a>
</div>

<?php if (!empty($msg)): ?>
<div class="alert admin-alert alert-<?= $msg_type ?> alert-dismissible fade show admin-fade-in" role="alert">
    <i class="bi bi-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-1"></i>
    <?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card admin-card admin-fade-in mt-3">
    <div class="card-body p-0">
        <?php if (count($suppliers) === 0): ?>
        <div class="empty-state">
            <i class="bi bi-truck d-block"></i>
            <p class="mb-0">No hay proveedores registrados aún.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover admin-table mb-0" id="suppliersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empresa</th>
                        <th class="d-none d-md-table-cell">Contacto</th>
                        <th class="d-none d-sm-table-cell">Teléfono</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($suppliers as $sup): ?>
                    <tr>
                        <td><?= $sup['id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($sup['name']) ?></td>
                        <td class="d-none d-md-table-cell text-muted"><?= htmlspecialchars($sup['contact_name'] ?? '—') ?></td>
                        <td class="d-none d-sm-table-cell"><?= htmlspecialchars($sup['phone'] ?? '—') ?></td>
                        <td class="text-end">
                            <a href="edit.php?id=<?= $sup['id'] ?>" class="btn btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="index.php?delete=<?= $sup['id'] ?>" class="btn btn-action btn-delete ms-1"
                               title="Eliminar"
                               onclick="return confirm('¿Eliminar el proveedor «<?= htmlspecialchars(addslashes($sup['name'])) ?>»?')">
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

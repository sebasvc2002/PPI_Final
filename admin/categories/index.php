<?php
$title = 'Categorías';
require_once '../../php/db.php';
require '../../layout/admin_header.php';

/* ── Handle DELETE ─────────────────────────────────────── */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Check for linked products before deleting
    $check = $mysqli->prepare("SELECT COUNT(*) AS c FROM products WHERE category_id = ?");
    $check->bind_param('i', $id);
    $check->execute();
    $linked = $check->get_result()->fetch_assoc()['c'];
    $check->close();

    if ($linked > 0) {
        $msg = "No se puede eliminar: hay $linked producto(s) vinculado(s) a esta categoría.";
        $msg_type = 'danger';
    } else {
        $stmt = $mysqli->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $msg = 'Categoría eliminada correctamente.';
            $msg_type = 'success';
        } else {
            $msg = 'Error al eliminar la categoría.';
            $msg_type = 'danger';
        }
        $stmt->close();
    }
}

/* ── Fetch all categories ──────────────────────────────── */
$result = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!-- Page Title -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center admin-page-title admin-fade-in">
    <div>
        <h2 class="font-playfair"><i class="bi bi-tags me-2 text-muted"></i>Categorías</h2>
        <p class="text-muted mb-0 mt-1">Listado completo de categorías</p>
    </div>
    <a href="create.php" class="btn btn-admin-accent mt-2 mt-sm-0">
        <i class="bi bi-plus-lg me-1"></i>Nueva Categoría
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

<!-- Table Card -->
<div class="card admin-card admin-fade-in mt-3">
    <div class="card-body p-0">
        <?php if (count($categories) === 0): ?>
        <div class="empty-state">
            <i class="bi bi-tags d-block"></i>
            <p class="mb-0">No hay categorías registradas aún.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover admin-table mb-0" id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="d-none d-md-table-cell">Descripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                        <td class="d-none d-md-table-cell text-muted"><?= htmlspecialchars($cat['description'] ?? '—') ?></td>
                        <td class="text-end">
                            <a href="edit.php?id=<?= $cat['id'] ?>" class="btn btn-action btn-edit" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="index.php?delete=<?= $cat['id'] ?>" class="btn btn-action btn-delete ms-1"
                               title="Eliminar"
                               onclick="return confirm('¿Eliminar la categoría «<?= htmlspecialchars(addslashes($cat['name'])) ?>»?')">
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

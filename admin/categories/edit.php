<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../../index.php");
    exit();
}
$title = 'Editar Categoría';
require_once '../../php/db.php';

$errors = [];
$id = (int)($_GET['id'] ?? 0);

/* ── Fetch existing category ───────────────────────────── */
$stmt = $mysqli->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$cat = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($cat) {
    $name        = $cat['name'];
    $description = $cat['description'] ?? '';

    /* ── Handle UPDATE ─────────────────────────────────────── */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') $errors[] = 'El nombre es obligatorio.';

        if (empty($errors)) {
            $stmt = $mysqli->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->bind_param('ssi', $name, $description, $id);
            if ($stmt->execute()) {
                header('Location: index.php?msg=updated');
                exit;
            } else {
                $errors[] = 'Error al actualizar: ' . $mysqli->error;
            }
            $stmt->close();
        }
    }
}

require '../../layout/admin_header.php';

if (!$cat) {
    echo '<div class="alert admin-alert alert-danger mt-4">Categoría no encontrada.</div>';
    require '../../layout/admin_footer.php';
    exit;
}
?>

<!-- Page Title -->
<div class="admin-page-title admin-fade-in">
    <h2 class="font-playfair"><i class="bi bi-pencil-square me-2 text-muted"></i>Editar Categoría</h2>
    <p class="text-muted mb-0 mt-1">Modificar «<?= htmlspecialchars($cat['name']) ?>»</p>
</div>

<!-- Errors -->
<?php if (!empty($errors)): ?>
<div class="alert admin-alert alert-danger admin-fade-in">
    <ul class="mb-0">
        <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- Form -->
<div class="card admin-card admin-fade-in mt-3">
    <div class="card-body p-3 p-md-4">
        <form method="POST" class="admin-form">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre de la categoría</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= htmlspecialchars($name) ?>" required autofocus>
            </div>
            <div class="mb-4">
                <label for="description" class="form-label">Descripción <small class="text-muted">(opcional)</small></label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-1"></i>Actualizar
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../layout/admin_footer.php'; ?>

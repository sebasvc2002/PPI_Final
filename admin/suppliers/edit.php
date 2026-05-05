<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../../index.php");
    exit();
}
$title = 'Editar Proveedor';
require_once '../../php/db.php';

$errors = [];
$id = (int)($_GET['id'] ?? 0);

// Fetch supplier
$stmt = $mysqli->prepare("SELECT * FROM suppliers WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$sup = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($sup) {
    $name         = $sup['name'];
    $contact_name = $sup['contact_name'] ?? '';
    $phone        = $sup['phone'] ?? '';

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');

    if ($name === '') $errors[] = 'El nombre de la empresa es obligatorio.';

    if (empty($errors)) {
        $stmt = $mysqli->prepare("UPDATE suppliers SET name = ?, contact_name = ?, phone = ? WHERE id = ?");
        $stmt->bind_param('sssi', $name, $contact_name, $phone, $id);
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

if (!$sup) {
    echo '<div class="alert admin-alert alert-danger mt-4">Proveedor no encontrado.</div>';
    require '../../layout/admin_footer.php';
    exit;
}
?>

<div class="admin-page-title admin-fade-in">
    <h2 class="font-playfair"><i class="bi bi-pencil-square me-2 text-muted"></i>Editar Proveedor</h2>
    <p class="text-muted mb-0 mt-1">Modificar «<?= htmlspecialchars($sup['name']) ?>»</p>
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
                <label for="name" class="form-label">Nombre de la empresa</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= htmlspecialchars($name) ?>" required autofocus>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="contact_name" class="form-label">Persona de contacto <small class="text-muted">(opcional)</small></label>
                    <input type="text" class="form-control" id="contact_name" name="contact_name"
                           value="<?= htmlspecialchars($contact_name) ?>">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Teléfono <small class="text-muted">(opcional)</small></label>
                    <input type="text" class="form-control" id="phone" name="phone"
                           value="<?= htmlspecialchars($phone) ?>">
                </div>
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

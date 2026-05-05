<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../../index.php");
    exit();
}
$title = 'Nueva Categoría';
require_once '../../php/db.php';

$errors = [];
$name = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = 'El nombre es obligatorio.';

    if (empty($errors)) {
        $stmt = $mysqli->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $description);
        if ($stmt->execute()) {
            header('Location: index.php?msg=created');
            exit;
        } else {
            $errors[] = 'Error al guardar: ' . $mysqli->error;
        }
        $stmt->close();
    }
}

require '../../layout/admin_header.php';
?>

<!-- Page Title -->
<div class="admin-page-title admin-fade-in">
    <h2 class="font-playfair"><i class="bi bi-plus-circle me-2 text-muted"></i>Nueva Categoría</h2>
    <p class="text-muted mb-0 mt-1">Completa los datos para crear una categoría</p>
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
                    <i class="bi bi-check-lg me-1"></i>Guardar
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../layout/admin_footer.php'; ?>

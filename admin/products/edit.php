<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../../index.php");
    exit();
}elseif($_SESSION['user_id']!=1){
    header("Location: ../../index.php");
    exit();
}
$title = 'Editar Producto';
require_once '../../php/db.php';
require '../../layout/admin_header.php';

$errors = [];
$id = (int)($_GET['id'] ?? 0);

// Fetch existing 
$stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$prod = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prod) {
    echo '<div class="alert admin-alert alert-danger mt-4">Producto no encontrado.</div>';
    require '../../layout/admin_footer.php';
    exit;
}

// Dropdowns
$categories = $mysqli->query("SELECT id, name FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$suppliers  = $mysqli->query("SELECT id, name FROM suppliers  ORDER BY name")->fetch_all(MYSQLI_ASSOC);

$name        = $prod['name'];
$description = $prod['description'];
$price       = $prod['price'];
$stock       = $prod['stock'];
$category_id = $prod['category_id'];
$supplier_id = $prod['supplier_id'];

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = $_POST['price'] ?? '';
    $stock       = $_POST['stock'] ?? '';
    $category_id = (int)($_POST['category_id'] ?? 0);
    $supplier_id = (int)($_POST['supplier_id'] ?? 0);

    if ($name === '')        $errors[] = 'El nombre es obligatorio.';
    if ($price === '' || $price < 0) $errors[] = 'Precio inválido.';
    if ($stock === '' || $stock < 0) $errors[] = 'Stock inválido.';
    if ($category_id === 0)  $errors[] = 'Selecciona una categoría.';
    if ($supplier_id === 0)  $errors[] = 'Selecciona un proveedor.';

    // New image
    $has_new_image = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;
    $image_data = null;
    if ($has_new_image) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors[] = 'Formato de imagen no válido.';
            $has_new_image = false;
        } else {
            $image_data = file_get_contents($_FILES['image']['tmp_name']);
        }
    }

    if (empty($errors)) {
        if ($has_new_image) {
            $stmt = $mysqli->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, supplier_id=?, image=? WHERE id=?");
            $null = null;
            $stmt->bind_param('ssdiiibi', $name, $description, $price, $stock, $category_id, $supplier_id, $null, $id);
            $stmt->send_long_data(6, $image_data);
        } else {
            $stmt = $mysqli->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, supplier_id=? WHERE id=?");
            $stmt->bind_param('ssdiiii', $name, $description, $price, $stock, $category_id, $supplier_id, $id);
        }

        if ($stmt->execute()) {
            header('Location: index.php?msg=updated');
            exit;
        } else {
            $errors[] = 'Error al actualizar: ' . $mysqli->error;
        }
        $stmt->close();
    }
}
?>

<div class="admin-page-title admin-fade-in">
    <h2 class="font-playfair"><i class="bi bi-pencil-square me-2 text-muted"></i>Editar Producto</h2>
    <p class="text-muted mb-0 mt-1">Modificar «<?= htmlspecialchars($prod['name']) ?>»</p>
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
        <form method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="row g-3">

                <div class="col-12">
                    <label for="name" class="form-label">Nombre del producto</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= htmlspecialchars($name) ?>" required autofocus>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>
                </div>

                <div class="col-sm-6">
                    <label for="price" class="form-label">Precio ($)</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0"
                           value="<?= htmlspecialchars($price) ?>" required>
                </div>
                <div class="col-sm-6">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" min="0"
                           value="<?= htmlspecialchars($stock) ?>" required>
                </div>

                <div class="col-sm-6">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">— Seleccionar —</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $category_id == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="supplier_id" class="form-label">Proveedor</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">— Seleccionar —</option>
                        <?php foreach ($suppliers as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $supplier_id == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <?php if (!empty($prod['image'])): ?>
                    <label class="form-label d-block">Imagen actual</label>
                    <img src="data:image/jpeg;base64,<?= base64_encode($prod['image']) ?>"
                         class="rounded mb-2" style="max-height:140px;" alt="Imagen actual">
                    <?php endif; ?>
                    <label for="image" class="form-label d-block mt-2">Cambiar imagen <small class="text-muted">(dejar vacío para conservar la actual)</small></label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-admin-primary">
                    <i class="bi bi-check-lg me-1"></i>Actualizar
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../layout/admin_footer.php'; ?>

<?php
session_start();
$title = "Producto - Las Delicias Horneadas";
require_once 'php/db.php';
require_once 'layout/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    echo "<div class='container py-5 text-center'><h2>Producto no encontrado.</h2><a href='menu.php' class='btn btn-accent mt-3'>Regresar a Menú</a></div>";
    require_once 'layout/footer.php';
    echo "</body></html>";
    exit;
}

$stmt = $mysqli->prepare("SELECT p.id, p.name, p.price, p.description, p.stock, p.image, c.name AS category FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div class='container py-5 text-center'><h2>Producto no encontrado.</h2><a href='menu.php' class='btn btn-accent mt-3'>Regresar a Menú</a></div>";
    require_once 'layout/footer.php';
    echo "</body></html>";
    exit;
}

$imageData = base64_encode($product['image']);
$src = 'data:image/jpeg;base64,' . $imageData;
$in_stock = $product['stock'] > 0;
?>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="<?php echo $src ?>" class="img-fluid w-100" style="object-fit: cover; min-height: 400px; max-height: 600px;" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>
        <div class="col-md-6 ps-md-5">
            <span class="badge bg-accent text-white mb-3 px-3 py-2 rounded-pill" style="font-size: 0.75rem; letter-spacing: 1px;"><?= htmlspecialchars($product['category']) ?></span>
            <h1 class="font-playfair"><?= htmlspecialchars($product['name']) ?></h1>
            <h2 class="text-accent fw-bold mb-4">$<?= number_format($product['price'], 2) ?></h2>
            <p class="lead text-muted mb-4" style="line-height: 1.8;">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>

            <?php if ($in_stock): ?>
            <p class="text-success small mb-3"><i class="bi bi-check-circle me-1"></i>En stock (<?= $product['stock'] ?> disponibles)</p>

            <form action="php/cart_actions.php" method="POST" class="d-flex align-items-center gap-3">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="number" name="quantity" class="form-control rounded-pill text-center" style="width: 80px;" min="1" max="<?= $product['stock'] ?>" value="1">
                <?php if (isset($_SESSION['user_id'])): ?>
                <button type="submit" class="btn btn-accent btn-lg ps-4 pe-4 rounded-pill shadow-sm">
                    <i class="bi bi-cart-plus me-2"></i>Agregar a Carrito
                </button>
                <?php else: ?>
                <a href="login.php" class="btn btn-accent btn-lg ps-4 pe-4 rounded-pill shadow-sm text-decoration-none">
                    <i class="bi bi-person me-2"></i>Inicia sesión para comprar
                </a>
                <?php endif; ?>
            </form>
            <?php else: ?>
            <p class="text-danger small mb-3"><i class="bi bi-x-circle me-1"></i>Agotado</p>
            <button class="btn btn-secondary btn-lg rounded-pill" disabled>
                <i class="bi bi-cart-x me-2"></i>No disponible
            </button>
            <?php endif; ?>
        </div>

    </div>

</div>

<?php
require_once 'layout/footer.php';
?>
</body>
</html>

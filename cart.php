<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = 'Debes iniciar sesión para ver tu carrito.';
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}

$title = "Carrito - Las Delicias Horneadas";
require_once 'php/db.php';
include 'layout/header.php';

$user_id = (int) $_SESSION['user_id'];

// Get cart items
$cart_items = [];
$subtotal = 0;

$stmt = $mysqli->prepare("SELECT c.id AS cart_id FROM carts c WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

if ($cart) {
    $stmt = $mysqli->prepare("
        SELECT ci.id AS item_id, ci.quantity, p.id AS product_id, p.name, p.description, p.price, p.image, p.stock
        FROM cart_items ci
        JOIN products p ON p.id = ci.product_id
        WHERE ci.cart_id = ?
        ORDER BY ci.id ASC
    ");
    $stmt->bind_param("i", $cart['cart_id']);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
}

// Flash messages
$cart_msg = $_SESSION['cart_msg'] ?? '';
$cart_msg_type = $_SESSION['cart_msg_type'] ?? 'info';
unset($_SESSION['cart_msg'], $_SESSION['cart_msg_type']);
?>

<style>
    /* Cart Items */
    .cart-item-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        transition: box-shadow 0.3s ease;
    }
    .cart-item-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .cart-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }
    .qty-selector {
        background-color: var(--bg-color);
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid #e9ecef;
    }
    .qty-btn {
        background: none;
        border: none;
        color: var(--primary-color);
        font-size: 1.2rem;
        padding: 0;
        cursor: pointer;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .qty-btn:hover {
        background: var(--accent-color);
        color: #fff;
    }
    .item-price {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    /* Order Summary */
    .summary-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 2rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        position: sticky;
        top: 100px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        color: #888;
        font-size: 0.95rem;
    }
    .summary-row.total {
        color: var(--primary-color);
        font-size: 1.75rem;
        margin-top: 1rem;
    }
    .fs-7 { font-size: 0.85rem; }

    .empty-cart-icon {
        font-size: 5rem;
        opacity: 0.15;
        color: var(--primary-color);
    }
    .btn-remove {
        color: #aaa;
        transition: color 0.2s;
    }
    .btn-remove:hover {
        color: #dc3545;
    }
</style>

<main class="container my-5 main-content">
    <div class="mb-5">
        <h1 class="display-4 mb-2 font-playfair">Carrito</h1>
    </div>

    <?php if ($cart_msg): ?>
    <div class="alert alert-<?= $cart_msg_type ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($cart_msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
    <!-- Empty cart -->
    <div class="text-center py-5">
        <i class="bi bi-cart-x empty-cart-icon d-block mb-3"></i>
        <h3 class="font-playfair mb-3">Tu carrito está vacío</h3>
        <p class="text-muted mb-4">Explora nuestro menú y agrega tus productos favoritos.</p>
        <a href="menu.php" class="btn btn-accent btn-lg rounded-pill px-5 shadow-sm">
            <i class="bi bi-shop me-2"></i>Ver Menú
        </a>
    </div>
    <?php else: ?>

    <div class="row gx-xl-5">
        <div class="col-lg-7 mb-5 mb-lg-0">

            <?php foreach ($cart_items as $item):
                $img_src = 'data:image/jpeg;base64,' . base64_encode($item['image']);
                $item_total = $item['price'] * $item['quantity'];
            ?>
            <div class="cart-item-card mb-4 d-flex flex-column flex-sm-row gap-4 position-relative">
                <!-- Remove button -->
                <a href="php/cart_actions.php?action=remove&item_id=<?= $item['item_id'] ?>"
                   class="btn btn-sm position-absolute top-0 end-0 m-3 btn-remove"
                   onclick="return confirm('¿Eliminar este producto del carrito?')">
                    <i class="bi bi-trash3-fill"></i>
                </a>

                <img src="<?= $img_src ?>" class="cart-img" alt="<?= htmlspecialchars($item['name']) ?>">

                <div class="d-flex flex-column justify-content-between w-100">
                    <div>
                        <h4 class="font-playfair mb-1 mt-2 mt-sm-0"><?= htmlspecialchars($item['name']) ?></h4>
                        <p class="text-muted fs-7 mb-1">$<?= number_format($item['price'], 2) ?> c/u</p>
                        <?php if ($item['stock'] <= 5 && $item['stock'] > 0): ?>
                        <span class="badge bg-warning text-dark" style="font-size:0.65rem;">Solo quedan <?= $item['stock'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-3">
                        <form action="php/cart_actions.php" method="POST" class="d-inline">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                            <div class="qty-selector shadow-sm">
                                <button type="submit" name="quantity" value="<?= max(1, $item['quantity'] - 1) ?>" class="qty-btn">&minus;</button>
                                <span class="fw-medium"><?= $item['quantity'] ?></span>
                                <button type="submit" name="quantity" value="<?= min($item['stock'], $item['quantity'] + 1) ?>" class="qty-btn">&plus;</button>
                            </div>
                        </form>
                        <div class="text-end">
                            <span class="d-block text-muted fs-7 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Total</span>
                            <span class="item-price">$<?= number_format($item_total, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="mt-4">
                <a href="menu.php" class="text-decoration-none slide-link pb-1" style="color: var(--primary-color);">
                    <i class="bi bi-arrow-left me-2"></i> Regresar al menú
                </a>
            </div>

        </div>

        <div class="col-lg-5 col-xl-4 ms-auto">
            <div class="summary-card mb-4">
                <h3 class="font-playfair mb-4">Resumen de orden</h3>

                <div class="summary-row">
                    <span>Subtotal (<?= count($cart_items) ?> producto<?= count($cart_items) > 1 ? 's' : '' ?>)</span>
                    <span class="fw-medium" style="color: var(--text-color);">$<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="summary-row">
                    <span>Envío</span>
                    <span class="fw-medium text-success">Gratis</span>
                </div>

                <hr class="my-3 opacity-25">

                <div class="summary-row total align-items-end mb-4">
                    <span class="text-uppercase text-muted mb-1 font-playfair" style="font-size: 0.8rem; letter-spacing: 1px;">Total</span>
                    <span class="font-playfair">$<?= number_format($subtotal, 2) ?></span>
                </div>

                <a href="checkout_page.php" class="btn btn-accent w-100 py-3 mb-3 d-flex justify-content-center align-items-center gap-2 text-decoration-none">
                    Continuar compra <i class="bi bi-arrow-right"></i>
                </a>

                <p class="text-center text-muted small mb-0 mt-2">
                    <i class="bi bi-shield-lock me-1"></i> Pago seguro garantizado
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>

<?php require_once 'layout/footer.php'; ?>
</body>
</html>
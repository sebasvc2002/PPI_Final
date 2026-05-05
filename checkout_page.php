<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = "Checkout - Las Delicias Horneadas";
require_once 'php/db.php';
include 'layout/header.php';

$user_id = (int) $_SESSION['user_id'];

// Get user info
$stmt = $mysqli->prepare("SELECT name, email, card_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get addresses
$stmt = $mysqli->prepare("SELECT id, street, city, country, postal_code FROM user_addresses WHERE user_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get cart items
$cart_items = [];
$subtotal = 0;

$stmt = $mysqli->prepare("SELECT id AS cart_id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

if ($cart) {
    $stmt = $mysqli->prepare("
        SELECT ci.id AS item_id, ci.quantity, p.id AS product_id, p.name, p.price, p.image
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

// If cart is empty, redirect
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Mask card number
$card_display = '';
if (!empty($user['card_number']) && $user['card_number'] > 0) {
    $card_str = (string) $user['card_number'];
    $card_display = '•••• •••• •••• ' . substr($card_str, -4);
} else {
    $card_display = 'No registrada';
}

$checkout_error = $_SESSION['checkout_error'] ?? '';
unset($_SESSION['checkout_error']);
?>

<style>
    .checkout-step {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
        border: none;
    }
    .checkout-step .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-color);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .address-option {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .address-option:hover {
        border-color: var(--accent-color);
        background: #fffaf5;
    }
    .address-option.selected,
    .address-option input:checked ~ .address-label {
        border-color: var(--accent-color);
        background: #fffaf5;
    }
    input[type="radio"]:checked + label .address-option {
        border-color: var(--accent-color);
        background: #fffaf5;
        box-shadow: 0 0 0 1px var(--accent-color);
    }
    .checkout-summary {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        position: sticky;
        top: 100px;
    }
    .checkout-item {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .checkout-item:last-child {
        border-bottom: none;
    }
    .checkout-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .payment-info {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem 1.25rem;
    }
    .summary-divider {
        border: none;
        border-top: 1px dashed #e0e0e0;
        margin: 1rem 0;
    }
</style>

<main class="container my-5 main-content">
    <div class="mb-5">
        <h1 class="display-4 mb-1 font-playfair">Checkout</h1>
        <p class="text-muted">Revisa tu pedido y selecciona una dirección de envío</p>
    </div>

    <?php if ($checkout_error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($checkout_error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="php/checkout.php" method="POST" id="checkoutForm">
    <div class="row gx-xl-5">

        <!-- Left column: Steps -->
        <div class="col-lg-7 mb-5 mb-lg-0">

            <!-- STEP 1: Shipping Address -->
            <div class="checkout-step">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="step-number">1</span>
                    <h3 class="font-playfair mb-0">Dirección de envío</h3>
                </div>

                <?php if (empty($addresses)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-geo-alt fs-1 text-muted opacity-50 d-block mb-2"></i>
                    <p class="text-muted mb-3">No tienes direcciones registradas.</p>
                    <a href="account.php?tab=addresses" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i>Agregar Dirección
                    </a>
                </div>
                <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($addresses as $i => $addr): ?>
                    <div>
                        <input type="radio" name="address_id" value="<?= $addr['id'] ?>"
                               id="addr_<?= $addr['id'] ?>" class="d-none address-radio"
                               <?= $i === 0 ? 'checked' : '' ?> required>
                        <label for="addr_<?= $addr['id'] ?>" class="d-block" style="cursor:pointer;">
                            <div class="address-option <?= $i === 0 ? 'selected' : '' ?>">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-geo-alt-fill text-accent mt-1"></i>
                                    <div>
                                        <p class="mb-1 fw-semibold"><?= htmlspecialchars($addr['street']) ?></p>
                                        <p class="mb-0 text-muted small">
                                            <?= htmlspecialchars($addr['city']) ?>, <?= htmlspecialchars($addr['country']) ?> — C.P. <?= htmlspecialchars($addr['postal_code']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Payment method -->
            <div class="checkout-step">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="step-number">2</span>
                    <h3 class="font-playfair mb-0">Método de pago</h3>
                </div>

                <div class="payment-info">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-credit-card-fill fs-4 text-accent"></i>
                        <div>
                            <p class="mb-0 fw-semibold">Tarjeta registrada</p>
                            <p class="mb-0 text-muted small"><?= htmlspecialchars($card_display) ?></p>
                        </div>
                        <?php if (empty($user['card_number']) || $user['card_number'] == 0): ?>
                        <a href="account.php" class="btn btn-sm btn-outline-dark rounded-pill ms-auto">Agregar</a>
                        <?php else: ?>
                        <span class="ms-auto badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                            <i class="bi bi-check-circle me-1"></i>Registrada
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Review order -->
            <div class="checkout-step">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="step-number">3</span>
                    <h3 class="font-playfair mb-0">Revisar productos</h3>
                </div>

                <?php foreach ($cart_items as $item):
                    $img_src = 'data:image/jpeg;base64,' . base64_encode($item['image']);
                ?>
                <div class="checkout-item">
                    <img src="<?= $img_src ?>" class="checkout-item-img" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold"><?= htmlspecialchars($item['name']) ?></p>
                        <p class="mb-0 text-muted small">Cant: <?= $item['quantity'] ?> × $<?= number_format($item['price'], 2) ?></p>
                    </div>
                    <span class="fw-semibold" style="color: var(--primary-color);">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                </div>
                <?php endforeach; ?>

                <div class="mt-3">
                    <a href="cart.php" class="text-decoration-none small slide-link" style="color: var(--primary-color);">
                        <i class="bi bi-pencil me-1"></i>Modificar carrito
                    </a>
                </div>
            </div>
        </div>

        <!-- Right column: Summary -->
        <div class="col-lg-5 col-xl-4 ms-auto">
            <div class="checkout-summary">
                <h3 class="font-playfair mb-4">Resumen del pedido</h3>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-medium">$<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Envío</span>
                    <span class="fw-medium text-success">Gratis</span>
                </div>

                <hr class="summary-divider">

                <div class="d-flex justify-content-between align-items-end mb-4">
                    <span class="text-uppercase text-muted font-playfair" style="font-size: 0.8rem; letter-spacing: 1px;">Total</span>
                    <span class="font-playfair fs-3 fw-bold" style="color: var(--primary-color);">$<?= number_format($subtotal, 2) ?></span>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-3 mb-3 d-flex justify-content-center align-items-center gap-2"
                        <?= empty($addresses) ? 'disabled' : '' ?>>
                    <i class="bi bi-bag-check"></i> Confirmar Pedido
                </button>

                <?php if (empty($addresses)): ?>
                <p class="text-center text-danger small mb-0">
                    <i class="bi bi-exclamation-circle me-1"></i>Agrega una dirección para continuar.
                </p>
                <?php else: ?>
                <p class="text-center text-muted small mb-0">
                    <i class="bi bi-shield-lock me-1"></i> Tu información está protegida.
                </p>
                <?php endif; ?>
            </div>
        </div>

    </div>
    </form>
</main>

<script>
// Highlight selected address
document.querySelectorAll('.address-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.address-option').forEach(opt => opt.classList.remove('selected'));
        radio.closest('div').querySelector('.address-option').classList.add('selected');
    });
});
</script>

<?php require_once 'layout/footer.php'; ?>
</body>
</html>

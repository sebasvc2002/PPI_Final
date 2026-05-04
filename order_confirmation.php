<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = "Pedido Confirmado - Las Delicias Horneadas";
require_once 'php/db.php';
include 'layout/header.php';

$user_id = (int) $_SESSION['user_id'];
$order_id = (int) ($_GET['id'] ?? $_SESSION['order_success'] ?? 0);
unset($_SESSION['order_success']);

if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch order — verify it belongs to this user
$stmt = $mysqli->prepare("
    SELECT o.id, o.total, o.placed_at,
           a.street, a.city, a.country, a.postal_code
    FROM orders o
    JOIN user_addresses a ON a.id = o.address_id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Fetch order details
$stmt = $mysqli->prepare("
    SELECT od.quantity, od.price, p.name, p.image
    FROM order_details od
    JOIN products p ON p.id = od.product_id
    WHERE od.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<style>
    .confirmation-card {
        background: #fff;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        max-width: 700px;
        margin: 0 auto;
    }
    .success-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: scaleIn 0.5s ease;
    }
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    .order-detail-item {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .order-detail-item:last-child {
        border-bottom: none;
    }
    .order-detail-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }
    .order-number {
        background: linear-gradient(135deg, var(--accent-color), #d19256);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<main class="container my-5 main-content py-4">

    <div class="confirmation-card">
        <div class="text-center mb-4">
            <div class="success-icon">
                <i class="bi bi-check-lg text-white" style="font-size: 2.5rem;"></i>
            </div>
            <h1 class="font-playfair mb-2">¡Pedido Confirmado!</h1>
            <p class="text-muted mb-0">Gracias por tu compra. Tu pedido ha sido registrado exitosamente.</p>
        </div>

        <div class="text-center mb-4">
            <span class="text-muted small text-uppercase" style="letter-spacing: 2px;">Número de pedido</span>
            <h2 class="font-playfair order-number mb-0">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h2>
            <p class="text-muted small mt-1">
                <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y H:i', strtotime($order['placed_at'])) ?>
            </p>
        </div>

        <hr class="opacity-15 my-4">

        <!-- Shipping address -->
        <div class="mb-4">
            <h5 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-2 text-accent"></i>Dirección de envío</h5>
            <p class="mb-0 ms-4">
                <?= htmlspecialchars($order['street']) ?><br>
                <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['country']) ?> — C.P. <?= htmlspecialchars($order['postal_code']) ?>
            </p>
        </div>

        <hr class="opacity-15 my-4">

        <!-- Items -->
        <div class="mb-4">
            <h5 class="fw-semibold mb-3"><i class="bi bi-bag me-2 text-accent"></i>Productos</h5>
            <?php foreach ($details as $d):
                $img_src = 'data:image/jpeg;base64,' . base64_encode($d['image']);
            ?>
            <div class="order-detail-item">
                <img src="<?= $img_src ?>" class="order-detail-img" alt="<?= htmlspecialchars($d['name']) ?>">
                <div class="flex-grow-1">
                    <p class="mb-0 fw-medium"><?= htmlspecialchars($d['name']) ?></p>
                    <p class="mb-0 text-muted small">Cant: <?= $d['quantity'] ?> × $<?= number_format($d['price'], 2) ?></p>
                </div>
                <span class="fw-semibold">$<?= number_format($d['price'] * $d['quantity'], 2) ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <hr class="opacity-15 my-4">

        <!-- Total -->
        <div class="d-flex justify-content-between align-items-end mb-4">
            <span class="text-uppercase text-muted" style="letter-spacing: 1px; font-size: 0.85rem;">Total Pagado</span>
            <span class="font-playfair fs-3 fw-bold" style="color: var(--primary-color);">$<?= number_format($order['total'], 2) ?></span>
        </div>

        <!-- Actions -->
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
            <a href="account.php?tab=orders" class="btn btn-outline-dark rounded-pill px-4 py-2">
                <i class="bi bi-clock-history me-2"></i>Ver Mis Pedidos
            </a>
            <a href="menu.php" class="btn btn-accent rounded-pill px-4 py-2">
                <i class="bi bi-shop me-2"></i>Seguir Comprando
            </a>
        </div>
    </div>

</main>

<?php require_once 'layout/footer.php'; ?>
</body>
</html>

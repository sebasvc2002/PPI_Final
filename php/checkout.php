<?php
session_start();
require_once 'db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$address_id = (int) ($_POST['address_id'] ?? 0);

if ($address_id <= 0) {
    $_SESSION['checkout_error'] = 'Por favor selecciona una dirección de envío.';
    header("Location: ../checkout_page.php");
    exit();
}

// Verify address belongs to user
$stmt = $mysqli->prepare("SELECT id FROM user_addresses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $address_id, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $_SESSION['checkout_error'] = 'Dirección no válida.';
    header("Location: ../checkout_page.php");
    exit();
}

// Get the user's cart
$stmt = $mysqli->prepare("SELECT id FROM carts WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

if (!$cart) {
    $_SESSION['checkout_error'] = 'Tu carrito está vacío.';
    header("Location: ../cart.php");
    exit();
}

$cart_id = $cart['id'];

// Fetch cart items with product info
$stmt = $mysqli->prepare("SELECT ci.id, ci.product_id, ci.quantity, p.name, p.price, p.stock FROM cart_items ci JOIN products p ON p.id = ci.product_id WHERE ci.cart_id = ?");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($items)) {
    $_SESSION['checkout_error'] = 'Tu carrito está vacío.';
    header("Location: ../cart.php");
    exit();
}

// Begin transaction
$mysqli->begin_transaction();

try {
    // Calculate total and verify stock
    $total = 0;
    foreach ($items as $item) {
        if ($item['quantity'] > $item['stock']) {
            throw new Exception("No hay suficiente stock para '{$item['name']}'. Stock disponible: {$item['stock']}.");
        }
        $total += $item['price'] * $item['quantity'];
    }

    // Create order
    $stmt = $mysqli->prepare("INSERT INTO orders (user_id, address_id, total, placed_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iii", $user_id, $address_id, $total);
    $stmt->execute();
    $order_id = $mysqli->insert_id;

    // Insert order details and update stock
    foreach ($items as $item) {
        $stmt = $mysqli->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt->execute();

        // Reduce stock
        $stmt = $mysqli->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt->execute();
    }

    // Clear cart items
    $stmt = $mysqli->prepare("DELETE FROM cart_items WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    $mysqli->commit();

    $_SESSION['order_success'] = $order_id;
    header("Location: ../order_confirmation.php?id=" . $order_id);
    exit();

} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['checkout_error'] = $e->getMessage();
    header("Location: ../checkout_page.php");
    exit();
}
?>

<?php
session_start();
require_once 'db.php';

// All cart operations require a logged-in user
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_error'] = 'Debes iniciar sesión para usar el carrito.';
    $_SESSION['active_form'] = 'login';
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Get or create the user's cart
function getOrCreateCart($mysqli, $user_id) {
    $stmt = $mysqli->prepare("SELECT id FROM carts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['id'];
    }

    // Create a new cart
    $stmt = $mysqli->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $mysqli->insert_id;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    //Add to cart
    case 'add':
        $product_id = (int) ($_POST['product_id'] ?? 0);
        $quantity   = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($product_id <= 0) {
            header("Location: ../menu.php");
            exit();
        }

        // Verify product exists and has stock
        $stmt = $mysqli->prepare("SELECT id, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) {
            $_SESSION['cart_msg'] = 'Producto no encontrado.';
            $_SESSION['cart_msg_type'] = 'danger';
            header("Location: ../menu.php");
            exit();
        }

        $cart_id = getOrCreateCart($mysqli, $user_id);

        // Check if product already in cart
        $stmt = $mysqli->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            $new_qty = $existing['quantity'] + $quantity;
            // Don't exceed stock
            if ($new_qty > $product['stock']) {
                $new_qty = $product['stock'];
            }
            $stmt = $mysqli->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_qty, $existing['id']);
            $stmt->execute();
        } else {
            if ($quantity > $product['stock']) {
                $quantity = $product['stock'];
            }
            $stmt = $mysqli->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            $stmt->execute();
        }

        $_SESSION['cart_msg'] = 'Producto agregado al carrito.';
        $_SESSION['cart_msg_type'] = 'success';
        header("Location: ../cart.php");
        exit();

    // Update quantity
    case 'update':
        $item_id  = (int) ($_POST['item_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        $cart_id = getOrCreateCart($mysqli, $user_id);

        // Verify item belongs to this user's cart and check stock
        $stmt = $mysqli->prepare("SELECT ci.id, p.stock FROM cart_items ci JOIN carts c ON c.id = ci.cart_id JOIN products p ON p.id = ci.product_id WHERE ci.id = ? AND c.user_id = ?");
        $stmt->bind_param("ii", $item_id, $user_id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        if ($item) {
            if ($quantity > $item['stock']) {
                $quantity = $item['stock'];
            }
            $stmt = $mysqli->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $item_id);
            $stmt->execute();
        }

        header("Location: ../cart.php");
        exit();

    // Remove item
    case 'remove':
        $item_id = (int) ($_GET['item_id'] ?? $_POST['item_id'] ?? 0);

        $cart_id = getOrCreateCart($mysqli, $user_id);

        // Verify item belongs to this user's cart
        $stmt = $mysqli->prepare("DELETE ci FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE ci.id = ? AND c.user_id = ?");
        $stmt->bind_param("ii", $item_id, $user_id);
        $stmt->execute();

        $_SESSION['cart_msg'] = 'Producto eliminado del carrito.';
        $_SESSION['cart_msg_type'] = 'info';
        header("Location: ../cart.php");
        exit();

    default:
        header("Location: ../cart.php");
        exit();
}
?>

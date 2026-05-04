<?php
 //Returns the total number of items in the user's cart.
function getCartItemCount($mysqli) {
    if (!isset($_SESSION['user_id'])) return 0;

    $user_id = (int) $_SESSION['user_id'];
    $stmt = $mysqli->prepare("SELECT SUM(ci.quantity) AS total FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int) ($row['total'] ?? 0);
}
?>

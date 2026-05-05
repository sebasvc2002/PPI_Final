<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$uid = (int)$_SESSION['user_id'];

// Determine action from POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── CREATE ─────────────────────────────────────────
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $street      = trim($_POST['street'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $country     = trim($_POST['country'] ?? '');
    $postal_code = (int)($_POST['postal_code'] ?? 0);

    if ($street === '' || $city === '' || $country === '' || $postal_code === 0) {
        $_SESSION['address_msg'] = 'Todos los campos son obligatorios.';
        $_SESSION['address_msg_type'] = 'danger';
    } else {
        $stmt = $mysqli->prepare("INSERT INTO user_addresses (user_id, street, city, country, postal_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isssi', $uid, $street, $city, $country, $postal_code);
        $stmt->execute();
        $stmt->close();
        $_SESSION['address_msg'] = 'Dirección agregada correctamente.';
        $_SESSION['address_msg_type'] = 'success';
    }
    header("Location: ../account.php?tab=addresses");
    exit();
}

// ── UPDATE ─────────────────────────────────────────
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $street      = trim($_POST['street'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $country     = trim($_POST['country'] ?? '');
    $postal_code = (int)($_POST['postal_code'] ?? 0);

    if ($street === '' || $city === '' || $country === '' || $postal_code === 0) {
        $_SESSION['address_msg'] = 'Todos los campos son obligatorios.';
        $_SESSION['address_msg_type'] = 'danger';
    } else {
        // Ensure the address belongs to the logged-in user
        $stmt = $mysqli->prepare("UPDATE user_addresses SET street = ?, city = ?, country = ?, postal_code = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param('sssiii', $street, $city, $country, $postal_code, $id, $uid);
        $stmt->execute();
        $stmt->close();
        $_SESSION['address_msg'] = 'Dirección actualizada correctamente.';
        $_SESSION['address_msg_type'] = 'success';
    }
    header("Location: ../account.php?tab=addresses");
    exit();
}

// ── DELETE ─────────────────────────────────────────
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    // Only allow deleting own addresses
    $stmt = $mysqli->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $uid);
    $stmt->execute();
    $stmt->close();
    $_SESSION['address_msg'] = 'Dirección eliminada.';
    $_SESSION['address_msg_type'] = 'success';
    header("Location: ../account.php?tab=addresses");
    exit();
}

// Fallback
header("Location: ../account.php?tab=addresses");
exit();
?>

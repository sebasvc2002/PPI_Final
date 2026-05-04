<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$uid = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../account.php");
    exit();
}

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$card_input = trim($_POST['card_number'] ?? '');
$new_password = $_POST['new_password'] ?? '';

// Validation
if ($name === '' || $email === '') {
    $_SESSION['profile_msg'] = 'Nombre y correo son obligatorios.';
    $_SESSION['profile_msg_type'] = 'danger';
    header("Location: ../account.php?tab=profile");
    exit();
}

// Check if email is taken by another user
$check = $mysqli->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$check->bind_param('si', $email, $uid);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    $_SESSION['profile_msg'] = 'Ese correo ya está registrado por otro usuario.';
    $_SESSION['profile_msg_type'] = 'danger';
    $check->close();
    header("Location: ../account.php?tab=profile");
    exit();
}
$check->close();

// Update name & email
$stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
$stmt->bind_param('ssi', $name, $email, $uid);
$stmt->execute();
$stmt->close();

// Update card number — only if user typed a new raw number (not the masked value)
if ($card_input !== '' && strpos($card_input, '****') === false) {
    $card_clean = preg_replace('/\D/', '', $card_input); // strip non-digits
    if ($card_clean !== '') {
        $card_num = (int)$card_clean;
        $stmt = $mysqli->prepare("UPDATE users SET card_number = ? WHERE id = ?");
        $stmt->bind_param('ii', $card_num, $uid);
        $stmt->execute();
        $stmt->close();
    }
}

// Update password — only if a new one was provided
if ($new_password !== '') {
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->bind_param('si', $hash, $uid);
    $stmt->execute();
    $stmt->close();
}

$_SESSION['profile_msg'] = 'Perfil actualizado correctamente.';
$_SESSION['profile_msg_type'] = 'success';
header("Location: ../account.php?tab=profile");
exit();
?>

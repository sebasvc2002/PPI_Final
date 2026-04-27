<!-- No funcional todavia -->

<?php
session_start();
require_once 'db.php';
if (isset($_POST[''])){
    $name=mysqli_real_escape_string($mysqli,$_POST['name']);
    $email=mysqli_real_escape_string($mysqli,$_POST['email']);
    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

    $checkEmail = $mysqli->query("SELECT email FROM users WHERE email = '$email'");
    if($checkEmail->num_rows > 0){
        $_SESSION['register_error'] = 'Esta dirección de correo ya está registrada';
        $_SESSION['active_form'] = 'register';
    } else {
        $mysqli->query("INSERT INTO users (name, email, password_hash) VALUES ('','','')");
    }
    header("Location: ./index.php");
    exit();
}
?>
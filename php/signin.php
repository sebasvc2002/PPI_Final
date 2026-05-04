<?php
session_start();
require_once 'db.php';
if (isset($_POST['register'])){
    $name=mysqli_real_escape_string($mysqli,$_POST['name']);
    $email=mysqli_real_escape_string($mysqli,$_POST['email']);
    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

    $checkEmail = $mysqli->query("SELECT email FROM users WHERE email = '$email'");
    if($checkEmail->num_rows > 0){
        $_SESSION['register_error'] = 'Esta dirección de correo ya está registrada';
        $_SESSION['active_form'] = 'register';
    } else {
        $mysqli->query("INSERT INTO users (name, email, password_hash, card_number) VALUES ('$name','$email','$password',0)");
    }
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['login'])){
    $email=mysqli_real_escape_string($mysqli,$_POST['email']);
    $plain_password = $_POST['password'] ?? '';
    $result=$mysqli->query("SELECT * FROM users WHERE email = '$email'");
    if($result->num_rows>0){
        $user=$result->fetch_assoc();
        if(password_verify($plain_password,$user['password_hash'])){
            $_SESSION['user_id']=$user['id'];
            if ($user['id'] == 1){
                header("Location: ../admin/index.php");
            } else{
                header("Location: ../account.php");
            }
            exit();
        }
    }
    $_SESSION['login_error'] = 'Correo electrónico o contraseña incorrectos';
    $_SESSION['active_form'] = 'login';
    header("Location: ../login.php");
    exit();
}

?>
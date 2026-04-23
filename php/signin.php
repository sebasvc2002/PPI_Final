<!-- No funcional todavia] -->

<?php
session_start();
require_once 'db.php';
if (isset($_POST[''])){
    $name=mysqli_real_escape_string($mysqli,$_POST['']);
    $email=mysqli_real_escape_string($mysqli,$_POST['']);
    $password=password_hash($_POST[''],PASSWORD_DEFAULT);

}

?>
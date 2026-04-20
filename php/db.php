<?php
$host='db';
$db="store";
$user="sebas";
$pass="Asdfgh123";
$mysqli = new mysqli($host,$user,$pass,$db);
if ($mysqli->connect_error) die(" Conexión fallida: " . $mysqli->connect_error);
?>
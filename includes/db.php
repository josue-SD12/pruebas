<?php
$host = 'localhost';      // Servidor de la base de datos
$dbname = 'textil_db'; // Nombre de la base de datos
$user = 'root';           // Usuario de MySQL
$pass = '';               // Contraseña de MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<?php
// descripcion_producto.php

// Generar un código automático único
$codigo_automatico = "PROD-" . uniqid();

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'] ?? '';

    // Aquí puedes guardar la descripción y el código en la base de datos si es necesario
    // Por ejemplo:
    /*
    $conn = new mysqli("localhost", "usuario", "contraseña", "basedatos");
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "INSERT INTO productos (codigo, descripcion) VALUES ('$codigo_automatico', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        echo "Producto registrado con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    */

    // Redirigir o mostrar un mensaje de éxito
    echo "<p>Producto registrado con éxito. Código: $codigo_automatico</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descripción del Producto</title>
</head>
<body>
    <h1>Descripción del Producto</h1>
    <p>Código generado automáticamente: <strong><?php echo $codigo_automatico; ?></strong></p>

    <form method="POST" action="">
        <label for="descripcion">Descripción del producto:</label><br>
        <textarea id="descripcion" name="descripcion" rows="5" cols="50" required></textarea><br><br>
        <button type="submit">Guardar Descripción</button>
    </form>

    <a href="index.php">Volver al inicio</a>
</body>
</html>
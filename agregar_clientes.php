<?php
// 🔧 Conexión a la base de datos (actualizada para InfinityFree)
$host       = "localhost";
$dbUsuario  = "root";
$dbPassword = "";
$dbNombre   = "textil_db";

$conn = new mysqli($host, $dbUsuario, $dbPassword, $dbNombre);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre'];
    $correo   = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $usuario  = $_POST['usuario_registro'];

    $sql = "INSERT INTO RegistrarClientes (nombre, correo, telefono, direccion, usuario_registro) 
            VALUES ('$nombre', '$correo', '$telefono', '$direccion', '$usuario')";

    if ($conn->query($sql) === TRUE) {
        echo "<h3>Cliente registrado correctamente. Registrado por: $usuario</h3>";
        echo "<a href='agregar_clientes.php'>Agregar otro cliente</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4fb;
        }

        .container {
            width: 60%;
            max-width: 700px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #6a5acd;
            margin-bottom: 25px;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        .form-actions {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }

        .form-actions button {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .form-actions button:hover {
            background-color: #5a4ab6;
        }

        .form-actions .volver {
            background-color: #cccccc;
            color: #333;
        }

        .form-actions .volver:hover {
            background-color: #bbbbbb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Agregar Nuevo Cliente</h2>
        <form action="procesar_cliente.php" method="POST">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>

            <label for="direccion">Dirección:</label>
            <textarea id="direccion" name="direccion" rows="3" required></textarea>

            <label for="usuario_registro">Registrado por (usuario):</label>
            <input type="text" id="usuario_registro" name="usuario_registro" required>

          <div class="form-actions">
    <button type="submit"><i class="fas fa-save"></i> Guardar</button>
    <button type="button" class="volver" onclick="window.location.href='dashboard.php'">
        <i class="fas fa-arrow-left"></i> Volver
    </button>
</div>
            </div>
        </form>
    </div>

</body>
</html>


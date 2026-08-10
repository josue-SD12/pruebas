<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "textil_db";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Registrar datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $descripcion = $_POST['descripcion'];
    $producto = $_POST['producto'];
    $usuario_registro = $_POST['usuario_registro'];

    $sql = "INSERT INTO reportes (nombre, correo, telefono, direccion, descripcion, producto, usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $correo, $telefono, $direccion, $descripcion, $producto, $usuario_registro);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center mt-3' role='alert'>✅ Guardado con éxito</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3' role='alert'>❌ Error al guardar: " . $stmt->error . "</div>";
    }
}

// Consultar datos
$sql = "SELECT * FROM reportes ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Reportes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .form-container {
            background-color: #2c2f33;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        .table-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #a78bfa;
            text-align: center;
            margin-bottom: 25px;
        }
        .btn-primary {
            background-color: #7a6be0;
            border: none;
        }
        .btn-primary:hover {
            background-color: #6758cc;
        }
        .btn-volver {
            background-color: #6c757d;
            border: none;
        }
        .btn-volver:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2><i class="fas fa-file-alt"></i> Registro de Reportes</h2>
    
    <form method="POST" class="form-container">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Producto</label>
                <input type="text" name="producto" class="form-control" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Dirección</label>
                <textarea name="direccion" class="form-control" rows="2" required></textarea>
            </div>
            <div class="col-md-12">
                <label class="form-label">Descripción del reporte</label>
                <textarea name="descripcion" class="form-control" rows="2" required></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Registrado por (usuario)</label>
                <input type="text" name="usuario_registro" class="form-control" required>
            </div>

            <div class="col-md-12 d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary px-4">Guardar</button>
                <a href="dashboardventas.php" class="btn btn-volver px-4 ms-3">Volver</a>
            </div>
        </div>
    </form>

    <div class="table-container mt-5">
        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Descripción del reporte</th>
                    <th>Producto</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td><?= htmlspecialchars($row['direccion']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion']) ?></td>
                    <td><?= htmlspecialchars($row['producto']) ?></td>
                    <td><?= htmlspecialchars($row['usuario']) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>

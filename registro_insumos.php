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

    $tinta_color = $_POST['tinta_color'];
    $litros_usados = $_POST['litros_usados'];
    $papel_subliminado = $_POST['papel_subliminado'];
    $medidas_usadas = $_POST['medidas_usadas'];

    // Usamos NOW() porque quieres que la fecha se genere automáticamente
    $sql = "INSERT INTO registro_insumos 
            (fecha_registro, tinta_color, litros_usados, papel_subliminado, medidas_usadas)
            VALUES (NOW(), ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $tinta_color, $litros_usados, $papel_subliminado, $medidas_usadas);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
}

// Consultar datos
$sql = "SELECT * FROM registro_insumos ORDER BY id DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Insumos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: white;
        }
        .form-container {
            background-color: #2c2f33;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }
        .table-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #17a2b8;
        }
        .btn-primary {
            background-color: #17a2b8;
            border: none;
        }
        .btn-primary:hover {
            background-color: #138496;
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
    <h2 class="text-center">Registro de Insumos</h2>
    
    <form method="POST" class="form-container">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Tintas Color(es)</label>
            <input type="text" name="tinta_color" class="form-control" placeholder="Escribe el/los color(es) usados" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Litros usados</label>
            <input type="number" step="0.01" name="litros_usados" class="form-control" placeholder="Registra los litros usados" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Papel subliminado</label>
            <input type="text" name="papel_subliminado" class="form-control" placeholder="Cantidad o tipo de papel usado" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Medidas usadas</label>
            <input type="text" name="medidas_usadas" class="form-control" placeholder="Escriba las medidas usadas" required>
        </div>

        <!-- Quitado porque la fecha la pone MySQL automáticamente -->
        <!--
        <div class="col-md-3">
            <label class="form-label">Fecha de registro</label>
            <input type="text" class="form-control" disabled placeholder="Se genera automáticamente">
        </div>
        -->

        <div class="col-md-12 d-flex justify-content-center">
            <button type="submit" class="btn btn-primary px-4">Registrar</button>
            <a href="dashboardtaller.php" class="btn btn-volver px-4 ms-3">Volver</a>
        </div>
</form>

    <div class="table-container mt-4">
        <table class="table table-dark table-bordered text-center">
            <thead>
    <tr>
        <th>ID</th>
        <th>Fecha Registro</th>
        <th>Tinta Color</th>
        <th>Litros Usados</th>
        <th>Papel Subliminado</th>
        <th>Medidas Usadas</th>
    </tr>

    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['fecha_registro'] ?></td>
        <td><?= $row['tinta_color'] ?></td>
        <td><?= $row['litros_usados'] ?></td>
        <td><?= $row['papel_subliminado'] ?></td>
        <td><?= $row['medidas_usadas'] ?></td>
    </tr>
    <?php } ?>
</table>
    </div>
</div>

</body>
</html>

<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "textil_db";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Insertar datos al presionar el botón Guardar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["accesorio"])) {
        $accesorio = $_POST["accesorio"];
        $tallas = $_POST["tallas"];
        $disenos = $_POST["disenos"];
        $cantidad_tallas = $_POST["cantidad_tallas"];

        $sql = "INSERT INTO stock_accesorios (accesorio, tallas, diseños, cantidad_tallas) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $accesorio, $tallas, $disenos, $cantidad_tallas);

        if ($stmt->execute()) {
            $mensaje = "Registro guardado correctamente.";
        } else {
            $mensaje = "Error al guardar: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock de Accesorios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #121212; color: white; }
        .form-container, .table-container { background-color: #2c2f33; padding: 20px; border-radius: 10px; }
        h2 { color: #17a2b8; }
        .btn-primary { background-color: #17a2b8; border: none; }
        .btn-primary:hover { background-color: #138496; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Stock de Accesorios</h2>

    <?php if (isset($mensaje)) { echo "<div class='alert alert-info text-center'>$mensaje</div>"; } ?>

    <form method="POST" class="form-container">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Accesorios</label>
                <input type="text" name="accesorio" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tallas</label>
                <select name="tallas" class="form-select" required>
                    <option value="L">L</option>
                    <option value="M">M</option>
                    <option value="S">S</option>
                    <option value="XL">XL</option>
                    <option value="L-S">Talla L-S</option>
                    <option value="S-M">Talla S-M</option>
                    <option value="M-XL">Talla M-XL</option>
                    <option value="L-S-M">Talla L-S-M</option>
                    <option value="S-M-XL">Talla S-M-XL</option>
                    <option value="L-S-M-XL">Talla L-S-M-XL</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Diseños</label>
                <input type="text" name="disenos" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cantidad de Tallas</label>
                <input type="number" name="cantidad_tallas" class="form-control" required>
            </div>
            <div class="col-md-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary px-4">Guardar</button>
            </div>
        </div>
    </form>

    <div class="table-container mt-4">
        <table class="table table-dark table-bordered text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Accesorios</th>
                    <th>Tallas</th>
                    <th>Diseños</th>
                    <th>Cantidad de Tallas</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT * FROM stock_accesorios";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $contador = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>$contador</td>
                            <td>{$row['accesorio']}</td>
                            <td>{$row['tallas']}</td>
                            <td>{$row['diseños']}</td>
                            <td>{$row['cantidad_tallas']}</td>
                          </tr>";
                    $contador++;
                }
            } else {
                echo "<tr><td colspan='5'>No hay registros</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <button class="btn btn-secondary" onclick="history.back()">Volver</button>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>

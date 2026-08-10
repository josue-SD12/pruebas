<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "textil_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_nota = $_POST['tipo_nota'];
    $categoria = $_POST['categoria'];
    $talla = $_POST['talla'];
    $diseno = $_POST['diseno'];
    $descripcion = $_POST['descripcion'];
    $vendedora = $_POST['Vendedora'];
    $nombre_completo = $_POST['nombre_completo'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $provincia = $_POST['provincia'];
    $agencia_envio = $_POST['agencia_envio'];
    $tipo_delivery = $_POST['tipo_delivery'];
    $tipo_cliente = $_POST['tipo_cliente'];

    $sql = "INSERT INTO Notas (tipo_nota, categoria, talla, diseño, descripcion, vendedora, nombre_completo, dni, telefono, direccion, provincia, agencia_envio, tipo_delivery, tipo_cliente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssss", $tipo_nota, $categoria, $talla, $diseno, $descripcion, $vendedora, $nombre_completo, $dni, $telefono, $direccion, $provincia, $agencia_envio, $tipo_delivery, $tipo_cliente);
    $stmt->execute();
    $stmt->close();
}

// Obtener registros
$notas = [];
$result = $conn->query("SELECT * FROM Notas");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notas[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacén (Notas)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #121212; color: white; }
        .form-container, .table-container { background-color: #1e1e1e; padding: 20px; border-radius: 10px; }
        h2 { color: #17a2b8; }
        .btn-primary { background-color: #17a2b8; border: none; }
        .btn-primary:hover { background-color: #138496; }
        .btn-secondary { background-color: #6c757d; border: none; }
        .btn-secondary:hover { background-color: #5a6268; }
        .table-container { max-height: 400px; overflow-y: auto; overflow-x: auto; }
        table { min-width: 1200px; }
        th, td { vertical-align: middle; }
        th { position: sticky; top: 0; background-color: #343a40; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Almacén (Notas)</h2>
        <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
    </div>

    <form method="POST" class="form-container mt-3">
        <div class="row g-3">
            <!-- Campos del formulario -->
            <div class="col-md-4">
                <label class="form-label">Tipo de nota</label>
                <select name="tipo_nota" class="form-select" required>
                    <option value="Nota de venta">Nota de venta</option>
                    <option value="Nota de cambio">Nota de cambio</option>
                    <option value="Nota de canje">Nota de canje</option>
                    <option value="Nota de evento">Nota de evento</option>
                    <option value="Nota de devolución">Nota de devolución</option>
                    <option value="Nota de regalos">Nota de regalo</option>
                    <option value="Nota por defectos">Nota por defectos</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Talla</label>
                <select name="talla" class="form-select">
                    <option value="Todos">Todos</option>
                    <option value="L">L</option>
                    <option value="M">M</option>
                    <option value="S">S</option>
                    <option value="XL">XL</option>
                    <option value="L-S">L-S</option>
                    <option value="S-M">S-M</option>
                    <option value="M-XL">M-XL</option>
                    <option value="L-S-M">L-S-M</option>
                    <option value="S-M-XL">S-M-XL</option>
                    <option value="L-S-M-XL">L-S-M-XL</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Diseño</label>
                <input type="text" name="diseno" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Vendedora</label>
                <input type="text" name="Vendedora" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Provincia/Distrito/Departamento</label>
                <input type="text" name="provincia" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Agencia de envío</label>
                <input type="text" name="agencia_envio" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo delivery</label>
                <select name="tipo_delivery" class="form-select">
                    <option value="Empresa">Empresa</option>
                    <option value="Particular">Particular</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo cliente</label>
                <select name="tipo_cliente" class="form-select">
                    <option value="Mayorista">Mayorista</option>
                    <option value="Minorista">Minorista</option>
                </select>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </div>
    </form>

    <div class="table-container mt-4">
        <table class="table table-dark table-bordered text-center table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo Nota</th>
                    <th>Categoría</th>
                    <th>Talla</th>
                    <th>Diseño</th>
                    <th>Descripción</th>
                    <th>Vendedora</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Provincia</th>
                    <th>Agencia</th>
                    <th>Delivery</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notas as $index => $nota): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($nota['tipo_nota']) ?></td>
                        <td><?= htmlspecialchars($nota['categoria']) ?></td>
                        <td><?= htmlspecialchars($nota['talla']) ?></td>
                        <td><?= htmlspecialchars($nota['diseño']) ?></td>
                        <td><?= htmlspecialchars($nota['descripcion']) ?></td>
                        <td><?= htmlspecialchars($nota['vendedora']) ?></td>
                        <td><?= htmlspecialchars($nota['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($nota['dni']) ?></td>
                        <td><?= htmlspecialchars($nota['telefono']) ?></td>
                        <td><?= htmlspecialchars($nota['direccion']) ?></td>
                        <td><?= htmlspecialchars($nota['provincia']) ?></td>
                        <td><?= htmlspecialchars($nota['agencia_envio']) ?></td>
                        <td><?= htmlspecialchars($nota['tipo_delivery']) ?></td>
                        <td><?= htmlspecialchars($nota['tipo_cliente']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

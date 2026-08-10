<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportar Insumos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4fb;
        }

        .container {
            width: 80%;
            max-width: 700px;
            margin: 40px auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
            padding: 25px 40px;
        }

        h2 {
            text-align: center;
            color: #6b5bcd;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        button {
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-guardar {
            background-color: #6b5bcd;
            color: white;
        }

        .btn-guardar:hover {
            background-color: #5748b7;
        }

        .btn-volver {
            background-color: #ccc;
        }

        .btn-volver:hover {
            background-color: #aaa;
        }

        .mensaje {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-fabric"></i> Reportar Insumos</h2>

    <?php
    $conn = new mysqli("localhost", "root", "", "textil_db");
    if ($conn->connect_error) {
        die("<p class='error'>Error de conexión: " . $conn->connect_error . "</p>");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $correo = $conn->real_escape_string($_POST['correo']);
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $producto = $conn->real_escape_string($_POST['producto']);

        if (!empty($nombre) && !empty($correo) && !empty($descripcion) && !empty($producto)) {
            $sql = "INSERT INTO reportes_insumos (nombre, correo, descripcion, producto) 
                    VALUES ('$nombre', '$correo', '$descripcion', '$producto')";
            if ($conn->query($sql)) {
                echo "<p class='mensaje'>Reporte enviado correctamente.</p>";
            } else {
                echo "<p class='error'>Error al guardar el reporte: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='error'>Por favor, completa todos los campos.</p>";
        }
    }

    $conn->close();
    ?>

    <form method="POST" action="">
        <label>Nombre Completo:</label>
        <input type="text" name="nombre" placeholder="Ej: Juan Pérez">

        <label>Correo Electrónico:</label>
        <input type="email" name="correo" placeholder="Ej: juanperez@gmail.com">

        <label>Descripción del reporte:</label>
        <textarea name="descripcion" placeholder="Detalla el tipo de tela faltante o problema encontrado..."></textarea>

        <label>Producto(s) Faltante:</label>
        <input type="text" name="producto" placeholder="Ej: Tela lino azul, 5 metros">

        <div class="buttons">
            <button type="submit" class="btn-guardar">Guardar</button>
            <button type="button" class="btn-volver" onclick="window.location.href='dashboardtaller.php'">Volver</button>
        </div>
    </form>
</div>

</body>
</html>

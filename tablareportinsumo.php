<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Insumos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4fb;
        }

        .header {
            background-color: #9e9ef0;
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px 10px 0 0;
        }

        .header i {
            margin-right: 10px;
        }

        .container {
            width: 80%;
            max-width: 1000px;
            margin: 40px auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #e0e0ff;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f7f7ff;
        }

        tr:hover {
            background-color: #e6e6ff;
        }

        .actions i {
            margin: 0 5px;
            cursor: pointer;
            color: #e74c3c;
        }

        .actions i:hover {
            color: #c0392b;
        }

        .footer, .pagination {
            text-align: right;
            padding: 10px 20px;
        }

        .footer button, .pagination a {
            background-color: #6b5bcd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 5px;
        }

        .footer button:hover, .pagination a:hover {
            background-color: #5748b7;
        }

        .search-box {
            padding: 15px 25px;
        }

        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
    </style>

    <script>
        function buscar() {
            let input = document.getElementById("search").value.toLowerCase();
            let filas = document.querySelectorAll("tbody tr");

            filas.forEach(fila => {
                let texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(input) ? "" : "none";
            });
        }
    </script>
</head>
<body>

<div class="container">
    <div class="header">
        <div><i class="fas fa-fabric"></i> Reportes Insumos</div>
        <i class="fas fa-chevron-up"></i>
    </div>

    <div class="search-box">
        <input type="text" id="search" placeholder="Buscar reporte..." onkeyup="buscar()">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Descripción</th>
                <th>Producto Faltante</th>
                <th>Fecha</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // 🔹 CONEXIÓN A LA BASE DE DATOS
        $conn = new mysqli("localhost", "root", "", "textil_db");
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // 🔹 ELIMINAR REGISTRO
        if (isset($_GET['eliminar'])) {
            $idEliminar = intval($_GET['eliminar']);
            $conn->query("DELETE FROM reportes_insumos WHERE id = $idEliminar");
            echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }

        // 🔹 PAGINACIÓN
        $por_pagina = 5;
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $inicio = ($pagina - 1) * $por_pagina;

        $total_resultados = $conn->query("SELECT COUNT(*) AS total FROM reportes_insumos")->fetch_assoc()['total'];
        $total_paginas = ceil($total_resultados / $por_pagina);

        // 🔹 OBTENER DATOS
        $resultado = $conn->query("SELECT * FROM reportes_insumos ORDER BY fecha DESC LIMIT $inicio, $por_pagina");

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila['id'] . "</td>";
                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['producto']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['fecha']) . "</td>";

                echo "<td class='actions'>
                        <a href='?eliminar=" . $fila['id'] . "' onclick=\"return confirm('¿Eliminar este reporte?');\">
                            <i class='fas fa-trash-alt'></i>
                        </a>
                      </td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay reportes de insumos faltantes.</td></tr>";
        }

        $conn->close();
        ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i'>$i</a>";
        }
        ?>
    </div>

    <div class="footer">
        <button onclick="window.location.href='dashboard.php'">Volver</button>
    </div>
</div>

</body>
</html>

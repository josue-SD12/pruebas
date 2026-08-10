<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notas realizadas por almacén</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4fb;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow-x: auto;
        }

        .header {
            background-color: #a58cf0;
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px 10px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1100px;
        }

        th, td {
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
        }

        th {
            background-color: #ece9f9;
            color: #333;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tr:nth-child(even) {
            background-color: #f9f9fc;
        }

        tr:hover {
            background-color: #f0eaff;
        }

        .actions {
            text-align: center;
        }

        .actions i {
            margin: 0 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .actions i.fa-trash-alt {
            color: #e74c3c;
        }

        .actions i.fa-trash-alt:hover {
            color: #c0392b;
        }

        .actions i.fa-file-pdf {
            color: #3498db;
        }

        .actions i.fa-file-pdf:hover {
            color: #1d6fa5;
        }

        .pagination {
            text-align: right;
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination a {
            background-color: #6a5acd;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .pagination a:hover {
            background-color: #5a4ab6;
        }

        .pagination a.active {
            background-color: #4a3a9e;
        }

        .search-box {
            padding: 15px 25px;
        }

        .search-box input {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #6a5acd;
        }

        .footer {
            padding: 15px 25px;
            display: flex;
            justify-content: flex-start;
        }

        .footer button {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .footer button:hover {
            background-color: #5a4ab6;
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

<?php
// CONEXIÓN CORRECTA A LA BASE DE DATOS
$conn = new mysqli("localhost", "root", "", "textil_db");

// Verificar conexión
if ($conn->connect_error) {
    die("<div style='color:red; padding:20px;'>Error de conexión: " . $conn->connect_error . "</div>");
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    if ($conn->query("DELETE FROM notas WHERE idNota = $idEliminar")) {
        echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        exit();
    } else {
        echo "<div style='color:red; padding:10px;'>Error al eliminar: " . $conn->error . "</div>";
    }
}

// PAGINACIÓN
$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina - 1) * $por_pagina;

// Obtener total de registros
$total_resultados = $conn->query("SELECT COUNT(*) as total FROM notas");
if ($total_resultados) {
    $total = $total_resultados->fetch_assoc()['total'];
    $total_paginas = ceil($total / $por_pagina);
} else {
    $total = 0;
    $total_paginas = 0;
}

// Consultar datos con paginación
$resultado = $conn->query("SELECT * FROM notas ORDER BY idNota DESC LIMIT $inicio, $por_pagina");

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("<div style='color:red; padding:20px;'>Error en la consulta: " . $conn->error . "</div>");
}
?>

<div class="container">
    <div class="header">
        <div><i class="fas fa-boxes"></i> Notas realizadas por almacén</div>
        <i class="fas fa-warehouse"></i>
    </div>

    <div class="search-box">
        <input type="text" id="search" placeholder="🔍 Buscar nota..." onkeyup="buscar()">
    </div>

    <table>
        <thead>
            <tr>
                <?php
                // Generamos los encabezados directamente desde las columnas
                if ($resultado->num_rows > 0) {
                    $campos = $resultado->fetch_fields();
                    foreach ($campos as $campo) {
                        $nombre = ucwords(str_replace(['_', '-'], ' ', $campo->name));
                        echo "<th>" . htmlspecialchars($nombre) . "</th>";
                    }
                } else {
                    // Si no hay datos, mostramos encabezados por defecto
                    echo "<th>ID</th>";
                    echo "<th>Tipo Cliente</th>";
                    echo "<th>Tipo Nota</th>";
                    echo "<th>Diseño</th>";
                    echo "<th>Categoría</th>";
                    echo "<th>Talla</th>";
                    echo "<th>Descripción</th>";
                    echo "<th>Vendedora</th>";
                    echo "<th>DNI</th>";
                    echo "<th>Nombre</th>";
                    echo "<th>Teléfono</th>";
                    echo "<th>Dirección</th>";
                    echo "<th>Provincia</th>";
                    echo "<th>Agencia</th>";
                    echo "<th>Tipo Delivery</th>";
                    echo "<th>Tipo Cliente Extra</th>";
                }
                ?>
                <th>PDF</th>
                <th>Eliminar</th>
            </tr>
        </thead>

        <tbody>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                
                // Mostrar cada campo de la fila
                foreach ($fila as $campo => $valor) {
                    $valor = $valor ?? "";
                    echo "<td>" . htmlspecialchars($valor) . "</td>";
                }
                
                // Acciones PDF y Eliminar
                echo "<td class='actions'>
                        <a href='generar_pdf.php?id=" . $fila['idNota'] . "' target='_blank' title='Descargar PDF'>
                            <i class='fas fa-file-pdf'></i>
                        </a>
                      </td>";
                echo "<td class='actions'>
                        <a href='?eliminar=" . $fila['idNota'] . "' onclick=\"return confirm('¿Estás seguro de eliminar este registro?');\">
                            <i class='fas fa-trash-alt'></i>
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='20' style='text-align:center; padding:30px; color:#999;'>
                    <i class='fas fa-inbox' style='font-size:24px; display:block; margin-bottom:10px;'></i>
                    No hay registros disponibles en la base de datos
                  </td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina-1 ?>">&laquo; Anterior</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="<?= $i == $pagina ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        
        <?php if ($pagina < $total_paginas): ?>
            <a href="?pagina=<?= $pagina+1 ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>

    <div class="footer">
        <button onclick="window.location.href='dashboardventas.php'">
            <i class="fas fa-arrow-left"></i> Volver
        </button>
    </div>
</div>

<?php
$conn->close();
?>

</body>
</html>
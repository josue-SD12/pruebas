  <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4fb;
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
            background-color: #ece9f9;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9fc;
        }

        tr:hover {
            background-color: #f0eaff;
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
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 5px;
        }

        .footer button:hover, .pagination a:hover {
            background-color: #5a4ab6;
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
        <div><i class="fas fa-users"></i>tablastelas</div>
        <i class="fas fa-chevron-up"></i>
    </div>

    <div class="search-box">
        <input type="text" id="search" placeholder="Buscar cliente..." onkeyup="buscar()">
    </div>

    <table>
        <thead>
            <tr>
                <th>Rollos</th>
                <th>Clor(es)</th>
                <th>Cantidad</th>
                <th>Tallas</th>
                <th>Cantidad de tallas</th>
                <th>Peso</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
       <?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "textil_db");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla de Telas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4fb;
        }
        .header {
            background-color: #a58cf0;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            border-radius: 10px 10px 0 0;
        }
        .container {
            width: 80%;
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; }
        th { background: #ece9f9; }
        tr:nth-child(even) { background: #f9f9fc; }
        tr:hover { background: #f0eaff; }
        .actions i { color: #e74c3c; cursor: pointer; }
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
        // Eliminar registro
        if (isset($_GET['eliminar'])) {
            $idEliminar = intval($_GET['eliminar']);
            $conn->query("DELETE FROM registro_telas WHERE id = $idEliminar");
            echo "<script>window.location.href='tablastelas.php';</script>";
            exit();
        }

        // Paginación
        $por_pagina = 5;
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $inicio = ($pagina - 1) * $por_pagina;

        $total = $conn->query("SELECT COUNT(*) as total FROM registros_telas")->fetch_assoc()['total'];
        $total_paginas = ceil($total / $por_pagina);

        $resultado = $conn->query("SELECT * FROM registros_telas LIMIT $inicio, $por_pagina");

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$fila['id']."</td>";
                echo "<td>".$fila['rollo']."</td>";
                echo "<td>".$fila['color']."</td>";
                echo "<td>".$fila['talla']."</td>";
                echo "<td>".$fila['cantidad_tallas']."</td>";
                echo "<td>".$fila['peso']."</td>";

                echo "<td class='actions'>
                        <a href='?eliminar=".$fila['id']."' onclick=\"return confirm('¿Eliminar registro?');\">
                            <i class='fas fa-trash-alt'></i>
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay registros de telas.</td></tr>";
        }

        ?>

        </tbody>
    </table>

    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='?pagina=$i'>$i</a> ";
        }
        ?>
    </div>

    <div class="footer">
        <button onclick="window.location.href='dashboardtaller.php'">Volver</button>
    </div>
</div>

</body>
</html>

     

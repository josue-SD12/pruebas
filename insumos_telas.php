<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "textil_db";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$successMessage = "";

// Insertar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['rollos'])) {
    $rollos = $_POST['rollos'];
    $color = $_POST['color'];
    $talla = $_POST['talla'];
    $cantidad_tallas = $_POST['cantidad_tallas'];
    $peso = $_POST['peso'];

    if (is_numeric($cantidad_tallas) && is_numeric($peso)) {
        $stmt = $conn->prepare("INSERT INTO registros_telas (rollo, color, talla, cantidad_tallas, peso) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdd", $rollos, $color, $talla, $cantidad_tallas, $peso);
        $successMessage = $stmt->execute() ? "Registro guardado correctamente." : "Error al guardar.";
        $stmt->close();
    }
}


$successMessage = "";

// Insertar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['rollos'])) {
    $rollos = $_POST['rollos'];
    $color = $_POST['color'];
    $talla = $_POST['talla'];
    $cantidad_tallas = $_POST['cantidad_tallas'];
    $peso = $_POST['peso'];

    if (is_numeric($cantidad_tallas) && is_numeric($peso)) {
        $stmt = $conn->prepare("INSERT INTO registros_telas (rollo, color, talla, cantidad_tallas, peso) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdd", $rollos, $color, $talla, $cantidad_tallas, $peso);
        $successMessage = $stmt->execute() ? "Registro guardado correctamente." : "Error al guardar.";
        $stmt->close();
    }
}

// Eliminar
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM registros_telas WHERE id = $id");
    $successMessage = "Registro eliminado correctamente.";
}

// Actualizar
if (isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $rollo = $_POST['edit_rollos'];
    $color = $_POST['edit_color'];
    $talla = $_POST['edit_talla'];
    $cantidad = $_POST['edit_cantidad_tallas'];
    $peso = $_POST['edit_peso'];

    if (is_numeric($cantidad) && is_numeric($peso)) {
        $stmt = $conn->prepare("UPDATE registros_telas SET rollo=?, color=?, talla=?, cantidad_tallas=?, peso=? WHERE id=?");
        $stmt->bind_param("sssddi", $rollo, $color, $talla, $cantidad, $peso, $id);
        $successMessage = $stmt->execute() ? "Registro actualizado correctamente." : "Error al actualizar.";
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Insumo Telas</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-color: rgb(36, 39, 54);
      color: white;
    }
    .form-container {
      background-color: rgb(38, 43, 58);
      padding: 20px;
      border-radius: 10px;
    }
    .table-container {
      background-color: rgb(36, 43, 59);
      padding: 20px;
      border-radius: 10px;
    }
    h2 {
      color: rgb(63, 104, 141);
    }
    .btn-primary {
      background-color: rgb(58, 68, 116);
      border: none;
    }
    .btn-primary:hover {
      background-color: #138496;
    }
    .btn-danger {
      background-color: #dc3545;
      border: none;
    }
    .btn-danger:hover {
      background-color: #c82333;
    }
    .btn-warning {
      border: none;
    }
    .btn-warning:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h2 class="text-center">Insumo Telas</h2>

  <form method="POST" class="form-container">
    <div class="row g-3">
      <div class="col-md-3">
        <label for="rollos" class="form-label">Rollos</label>
        <select name="rollos" class="form-select" required>
          <option value="Tela Suplex">Tela Suplex</option>
          <option value="Tela Tull Licrado">Tela Tull Licrado</option>
          <option value="Tela Piel de Durazno">Tela Piel de Durazno</option>
          <option value="Yersey">Yersey</option>
          <option value="Malla Licrada">Malla Licrada</option>
          <option value="Fresh Terri">Fresh Terri</option>
          <option value="Franela">Franela</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="color" class="form-label">Color(es)</label>
        <input type="text" name="color" class="form-control" required />
      </div>
      <div class="col-md-3">
        <label for="talla" class="form-label">Tallas</label>
        <select name="talla" class="form-select" required>
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
        <label for="cantidad_tallas" class="form-label">Cantidad Tallas</label>
        <input type="number" name="cantidad_tallas" class="form-control" required />
      </div>
      <div class="col-md-3">
        <label for="peso" class="form-label">Peso</label>
        <input type="number" step="0.01" name="peso" class="form-control" required />
      </div>
      <div class="col-md-12 d-flex justify-content-center mt-3">
        <button type="submit" class="btn btn-primary px-4">Guardar</button>
      </div>
    </div>
  </form>

  <div class="table-container mt-4">
    <table class="table table-dark table-bordered text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>Rollo</th>
          <th>Color</th>
          <th>Talla</th>
          <th>Cantidad</th>
          <th>Peso</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM registros_telas"; // CORREGIDO
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['rollo']}</td>
                    <td>{$row['color']}</td>
                    <td>{$row['talla']}</td>
                    <td>{$row['cantidad_tallas']}</td>
                    <td>" . number_format($row['peso'], 2) . " kg</td>
                    <td>
                      <button type='button' class='btn btn-warning btn-sm' 
                        onclick='editRecord(" . $row['id'] . ", " . json_encode($row['rollo']) . ", " . json_encode($row['color']) . ", " . json_encode($row['talla']) . ", " . $row['cantidad_tallas'] . ", " . json_encode($row['peso']) . ")'>Editar</button>
                      <button type='button' class='btn btn-danger btn-sm' onclick='confirmDeletion(" . $row['id'] . ")'>Eliminar</button>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='7' class='text-center'>No hay datos disponibles</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-3">
  <a href="dashboardtaller.php" class="btn btn-secondary">Volver</a>
</div>


<!-- SweetAlert2 Scripts -->
<script>
  function confirmDeletion(id) {
    Swal.fire({
      title: '¿Desea eliminar este insumo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        const input = document.createElement('input');
        input.name = 'delete_id';
        input.value = id;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function editRecord(id, rollo, color, talla, cantidad_tallas, peso) {
    Swal.fire({
      title: 'Editar insumo',
      html:
        `<input id="swal-rollo" class="swal2-input" placeholder="Rollo" value="${rollo}">
         <input id="swal-color" class="swal2-input" placeholder="Color" value="${color}">
         <input id="swal-talla" class="swal2-input" placeholder="Talla" value="${talla}">
         <input id="swal-cantidad" type="number" class="swal2-input" placeholder="Cantidad" value="${cantidad_tallas}">
         <input id="swal-peso" type="number" step="0.01" class="swal2-input" placeholder="Peso" value="${peso}">`,
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        const newRollo = document.getElementById('swal-rollo').value;
        const newColor = document.getElementById('swal-color').value;
        const newTalla = document.getElementById('swal-talla').value;
        const newCantidad = document.getElementById('swal-cantidad').value;
        const newPeso = document.getElementById('swal-peso').value;

        if (!newRollo || !newColor || !newTalla || !newCantidad || !newPeso) {
          Swal.showValidationMessage('Todos los campos son obligatorios');
          return false;
        }

        if (isNaN(newCantidad) || isNaN(newPeso)) {
          Swal.showValidationMessage('Cantidad y Peso deben ser numéricos');
          return false;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        const inputs = [
          { name: 'update_id', value: id },
          { name: 'edit_rollos', value: newRollo },
          { name: 'edit_color', value: newColor },
          { name: 'edit_talla', value: newTalla },
          { name: 'edit_cantidad_tallas', value: newCantidad },
          { name: 'edit_peso', value: newPeso }
        ];

        inputs.forEach(({ name, value }) => {
          const input = document.createElement('input');
          input.name = name;
          input.value = value;
          form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  <?php if ($successMessage): ?>
    Swal.fire({
      icon: 'success',
      title: '¡Listo!',
      text: '<?php echo $successMessage; ?>',
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>
</script>
</body>
</html>

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Datos de conexión a la base de datos
$host       = "localhost";
$dbUsuario  = "root";
$dbPassword = "";
$dbNombre   = "textil_db";

// Conexión a la base de datos
$conn = new mysqli($host, $dbUsuario, $dbPassword, $dbNombre);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consultar la información del usuario
$stmt = $conn->prepare("SELECT nombre, email, telefono FROM usuarios WHERE id = ?");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombre, $email, $telefono);
    $stmt->fetch();
} else {
    echo "Usuario no encontrado.";
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Información del Usuario</title>
  <link rel="icon" type="image/x-icon" href="img/image.png"/>
  <!-- Fuente Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome para íconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --accent-color: #6c5ce7;
      --hover-color: #a29bfe;
      --card-color: #ffffff;
      --background-color: #6c5ce7; /* Fondo de la página (mismo que el botón) */
      --text-color: #333;
      --shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--background-color);
      color: var(--text-color);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .container {
      width: 100%;
      max-width: 600px;
      background: var(--card-color);
      border-radius: 10px;
      box-shadow: var(--shadow);
      overflow: hidden;
      animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .form-header {
      background: var(--accent-color);
      padding: 20px;
      text-align: center;
    }
    .form-header img {
      max-width: 100px;
      margin-bottom: 10px;
    }
    .form-header h1 {
      font-size: 22px;
      color: #fff;
      margin: 0;
    }
    form {
      padding: 30px;
    }
    form label {
      font-size: 14px;
      margin-top: 20px;
      display: block;
      color: var(--accent-color);
      font-weight: 600;
    }
    form input[type="text"],
    form input[type="email"],
    form input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 15px;
      transition: border 0.3s;
    }
    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form input[type="password"]:focus {
      border-color: var(--accent-color);
      outline: none;
    }
    form input[type="submit"] {
      margin-top: 30px;
      width: 100%;
      padding: 14px;
      background: var(--accent-color);
      color: var(--card-color);
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }
    form input[type="submit"]:hover {
      background: var(--hover-color);
      transform: translateY(-2px);
    }
    /* Botón para volver al dashboard */
    .back-btn {
      display: block;
      text-align: center;
      margin-top: 20px;
      padding: 14px;
      background: var(--hover-color);
      color: var(--card-color);
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      transition: background 0.3s, transform 0.2s;
    }
    .back-btn:hover {
      background: var(--accent-color);
      transform: translateY(-2px);
    }
    /* Modal de confirmación */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      animation: fadeIn 0.5s ease;
    }
    .modal {
      background: var(--card-color);
      width: 90%;
      max-width: 400px;
      padding: 30px;
      border-radius: 8px;
      text-align: center;
      box-shadow: var(--shadow);
      animation: slideDown 0.5s ease;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .modal .alert-icon {
      font-size: 50px;
      color: var(--accent-color);
      margin-bottom: 10px;
    }
    .modal h2 {
      font-size: 20px;
      color: var(--accent-color);
      margin-bottom: 10px;
    }
    .modal p {
      font-size: 16px;
      margin-bottom: 20px;
      color: var(--text-color);
    }
    .modal input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 15px;
      transition: border 0.3s;
    }
    .modal input[type="password"]:focus {
      border-color: var(--accent-color);
      outline: none;
    }
    .modal button {
      padding: 12px 20px;
      background: var(--accent-color);
      color: var(--card-color);
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }
    .modal button:hover {
      background: var(--hover-color);
      transform: translateY(-2px);
    }
    @media (max-width: 480px) {
      .container {
        margin: 20px;
      }
      .form-header h1 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Modal de confirmación -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <div class="alert-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h2>Confirmación</h2>
      <p>Para editar su información personal, confirme su contraseña.</p>
      <input type="password" id="confirmPassword" placeholder="Ingrese su contraseña" required>
      <button id="confirmButton">Confirmar</button>
    </div>
  </div>
  
  <!-- Contenedor del formulario con logo en el header -->
  <div class="container" id="editContainer" style="display: none;">
    <div class="form-header">
      <img src="img/image.png" alt="Logo de la empresa">
      <h1>Editar Información</h1>
    </div>
    <form action="update_user.php" method="post">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
      
      <label for="email">Correo Electrónico</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
      
      <label for="telefono">Teléfono</label>
      <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
      
      <!-- Campo oculto para la contraseña confirmada -->
      <input type="hidden" name="password" id="passwordField">
      <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
      <input type="submit" value="Actualizar Información">
    </form>
    <!-- Botón para volver al dashboard -->
    <a href="dashboard.php" class="back-btn">Volver al Dashboard</a>
  </div>
  
  <script>
    document.getElementById('confirmButton').addEventListener('click', function() {
      var passwordInput = document.getElementById('confirmPassword').value.trim();
      if (passwordInput === "") {
        alert("Por favor, ingrese su contraseña.");
        return;
      }
      document.getElementById('passwordField').value = passwordInput;
      document.getElementById('modalOverlay').style.display = 'none';
      document.getElementById('editContainer').style.display = 'block';
    });
  </script>
</body>
</html>

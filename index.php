<?php
session_start();
include 'includes/db.php';

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['mensaje'] = "Solicitud no válida.";
        header('Location: index.php');
        exit;
    }

    // Sanitizar y validar el correo electrónico
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = "Correo electrónico inválido.";
        header('Location: index.php');
        exit;
    }

    $password = $_POST['password'];

    try {
        // Consulta usando PDO
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
      
        if ($usuario) {
            // Si tu contraseña en la base de datos NO está cifrada, comparamos directo
            if ($password === $usuario['contraseña']) { 
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['login_success'] = true;
                $_SESSION['role'] = $usuario['rol']; // Guardamos el rol REAL del usuario

                // Determinar la página de redirección según el rol
                switch (strtolower($usuario['rol'])) {
                    case 'admin':
                        $redirect = 'dashboard.php';
                        break;
                    case 'ventas':
                        $redirect = 'dashboardventas.php';
                        break;
                    case 'almacen':
                        $redirect = 'dashboardalmacen.php';
                        break;
                    case 'taller':
                        $redirect = 'dashboardtaller.php';
                        break;
                    default:
                        $redirect = 'index.php';
                        break;
                }
                header('Location: ' . $redirect);
                exit;
            } else {
                $_SESSION['mensaje'] = "Correo electrónico o contraseña incorrectos.";
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['mensaje'] = "Correo electrónico o contraseña incorrectos.";
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error en la base de datos: " . $e->getMessage());
        $_SESSION['mensaje'] = "Ocurrió un error inesperado. Por favor, inténtalo de nuevo.";
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Econocomercio S.A.C</title>
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="img/image.png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Aplicar box-sizing global para consistencia */
    *, *::before, *::after {
      box-sizing: border-box;
    }
    /* Estilos generales */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url('img/colorido-arte-digital_7680x4320_xtrafondos.com.jpg'); /* Aquí debes poner la ruta de tu imagen */
      background-size: cover; /* Asegura que la imagen cubra toda la pantalla */
      background-position: center; /* Centra la imagen */
      background-attachment: fixed; /* Mantiene la imagen fija al hacer scroll */
      margin: 0; 
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
    }
    .login-container {
      display: flex;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 90%;
      max-width: 800px;
      text-align: center;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .logo {
      flex: 1;
      margin-right: 40px;
    }
    .logo img {
      width: 200px;
      border-radius: 20px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease;
    }
    .logo img:hover {
      transform: scale(1.05);
    }
    .form-container {
      flex: 2;
      padding: 20px;
    }
    h1 {
      font-size: 32px;
      margin-bottom: 20px;
      color: #333;
      font-weight: 700;
    }
    .input-group {
      position: relative;
      margin-bottom: 20px;
      width: 100%;
    }
    .input-group input,
    .input-group select {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #ddd;
      border-radius: 12px;
      font-size: 16px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }
    .input-group input:focus,
    .input-group select:focus {
      border-color: #007bff;
      box-shadow: 0 0 12px rgba(0, 123, 255, 0.4);
      outline: none;
    }
    .input-group i {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 18px;
      cursor: pointer;
      transition: color 0.3s ease;
    }
    .input-group i:hover {
      color: #007bff;
    }
    .btn-login {
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 18px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.3s ease;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #0056b3, #003d7a);
      transform: scale(1.03);
    }
    .mensaje {
      color: #ff4d4d;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .register-link a {
      color: #007bff;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.3s ease;
    }
    .register-link a:hover {
      color: #0056b3;
      text-decoration: underline;
    }
    /* Responsive Design */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
        padding: 20px;
      }
      .logo {
        margin-right: 0;
        margin-bottom: 20px;
      }
      .logo img {
        width: 150px;
      }
      h1 {
        font-size: 28px;
      }
      .input-group input,
      .input-group select {
        padding: 12px 20px;
        font-size: 14px;
      }
      .btn-login {
        padding: 12px;
        font-size: 16px;
      }
    }
    /* Ventana emergente (opcional) */
    #success-message {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #fff;
      padding: 30px 50px;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
      z-index: 1000;
      display: none;
      animation: fadeInOut 2.5s ease forwards;
      text-align: center;
      border: 2px solid #4caf50;
      max-width: 90%;
    }
    #success-message::before {
      content: '✔';
      font-size: 40px;
      color: #4caf50;
      display: block;
      margin-bottom: 15px;
      animation: bounce 0.5s ease;
    }
    @keyframes fadeInOut {
      0% { opacity: 0; transform: translate(-50%, -60%); }
      10% { opacity: 1; transform: translate(-50%, -50%); }
      90% { opacity: 1; transform: translate(-50%, -50%); }
      100% { opacity: 0; transform: translate(-50%, -60%); }
    }
    @keyframes bounce {
      0% { transform: scale(0.8); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }
    body.modal-open::after {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 999;
      backdrop-filter: blur(5px);
    }
  </style>
</head>
<body>
  <!-- Ventana emergente (opcional) -->
  <div id="success-message">Inicio de sesión exitoso</div>
  <div class="login-container">
    <div class="logo">
      <img src="img/image.png" alt="Logo de la empresa">
    </div>
    <div class="form-container">
      <h1>Iniciar Sesión</h1>
      <?php if (isset($_SESSION['mensaje'])): ?>
        <p class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']); ?></p>
        <?php unset($_SESSION['mensaje']); ?>
      <?php endif; ?>
      <form action="index.php" method="POST" role="form" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" id="email" name="email" placeholder="Correo electrónico" required autofocus aria-label="Correo electrónico">
        </div>
        <div class="input-group">
          <i class="fas fa-lock" id="togglePasswordIcon" title="Mostrar/Ocultar contraseña"></i>
          <input type="password" id="password" name="password" placeholder="Contraseña" required aria-label="Contraseña">
        </div>
        <div class="input-group">
          <select name="role" id="role" required>
            <option value="" disabled selected>Seleccione el rol</option>
            <option value="administrador">Admin</option>
            <option value="ventas">Ventas</option>
            <option value="almacen">Almacén</option>
            <option value="taller">Taller</option>
            <option value="contador">Contador</option>
          </select>
        </div>
        <button type="submit" class="btn-login" aria-label="Iniciar Sesión">Iniciar Sesión</button>
      
  </div>
  <script>
    // Mostrar/Ocultar contraseña
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const passwordField = document.getElementById('password');
    togglePasswordIcon.addEventListener('click', () => {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      togglePasswordIcon.classList.toggle('fa-eye');
      togglePasswordIcon.classList.toggle('fa-eye-slash');
    });
    // Validación en tiempo real
    document.querySelector('form').addEventListener('submit', function(event) {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const role = document.getElementById('role').value;
      if (!email || !password || !role) {
        event.preventDefault();
        alert('Por favor, completa todos los campos.');
      }
    });
    // Función opcional para mostrar mensaje de éxito
    function showSuccessMessage(username) {
      const successMessage = document.getElementById('success-message');
      successMessage.textContent = Acceso exitoso¿, ${username}!;
      document.body.classList.add('modal-open');
      successMessage.style.display = 'block';
    }
    <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
      window.onload = function() {
        const username = "<?= htmlspecialchars($_SESSION['username'] ?? 'Usuario'); ?>";
        showSuccessMessage(username);
        setTimeout(() => {
          document.body.classList.remove('modal-open');
          <?php
            if (isset($_SESSION['role'])) {
              $role = strtolower($_SESSION['role']);
              switch ($role) {
                case 'administrador': $redirectJS = 'dashboard.php'; break;
                case 'ventas': $redirectJS = 'dashboardventas.php'; break;
                case 'almacen': $redirectJS = 'dashboardalmacen.php'; break;
                case 'taller': $redirectJS = 'dashboardtaller.php'; break;
                case 'contador': $redirectJS = 'dashboardconta.php'; break;
                default: $redirectJS = 'index.php'; break;
              }
              echo "window.location.href = '$redirectJS';";
            }
          ?>
        }, 2000);
      };
      <?php unset($_SESSION['login_success']); ?>
      <?php unset($_SESSION['username']); ?>
    <?php endif; ?>
  </script>
</body>
</html>

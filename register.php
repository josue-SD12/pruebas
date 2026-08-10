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
        header('Location: register.php');
        exit;
    }

    // Sanitizar y validar los datos del formulario
    $nombre = trim(filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];


    // Validaciones adicionales
    $errores = [];
    if (strlen($nombre) < 3 || strlen($nombre) > 50) {
        $errores[] = "El nombre debe tener entre 3 y 50 caracteres.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }
    if (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }
    if (!preg_match('/^\d{7,15}$/', $telefono)) {
        $errores[] = "El número de teléfono debe contener entre 7 y 15 dígitos.";
    }

    // Verificar si el correo ya está registrado
    try {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $errores[] = "El correo electrónico ya está registrado.";
        }
    } catch (PDOException $e) {
        $errores[] = "Error al verificar el correo electrónico.";
    }

    // Si no hay errores, registrar al usuario
    if (empty($errores)) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password, :telefono)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hashed);
            $stmt->execute();

            $_SESSION['mensaje'] = "Registro exitoso. ¡Por favor inicia sesión!";
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errores[] = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Econocomercio S.A.C</title>
    <!-- Agregar el favicon -->
    <link rel="icon" type="image/x-icon" href="img/image.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #16213e); /* Fondo degradado */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1); /* Fondo semi-transparente */
            padding: 40px;
            border-radius: 20px; /* Bordes más redondeados */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 1000px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            backdrop-filter: blur(15px); /* Efecto de desenfoque más pronunciado */
        }
        .logo {
            flex: 1;
            margin-right: 50px;
            animation: float 3s ease-in-out infinite; /* Animación flotante */
        }
        .logo img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        .form-container {
            flex: 2;
        }
        h1 {
            font-size: 36px; /* Título más grande */
            color: #fff;
            margin-bottom: 20px;
            font-weight: 600;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .input-group {
            position: relative;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 15px 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        input:focus {
            border-color: #6c63ff;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 12px rgba(108, 99, 255, 0.5);
        }
        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 18px;
        }
        button {
            padding: 15px;
            background: linear-gradient(135deg, #6c63ff, #5a51e6); /* Botón degradado */
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(108, 99, 255, 0.4);
        }
        .mensaje-error {
            color: #ff4d4d;
            font-size: 14px;
            margin-top: 10px;
        }
        .mensaje-exito {
            color: #28a745;
            font-size: 16px;
            margin-top: 10px;
        }
        p {
            margin-top: 20px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
        }
        a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 30px;
            }
            .logo {
                margin-right: 0;
                margin-bottom: 30px;
            }
            h1 {
                font-size: 28px;
            }
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="tel"] {
                padding: 12px 40px;
                font-size: 14px;
            }
            button {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/image.png" alt="Logo de la empresa">
        </div>
        <div class="form-container">
            <h1>Registro</h1>

            <?php if (!empty($errores)): ?>
                <div class="mensaje-error">
                    <?php foreach ($errores as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <!-- Token CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required minlength="3" maxlength="50">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Contraseña" required minlength="8">
                </div>

            

                <button type="submit">Registrarse</button>
            </form>

            <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>
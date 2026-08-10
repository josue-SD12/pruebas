// procesar_cliente.php
<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "textil_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$usuario = $_POST['usuario_registro'];

// Insertar en la tabla RegistrarClientes
$sql = "INSERT INTO RegistrarClientes (nombre, correo, telefono, direccion, usuario_registro) 
        VALUES ('$nombre', '$correo', '$telefono', '$direccion', '$usuario')";

if ($conn->query($sql) === TRUE) {
    header("Location: agregar_clientes.php?success=1&user=$usuario");
    exit();
} else {
    header("Location: agregar_clientes.php?success=0");
    exit();
}

$conn->close();
?>

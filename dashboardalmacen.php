<?php
session_start();

// Mostrar errores (en desarrollo; recuerda deshabilitarlos en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Para pruebas: asigna manualmente un user_id si no existe en la sesión.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// 🔧 Conexión a la base de datos (actualizada para InfinityFree)
$host       = "localhost";
$dbUsuario  = "root";
$dbPassword = "";
$dbNombre   = "textil_db";

// Conexión a la base de datos con manejo de errores
$conn = new mysqli($host, $dbUsuario, $dbPassword, $dbNombre);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variable de correo de usuario
$userEmail = "";

// Verificar que exista un user_id en la sesión
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Preparar la consulta con prepared statements
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userEmail);
        $stmt->fetch();
    } else {
        $userEmail = "Bienvenido"; // Usuario no encontrado
    }
    $stmt->close();
} else {
    // Valor por defecto si no hay sesión iniciada
    $userEmail = "usuario@ejemplo.com";
}

// Datos simulados para el Almacén
$stockTotal            = 5000;   // Total de stock en unidades
$entradasRecientes     = 800;    // Entradas recientes (unidades)
$salidasRecientes      = 600;    // Salidas recientes (unidades)
$inventarioCritico     = 45;     // Productos con stock crítico
$productosRegistrados  = 300;    // Total de productos registrados
$ajustesInventario     = 10;     // Ajustes realizados en el inventario

// Datos para gráficos (simulados)
$meses         = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
$entradasData  = [800, 850, 900, 950, 1000, 1050];
$salidasData   = [600, 650, 700, 750, 800, 850];
$stockData     = [5000, 5050, 5100, 5150, 5200, 5250];
// $ajustesData  = [10, 12, 8, 15, 11, 9]; // (Si se requiere mostrar en un gráfico adicional)

// Datos simulados para la tabla de últimos movimientos de inventario
$ultimosMovimientos = [
    ["id" => 1, "producto" => "Tela Algodón", "tipo" => "Entrada", "cantidad" => 200, "fecha" => "2023-10-01"],
    ["id" => 2, "producto" => "Jeans", "tipo" => "Salida", "cantidad" => 50,  "fecha" => "2023-10-02"],
    ["id" => 3, "producto" => "Camisa", "tipo" => "Entrada", "cantidad" => 150, "fecha" => "2023-10-03"],
    ["id" => 4, "producto" => "Falda", "tipo" => "Salida", "cantidad" => 80,  "fecha" => "2023-10-04"],
    ["id" => 5, "producto" => "Chaqueta", "tipo" => "Entrada", "cantidad" => 120, "fecha" => "2023-10-05"],
];

// Datos simulados para Ranking de Vendedores y Compradores
$rankingVendedores = [
    ['rank' => 1, 'nombre' => 'Juan Pérez',   'ventas' => 150],
    ['rank' => 2, 'nombre' => 'María García', 'ventas' => 130],
    ['rank' => 3, 'nombre' => 'Carlos López', 'ventas' => 110],
    ['rank' => 4, 'nombre' => 'Ana Torres',   'ventas' => 90],
    ['rank' => 5, 'nombre' => 'Luis Gómez',   'ventas' => 80],
];

$rankingCompradores = [
    ['rank' => 1, 'nombre' => 'Empresa ABC',     'compras' => 200],
    ['rank' => 2, 'nombre' => 'Empresa XYZ',     'compras' => 180],
    ['rank' => 3, 'nombre' => 'Comercial 123',   'compras' => 160],
    ['rank' => 4, 'nombre' => 'Distribuidora LMN','compras' => 140],
    ['rank' => 5, 'nombre' => 'Retail QRS',      'compras' => 120],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Econocomercio S.A.C - Almacén</title>
  <link rel="icon" type="image/x-icon" href="img/image.png"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <style>
    :root {
      --sidebar-width: 250px;          
      --sidebar-collapsed-width: 80px; 
      --background-color: #f8f9fa;
      --card-color: #ffffff;
      --text-color: #333;
      --accent-color: #6c5ce7;
      --hover-color: #a29bfe;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition-speed: 0.3s;
      --gradient-primary: linear-gradient(135deg, #6c5ce7, #a29bfe);
      --gradient-secondary: linear-gradient(135deg, #ff7675, #fdcb6e);
    }
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
      background-color: var(--background-color);
      color: var(--text-color);
      transition: background-color var(--transition-speed), color var(--transition-speed);
    }
    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--card-color);
      height: 100vh;
      box-shadow: var(--shadow);
      transition: width var(--transition-speed);
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      padding: 10px;
    }
    .sidebar.collapsed {
      width: var(--sidebar-collapsed-width);
    }
    .sidebar-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 10px 0;
    }
    .sidebar-header img {
      width: 170px;
      height: auto;
      display: block;
    }
    .toggle-sidebar {
      width: 30px;
      height: 30px;
      border: none;
      background: var(--accent-color);
      border-radius: 5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      box-shadow: var(--shadow);
      transition: transform var(--transition-speed);
    }
    .toggle-sidebar:hover {
      transform: scale(1.05);
    }
    .toggle-sidebar i {
      font-size: 14px;
    }
    .sidebar.collapsed .sidebar-header img,
    .sidebar.collapsed .menu-item span,
    .sidebar.collapsed .submenu,
    .sidebar.collapsed .submenu-arrow,
    .sidebar.collapsed .dashboard-button span {
      display: none;
    }
    /* MENÚ PRINCIPAL */
    .menu {
      margin-top: 20px;
      list-style: none;
      padding: 0;
    }
    .menu li {
      margin-bottom: 15px;
    }
    .dashboard-button {
      width: 100%;
      padding: 8px 10px;
      background: transparent;
      color: var(--text-color);
      border: none;
      border-radius: 5px;
      text-align: left;
      margin-bottom: 15px;
      cursor: pointer;
      font-size: 18px;
      font-weight: 400;
      transition: background var(--transition-speed), color var(--transition-speed);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    body.dark-mode .dashboard-button {
      color: #fff;
    }
    .dashboard-button:hover {
      background: var(--hover-color);
    }
    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 8px 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background var(--transition-speed), color var(--transition-speed);
    }
    .menu-item i {
      font-size: 18px;
      margin-right: 8px;
    }
    .menu-item span {
      flex: 1;
      text-align: left;
    }
    .menu-item:hover {
      background-color: var(--hover-color);
      color: #fff;
    }
    .submenu-arrow {
      transition: transform var(--transition-speed);
    }
    .submenu-arrow.rotated {
      transform: rotate(180deg);
    }
     /* Submenú */
.submenu {
  margin-top: 5px;
  list-style: none;
  padding-left: 20px;
  display: none;
}

.submenu.show {
  display: block;
}

.submenu li {
  padding: 6px;
  font-size: 14px;
  border-radius: 4px;
  transition: background var(--transition-speed), color var(--transition-speed);
  cursor: pointer;
  text-align: left;
  margin-bottom: 3px;
}

.submenu li:hover {
  background: var(--hover-color);
  color: #fff;
}

.submenu li a {
  text-decoration: none;
  color: inherit;
  display: block;
  width: 100%;
}
    /* MAIN CONTENT */
    .main-content {
      flex: 1;
      padding: 20px;
      background: var(--background-color);
      transition: all var(--transition-speed);
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background: var(--card-color);
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: var(--shadow);
    }
    .header h1 {
      font-size: 24px;
      font-weight: 600;
      margin: 0;
      color: var(--accent-color);
    }
    .header-controls {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    /* Notificaciones */
    .notifications-dropdown-container {
      position: relative;
      display: inline-block;
    }
    .notifications-button {
      background: var(--accent-color);
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      cursor: pointer;
      font-weight: 500;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background var(--transition-speed);
    }
    .notifications-button:hover {
      background: var(--hover-color);
    }
    .notifications-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      background: var(--card-color);
      border-radius: 5px;
      box-shadow: var(--shadow);
      overflow: hidden;
      z-index: 100;
      min-width: 200px;
      display: none;
    }
    .notifications-dropdown.show {
      display: block;
    }
    .notifications-content {
      padding: 10px 15px;
    }
    .notifications-content p {
      margin: 0;
      font-size: 14px;
      color: var(--text-color);
    }
    /* Botón de tema */
    .theme-toggle {
      background: var(--accent-color);
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      cursor: pointer;
      font-weight: 500;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background var(--transition-speed);
    }
    .theme-toggle:hover {
      background: var(--hover-color);
    }
    .theme-toggle i {
      font-size: 18px;
    }
    /* Dropdown de usuario */
    .user-dropdown {
      position: relative;
      display: inline-block;
    }
    .user-info {
      background: var(--accent-color);
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background var(--transition-speed);
    }
    .user-info:hover {
      background: var(--hover-color);
    }
    .user-options {
      position: absolute;
      top: 100%;
      right: 0;
      background: var(--card-color);
      border-radius: 5px;
      box-shadow: var(--shadow);
      overflow: hidden;
      z-index: 100;
      display: none;
    }
    .user-options.show {
      display: block;
    }
    .user-options a {
      display: block;
      padding: 10px 15px;
      text-decoration: none;
      color: var(--text-color);
      white-space: nowrap;
    }
    .user-options a:hover {
      background: var(--hover-color);
      color: #fff;
    }
    /* TARJETAS */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 15px;
      margin-bottom: 30px;
    }
    .card {
      background: var(--card-color);
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      box-shadow: var(--shadow);
      transition: transform var(--transition-speed), box-shadow var(--transition-speed);
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .card h3 {
      margin-bottom: 8px;
      font-size: 16px;
      font-weight: 600;
      color: var(--accent-color);
    }
    .card span {
      font-size: 20px;
      font-weight: 600;
    }
    /* Filtros */
    .filters {
      text-align: center;
      margin-bottom: 20px;
    }
    .filters hr {
      margin: 10px auto;
      border: none;
      border-top: 1px solid #999;
      width: 80%;
    }
    .filters h2 {
      margin: 0;
      font-size: 18px;
      color: var(--accent-color);
    }
    .filter-row {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 10px;
      align-items: center;
    }
    .filter-row label {
      font-weight: 500;
    }
    .filter-row select {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    /* GRÁFICOS */
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
    }
    .chart {
      background: var(--card-color);
      padding: 15px;
      border-radius: 10px;
      box-shadow: var(--shadow);
    }
    .chart h3 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 10px;
      color: var(--accent-color);
    }
    canvas {
      width: 100% !important;
      height: auto !important;
    }
    /* TABLAS */
    .table-container {
      margin-top: 30px;
      background: var(--card-color);
      padding: 15px;
      border-radius: 10px;
      box-shadow: var(--shadow);
    }
    .table-container h2, .table-container h3 {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--accent-color);
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table th, table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    table th {
      background: var(--accent-color);
      color: #fff;
    }
    table tr:hover {
      background: var(--hover-color);
      color: #fff;
    }
    /* Botones de PDF y XML */
    .btn-pdf, .btn-xml {
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-pdf {
      background-color: #ff7675;
      color: #fff;
    }
    .btn-xml {
      background-color: #6c5ce7;
      color: #fff;
    }
    .btn-pdf:hover, .btn-xml:hover {
      transform: translateY(-2px);
    }
    .btn-pdf:active, .btn-xml:active {
      transform: translateY(0);
    }
    /* Modo Oscuro */
    body.dark-mode {
      --background-color: #1e1e2f;
      --card-color: #2a2a40;
      --text-color: #fff;
      --accent-color: #6c5ce7;
      --hover-color: #a29bfe;
    }
    body.dark-mode .sidebar,
    body.dark-mode .header,
    body.dark-mode .card,
    body.dark-mode .chart,
    body.dark-mode .table-container {
      background: var(--card-color);
      color: var(--text-color);
    }
    body.dark-mode .sidebar ul li:hover {
      background: transparent;
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <button class="toggle-sidebar" id="btnToggleSidebar">
        <i class="fas fa-chevron-left"></i>
      </button>
      <img src="img/image-removebg-preview (7).png" alt="Logo de la empresa" />
    </div>
    <!-- MENÚ PRINCIPAL -->
    <ul class="menu">
      <li>
        <button class="dashboard-button" onclick="location.href='dashboardalmacen.php'">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </button>
      </li>
      <!-- Menú de Almacén -->
<li class="menu-item-container">
  <div class="menu-item toggle-submenu">
    <i class="fas fa-warehouse"></i>
    <span>Almacén</span>
    <i class="fas fa-chevron-down submenu-arrow"></i>
  </div>
  <ul class="submenu">
    <li><a href="Notas del Almacen.php">Notas del Almacen</a></li>
    <li><a href="Stock.php">Stock</a></li>
    <li><a href="tablastock.php">Reguistros de accesorios de stock</a></li>
  </ul>
</li>
    </ul>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main-content">
    <div class="header">
      <h1>Econocomercio S.A.C - Almacén</h1>
      <div class="header-controls">
        <!-- Botón de Notificaciones -->
        <div class="notifications-dropdown-container">
          <button class="notifications-button" id="btnNotifications">
            <i class="fas fa-bell"></i>
          </button>
          <div class="notifications-dropdown" id="notificationsDropdown">
            <div class="notifications-content">
              <p>No hay notificaciones nuevas.</p>
            </div>
          </div>
        </div>
        <!-- Botón de Modo Oscuro/Claro -->
        <button class="theme-toggle" id="btnThemeToggle">
          <i class="fas fa-moon"></i> Modo Oscuro
        </button>
        <!-- Dropdown de Usuario -->
        <div class="user-dropdown">
          <div class="user-info" id="btnUserInfo">
            <?= htmlspecialchars($userEmail); ?> <i class="fas fa-chevron-down"></i>
          </div>
          <div class="user-options" id="userOptions">
            <a href="information_user.php">Editar información</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="cards">
      <div class="card">
        <h3>Stock Total</h3>
        <span><?= number_format($stockTotal, 0); ?> Unidades</span>
      </div>
      <div class="card">
        <h3>Entradas Recientes</h3>
        <span><?= number_format($entradasRecientes, 0); ?> Unidades</span>
      </div>
      <div class="card">
        <h3>Salidas Recientes</h3>
        <span><?= number_format($salidasRecientes, 0); ?> Unidades</span>
      </div>
      <div class="card">
        <h3>Inventario Crítico</h3>
        <span><?= number_format($inventarioCritico, 0); ?></span>
      </div>
      <div class="card">
        <h3>Productos Registrados</h3>
        <span><?= number_format($productosRegistrados, 0); ?></span>
      </div>
      <div class="card">
        <h3>Ajustes de Inventario</h3>
        <span><?= number_format($ajustesInventario, 0); ?></span>
      </div>
    </div>

    <!-- Filtros adicionales -->
    <div class="filters">
      <hr>
      <h2>Filtros Adicionales</h2>
      <div class="filter-row">
        <label for="periodo">Período:</label>
        <select id="periodo">
          <option>Última semana</option>
          <option>Por Mes</option>
          <option>Todos</option>
          <option>Por Fecha</option>
        </select>
      </div>
      <hr>
    </div>

    <!-- Gráficos -->
    <div class="charts">
      <div class="chart">
        <h3>Entradas Mensuales</h3>
        <canvas id="entradasChart"></canvas>
      </div>
      <div class="chart">
        <h3>Salidas Mensuales</h3>
        <canvas id="salidasChart"></canvas>
      </div>
      <div class="chart">
        <h3>Entradas vs Salidas</h3>
        <canvas id="movimientosChart"></canvas>
      </div>
      <div class="chart">
        <h3>Stock Total Mensual</h3>
        <canvas id="stockChart"></canvas>
      </div>
    </div>

    <!-- Sección de Ranking de Vendedores y Compradores -->
    <div class="table-container">
      <h2>Ranking de Vendedores y Compradores</h2>
      <div class="rankings" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
        <!-- Ranking de Vendedores -->
        <div class="ranking-table">
          <h3>Ranking de Vendedores</h3>
          <div class="filters">
            <div class="filter-row">
              <label for="mesVendedores">Mes:</label>
              <select id="mesVendedores">
                <option value="enero">Enero</option>
                <option value="febrero">Febrero</option>
                <option value="marzo">Marzo</option>
                <option value="abril">Abril</option>
                <option value="mayo">Mayo</option>
                <option value="junio">Junio</option>
                <option value="julio">Julio</option>
                <option value="agosto">Agosto</option>
                <option value="septiembre">Septiembre</option>
                <option value="octubre">Octubre</option>
                <option value="noviembre">Noviembre</option>
                <option value="diciembre">Diciembre</option>
              </select>
              <label for="anioVendedores">Año:</label>
              <select id="anioVendedores">
              <option value="2023">2025</option>
              <option value="2023">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
              </select>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Posición</th>
                <th>Vendedor</th>
                <th>Ventas</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rankingVendedores as $vendedor): ?>
                <tr>
                  <td><?= $vendedor['rank']; ?></td>
                  <td><?= $vendedor['nombre']; ?></td>
                  <td><?= number_format($vendedor['ventas'], 0); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- Ranking de Compradores -->
        <div class="ranking-table">
          <h3>Ranking de Compradores</h3>
          <div class="filters">
            <div class="filter-row">
              <label for="mesCompradores">Mes:</label>
              <select id="mesCompradores">
                <option value="enero">Enero</option>
                <option value="febrero">Febrero</option>
                <option value="marzo">Marzo</option>
                <option value="abril">Abril</option>
                <option value="mayo">Mayo</option>
                <option value="junio">Junio</option>
                <option value="julio">Julio</option>
                <option value="agosto">Agosto</option>
                <option value="septiembre">Septiembre</option>
                <option value="octubre">Octubre</option>
                <option value="noviembre">Noviembre</option>
                <option value="diciembre">Diciembre</option>
              </select>
              <label for="anioCompradores">Año:</label>
              <select id="anioCompradores">
              <option value="2023">2025</option>
              <option value="2023">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
              </select>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Posición</th>
                <th>Comprador</th>
                <th>Compras</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rankingCompradores as $comprador): ?>
                <tr>
                  <td><?= $comprador['rank']; ?></td>
                  <td><?= $comprador['nombre']; ?></td>
                  <td><?= number_format($comprador['compras'], 0); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Tabla de últimos movimientos -->
    <div class="table-container">
      <h2>Últimos Movimientos de Inventario</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimosMovimientos as $movimiento): ?>
            <tr>
              <td><?= $movimiento['id']; ?></td>
              <td><?= $movimiento['producto']; ?></td>
              <td><?= $movimiento['tipo']; ?></td>
              <td><?= number_format($movimiento['cantidad'], 0); ?></td>
              <td><?= $movimiento['fecha']; ?></td>
              <td>
                <!-- Botones de PDF y XML -->
                <button class="btn-pdf" onclick="generarPDF(<?= $movimiento['id']; ?>)">
                  <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button class="btn-xml" onclick="generarXML(<?= $movimiento['id']; ?>)">
                  <i class="fas fa-file-code"></i> XML
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Función para alternar clases de visibilidad
    function toggleClass(element, className) {
      element.classList.toggle(className);
    }

    // Toggle Sidebar
    document.getElementById('btnToggleSidebar').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('collapsed');

      const icon = this.querySelector('i');
      icon.classList.toggle('fa-chevron-left');
      icon.classList.toggle('fa-chevron-right');
    });

    // Toggle Tema (Oscuro/Claro)
    document.getElementById('btnThemeToggle').addEventListener('click', function() {
      const body = document.body;
      body.classList.toggle('dark-mode');
      const icon = this.querySelector('i');
      if (body.classList.contains('dark-mode')) {
        this.innerHTML = '<i class="fas fa-sun"></i> Modo Claro';
      } else {
        this.innerHTML = '<i class="fas fa-moon"></i> Modo Oscuro';
      }
    });

    // Toggle Notificaciones
    document.getElementById('btnNotifications').addEventListener('click', function() {
      const dropdown = document.getElementById('notificationsDropdown');
      toggleClass(dropdown, 'show');
    });

    // Toggle Submenús
document.querySelectorAll('.toggle-submenu').forEach(item => {
  item.addEventListener('click', function() {
    const submenu = this.parentElement.querySelector('.submenu');
    const arrow = this.querySelector('.submenu-arrow');
    submenu.classList.toggle('show');
    arrow.classList.toggle('rotated');
  });
});

    // Toggle Dropdown de Usuario
    document.getElementById('btnUserInfo').addEventListener('click', function() {
      const userOptions = document.getElementById('userOptions');
      toggleClass(userOptions, 'show');
    });

    // Datos para los gráficos (obtenidos desde PHP)
    const meses        = <?= json_encode($meses); ?>;
    const entradasData = <?= json_encode($entradasData); ?>;
    const salidasData  = <?= json_encode($salidasData); ?>;
    const stockData    = <?= json_encode($stockData); ?>;

    // Gráfico: Entradas Mensuales
    const entradasCtx = document.getElementById('entradasChart').getContext('2d');
    new Chart(entradasCtx, {
      type: 'line',
      data: {
        labels: meses,
        datasets: [{
          label: 'Entradas Mensuales',
          data: entradasData,
          borderColor: '#6c5ce7',
          borderWidth: 2,
          fill: false
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Salidas Mensuales
    const salidasCtx = document.getElementById('salidasChart').getContext('2d');
    new Chart(salidasCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Salidas Mensuales',
          data: salidasData,
          backgroundColor: '#ff7675',
          borderColor: '#ff7675',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Entradas vs Salidas
    const movimientosCtx = document.getElementById('movimientosChart').getContext('2d');
    new Chart(movimientosCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [
          {
            label: 'Entradas',
            data: entradasData,
            backgroundColor: '#6c5ce7',
            borderColor: '#6c5ce7',
            borderWidth: 1
          },
          {
            label: 'Salidas',
            data: salidasData,
            backgroundColor: '#ff7675',
            borderColor: '#ff7675',
            borderWidth: 1
          }
        ]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Stock Total Mensual
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
      type: 'line',
      data: {
        labels: meses,
        datasets: [{
          label: 'Stock Total Mensual',
          data: stockData,
          borderColor: '#fdcb6e',
          borderWidth: 2,
          fill: false
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Funciones para generar PDF y XML
    function generarPDF(id) {
      alert(`Generando PDF para el movimiento con ID: ${id}`);
      // Aquí puedes agregar la lógica para generar el PDF
    }

    function generarXML(id) {
      alert(`Generando XML para el movimiento con ID: ${id}`);
      // Aquí puedes agregar la lógica para generar el XML
    }
  </script>
</body>
</html>

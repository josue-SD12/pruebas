<?php
session_start();

// Mostrar errores (en desarrollo; recuerda deshabilitarlos en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Para pruebas: asigna manualmente un user_id si no existe en la sesión.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// 🔧 Datos de conexión a la base de datos (actualizados para InfinityFree)
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

// Datos simulados para ventas
$ventasTotales       = 120000;    // Ventas totales
$clientesNuevos      = 150;       // Clientes nuevos
$ventasOnline        = 80000;     // Ventas online
$ventasFisicas       = 40000;     // Ventas físicas
$productosVendidos   = 1200;      // Productos vendidos
$devoluciones        = 50;        // Devoluciones

// Datos para gráficos
$meses         = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
$ventasData    = [120000, 130000, 140000, 150000, 160000, 170000];
$clientesData  = [150, 170, 200, 180, 190, 210];
$onlineData    = [80000, 85000, 90000, 95000, 100000, 105000];
$fisicasData   = [40000, 45000, 50000, 55000, 60000, 65000];
$productosData = [1200, 1300, 1400, 1500, 1600, 1700];
$devolucionesData = [50, 60, 70, 80, 90, 100];

// Datos simulados para la tabla de últimas ventas
$ultimasVentas = [
    ["id" => 1, "cliente" => "Juan Pérez", "monto" => 1200, "fecha" => "2023-10-01"],
    ["id" => 2, "cliente" => "María Gómez", "monto" => 800, "fecha" => "2023-10-02"],
    ["id" => 3, "cliente" => "Carlos López", "monto" => 1500, "fecha" => "2023-10-03"],
    ["id" => 4, "cliente" => "Ana Martínez", "monto" => 900, "fecha" => "2023-10-04"],
    ["id" => 5, "cliente" => "Luis Rodríguez", "monto" => 2000, "fecha" => "2023-10-05"],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Econocomercio S.A.C - Ventas</title>
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
    /* Tabla de últimas ventas */
    .table-container {
      margin-top: 30px;
      background: var(--card-color);
      padding: 15px;
      border-radius: 10px;
      box-shadow: var(--shadow);
    }
    .table-container h2 {
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
        <button class="dashboard-button" onclick="location.href='dashboardventas.php'">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </button>
      </li>
      <!-- Menú de Ventas -->
      <li class="menu-item-container">
        <div class="menu-item toggle-submenu">
          <i class="fas fa-chart-line"></i>
          <span>Ventas</span>
           <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
         <li><a href="ventas.php">Registrar una venta </a></li>
          <li><a href="reguistroventa.php">Reguistro de la venta </a></li>
          <li><a href="tablanota.php">Revision de notas </a></li>
          <li><a href="reportarventa.php">Reportar una Ventas</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main-content">
    <div class="header">
      <h1>Econocomercio S.A.C - Ventas</h1>
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
        <h3>Ventas Totales</h3>
        <span>S/ <?= number_format($ventasTotales, 2); ?></span>
      </div>
      <div class="card">
        <h3>Clientes Nuevos</h3>
        <span><?= number_format($clientesNuevos, 0); ?></span>
      </div>
      <div class="card">
        <h3>Ventas Online</h3>
        <span>S/ <?= number_format($ventasOnline, 2); ?></span>
      </div>
      <div class="card">
        <h3>Ventas Físicas</h3>
        <span>S/ <?= number_format($ventasFisicas, 2); ?></span>
      </div>
      <div class="card">
        <h3>Productos Vendidos</h3>
        <span><?= number_format($productosVendidos, 0); ?></span>
      </div>
      <div class="card">
        <h3>Devoluciones</h3>
        <span><?= number_format($devoluciones, 0); ?></span>
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
        <h3>Ventas Mensuales</h3>
        <canvas id="ventasChart"></canvas>
      </div>
      <div class="chart">
        <h3>Clientes Nuevos</h3>
        <canvas id="clientesChart"></canvas>
      </div>
      <div class="chart">
        <h3>Ventas Online vs Físicas</h3>
        <canvas id="ventasOnlineFisicasChart"></canvas>
      </div>
      <div class="chart">
        <h3>Productos Vendidos</h3>
        <canvas id="productosChart"></canvas>
      </div>
    </div>

    <!-- Tabla de últimas ventas -->
    <div class="table-container">
      <h2>Últimas Ventas</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Acciones</th> <!-- Nueva columna para los botones -->
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ultimasVentas as $venta): ?>
            <tr>
              <td><?= $venta['id']; ?></td>
              <td><?= $venta['cliente']; ?></td>
              <td>S/ <?= number_format($venta['monto'], 2); ?></td>
              <td><?= $venta['fecha']; ?></td>
              <td>
                <!-- Botones de PDF y XML -->
                <button class="btn-pdf" onclick="generarPDF(<?= $venta['id']; ?>)">
                  <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button class="btn-xml" onclick="generarXML(<?= $venta['id']; ?>)">
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
        const arrow   = this.querySelector('.submenu-arrow');
        toggleClass(submenu, 'show');
        arrow.classList.toggle('rotated');
      });
    });

    // Toggle Dropdown de Usuario
    document.getElementById('btnUserInfo').addEventListener('click', function() {
      const userOptions = document.getElementById('userOptions');
      toggleClass(userOptions, 'show');
    });

    // Datos para los gráficos (obtenidos desde PHP)
    const meses         = <?= json_encode($meses); ?>;
    const ventasData    = <?= json_encode($ventasData); ?>;
    const clientesData  = <?= json_encode($clientesData); ?>;
    const onlineData    = <?= json_encode($onlineData); ?>;
    const fisicasData   = <?= json_encode($fisicasData); ?>;
    const productosData = <?= json_encode($productosData); ?>;
    const devolucionesData = <?= json_encode($devolucionesData); ?>;

    // Gráfico: Ventas Mensuales
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
      type: 'line',
      data: {
        labels: meses,
        datasets: [{
          label: 'Ventas Mensuales',
          data: ventasData,
          borderColor: '#6c5ce7',
          borderWidth: 2,
          fill: false
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Clientes Nuevos
    const clientesCtx = document.getElementById('clientesChart').getContext('2d');
    new Chart(clientesCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Clientes Nuevos',
          data: clientesData,
          backgroundColor: '#00b894',
          borderColor: '#00b894',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Ventas Online vs Físicas
    const ventasOnlineFisicasCtx = document.getElementById('ventasOnlineFisicasChart').getContext('2d');
    new Chart(ventasOnlineFisicasCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [
          {
            label: 'Ventas Online',
            data: onlineData,
            backgroundColor: '#6c5ce7',
            borderColor: '#6c5ce7',
            borderWidth: 1
          },
          {
            label: 'Ventas Físicas',
            data: fisicasData,
            backgroundColor: '#ff7675',
            borderColor: '#ff7675',
            borderWidth: 1
          }
        ]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico: Productos Vendidos
    const productosCtx = document.getElementById('productosChart').getContext('2d');
    new Chart(productosCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Productos Vendidos',
          data: productosData,
          backgroundColor: '#fdcb6e',
          borderColor: '#fdcb6e',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Funciones para generar PDF y XML
    function generarPDF(id) {
      alert(`Generando PDF para la venta con ID: ${id}`);
      // Aquí puedes agregar la lógica para generar el PDF
    }

    function generarXML(id) {
      alert(`Generando XML para la venta con ID: ${id}`);
      // Aquí puedes agregar la lógica para generar el XML
    }
  </script>
</body>
</html>
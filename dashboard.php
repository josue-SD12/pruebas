<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Para pruebas: asigna manualmente un user_id si no existe en la sesión.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// Datos de conexión a la base de datos
$host       = "localhost";
$dbUsuario  = "root";
$dbPassword = "";
$dbNombre   = "textil_db";

// Conexión a la base de datos
$conn = new mysqli($host, $dbUsuario, $dbPassword, $dbNombre);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Variable para almacenar el correo
$userEmail = "";

// Verifica que el user_id esté en la sesión
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Prepara la consulta
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Almacena el resultado para poder verificar si se encontró algún registro
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userEmail);
        $stmt->fetch();
    } else {
        $userEmail = "Bienvenido"; // No se encontró el usuario con ese id
    }
    $stmt->close();
} else {
    // Si no hay sesión iniciada, asigna un valor por defecto
    $userEmail = "usuario@ejemplo.com";
}

// Simulación de datos dinámicos para el sistema textil
$notasVenta    = 12000;
$comprobantes  = 5000;
$totales       = $notasVenta + $comprobantes;
$balance       = 30000;
$utilidadNeta  = 15000;
$compras       = 10000;

// Datos para gráficos de línea/barra
$meses            = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
$notasVentaData   = [12000, 19000, 3000, 5000, 2000, 3000];
$comprobantesData = [5000, 10000, 8000, 12000, 9000, 15000];
$totalesData      = [30000, 50000, 20000];

// Nuevos datos para Ganancias y Compras
$gananciasData = [2000, 3000, 1500, 4000, 3500, 5000];
$comprasLabels = ['Total percepciones', 'Total compras', 'Total'];
$comprasData   = [7000, 12000, 19000];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Econocomercio S.A.C - Sistema Textil</title>
  <link rel="icon" type="image/x-icon" href="img/image.png"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    }
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
      background-color: var(--background-color);
      color: var(--text-color);
      transition: background-color 0.3s, color 0.3s;
    }
    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--card-color);
      height: 100vh;
      box-shadow: var(--shadow);
      transition: width 0.3s;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      padding: 0 10px 20px 10px;
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
      transition: transform 0.3s;
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
    /* Botón Dashboard personalizado */
    .dashboard-button {
      width: 100%;
      padding: 8px 10px;
      background: transparent; /* Sin fondo por defecto */
      color: var(--text-color);
      border: none;
      border-radius: 5px;
      text-align: left;
      margin-bottom: 15px;
      cursor: pointer;
      font-size: 18px;
      font-weight: 400; /* Sin negrita */
      transition: background 0.3s, color 0.3s;
      display: flex;
      align-items: center;
      gap: 10px; /* Espacio entre ícono y texto */
    }
    body.dark-mode .dashboard-button {
      color: #fff;
    }
    .dashboard-button:hover {
      background: var(--hover-color);
    }
    /* Items del menú */
    .menu-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 8px 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s, color 0.3s;
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
      transition: transform 0.3s;
    }
    .submenu-arrow.rotated {
      transform: rotate(180deg);
    }
    /* Submenú */
    .submenu {
      margin-top: 5px;
      list-style: none;
      display: none;
      padding-left: 20px;
    }
    .submenu li {
      display: block;
      padding: 6px;
      font-size: 14px;
      border-radius: 4px;
      transition: background 0.3s, color 0.3s;
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
      transition: all 0.3s;
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
      transition: background 0.3s;
    }
    .theme-toggle:hover {
      background: var(--hover-color);
    }
    .theme-toggle i {
      font-size: 18px;
    }
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
      transition: background 0.3s;
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
      display: none;
      z-index: 100;
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
    /* TARJETAS (Cards) */
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
      transition: transform 0.3s, box-shadow 0.3s;
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
    /* FILTROS ADICIONALES */
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
    .small-pie-chart canvas {
      width: 250px !important;
      height: 250px !important;
      margin: 0 auto;
    }
    canvas {
      width: 100% !important;
      height: auto !important;
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
    body.dark-mode .chart {
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
      <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-chevron-left"></i>
      </button>
      <img src="img/image-removebg-preview (7).png" alt="Logo de la empresa" />
    </div>

    <!-- MENÚ PRINCIPAL -->
    <ul class="menu">
      <!-- Se comenta el bloque original de Dashboard -->
      <!--
      <li class="active" onclick="toggleSubmenu(this)">
        <div class="menu-item">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
          <li><a href="dashboard_resumen.php">Resumen</a></li>
          <li><a href="dashboard_estadisticas.php">Estadísticas</a></li>
        </ul>
      </li>
      -->
      <!-- NUEVO: Botón para Dashboard -->
      <li>
        <button class="dashboard-button" onclick="location.href='dashboard.php'">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </button>
      </li>

     
      <li onclick="toggleSubmenu(this)">
        <div class="menu-item">
          <i class="fas fa-cash-register"></i>
          <span>POS</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
          <li><a href="pos_punto_de_venta.php">Punto de Venta</a></li>
        </ul>
      </li>
      <li onclick="toggleSubmenu(this)">
        <div class="menu-item">
          <i class="fas fa-box"></i>
         
          <span>Clientes</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
          <li><a href="listade_clientes.php">Lista de provedores</a></li>
          <li><a href="agregar_clientes.php">Agregar provedores</a></li>
        </ul>
      </li>
      <li onclick="toggleSubmenu(this)">
        <div class="menu-item">
          <i class="fas fa-clipboard-list"></i>
          <span>Almacenes</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
          <li><a href="tabla2stock.php">Gestion de inventario(stock)</a></li>
         <li><a href="tablanota2.php">Revision de notas </a></li>
        </ul>
      </li>
      <li onclick="toggleSubmenu(this)">
        <div class="menu-item">
          <i class="fas fa-chart-pie"></i>
          <span>Reportes</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
        </div>
        <ul class="submenu">
          <li><a href="tablareportvent.php">report.Ventas</a></li>
          <li><a href="tablareportinsumo.php">reporte de insumos </a></li>
          <li><a href="tablareporttelas.php">reporte de telas </a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main-content">
    <div class="header">
      <h1>Econocomercio S.A.C - Sistema Textil</h1>
      <div class="header-controls">
        <button class="theme-toggle" onclick="toggleTheme()">
          <i class="fas fa-moon"></i> Modo Oscuro
        </button>
        <div class="user-dropdown">
          <div class="user-info" onclick="toggleUserDropdown()">
            <?php echo $userEmail; ?> <i class="fas fa-chevron-down"></i>
          </div>
          <div class="user-options">
            <a href="cerrar_sesion.php">Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Tarjetas -->
    <div class="cards">
      <div class="card">
        <h3>Notas de venta</h3>
        <span>S/ <?php echo number_format($notasVenta, 2); ?></span>
      </div>
      <div class="card">
        <h3>Comprobantes</h3>
        <span>S/ <?php echo number_format($comprobantes, 2); ?></span>
      </div>
      <div class="card">
        <h3>Totales</h3>
        <span>S/ <?php echo number_format($totales, 2); ?></span>
      </div>
      <div class="card">
        <h3>Balance</h3>
        <span>S/ <?php echo number_format($balance, 2); ?></span>
      </div>
      <div class="card">
        <h3>Utilidad Neta</h3>
        <span>S/ <?php echo number_format($utilidadNeta, 2); ?></span>
      </div>
      <div class="card">
        <h3>Compras</h3>
        <span>S/ <?php echo number_format($compras, 2); ?></span>
      </div>
    </div>

    <!-- Filtros Adicionales -->
    <div class="filters">
      <hr>
      <h2>Filtros Adicionales</h2>
      <div class="filter-row">
        <label for="establecimiento">Establecimiento:</label>
        <select id="establecimiento">
          <option>Oficina Principal</option>
          <option>ALMACEN LORY CASUAL</option>
          <option>ALMACEN MIA</option>
          <option>ALMACEN KIRA</option>
          <option>ALMACEN - TALLER</option>
        </select>

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
        <h3>Gráfico Notas de Venta</h3>
        <canvas id="notasVentaChart"></canvas>
      </div>
      <div class="chart">
        <h3>Gráfico Comprobantes</h3>
        <canvas id="comprobantesChart"></canvas>
      </div>
      <div class="chart small-pie-chart">
        <h3>Totales</h3>
        <canvas id="totalesChart"></canvas>
      </div>
      <div class="chart">
        <h3>Ganancias / Utilidades</h3>
        <canvas id="gananciasChart"></canvas>
      </div>
      <div class="chart">
        <h3>Compras</h3>
        <canvas id="comprasStatsChart"></canvas>
      </div>
    </div>
  </div>

  <script>
    // Toggle Sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('collapsed');
      const toggleBtn = sidebar.querySelector('.toggle-sidebar i');
      if (sidebar.classList.contains('collapsed')) {
        toggleBtn.classList.remove('fa-chevron-left');
        toggleBtn.classList.add('fa-chevron-right');
      } else {
        toggleBtn.classList.remove('fa-chevron-right');
        toggleBtn.classList.add('fa-chevron-left');
      }
    }

    // Toggle Tema
    function toggleTheme() {
      const body = document.body;
      const themeToggle = document.querySelector('.theme-toggle i');
      body.classList.toggle('dark-mode');
      if (body.classList.contains('dark-mode')) {
        themeToggle.classList.remove('fa-moon');
        themeToggle.classList.add('fa-sun');
        document.querySelector('.theme-toggle').innerHTML = `<i class="fas fa-sun"></i> Modo Claro`;
      } else {
        themeToggle.classList.remove('fa-sun');
        themeToggle.classList.add('fa-moon');
        document.querySelector('.theme-toggle').innerHTML = `<i class="fas fa-moon"></i> Modo Oscuro`;
      }
    }

    // Toggle Submenús
    function toggleSubmenu(element) {
      const submenu = element.querySelector('.submenu');
      const arrow   = element.querySelector('.submenu-arrow');
      if (submenu.style.display === 'block') {
        submenu.style.display = 'none';
        arrow.classList.remove('rotated');
        element.classList.remove('active');
      } else {
        submenu.style.display = 'block';
        arrow.classList.add('rotated');
        element.classList.add('active');
      }
    }

    // Toggle dropdown de usuario
    function toggleUserDropdown() {
      const userOptions = document.querySelector('.user-options');
      userOptions.style.display = userOptions.style.display === 'block' ? 'none' : 'block';
    }

    // Datos para los gráficos
    const meses = <?php echo json_encode($meses); ?>;
    const notasVentaData = <?php echo json_encode($notasVentaData); ?>;
    const comprobantesData = <?php echo json_encode($comprobantesData); ?>;
    const totalesData = <?php echo json_encode($totalesData); ?>;
    const gananciasData = <?php echo json_encode($gananciasData); ?>;
    const comprasLabels = <?php echo json_encode($comprasLabels); ?>;
    const comprasData   = <?php echo json_encode($comprasData); ?>;

    // Gráfico Notas de Venta
    const notasVentaCtx = document.getElementById('notasVentaChart').getContext('2d');
    new Chart(notasVentaCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Notas de Venta',
          data: notasVentaData,
          backgroundColor: '#6c5ce7',
          borderColor: '#6c5ce7',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico Comprobantes
    const comprobantesCtx = document.getElementById('comprobantesChart').getContext('2d');
    new Chart(comprobantesCtx, {
      type: 'line',
      data: {
        labels: meses,
        datasets: [{
          label: 'Comprobantes',
          data: comprobantesData,
          borderColor: '#6c5ce7',
          borderWidth: 2,
          fill: false
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico Totales (pie chart)
    const totalesCtx = document.getElementById('totalesChart').getContext('2d');
    new Chart(totalesCtx, {
      type: 'pie',
      data: {
        labels: ['Notas de Venta', 'Comprobantes', 'Otros'],
        datasets: [{
          label: 'Totales',
          data: totalesData,
          backgroundColor: ['#6c5ce7', '#a29bfe', '#dfe6e9'],
          borderWidth: 1
        }]
      }
    });

    // Gráfico Ganancias/Utilidades (barras)
    const gananciasCtx = document.getElementById('gananciasChart').getContext('2d');
    new Chart(gananciasCtx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [{
          label: 'Ganancias / Utilidades',
          data: gananciasData,
          backgroundColor: '#00b894',
          borderColor: '#00b894',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });

    // Gráfico Compras (barras)
    const comprasStatsCtx = document.getElementById('comprasStatsChart').getContext('2d');
    new Chart(comprasStatsCtx, {
      type: 'bar',
      data: {
        labels: comprasLabels,
        datasets: [{
          label: 'Compras',
          data: comprasData,
          backgroundColor: ['#0984e3', '#74b9ff', '#a29bfe'],
          borderColor: '#0984e3',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });
  </script>
</body>
</html>

''''''<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo BASE_URL; ?>"> <img alt="image" src="<?php echo BASE_URL; ?>assets/img/icono_diresa.png" class="header-logo" /> <span class="logo-name">DIRESA TACNA</span>
      </a>
    </div>
    <ul class="sidebar-menu">
      <?php
      if ($_SESSION['nivel'] !== 5) {
        echo ' <li class="menu-header">General</li>';
      }
      // 1 admin            alextm
      // 2 jefe oficina     alextm1
      // 3 Visualizador     alextm2
      // 4 portero          alextm3
      // 100 mio


      // <!-- Administrables -->
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Administrables</span></a>
                <ul class="dropdown-menu">';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Trabajador">Trabajador</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Direccion">Dirección</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Equipo">Equipo</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Horario">Horario</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Cargo">Cargo</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Regimen">Régimen</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Usuario">Usuarios</a></li>';
        echo '</ul>
              </li>';
      }
      //  <!-- Asistencia -->
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown">
        <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Asistencia</span></a>
        <ul class="dropdown-menu">';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Importar" class="nav-link"><i data-feather="upload"></i><span>Importar</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Asistencia" class="nav-link"><i data-feather="file-text"></i><span>Hoja de Asistencia</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Asistencia/ver" class="nav-link"><i data-feather="file-text"></i><span>Mis Asistencias</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Festividades/ver" class="nav-link"><i data-feather="file-text"></i><span>Calendario</span></a></li>';
        echo '</ul>
      </li>';
      }
      // <!-- Jefe de Oficina -->
      if ($_SESSION['nivel'] == 2) {
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Boleta/RevisarBoletas" class="nav-link"><i data-feather="monitor"></i><span>Revisar Boletas</span></a></li>';
      }
      // <!-- Visualizador -->
      if ($_SESSION['nivel'] == 3) {
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Asistencia/ver" class="nav-link"><i data-feather="monitor"></i><span>Asistencia</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Festividades/ver" class="nav-link"><i data-feather="calendar"></i><span>Calendario</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Boleta/MisBoletas" class="nav-link"><i data-feather="file"></i><span>Boleta</span></a></li>';
      }
      // <!-- Portero -->
      if ($_SESSION['nivel'] == 4) {
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Boleta/Porteria" class="nav-link"><i data-feather="monitor"></i><span>Boletas</span></a></li>';
      }
      // <!-- Portero -->
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Boletas</span></a>
                <ul class="dropdown-menu">';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta" class="nav-link"><i data-feather="file-text"></i><span>Boletas</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/RevisarBoletas" class="nav-link"><i data-feather="file-text"></i><span>Revisar Boletas</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/Porteria" class="nav-link"><i data-feather="file-text"></i><span>Portería</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/MisBoletas" class="nav-link"><i data-feather="file-text"></i><span>Mis Boletas</span></a> ';
        echo '</ul>
        </li> ';
      }
      // <!-- Reportes -->
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Reportes</span></a>
                <ul class="dropdown-menu">';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Reporte/Mensual" class="nav-link"><i data-feather="file-text"></i><span>Mensual</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Reporte/Fechas" class="nav-link"><i data-feather="file-text"></i><span>Entre Fechas</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Reporte/Kardex" class="nav-link"><i data-feather="file-text"></i><span>Kardex</span></a></li>';
        echo '</ul>
        </li>';
      }
      // <!-- Configuración y Soporte -->
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Configuracion/" class="nav-link"><i data-feather="settings"></i><span>Configuración</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Log/" class="nav-link"><i data-feather="archive"></i><span>Log</span></a></li>';
        echo '<li class="dropdown"><a href="' . BASE_URL . 'Soporte/" class="nav-link"><i data-feather="info"></i><span>Soporte</span></a></li>';
      }
      ?>
    </ul>
  </aside>
</div>
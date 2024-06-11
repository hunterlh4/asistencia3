<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo BASE_URL; ?>"> <img alt="image" src="<?php echo BASE_URL; ?>assets/img/icono_diresa.png" class="header-logo" /> <span class="logo-name">DIRESA TACNA</span>
      </a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">General</li>

      <?php
      // 1 admin            alextm
      // 2 jefe oficina     alextm1
      // 3 vizualizador     alextm2
      // 4 portero          alextm3
      // 100 mio
      //Solo el admin(1) y jefes de oficinas puede visualizar las tardanzas 
      if ($_SESSION['nivel'] == 5 || $_SESSION['nivel'] == 100) {
        echo '<li class="dropdown"> 
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book-open"></i><span>Mis Asistencias</span></a>
                  <ul class="dropdown-menu">
                    <a href="" class="nav-link"><i data-feather="chevron-right"></i><span>Resumido</span></a> 
                    <a href="" class="nav-link"><i data-feather="chevron-right"></i><span>Detallado</span></a>
                    <a href="" class="nav-link"><i data-feather="chevron-right"></i><span>Kardex</span></a>  
                  </ul>
                </li>';
        echo '<li class="dropdown"> <a href="" class="nav-link"><i data-feather="file-text"></i><span>Mis Boletas</span></a></li>';
        echo '<li class="dropdown"> <a href="" class="nav-link"><i data-feather="file-text"></i><span>Mis Datos</span></a></li>';
      }
      //Solo un Administrador(1) puede administrar usuarios
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 100) {
        //echo '<li class="menu-header">Administrables</li>' ;
        echo '<li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Administrables</span></a>
              <ul class="dropdown-menu">';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Trabajador">Trabajador</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Direccion">Direccion</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Equipo">Equipo</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Horario">Horario</a></li>';
        // echo '<li><a class="nav-link" href="'.BASE_URL.'Licencia">Licencia</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Cargo">Cargo</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Regimen">Regimen</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Usuario">Usuarios</a></li>';
        echo '<li><a class="nav-link" href="' . BASE_URL . 'Festividades">Festividades</a></li>
              </ul>
            </li>';
        // echo '<li class="dropdown"> <a href="' .BASE_URL . 'Usuario" class="nav-link"><i data-feather="users"></i><span>Administrar Usuarios</span></a> </li> ';
      }
      ?>

      <!-- <li class="menu-header">Asistencia</li> -->
      <?php
      echo '<li class="dropdown">
        <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Asistencia</span></a>
      <ul class="dropdown-menu">';
      if ($_SESSION['nivel'] == 1) {
        // echo '  <li class="menu-header">Excel</li>';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Importar" class="nav-link"><i data-feather="upload"></i><span>Importar</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Asistencia" class="nav-link"><i data-feather="file-text"></i><span>Hoja de Asistencia</span></a> </li> ';
      }
      echo '<li class="dropdown"> <a href="' . BASE_URL . 'Asistencia/ver" class="nav-link"><i data-feather="file-text"></i><span>Mis Asistencias</span></a> </li> ';
      echo '<li class="dropdown"> <a href="' . BASE_URL . 'Festividades/ver" class="nav-link"><i data-feather="file-text"></i><span>Calendario</span></a> </li> ';
      echo '</ul>
      </li> ';
     ?>
      <!-- <li class="dropdown"> <a href="tab_auth_salida_new.php" class="nav-link"><i data-feather="book-open"></i><span>Porteria</span></a> </li> -->
      <!-- <li class="menu-header">Boletas</li> -->
      
      <?php

      // echo '  <li class="menu-header">Excel</li>';
      echo '<li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Boletas</span></a>
                  <ul class="dropdown-menu">';
      if ($_SESSION['nivel'] == 1) {
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta" class="nav-link"><i data-feather="file-text"></i><span>Boletas</span></a> </li> ';
      }
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2) {
        // echo '<li class="dropdown"> <a href="' .BASE_URL . 'Boletas/Buzon" class="nav-link"><i data-feather="file-text"></i><span>Boletas Autorizadas</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/RevisarBoletas" class="nav-link"><i data-feather="file-text"></i><span>Revisar Boletas</span></a> </li> ';
      }
      if ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 4) {
        // echo '<li class="dropdown"> <a href="' .BASE_URL . 'Boletas/Buzon" class="nav-link"><i data-feather="file-text"></i><span>Boletas Autorizadas</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/Porteria" class="nav-link"><i data-feather="file-text"></i><span>Porteria</span></a> </li> ';
      }
      echo '<li class="dropdown"> <a href="' . BASE_URL . 'Boleta/MisBoletas" class="nav-link"><i data-feather="file-text"></i><span>Mis Boletas</span></a> ';
      echo '</ul>
      </li> ';
      ?>
      <!-- <li class="menu-header">Reportes</li> -->
      <?php
      if ($_SESSION['nivel'] == 1) {
        // echo '  <li class="menu-header">Excel</li>';
        echo '<li class="dropdown">
                  <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Reportes</span></a>
                  <ul class="dropdown-menu">';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Reporte" class="nav-link"><i data-feather="file-text"></i><span>General</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Reporte/Trabajador" class="nav-link"><i data-feather="file-text"></i><span>Trabajador</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Reporte/Direccion" class="nav-link"><i data-feather="file-text"></i><span>Direccion</span></a> 
                  </ul>
                  </li> ';
      }
      ?>

      <?php
      if ($_SESSION['nivel'] == 1) {
        // echo '  <li class="menu-header">Excel</li>';
        echo '<li class="dropdown">';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Configuracion/" class="nav-link"><i data-feather="settings"></i><span>Configuracion</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Log/" class="nav-link"><i data-feather="archive"></i><span>Log</span></a> </li> ';
        echo '<li class="dropdown"> <a href="' . BASE_URL . 'Soporte/" class="nav-link"><i data-feather="info"></i><span>Soporte</span></a> 
               
                  </li> ';
      }
      ?>


      <?php
      //Portero y jefe de area (4) no tiene acceso al buscador
      if ($_SESSION['nivel'] == 100 and $_SESSION['nivel'] == 100) {
        // echo '<li class="dropdown"> <a href="tab_search_new.php" class="nav-link"><i data-feather="search"></i><span>Búsqueda General</span></a></li>';
        echo '<li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Búsqueda General</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="tab_search_new.php">Búsqueda de asistencia</a></li>
                <li><a class="nav-link" href="tab_search_detallado.php">Búsqueda detallada</a></li>
              </ul>
            </li>';
      } ?>

    </ul>
  </aside>
</div>
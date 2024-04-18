<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="<?php echo BASE_URL; ?>/views/home.php"> <img alt="image" src="<?php echo BASE_URL; ?>/assets/img/icono_diresa.png" class="header-logo" /> <span
            class="logo-name">DIRESA TACNA</span>
        </a>
      </div>
      <ul class="sidebar-menu">
        <li class="menu-header">General</li>
        <?php
        //Portero y jefe de area (4) no tiene acceso al buscador
        if($data['nivel'] != 4 AND$data['nivel'] != 2) {
        // echo '<li class="dropdown"> <a href="tab_search_new.php" class="nav-link"><i data-feather="search"></i><span>Búsqueda General</span></a></li>';
        echo '<li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Búsqueda General</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="tab_search_new.php">Búsqueda de asistencia</a></li>
                <li><a class="nav-link" href="tab_search_detallado.php">Búsqueda detallada</a></li>
              </ul>
            </li>';
        }
        ?>
        <li class="dropdown"> <a href="tab_auth_salida_new.php" class="nav-link"><i data-feather="book-open"></i><span>Porteria</span></a> </li>
        <?php 
        if($data['nivel'] < 3) {
            echo '<li class="dropdown"> <a href="tab_hoja_asis_new.php" class="nav-link"><i data-feather="list"></i><span>Hojas de Asistencia</span></a></li>';
        }
        ?>
        <?php
        //Solo el admin(1) puede alimentar el sistema 
        if($data['nivel'] == 1) {
            echo '<li class="dropdown"> <a href="tab_import_new.php" class="nav-link"><i data-feather="upload"></i><span>Importar CSV</span></a></li>';
        }
        ?>
        
        <?php
        //Solo el admin(1) puede alimentar el sistema 
        if($data['nivel'] <= 3) {
            echo '<li class="dropdown"> <a href="tab_boleta_auth.php" class="nav-link"><i data-feather="file-text"></i><span>Boletas de Autorización</span></a></li>';
        }
        ?>
        
        <li class="menu-header">Otros</li>
        <?php
        //Solo un Administrador(1) puede administrar roles de turnos
        if($data['nivel'] == 1 OR $data['nivel'] == 2) {
            echo '<li class="dropdown"> <a href="tab_rol_turnos.php" class="nav-link"><i data-feather="bookmark"></i><span>Rol de Turnos</span></a> </li>';
        }
        ?>
        
        <?php
        //Solo el admin(1) y jefes de oficinas puede visualizar las tardanzas 
        if($data['nivel'] <= 2) {
            echo '<li class="dropdown"> <a href="tab_tardanzas.php" class="nav-link"><i data-feather="watch"></i><span>Tardanzas</span></a></li>';
        }
        ?>
        
        
        <li class="menu-header">Perfil</li>
        <li class="dropdown"> <a href="<?php echo BASE_URL . 'admin/salir'; ?>" class="nav-link"><i data-feather="user-check"></i><span>Mi Perfil</span></a> </li>
        <?php
        //Solo un Administrador(1) puede administrar usuarios
        if($data['nivel'] == 1) {
            echo '<li class="dropdown"> <a href="users.php" class="nav-link"><i data-feather="users"></i><span>Administrar Usuarios</span></a> </li> ';
        }
        ?>
      </ul>
    </aside>
</div>
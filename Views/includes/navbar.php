<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
      <ul class="navbar-nav mr-3">
        <li>
            <!--data-toggle="sidebar"-->
            <a href="#"  class="nav-link nav-link-lg collapse-btn" id="sidebar_click"><i data-feather="align-justify"></i></a>
	    </li>
        <li>
            <a href="#" class="nav-link nav-link-lg fullscreen-btn"><i data-feather="maximize"></i></a>
        </li>
      </ul>
    </div>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
            <img alt="image" src="<?php echo BASE_URL; ?>assets/img/icono_diresa.png"
            class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hola,
              <?php echo $_SESSION['nombre'].' '.$_SESSION['apellido'];?>
                </div>
               
              <a href="<?php echo BASE_URL . 'admin/perfil'; ?>" class="dropdown-item has-icon"><i class="far fa-user"></i> Perfil</a> 
              <a href="<?php echo BASE_URL . 'admin/mensajes'; ?>" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i> Actividades </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo BASE_URL . 'admin/salir'; ?>" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Salir
              </a>
            </div>
        </li>
    </ul>
</nav>
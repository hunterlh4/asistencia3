<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    <?php echo $data['title']; ?>
  </title>
  <script src="<?php echo BASE_URL; ?>/assets/js/modulos/iconos.js" crossorigin="anonymus"></script>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/loginstyle.css" />
  <link rel="icon" type="png" href="<?php echo BASE_URL; ?>/assets/img/icono_diresa.png">
</head>

<div class="container">
  <div class="forms-container">
    <div class="signin-signup">
      <form role="form" class="sign-in-form" id="formulario" autocomplete="off">
          <img class="logo" src="<?php echo BASE_URL; ?>/assets/img/icono_diresa.png" alt=""><br>
          <h2 class="title">Sistema de Control de Asistencia </h2> <br><br>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" id="username" name="username" placeholder="Usuario" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            
            <input type="password" id="password" name="password" placeholder="Contraseña" />
          </div>
          <div class="text-center">
            <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Ingresar</button>
          </div>
      </form>
      <form action="login.php" method="POST" class="sign-up-form">
        <h2 class="title">Manual</h2>

      </form>
    </div>
  </div>

  <div class="panels-container">
    <div class="panel left-panel">
      <div class="content">
        <h3>Manual de Usuario</h3>
        <p>
          Necesitas informacion sobre como usar el sistema. Aprende todo aqui!
        </p>
        <button class="btn transparent" id="sign-up-btn">
          Descarga Manual
        </button>
      </div>
      <img src="<?php echo BASE_URL; ?>/assets/img/login_time.svg" class="image" alt="" />
    </div>
    <div class="panel right-panel">
      <div class="content">
        <h3>Ingresa al sistema</h3>
        <p>
          Ingresa tu correo afiliado donde te llegará un mensaje de sislega, dale click al enlace y listo!
        </p>
        <button class="btn transparent" id="sign-in-btn">
          Ingresar
        </button>
      </div>
      <img src="<?php echo BASE_URL; ?>/assets/img/email.svg" class="image" alt="" />
    </div>
  </div>
</div>




</script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->

<script>
  const base_url = '<?php echo BASE_URL; ?>';
  
</script>
<script src="<?php echo BASE_URL; ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/login.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/script.js"></script>
</body>

</html>
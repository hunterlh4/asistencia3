<?php include_once './Views/includes/header.php'; ?>
<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <?php
      include './views/includes/navbar.php';
      include './views/includes/sidebarnew.php';
      ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4 p-4 shadow-lg p-3 rounded">
                    <div class="row align-items-center">
                      <div class="col-md-4">
                        <div class="banner-img">
                          <img src="<?php echo BASE_URL; ?>/assets/img/Scenes05-removebg-preview.png" alt="imagen de un calendario" width="100%" height="auto">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="card-content">
                          <h3 class="card-title text-danger text-center font-weight-bold">¡BIENVENIDO AL SISTEMA DE CONTROL DE ASISTENCIA DE LA DIRESA TACNA! </h3>
                          <?php 
                          // $usuario_nivel ='';
                          // if ($data['nivel'] === 1) {
                          //   $usuario_nivel = "Administrador";
                          // } else if ($data['nivel'] === 2) {
                          //     $usuario_nivel = "Jefe";
                          // } else if ($data['nivel'] === 3) {
                          //     $usuario_nivel = "Vizualizador";
                          // } else if ($data['nivel'] === 4) {
                          //     $usuario_nivel = "Portero";
                          // } else if ($data['nivel'] === 5) {
                          //     $usuario_nivel = "Sin permisos";
                          // } else if ($data['nivel'] === 100) {
                          //     $usuario_nivel = "Super Admin";
                          // } else {
                          //     $usuario_nivel = "Nivel no definido";
                          // }
                          ?>
   
                          <h4 class="font-weight-bold text-center"> <?php echo  $data['nombre'] . ' ' .  $data['apellido'] ;  ?></h4>
                          
                          <?php 
                           
                          if( $data['nivel'] ==1 || $data['nivel'] ==2 ){
                            echo ' 
                            <p style="text-align: justify;">Como usuario del área del Sistema de Control de Asistencia usted podra visualizar el ingreso y salida que marcan los relojes, así como evaluar y generar los reportes.</p>
                             <p style="text-align: justify;">Para iniciar a REVISAR los horarios de los trabajadores de clic al siguiente botón.</p>
                            <a type="button" class="btn btn-primary btn-lg text-center" href="'.BASE_URL.'Usuarios"><i class="far fa-hand-point-right"></i> INICIAR</a>';
                          }else{
                            // echo '<a type="button" class="btn btn-primary btn-lg text-center" href="tab_search_new.php"><i class="far fa-hand-point-right"></i> INICIAR</a>';
                          }
                          ?>
                         
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="row">
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                          <div class="card-content">
                            <h5 class="font-15">Trabajo pendiente</h5>
                            <h2 class="mb-3 font-18">258</h2>
                            <!-- <p class="mb-0"><span class="col-green">10%</span> Incremento</p> -->
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                          <div class="banner-img">
                            <img src="<?php echo BASE_URL; ?>/assets/img/banner/1.png" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                          <div class="card-content">
                            <h5 class="font-15">Tardanzas del día</h5>
                            <h2 class="mb-2 font-18" id="num_tardanzas"> 0 </h2>
                            <!-- <a type="button" class="btn btn-primary text-center mt-3" href="tab_tardanzas.php"><i class="fa fa-stopwatch"></i> Visualizar</a> -->
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                          <div class="banner-img">
                            <img src="<?php echo BASE_URL; ?>/assets/img/banner/3.png" alt="tardanzas">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                          <div class="card-content">
                            <h5 class="font-15">Horas Trabajadas</h5>
                            <h2 class="mb-3 font-18">100,000</h2>
                            <!-- <p class="mb-0"><span class="col-green">128,589</span> -->
                            <!-- 128,589</p> -->
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                          <div class="banner-img">
                            <img src="<?php echo BASE_URL; ?>/assets/img/banner/2.png" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                  <div class="card-statistic-4">
                    <div class="align-items-center justify-content-between">
                      <div class="row ">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                          <div class="card-content">
                            <h5 class="font-15">Dias de Vacaciones</h5>
                            <h2 class="mb-3 font-18">48,697</h2>
                            <!-- <p class="mb-0"><span class="col-green">42%</span> Incremento</p> -->
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                          <div class="banner-img">
                            <img src="<?php echo BASE_URL; ?>/assets/img/banner/4.png" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!--<section class="section">-->

        <!--</section>-->
        <?php // include './views/includes/sidebar-config.html'; ?>
      </div>
      <?php include './views/includes/footer.php'; ?>
    </div>
  </div>

  <?php include './views/includes/script_new.php' ?>
  <script>
    // $(document).ready(function(){
    //   $(".collapse-btn").click();
    // });
  </script>
</body>

</html>
<?php include_once './Views/includes/header.php'; ?>
<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <?php
      include './Views/includes/navbar.php';
      include './Views/includes/sidebarnew.php';
      ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mb-0 mt-3">
                <h3 class="font-weight-bolder"><i class="fa fa-briefcase"></i> Trabajadores</h3>
                <div class="ml-auto">
                  <button class="btn btn-lg btn-outline-primary rounded-0" type="button" onclick=goBack()>Volver</button>
                  <button class="btn btn-lg btn-outline-primary rounded-0" type="button" id="nuevo_registro">Nuevo</button>
                </div>
              </div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover align-center" style="width:100%;" id="table-alex">
                        <thead>
                          <tr>
                            <th class="text-center"># </th>
                            <th>DNI</th>
                            <th>nombre</th>
                            <th>Direccion</th>
                            <th>Cargo </th>
                            <th>Regimen </th>
                            <!-- <th>Particular</th> -->
                            <th>Estado</th>
                            <th>Accion</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
      <?php include './Views/includes/footer.php'; ?>
    </div>
  </div>

  <!-- MODAL -->
<div id="nuevoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
         
          <h5 class="modal-title" id="titleModal"></h5>
          <button type="button" onclick=cerrarModal() class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form form id="formulario" class="needs-validation" novalidate="" method="POST" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" id="id" name="id">
          <!-- AQUI EMPIEZA -->
         

            <!-- aqui termina -->
              <p><b>Datos Personales</b></p> 
              <hr> 
          <div class="row">
            <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label for="dni">DNI</label>  
                <!-- <button type="button" onclick=consultar()>Consultar</button> -->
                <!-- <input type="text" class="form-control" id="dni" name="dni" required> -->
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-address-card"></i>
                      </div>
                    </div>
                  <input type="text" class="form-control" placeholder="DNI" name="dni" id="dni" minlength="8" maxlength="8" required>

                 
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="button" onclick=consultar()> <i class="fas fa-search"></i></button>
                        </div>
                  </div>
              </div>
            </div>
            
            <!--  -->
            <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label>Telefono</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                    <i class="fas fa-phone"></i>
                    </div>
                  </div>  
                  <input type="text" class="form-control" placeholder="Telefono" name="telefono" id="telefono" minlength="11" maxlength="11">
                </div>
              </div>
            </div>
            <!--  -->
            <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label>Tarjeta</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-address-card"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control" placeholder="Tarjeta" name="tarjeta" id="tarjeta" minlength="10" maxlength="18">
                </div>
              </div>
            </div>
            

          <!-- </div>

          <div class="row"> -->
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="nombre">Nombre</label>
                  <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-address-card"></i>
                    </div>
                  </div>
                  <input type="text" class="form-control" placeholder="Nombre" name="nombre" id="nombre" minlength="10" maxlength="50" required>
                </div>

                </div>
              </div>

              <!--  -->
              <div class="col-md-6 col-sm-6">
              <div class="form-group">
              <label>Correo</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-address-card"></i>
                    </div>
                  </div>
                  <input type="email" class="form-control" placeholder="Correo" name="email" id="email" minlength="5" maxlength="40">
                </div>
              </div>
            </div>
              
            <!--  -->
          
          <!-- </div>
          <div class="row"> -->

            <div class="col-md-6 col-sm-6">
              <div class="form-group">
                <label>Cumpleaños</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-calendar"></i>
                    </div>
                  </div>
                  <input type="Date" class="form-control" placeholder="YYYY/MM/DD" name="nacimiento" id="nacimiento">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Sexo</label>
                <div class="input-group">
                  <div class="col-sm-9 d-flex align-items-center mt-1">
                    <div class="custom-control custom-radio mr-3">
                      <input type="radio" id="radio-hombre" value='H' name="sexo" class="custom-control-input" checked>
                      <label class="custom-control-label" for="radio-hombre">Hombre</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="radio-mujer" value='M' name="sexo" class="custom-control-input">
                      <label class="custom-control-label" for="radio-mujer">Mujer</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <p><b>Datos de Trabajo</b></p>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="direccion">Direccion</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-address-card"></i>
                      </div>
                    </div>
                    <select class="form-control" id="direccion" name="direccion" required>
                    <option value="" selected>Selecciona una Direccion</option>
                    <!-- Opciones para la dirección -->
                  </select>
                </div>

              </div>
            </div>
          <!-- </div>

          <div class="row"> -->
            <div class="col-md-4 col-sm-6">
              <div class="form-group">
              <label>Regimen</label>
                <div class="input-group">
                 
                    <select class="form-control" id="regimen" name="regimen" required>
                    <option value="" selected>Selecciona un Regimen</option>
                    <!-- Opciones para la dirección -->
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6">
              <div class="form-group">
                <label>Cargo</label>
                <div class="input-group">
                 
                    <select class="form-control" id="cargo" name="cargo" required>
                    <option value="" selected>Selecciona un Cargo</option>
                    <!-- Opciones para la dirección -->
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-6">
            <div class="form-group">
                  <label>Modalidad de Trabajo</label>
                  <div class="input-group">
                   
                    <select class="form-control" id="modalidad" name="modalidad" required>
                    <option value="" selected>Selecciona una Modalidad</option>
                    <option value="Trabajo Presencial">Trabajo Presencial</option>
                    <option value="Trabajo Remoto" >Trabajo Remoto</option>
                    <option value="Licencia con Goce de Haber" >Licencia con Goce de Haber</option>
                    <option value="Vacaciones" >Vacaciones</option>
                    <!-- Opciones para la dirección -->
                  </select>
                  </div>
                </div>
                
              </div>
          
          <!-- </div>
          <div class="row"> -->

          <div class="col-md-6 col-sm-6">
            <div class="form-group">
                  <label>Horario</label>
                  <div class="input-group">
                   
                    <select class="form-control" id="horario" name="horario" required>
                    <option value="" selected>Selecciona un Horario</option>
                    <!-- Opciones para la dirección -->
                  </select>
                  </div>
                </div>
                
              </div>
           
              <div class="col-md-6">
              
                  <div class="form-group" id="estado-grupo">
                  <label>Estado</label>
                    <div class="input-group">
                      <div class="col-sm-9 d-flex align-items-center mt-1">
                        <div class="custom-control custom-radio mr-3">
                          <input type="radio" id="radio-true" value='Activo' name="estado" class="custom-control-input" checked>
                          <label class="custom-control-label" for="radio-true">Activo</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input type="radio" id="radio-false" value='Inactivo' name="estado" class="custom-control-input">
                          <label class="custom-control-label" for="radio-false">Inactivo</label>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
            </div>

       <!-- FIN DEL ROW -->
          </div>

              <div class="modal-footer bg-white">
                <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                <button class="btn btn-danger" onclick=cerrarModal() class="close" data-dismiss="modal" aria-label="Close">Cancelar</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!--MODAL - NUEVO USARIO-->

  <!-- MODAL FIN -->
  <?php include './Views/includes/script_new.php' ?>

  </html>
  <script src="<?php echo BASE_URL; ?>/assets/js/modulos/trabajador.js"></script>

  <script>
    const base_url = '<?php echo BASE_URL; ?>';
  </script>
</body>

</html>
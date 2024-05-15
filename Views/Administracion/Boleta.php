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

                    <h3 class="font-weight-bolder"><i class="fa fa-briefcase"></i> Boletas</h3>
                    <button class="btn btn-lg btn-outline-primary rounded-0 " type="button" id="nuevo_registro">Nuevo</button>
                  </div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped table-hover text-center" style="width:100%;" id="table-alex">
                        <thead>
                          <tr>
                            <th># </th>
                            <th>Numero</th>
                            <th>Solicitante</th>
                            <th>Fecha</th>
                            <!-- <th>Fin</th> -->
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>tramite</th>
                            <!-- <th>estado</th> -->
                            <th>accion</th>
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
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="solicitante">Solicitante</label>
                  <select class="form-control" id="solicitante" name="solicitante" required>
                  <option value="">Seleccione un Solicitante</option>
                    
                    <!-- Opciones del select -->
                  </select>
                </div>
                </div>
                <div class="col-md-6 col-sm-6">
                <div class="form-group">
                  <label for="aprobador">Aprobador</label>
                  <select class="form-control" id="aprobador" name="aprobador" required>
                  <option value="">Seleccione un aprobador</option>
                    <!-- Opciones del select -->
                  </select>
                </div>
                </div>
                <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label for="fecha_inicio">Fecha de Inicio</label>
                  <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                </div>
                <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label for="fecha_fin">Fecha de Fin</label>
                  <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" >
                </div>
                </div>
                <div class="col-md-3 col-sm-6">

                <div class="form-group">
                  <label for="hora_salida">Hora de Salida</label>
                  <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
                </div>
                </div>
                <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label for="hora_entrada">Hora de Retorno</label>
                  <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" required>
                </div>
                </div>
                <!-- <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label for="duracion">Duración</label>
                  <input type="time" class="form-control" id="duracion" name="duracion" required>
                </div>
                </div> -->
                <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="razon">Razón</label>
                  <select class="form-control" id="razon" name="razon" required>
                    <option value="">Seleccione una Razon</option>
                    <option value="Comsion de Servicio">Comsion de Servicio</option>
                    <option value="Compensacion Horas">Compensacion Horas</option>
                    <option value="Motivos Particulares">Motivos Particulares</option>
                    <option value="Enfermedad">Enfermedad</option>
                    <option value="ESSALUD">ESSALUD</option>
                    <option value="Otra">OTro</option>
                    <!-- Opciones del select -->
                  </select>
                </div>
                </div>
                <div class="col-md-4 col-sm-6">

                <div class="form-group">
                  <label for="otra_razon">Otra Razón</label>
                  <input type="text" class="form-control" id="otra_razon" name="otra_razon">
                </div>
                </div>
               <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="observaciones">Observaciones</label>
                  <input type="text" class="form-control" id="observaciones" name="observaciones">
                </div>
                </div>
                <!-- <div class="col-md-4 col-sm-6">
                <div class="form-group" id="estado-grupo">
                  <label>Estado</label>
                  <div class="input-group">
                    <div class="col-sm-9 d-flex align-items-center">
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
                </div> -->
                <!--  -->

                <!--  -->
                <div class="modal-footer bg-white col-md-12 col-sm-12">
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
  <script src="<?php echo BASE_URL; ?>assets/js/modulos/boleta.js"></script>

  <script>
    const base_url = '<?php echo BASE_URL; ?>';
  </script>
</body>

</html>
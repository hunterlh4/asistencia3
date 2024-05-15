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

                    <h3 class="font-weight-bolder col-md-3"><i class="fa fa-briefcase"></i> Busqueda</h3>
                    <!-- <button class="btn btn-lg btn-outline-primary rounded-0 " type="button" id="nuevo_registro">Nuevo</button> -->


                    <!-- <label>Select2</label> -->
                    <select class="form-control select2 col-md-9" id="trabajador" required>
                      <option value="" selected>Selecciona un trabajador</option>

                    </select>

                  </div>

                  <div class="card-body">
                    <!-- <div class="table-responsive">
                      <table class="table table-striped table-hover" style="width:100%;" id="table-alex">
                        <thead>
                          <tr>
                            <th class="text-center"># </th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Estado</th>
                            <th>accion</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div> -->

                    <div class="fc-overflow">

                      <div id="myEvent">

                      </div>

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
            <input type="hidden" id="trabajador_id" name="trabajador_id">
            <div class="row">
              <div class="col-12">
                <div class="table-responsive text-center">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th scope="col">Entrada</th>
                        <th scope="col">Salida</th>
                        <th scope="col">Total</th>
                        <th scope="col">Total Horario</th>
                        <th scope="col">Tardanza</th>
                        <th scope="col">Cantidad T.</th>
                        <th scope="col">Licencia</th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td name="entrada" id="entrada"></td>
                        <td name="salida" id="salida"></td>
                        <td name="total_reloj" id="total_reloj"></td>
                        <td name="total_horario" id="total_horario"></td>
                        <td name="tardanza" id="tardanza"></td>
                        <td name="tardanza_cantidad" id="tardanza_cantidad"></td>
                        <td name="licencia" id="licencia"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div>



                  <div class="col-12">
                    <div class="table-responsive text-center">
                      <table class="table table-sm">
                        <thead>
                          <tr>

                            <th scope="col">R1</th>
                            <th scope="col">R2</th>
                            <th scope="col">R3</th>
                            <th scope="col">R4</th>
                            <th scope="col">R5</th>
                            <th scope="col">R6</th>
                            <th scope="col">R7</th>
                            <th scope="col">R8</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>

                            <td name="reloj_1" id="reloj_1"></td>
                            <td name="reloj_2" id="reloj_2"></td>
                            <td name="reloj_3" id="reloj_3"></td>
                            <td name="reloj_4" id="reloj_4"></td>
                            <td name="reloj_5" id="reloj_5"></td>
                            <td name="reloj_6" id="reloj_6"></td>
                            <td name="reloj_7" id="reloj_7"></td>
                            <td name="reloj_8" id="reloj_8"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div>
                    </div>
                    <div id="resultado">
                      <!-- Aquí se insertará el resultado generado dinámicamente -->
                    </div>
                    <!-- <div class="row">
                      <hr>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="numero">Boleta N° <span>000012</span></label>

                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="aprobado_por">Aprobado por:</label>
                          <input type="text" class="form-control" placeholder="Aprobado por" name="aprobado_por" id="aprobado_por" required>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="fecha_inicio">Desde:</label>
                          <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="fecha_fin">Hasta:</label>
                          <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="hora_salida">Salida:</label>
                          <input type="time" class="form-control" name="hora_salida" id="hora_salida" required>
                        </div>
                      </div>

                      <div class="col-4">
                        <div class="form-group">
                          <label for="hora_entrada">Entrada:</label>
                          <input type="time" class="form-control" name="hora_entrada" id="hora_entrada" required>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="duracion">Duración:</label>
                          <input type="time" class="form-control" name="duracion" id="duracion" required>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="razon">Razón:</label>
                          <select class="form-control" name="razon" id="razon">
                            <option value="invalidez">invalidez</option>
                            <option value="2">Razón 2</option>
                            <option value="3">Razón 3</option>
                            <option value="4">Razón 4</option>
                            <option value="5">Razón 5</option>
                            <option value="6">Otro</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group" id="otra_razon" style="display: none;">
                          <label for="otra_razon_texto">Otra razón:</label>
                          <input type="text" class="form-control" name="otra_razon_texto" id="otra_razon_texto">
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="observaciones">Observaciones:</label>
                          <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                        </div>
                      </div>
                    </div> -->


                    <!-- <div class="form-group">
              <label>licencia:</label>
              <span name="licencia" id="licencia"></span>
            </div>
            <div class="form-group">
              <label>entrada:</label>
              <span name="entrada" id="entrada"></span>

            </div>
            <div class="form-group">
              <label>salida</label>
              <span name="salida" id="salida"></span>

            </div>
            <div class="form-group">
              <label>total_reloj</label>
              <span name="total_reloj" id="total_reloj"></span>

            </div>
            <div class="form-group">
              <label>tardanza</label>
              <span name="tardanza" id="tardanza"></span>
 
            </div>
            <div class="form-group">
              <label>tardanza_cantidad</label>
              <span name="tardanza_cantidad" id="tardanza_cantidad"></span>
              
            </div>
            <div class="form-group">
              <label>reloj_1</label>
              <span name="reloj_1" id="reloj_1"></span>
            </div>
            <div class="form-group">
              <label>reloj_2</label>
              <span name="reloj_2" id="reloj_2"></span>
            </div>
            <div class="form-group">
              <label>reloj_3</label>
              <span name="reloj_3" id="reloj_3"></span>
            </div>
            <div class="form-group">
              <label>reloj_4</label>
              <span name="reloj_4" id="reloj_4"></span>
            </div>
            <div class="form-group">
              <label>reloj_5</label>
              <span name="reloj_5" id="reloj_5"></span>
            </div>
            <div class="form-group">
              <label>reloj_6</label>
              <span name="reloj_6" id="reloj_6"></span>
            </div>
            <div class="form-group">
              <label>reloj_7</label>
              <span name="reloj_8" id="reloj_7"></span>
            </div>
            <div class="form-group">
              <label>reloj_8</label>
              <span name="reloj_8" id="reloj_8"></span>
            </div>

             -->



                    <!--  -->

                    <!--  -->

                    <!--  -->
                    <!-- <div class="modal-footer bg-white">
              <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
              <button class="btn btn-danger" onclick=cerrarModal() class="close" data-dismiss="modal" aria-label="Close">Cancelar</button>
            </div> -->
        </form>
      </div>
    </div>
  </div>
  </div>


  <!--MODAL - NUEVO USARIO-->

  <!-- MODAL FIN -->
  <?php include './Views/includes/script_new.php' ?>

  </html>

  <script src="<?php echo BASE_URL; ?>assets/js/modulos/Asistencia.js"></script>

  <script>
    const base_url = '<?php echo BASE_URL; ?>';
  </script>
</body>

<style>
  #myEvent .fc-center {
    font-size: 1.5em;
    /* Tamaño de fuente h2 */
    margin-top: 4px;
    font-weight: 0;
    line-height: 1.2;

    /* h1: 2em
h2: 1.5em
h3: 1.17em
h4: 1em
h5: 0.83em */
  }

  .fc-day-grid-event .fc-time {
    font-weight: normal;
    font-style: normal;
    font-size: 1.5em;
    font-family: "Nunito", "Segoe UI", arial;
    /* color: #6c757d; */
    color: #414141;
    display: block;
    white-space: nowrap;
  }

  .fc-day-grid-event .fc-content {
    box-shadow: none;
    display: flex;
    flex-direction: column;
  }

  .fc-event {
    box-shadow: none;
  }

  .fc-view>table td {
    background-color: none;
  }

  .fc-title {
    color: #34395e !important;
    display: block;
    padding-top: 3px;
   

  }
</style>

</html>
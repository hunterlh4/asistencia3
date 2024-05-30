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
                  
                    </div>
                    <div  class="col-md-12 col-sm-6">
                     
                      <div class="form-group">
                        <label for="justificacion">Numero Informe</label>
                        <input type="text" class="form-control" placeholder="justificacion" name="justificacion" id="justificacion" minlength="5" maxlength="30" readonly>
                      </div>
                    </div>
                    <div class="modal-footer bg-white col-md-12 col-sm-12">
                  <!-- <button class="btn btn-primary" type="submit" onclick=guardar() id="btnAccion">Registrar</button> -->
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

  

  <script>
    const base_url = '<?php echo BASE_URL; ?>';
 
    var miVariable = <?php echo $data1['id']; ?>;

</script>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/asistencia_trabajador.js"></script>
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
    font-size: 1.1em;
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
    font-size: 1.4em;
   

  }
</style>

</html>
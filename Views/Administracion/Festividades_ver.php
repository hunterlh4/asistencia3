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

                                        <h3 class="font-weight-bolder"><i data-feather="calendar" class="mb-1 ml-1"></i> Dias Festivos</h3>
                                        <!-- <h3 class="font-weight-bolder"><i class="fa fa-calendar"></i> Festivos</h3> -->
                                        <!-- <h3 class="font-weight-bolder"><i class="fa fa-calendar"></i> Horario</h3> -->
                                    </div>

                                    <div class="card-body">
                                    <div id="Calendario">

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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="titleModal"></h5>
                    <button type="button" onclick=cerrarModal() class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

               
            </div>
        </div>
    </div>
    </div>


    <!--MODAL - NUEVO USARIO-->

    <!-- MODAL FIN -->
    <?php include './Views/includes/script_new.php' ?>

    </html>
    <script src="<?php echo BASE_URL; ?>/assets/js/modulos/festividad_ver.js"></script>
    <!-- <script src="<?php echo BASE_URL; ?>assets/bundles/fullcalendar/locales-all.js"></script> -->

    <script>
        const base_url = '<?php echo BASE_URL; ?>';
    </script>

    <style>
        .fc-center {
    font-size: 2em;
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

  .fc-content {
    overflow: visible; /* Permitir que el contenido se muestre fuera de su contenedor */
}

.fc-event-container .fc-content span.fc-title {
    display: block; /* Cambiar a bloque para permitir múltiples líneas */
    width: 100%; /* Establecer el ancho al 100% para que se expanda horizontalmente */
    white-space: normal; /* Permitir que el texto se divida en múltiples líneas */
    word-wrap: break-word; /* Permitir que las palabras largas se dividan en varias líneas */
}
    </style>
</body>

</html>
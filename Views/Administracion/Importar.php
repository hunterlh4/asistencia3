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
        <div class="card-header mb-0 mt-3  d-flex justify-content-between align-items-center">
            <div class="col-md-12">
                <h2 class="card-title"><i class="fa fa-file-csv"></i> Importar</h2>
            </div>
        </div>
        
        <div class="card-body">
            <form form id="formulario" class="needs-validation" novalidate="" method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="col-md-12 mb-3 ">
                    <div class="custom-file">
                        <input type="hidden" id="nombreArchivoActual" name="nombreArchivoActual">
                        <input type="file" class="custom-file-input" id="archivo" name="archivo" accept=".csv,.xls,.xlsx" required>
                        <label class="custom-file-label" id="nombreArchivo" for="archivo">Seleccione un Archivo</label>
                    </div>
                </div>
                <div class="col-md-12 m-2 text-center"  id="loadingMessage"  style="display: none;" >
                <span class="text-danger font-weight-bolder ">*Este proceso puede tardar unos segundos*</span>
                </div>

              

                <div class="col-md-12 m-2 text-center">
                <button type="submit" id="Importar" class="btn btn-secondary btn-block btn-lg"  >Cargar Datos </button>
                </div>
               
            </form>
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

  
</div>


    <!--MODAL - NUEVO USARIO-->

    <!-- MODAL FIN -->
    <?php include './Views/includes/script_new.php' ?>

    </html>
    <script src="<?php echo BASE_URL; ?>assets/js/modulos/importar.js"></script>

    <script>
        const base_url = '<?php echo BASE_URL; ?>';
    </script>
</body>

</html>
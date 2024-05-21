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
<h3 class="font-weight-bolder"><i class="fa fa-laptop"></i> Reporte</h3>
<hr>
</div>
<div class="card-body">
<div class="row ">
    <div class="col-md-12">
        
       
            <div class="form-group">
                <label for="direccion">Trabajadores</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-address-card"></i>
                        </div>
                    </div>
                <select class="form-control select2"  multiple="" id="trabajador" name="trabajador" required>
                <option value="0">Alex</option>
                <option value="1">Pedro</option>
                <option value="2">juan</option>
                <option value="3">c</option>
                <option value="4">d</option>
                <option value="5">eb</option>
                <option value="6">mb</option>
                </select>
                </div>
            </div>
    </div>
    <div class="col-md-8">
     
            <div class="form-group">
                <label for="direccion">Mes</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-address-card"></i>
                        </div>
                    </div>
                <select class="form-control select2"  multiple="" id="mes" name="mes" required>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Setiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
                </select>
                </div>
            </div>

    </div>
    <div class="col-md-4">
        
            <div class="form-group">
                <label for="direccion">Año</label>
                <div class="input-group">
                    
               
                <select class="form-control"  id="mes" name="mes" required>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
              
                </select>
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
</div>
<?php include './Views/includes/footer.php'; ?>
</div>
</div>
<?php include './Views/includes/script_new.php' ?>
</html>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/reporte.js"></script>
<script>
const base_url = '<?php echo BASE_URL; ?>';
</script>
</body>
</html>
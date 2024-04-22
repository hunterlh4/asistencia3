<?php include_once './Views/includes/header.php'; ?>
<body>
  <!-- <div class="loader"></div> -->
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h3 class="font-weight-bolder mb-0"><i class="fa fa-users"></i> Administrar Usuarios</h3>
                                      
                                        <button class="btn btn-lg btn-success rounded-0 " data-toggle="modal"  data-target="#nuevoModal" id="nuevo_registro">Crear Nuevo Usuario</button>
                                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Modal With Form</button> btnNuevoUsuario-->

                                    </div>
                                    <!-- 
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" style="width:100%;" id="table-1">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"># </th>
                                                        <th>usuario</th>
                                                        <th>nivel</th>
                                                        
                                                        <th>DNI</th>
                                                        
                                                        <th>estado</th>
                                                        <th>action</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                foreach ($data1 as $fila) {
                                                        echo '<tr>';
                                                        echo '<td>' . $fila['usuario_id'] . '</td>';
                                                        echo '<td>' . $fila['usuario_username'] . '</td>';
                                                        echo '<td>' . $fila['usuario_nivel'] . '</td>';

                                                        echo '<td>' . $fila['dni']. '</td>';
                                                        $activo = ($fila['usuario_estado']==true)? "<div class='badge badge-success'>Activos</div>":"<div class='badge badge-danger'>Inactivos</div>";
                                                        echo '<td>' . $activo .   '</td>';
                                                        echo '<td>' .
                                                        '<div class="d-flex">
                                                            <button class="btn btn-primary" type="button" onclick="editUser(' . $fila['usuario_id'] . ')"><i class="fas fa-edit"></i></button>
                                                            <button class="btn btn-success" type="button" onclick="verUser(' . $fila['usuario_id'] . ')"><i class="fas fa-eye"></i></button>
                                                            <button class="btn btn-danger" type="button" onclick="eliminarUser(' . $fila['usuario_id'] . ')"><i class="fas fa-trash"></i></button>
                                                         
                                                        </div>'
                                                        .'</td>';
                                                        echo '</tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                           
                                        </div>
                                    </div>
                                     -->
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover"  style="width:100%;"  id="table-alex">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center"># </th>
                                                        <th>usuario</th>
                                                        <th>nombre</th>
                                                        <th>apellido</th>
                                                        <th>dni</th>
                                                        <th>nivel</th>
                                                        <th>estado</th>
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
                
                <?php //include 'includes/sidebar-config.html'; ?>
            </div>
            <?php include './views/includes/footer.php'; ?>
    </div>
  </div>
  <!-- MODAL -->
  <div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <h5 class="modal-title" id="formModal">Usuario</h5> -->
                <h5 class="modal-title" id="titleModal"></h5>
                <button type="button"  onclick=cerrar() class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="formUsuarios" method="POST">
                <input type="hidden" id="id" name="id">
                  <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Usuario" name="username" id="username">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </div>
                      </div>
                      <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nombre</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Nombre" name="nombre" id="nombre">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Apellido</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Apellido" name="apellido" id="apellido">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nivel</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Nivel" name="nivel" id="nivel">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Direccion</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Trabajador" name="Trabajador" id="Trabajador">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Estado</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-envelope"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Estado" name="estado" id="estado">
                    </div>
                  </div>
                  <div class="modal-footer bg-white">
                   
                    <!-- <button type="button" class="btn btn-secondary" type="button" onclick=cerrar() data-dismiss="modal">Close</button> -->

                    <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    <button class="btn btn-danger" type="button"onclick=cerrar()  data-bs-dismiss="modal">Cancelar</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>

    
    <!--MODAL - NUEVO USARIO-->
  
  <!-- MODAL FIN -->
  <?php include './views/includes/script_new.php' ?>

  </html><script src="<?php echo BASE_URL; ?>/assets/js/modulos/usuarios2.js"></script>
    
  <script>
    // $(document).ready(function(){
    //   $(".collapse-btn").click();
    // });
    const base_url = '<?php echo BASE_URL; ?>';
  </script>
</body>

</html>

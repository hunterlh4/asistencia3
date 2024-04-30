
const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));



let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9 ;
var datos;

// Ajustar el tamaño del modal
 // Establece el ancho máximo del modal


document.addEventListener("DOMContentLoaded", function() {

    llenarTabla();
    llenarselectDireccion();
    llenarselectRegimen();
    llenarselectHorario();
    llenarselectCargo();

  

    //levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nuevo Trabajador";

        document.querySelector('#radio-true').checked = true;
        document.querySelector('#id').value = '';
        document.querySelectorAll('#estado-grupo').forEach(element => {
            element.style.display = 'none';
        });
        myModal.show();
    });
    
    //submit usuarios
    frm.addEventListener("submit", function(e) {
        e.preventDefault();

    
        let data = new FormData(this);
        const url = base_url + "Trabajador/registrar";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(data);
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                const res = JSON.parse(this.responseText);
                if (res.icono == "success") {
                    
                    tblUsuario.ajax.reload();
                    frm.reset(); // Limpia el formulario
                    cerrarModal(); // Oculta el modal y el fondo oscuro
                }
                Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
            }
        }
    });
});


function llenarTabla(){
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "Trabajador/listar",
            dataSrc: "",
        },
        columns: [
            { data: "tid" },
            { data: "tdni" },
            { data: "tnombre" },
            { data: "dnombre" },
            { data: "cnombre" },
            { data: "rnombre" },   
                    
            { data: "estado" },
            { data: "accion" }

        ],
        dom: 'Bfrtip',
        
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6] // Especifica las columnas que deseas copiar
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6] // Especifica las columnas que deseas exportar a CSV
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6] // Especifica las columnas que deseas exportar a Excel
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6] // Especifica las columnas que deseas exportar a PDF
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3,4,5,6] // Especifica las columnas que deseas imprimir
                }
            }
        ]
    });
}



function actualizartabla(){
    mytable = $('#table-alex').DataTable();
    // var datosSeleccionados = tabla.rows('.selected').data();
    tabla.ajax.reload();
}


function edit(id) {    
    const url = base_url + "Trabajador/edit/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            frm.reset();
            resetRequiredFields();
            console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            document.querySelector('#id').value = res.tid;
            document.querySelector('#dni').value = res.tdni; 
            document.querySelector('#telefono').value = res.telefono;
            document.querySelector('#nro_tarjeta').value = res.nro_tarjeta;
            document.querySelector('#nombre').value = res.nombre;      
            document.querySelector('#email').value = res.email;    
            document.querySelector('#nacimiento').value = res.fecha_nacimiento;    
            document.querySelector('#direccion').value = res.direccion_id;
            document.querySelector('#regimen').value = res.regimen_id;
            document.querySelector('#cargo').value = res.cargo_id;
            document.querySelector('#horario').value = res.horario_id;
            document.querySelector('#modalidad').value = res.modalidad_trabajo;
          

            if(res.sexo=='F'){
                document.querySelector('#radio-mujer').checked = true;
                document.querySelector('#radio-hombre').checked = false;
            }else{
                document.querySelector('#radio-hombre').checked = true;
                document.querySelector('#radio-mujer').checked = false;
            }
     
            if(res.estado=='Activo'){
                document.querySelector('#estado-true').checked = true;
                document.querySelector('#estado-false').checked = false;
            }else{
                document.querySelector('#estado-false').checked = true;
                document.querySelector('#estado-true').checked = false;
            }
           
            document.querySelectorAll('#estado-grupo').forEach(element => {
                element.style.display = 'block';
            });
            // document.querySelector('#password').setAttribute('readonly', 'readonly');
            btnAccion.textContent = 'Actualizar';
            titleModal.textContent = "Modificar Trabajador";
            myModal.show();
            

            
            //$('#nuevoModal').modal('show');
        }
    }
}


// reiniciar validaciones
function resetRequiredFields() {
    // Obtener todos los elementos de entrada requeridos
    $('#formulario').removeClass('was-validated');
}

// Llamar a la función cuando se abre el modal
function abrirModal() {
    myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
    myModal.hide();
}

function llenarselectDireccion(){
    $.ajax({
        url: base_url + "Trabajador/listarDireccion",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.did;

                if (opcion.destado === "Inactivo" || opcion.eestado === "Inactivo" ) {
                    // Aplicar estilo al campo seleccionado
                    option.style.color = "red"; // Cambiar a tu color deseado
                  } else {
                    // Restablecer el color de fondo si el valor no es "Inactivo"
                    option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
                  }

                option.text = opcion.dnombre + ' '+opcion.enombre;
                // Agregar la opción al select
                direccion.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselectRegimen(){
    $.ajax({
        url: base_url + "Trabajador/listarRegimen",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;

                if (opcion.estado === "Inactivo" ) {
                    // Aplicar estilo al campo seleccionado
                    option.style.color = "red"; // Cambiar a tu color deseado
                  } else {
                    // Restablecer el color de fondo si el valor no es "Inactivo"
                    option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
                  }

                option.text = opcion.nombre;
                // Agregar la opción al select
                regimen.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselectHorario(){
    $.ajax({
        url: base_url + "Trabajador/listarHorario",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;

                if (opcion.estado === "Inactivo" ) {
                    // Aplicar estilo al campo seleccionado
                    option.style.color = "red"; // Cambiar a tu color deseado
                  } else {
                    // Restablecer el color de fondo si el valor no es "Inactivo"
                    option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
                  }

                option.text = opcion.nombre;
                // Agregar la opción al select
                horario.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselectCargo(){
    $.ajax({
        url: base_url + "Trabajador/listarCargo",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;

                if (opcion.estado === "Inactivo" ) {
                    // Aplicar estilo al campo seleccionado
                    option.style.color = "red"; // Cambiar a tu color deseado
                  } else {
                    // Restablecer el color de fondo si el valor no es "Inactivo"
                    option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
                  }

                option.text = opcion.nombre;
                // Agregar la opción al select
                cargo.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}




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
    llenarselectDirecciones();

  

    //levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nueva Trabajador";

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
        const url = base_url + "Trabajadores/registrar";
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
            url: base_url + "Trabajadores/listar",
            dataSrc: "",
        },
        columns: [
            { data: "Trabajador_id" },
            { data: "Trabajador_dni" },
            { data: "trabajador_apellido_nombre" },
            { data: "regimen_nombre" },
            // { data: "horario_nombre" },
            // { data: "cargo_nombre" },
            
            { data: "Trabajador_estado" },
            { data: "accion" },

        ],
        dom: 'Bfrtip',
        
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas copiar
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas exportar a CSV
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas exportar a Excel
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas exportar a PDF
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas imprimir
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


function editUser(id) {    
    const url = base_url + "Trabajadores/edit/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            frm.reset();
            resetRequiredFields();
            console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            document.querySelector('#id').value = res.id;
            document.querySelector('#dni').value = res.apellido_nombre; 
            document.querySelector('#nombre').value = res.nombre;            
            document.querySelector('#selectDirecciones').value = res.direccion_id;
            document.querySelector('#selectRegimen').value = res.regimen_id;
            document.querySelector('#selectHorario').value = res.horario_id;
            document.querySelector('#selectCargos').value = res.cargo_id;
            document.querySelector('#email').value = res.email;
            document.querySelector('#telefono').value = res.telefono;
            document.querySelector('#nro_tarjeta').value = res.nro_tarjeta;

            if(res.sexo=='F'){
                document.querySelector('#sexo-true').checked = true;
                document.querySelector('#sexo-false').checked = false;
            }else{
                document.querySelector('#sexo-false').checked = true;
                document.querySelector('#sexo-true').checked = false;
            }
            document.querySelector('#fechaNacimiento').value = res.fecha_nacimiento;
            document.querySelector('#Modalidad_trabajo').value = res.modalidad_trabajo;
            document.querySelector('#diasParticulares').value = res.dias_particulares;
            
            
            
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

function llenarselectDirecciones(){
    $.ajax({
        url: base_url + "trabajadores/listarDirecciones",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.nombre;
                // Agregar la opción al select
                selectTrabajadores.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselectRegimen(){
    $.ajax({
        url: base_url + "trabajadores/listarRegimen",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.nombre;
                // Agregar la opción al select
                selectTrabajadores.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselecthorarios(){
    $.ajax({
        url: base_url + "trabajadores/listarHorarios",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.nombre;
                // Agregar la opción al select
                selectTrabajadores.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarselectCargos(){
    $.ajax({
        url: base_url + "trabajadores/listarTrabajadores",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
                
                
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.nombre;
                // Agregar la opción al select
                selectTrabajadores.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


SELECT 
    t.id AS trabajador_id,
    t.dni AS dni,
    t.apellido_nombre AS trabajador,
    d.nombre AS direccion,
    r.nombre AS regimen,
    h.nombre AS horario, 
    c.nombre AS cargo
FROM 
    trabajadores t
INNER JOIN 
    direccion d ON t.direccion_id = d.id
INNER JOIN 
    regimen r ON t.regimen_id = r.id
INNER JOIN 
    horario h ON t.horario_id = h.id
INNER JOIN 
    cargo c ON t.cargo_id = c.id WHERE dni ='71205269';
    

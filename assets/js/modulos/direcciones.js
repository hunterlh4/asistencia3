
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
    llenarselect();

  

    //levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nueva Direccion";

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
        const url = base_url + "direcciones/registrar";
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
            url: base_url + "direcciones/listar",
            dataSrc: "",
        },
        columns: [
            { data: "direccion_id" },
            { data: "direccion_equipo" },
            // { data: "direccion_nombre" },
            // { data: "equipo_nombre" },
            { data: "direccion_estado" },
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
    const url = base_url + "direcciones/edit/" + id;
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
            document.querySelector('#nombre').value = res.nombre;
            
            // document.querySelector('#equipo_id').value = res.equipo_id;

            if(res.equipo_id===null){
                // console.log('equipo_id nulo');
                document.querySelector('#select1').value = 0;
            }else{
                document.querySelector('#select1').value = res.equipo_id;
                }
            
            
            if(res.estado=='Activo'){
                document.querySelector('#radio-true').checked = true;
                document.querySelector('#radio-false').checked = false;
            }else{
                document.querySelector('#radio-false').checked = true;
                document.querySelector('#radio-true').checked = false;
            }
           
            document.querySelectorAll('#estado-grupo').forEach(element => {
                element.style.display = 'block';
            });
            // document.querySelector('#password').setAttribute('readonly', 'readonly');
            btnAccion.textContent = 'Actualizar';
            titleModal.textContent = "Modificar direccion";
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

function llenarselect(){
    $.ajax({
        url: base_url + "Direcciones/listarEquipos",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response);
              
                // opcionInicial.value = '0';
                // opcionInicial.text = 'Seleccione un empleado';
                // select1.appendChild(opcionInicial);
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.nombre;
                // Agregar la opción al select
                select1.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
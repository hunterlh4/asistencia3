
const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formUsuarios");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
const select1 = document.getElementById("selectTrabajadores");

select1.innerHTML="";

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
        resetRequiredFields();
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "NUEVO USUARIO";
        document.getElementById("password").setAttribute("required", "true");
        
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
        const url = base_url + "usuarios/registrar";
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



function llenarselect(){
    $.ajax({
        url: base_url + "usuarios/listartrabajadores",
        type: 'GET',
       
        success: function(response) {
                datos = JSON.parse(response);
                let opcionInicial = document.createElement("option");
                opcionInicial.value = '0';
                opcionInicial.text = 'Seleccione un empleado';
                select1.appendChild(opcionInicial);


                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción
                option.value = opcion.id;
                option.text = opcion.apellido_nombre+ ' - '+ opcion.dni;
                // Agregar la opción al select
                select1.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function llenarTabla(){
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "usuarios/listar",
            dataSrc: "",
        },
        columns: [
            // { data: "id" },
            // { data: "nombre" },
            // { data: "apellido" },

            { data: "usuario_id" },
            { data: "usuario_username" },
            { data: "usuario_nombre" },
            { data: "usuario_apellido" },
            { data: "dni" },
            { data: "usuario_nivel" },
            { data: "usuario_estado" },
            { data: "accion" },
           
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


function editUser(idUser) {
    const url = base_url + "usuarios/edit/" + idUser;
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
            document.querySelector('#username').value = res.username;
            document.getElementById("password").setAttribute("required", "true");
            document.querySelector('#password').value = '';//res.password;
            document.querySelector('#nombre').value = res.nombre;
            document.querySelector('#apellido').value = res.apellido;
            document.querySelector('#nivel').value = res.nivel;

            if(res.trabajador_id===null){
            document.querySelector('#selectTrabajadores').value = 0;
            
            }else{
            document.querySelector('#selectTrabajadores').value = res.trabajador_id;
            }
            
           

            if(res.estado=='Activo'){
                document.querySelector('#radio-true').checked = true;
                document.querySelector('#radio-false').checked = false;
            }else{
                document.querySelector('#radio-false').checked = true;
                document.querySelector('#radio-true').checked = false;
            }
            // document.querySelector('#estado').value = res.estado;
            document.querySelectorAll('#estado-grupo').forEach(element => {
                element.style.display = 'block';
            });
            // document.querySelector('#password').setAttribute('readonly', 'readonly');
            btnAccion.textContent = 'Actualizar';
            titleModal.textContent = "MODIFICAR USUARIO";
            myModal.show();
            
            //$('#nuevoModal').modal('show');
        }
    }
}

function eliminarUser(idUser) {
    Swal.fire({
        title: "Aviso",
        text: "Esta seguro de eliminar el registro!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "usuarios/delete/" + idUser;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                    if (res.icono == "success") {
                        // mytable.ajax.reload();
                        actualizartabla();
                    }
                    Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
                }
            }
        }
    });
}

// reiniciar validaciones
function resetRequiredFields() {
    // Obtener todos los elementos de entrada requeridos
    $('#formUsuarios').removeClass('was-validated');
}

// Llamar a la función cuando se abre el modal
function abrirModal() {
    myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
    myModal.hide();
}
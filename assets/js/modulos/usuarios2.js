
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
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "NUEVO USUARIO";

        document.querySelector('#id').value = '';
        document.querySelector('#username').value = '';
            // document.querySelector('#password').value = res.password;
        document.querySelector('#nombre').value = '';
        document.querySelector('#apellido').value = '';
        document.querySelector('#nivel').value = '';
        document.querySelector('#Trabajador').value = '';
        document.querySelector('#estado').value = '';
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
                    myModal.hide();
                    tblUsuario.ajax.reload();
                }
                Swal.fire("Aviso?", res.msg.toUpperCase(), res.icono);
            }
        }
    });
});

function llenarselect(){
    $.ajax({
        url: base_url + "usuarios/listartrabajadores",
        type: 'GET',
       
        success: function(response) {
            // Limpiar el select antes de agregar nuevas opciones
            // $('#selectTrabajadores').empty();
            var len = response.length;
           
            // for (var i = 0; i < response.length; i++) {
            //     var item = response[i];
            //     console.log("ID:", item.id, ", Nombre:", item.apellido_nombre);
            // }
            // response.map(function(item) {
            //     console.log("ID:", item.id, "- Nombre:", item.apellido_nombre);
            // });
              
                // for (var i = 0; i < response.length; i++) {
                //     var item = response[i];
                //     var parsedItem = JSON.parse(item); // Convierte la cadena JSON en un objeto
                //     console.log(parsedItem); // Imprime el objeto en la consola
                // }
                // console.log(response);
                // if (Array.isArray(response)) {
                //     // Tu código que usa map() o forEach() aquí
                // } else {
                //     console.log("El objeto response no es un array:", response);
                // }
                datos = JSON.parse(response);
                datos.forEach(opcion => {
                    // Crear un elemento de opción
                    let option = document.createElement("option");
                    // Establecer el valor y el texto de la opción
                    option.value = opcion.id;
                    option.text = opcion.apellido_nombre;
                    // Agregar la opción al select
                    select1.appendChild(option);
                });
                // for (let key in response) {
                //     if (response.hasOwnProperty(key)) {
                //         console.log(key + ": " + response[key]);
                //     }
                // }

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

// Función para imprimir un mensaje en la consola
// function imprimirMensaje() {
//     tblUsuario.ajax.reload();
//     console.log('¡Hola! Este es un mensaje que se imprime cada 5 segundos.');
// }


// // Configurar el intervalo para que llame a la función cada 5 segundos (5000 milisegundos)
// setInterval(imprimirMensaje, 2000);



// document.addEventListener("DOMContentLoaded", function() {
    // tblUsuario = $("#tblUsuarios").DataTable({
    //     ajax: {
    //         url: base_url + "usuarios/listar",
    //         dataSrc: "",
    //     },
    //     columns: [
    //         { data: "usuario_id" },
    //         { data: "usuario_username" },
    //         { data: "usuario_nombre" },
    //         { data: "usuario_apellido" },
    //         { data: "usuario_nivel" },
    //         { data: "direccion_nombre" },
    //         { data: "equipo_nombre" },
    //         { data: "usuario_estado" },
    //         { data: "creacion" },
    //         { data: "edicion" },
    //         { data: "accion" },
            
           
    //     ],
    //     language: {
    //         processing: "Procesando...",
    //         lengthMenu: "Mostrar _MENU_ registros",
    //         zeroRecords: "No hay boletas de autorización registradas.",
    //         emptyTable: "No hay boletas de autorización registradas.",
    //         info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    //         infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    //         infoFiltered: "(filtrado de un total de _MAX_ registros)",
    //         search: "Buscar boletas de autorización:",
    //         infoThousands: ",",
    //         loadingRecords: "Cargando...",
    //         paginate: {
    //           first: "Primero",
    //           last: "Último",
    //           next: "Siguiente",
    //           previous: "Anterior"
    //         },
    //         aria: {
    //           sortAscending: ": Activar para ordenar la columna de manera ascendente",
    //           sortDescending: ": Activar para ordenar la columna de manera descendente"
    //         },
    //         buttons: {
    //           copy: "Copiar",
    //           colvis: "Visibilidad"
    //         }
    //     },
    //     dom:"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    //     "<'row'<'col-sm-12'tr>>" +
    //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    //     buttons: {
    //         copy: "Copiar",
    //         colvis: "Visibilidad"
    //       }
    // });
    // levantar modal


    // nuevo.addEventListener("click", function() {
    //     document.querySelector('#id').value = '';
    //     titleModal.textContent = "NUEVO USUARIO";
    //     btnAccion.textContent = 'Registrar';
    //     frm.reset();
    //     document.querySelector('#clave').removeAttribute('readonly');
    //     myModal.show();
    // });
    // submit usuarios
    // frm.addEventListener("submit", function(e) {
    //     e.preventDefault();
    //     let data = new FormData(this);
    //     const url = base_url + "usuarios/registrar";
    //     const http = new XMLHttpRequest();
    //     http.open("POST", url, true);
    //     http.send(data);
    //     http.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //             console.log(this.responseText);
    //             const res = JSON.parse(this.responseText);
    //             if (res.icono == "success") {
    //                 myModal.hide();
    //                 tblUsuario.ajax.reload();
    //             }
    //             Swal.fire("Aviso?", res.msg.toUpperCase(), res.icono);
    //         }
    //     }
    // });

// });


function cerrar(){
    // myModal.hide();
    myModal.hide(); // Ocultar el modal
    // document.getElementById('cancelar').focus();
    
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
            console.log(this.responseText);
            const res = JSON.parse(this.responseText);
            document.querySelector('#id').value = res.id;
            document.querySelector('#username').value = res.username;
            document.querySelector('#password').value = res.password;
            document.querySelector('#nombre').value = res.nombre;
            document.querySelector('#apellido').value = res.apellido;
            document.querySelector('#nivel').value = res.nivel;
            document.querySelector('#selectTrabajadores').value = res.trabajador_id;
            document.querySelector('#estado').value = res.estado;
            document.querySelector('#password').setAttribute('readonly', 'readonly');
            btnAccion.textContent = 'Actualizar';
            titleModal.textContent = "MODIFICAR USUARIO";
            myModal.show();
            //$('#nuevoModal').modal('show');
        }
    }
}

function eliminarUser(idUser) {
    Swal.fire({
        title: "Aviso?",
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
                    Swal.fire("Aviso?", res.msg.toUpperCase(), res.icono);
                }
            }
        }
    });
}


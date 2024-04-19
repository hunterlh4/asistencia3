
const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formUsuarios");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9 ;

document.addEventListener("DOMContentLoaded", function() {
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "usuarios/listar2",
            dataSrc: "",
        },
        columns: [
            { data: "id" },
            { data: "nombre" },
            { data: "apellido" },
           
        ],
        
        
       
    });
    //levantar modal
    nuevo.addEventListener("click", function() {

        myModal.show();
    });
    //submit usuarios
    frm.addEventListener("submit", function(e) {
    });
});




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
    myModal.hide();
}

function actualizartabla(){
     mytable = $('#table-1').DataTable();
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
            // document.querySelector('#password').value = res.password;
            document.querySelector('#nombre').value = res.nombre;
            document.querySelector('#apellido').value = res.apellido;
            document.querySelector('#nivel').value = res.nivel;
            document.querySelector('#direccion').value = res.direccion_id;
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

// function editUser(idUser) {
//     const url = base_url + "usuarios/edit/" + idUser;
//     const http = new XMLHttpRequest();
//     http.open("GET", url, true);
//     http.send();
//     http.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             console.log(this.responseText);
//             const res = JSON.parse(this.responseText);
//             document.querySelector('#id').value = res.id;
//             document.querySelector('#nombre').value = res.nombres;
//             document.querySelector('#apellido').value = res.apellidos;
//             document.querySelector('#correo').value = res.correo;
//             document.querySelector('#clave').setAttribute('readonly', 'readonly');
//             btnAccion.textContent = 'Actualizar';
//             titleModal.textContent = "MODIFICAR USUARIO";
//             myModal.show();
//             //$('#nuevoModal').modal('show');
//         }
//     }
// }
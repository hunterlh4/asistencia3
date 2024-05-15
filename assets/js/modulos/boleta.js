
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
    llenarSelectSolicitante();
    llenarSelectAprobador();

  

    //levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nueva Boleta";

        // document.querySelector('#radio-true').checked = true;
        document.querySelector('#id').value = '';
        // document.querySelectorAll('#estado-grupo').forEach(element => {
        //     element.style.display = 'none';
        // });
        myModal.show();
    });
    
    //submit usuarios
    frm.addEventListener("submit", function(e) {
        e.preventDefault();
        let data = new FormData(this);
        const url = base_url + "Boleta/registrar";

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false, 
            contentType: false,
            beforeSend: function() {
                // Se ejecuta antes de enviar la solicitud
                console.log('Enviando solicitud...');
            },
            success: function(response) {
                // Se ejecuta cuando se recibe una respuesta exitosa
                const res = JSON.parse(response);
                if (res.icono == "success") {
                    
                    tblUsuario.ajax.reload();
                    frm.reset(); // Limpia el formulario
                    cerrarModal(); // Oculta el modal y el fondo oscuro
                }
                Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
            },
            error: function(xhr, status, error) {
                // Se ejecuta si hay algún error en la solicitud
                console.error('Error en la solicitud:', error);
            }
        });

        // const http = new XMLHttpRequest();
        // http.open("POST", url, true);
        // http.send(data);
        // http.onreadystatechange = function() {
        //     if (this.readyState == 4 && this.status == 200) {
        //         console.log(this.responseText);
        //         const res = JSON.parse(this.responseText);
        //         if (res.icono == "success") {
                    
        //             tblUsuario.ajax.reload();
        //             frm.reset(); // Limpia el formulario
        //             cerrarModal(); // Oculta el modal y el fondo oscuro
        //         }
        //         Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
        //     }
        // }
    });
});


function llenarTabla(){
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "Boleta/listar",
            dataSrc: "",
        },
        columns: [
            { data: "bid" },
            { data: "numero" },
            { data: "solitantenombre" },
            { data: "fecha_nueva" },
            // { data: "fecha_fin" },
            { data: "hora_entrada" },
            { data: "hora_salida" },
            { data: "estado_tramite" },
            // { data: "estado" },
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

function edit(id) {

    $.ajax({
        url: base_url + "Boleta/edit/" + id,
        type: 'GET',

        success: function(response) {
                frm.reset();
                resetRequiredFields();
                console.log(response);
                datos = JSON.parse(response); 
                // datos.forEach(opcion => {
                // // Crear un elemento de opción
                // let option = document.createElement("option");
                // // Establecer el valor y el texto de la opción

                // if (opcion.estado === "Inactivo" ) {
                //     // Aplicar estilo al campo seleccionado
                //     option.style.color = "red"; // Cambiar a tu color deseado
                // }
                
                // option.value = opcion.id;
               
                // if(opcion.dni==null){
                //     option.text = opcion.apellido_nombre;
                   
                // }else{
                    
                //     option.text = opcion.apellido_nombre+ ' - '+ opcion.dni;
                // }
                
                // // Agregar la opción al select
                // aprobador.appendChild(option);
                
                // });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });


  
       
   
}

function llenarSelectSolicitante(){
   
        $.ajax({
            url: base_url + "usuario/listartrabajadores",
            type: 'GET',
    
            success: function(response) {
                    datos = JSON.parse(response); 
                    datos.forEach(opcion => {
                    // Crear un elemento de opción
                    let option = document.createElement("option");
                    // Establecer el valor y el texto de la opción
    
                    if (opcion.estado === "Inactivo" ) {
                        // Aplicar estilo al campo seleccionado
                        option.style.color = "red"; // Cambiar a tu color deseado
                    }
                    
                    option.value = opcion.id;
                   
                    if(opcion.dni==null){
                        option.text = opcion.apellido_nombre;
                       
                    }else{
                        
                        option.text = opcion.apellido_nombre+ ' - '+ opcion.dni;
                    }
                    
                    // Agregar la opción al select
                    solicitante.appendChild(option);
                    
                    });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    
}

function llenarSelectAprobador(){
   
    $.ajax({
        url: base_url + "usuario/listartrabajadores",
        type: 'GET',

        success: function(response) {
                datos = JSON.parse(response); 
                datos.forEach(opcion => {
                // Crear un elemento de opción
                let option = document.createElement("option");
                // Establecer el valor y el texto de la opción

                if (opcion.estado === "Inactivo" ) {
                    // Aplicar estilo al campo seleccionado
                    option.style.color = "red"; // Cambiar a tu color deseado
                }
                
                option.value = opcion.id;
               
                if(opcion.dni==null){
                    option.text = opcion.apellido_nombre;
                   
                }else{
                    
                    option.text = opcion.apellido_nombre+ ' - '+ opcion.dni;
                }
                
                // Agregar la opción al select
                aprobador.appendChild(option);
                
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

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
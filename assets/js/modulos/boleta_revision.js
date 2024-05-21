
const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAprobar = document.querySelector("#btnAprobar");
const btnRechazar = document.querySelector("#btnRechazar");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));

// INPUTS

const idElement = document.querySelector('#id');
const solicitanteElement = document.querySelector('#solicitante');

const fechaInicioElement = document.querySelector('#fecha_inicio');
const fechaFinElement = document.querySelector('#fecha_fin');
const horaSalidaElement = document.querySelector('#hora_salida');
const horaEntradaElement = document.querySelector('#hora_entrada');
const razonElement = document.querySelector('#razon');
const otra_razonElement = document.querySelector('#otra_razon');
const observacionElement = document.querySelector('#observacion');
// 

let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9 ;
var datos;

// Ajustar el tamaño del modal
 // Establece el ancho máximo del modal


document.addEventListener("DOMContentLoaded", function() {

    llenarTabla();
    // vertabla();
    llenarSelectSolicitante();
  

  

    //levantar modal
    // nuevo.addEventListener("click", function() {
    //     frm.reset();
    //     resetRequiredFields()
    //     btnAprobar.textContent = 'Registrar';
    //     titleModal.textContent = "Nueva Boleta";
    //     cambiarEstadoInputs(1);
    //     // document.querySelector('#radio-true').checked = true;
    //     document.querySelector('#id').value = '';
    //     // document.querySelectorAll('#estado-grupo').forEach(element => {
    //     //     element.style.display = 'none';
    //     // });
    //     myModal.show();
    // });
    
    //submit usuarios

});

function revisar(accion){
    

    frm.addEventListener("submit", function(e) {
        e.preventDefault();
        idElement.disabled = false;
        const formData = new FormData(frm);
        const url = base_url + "Boleta/revisar";
        formData.append('accion', accion);
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false, 
            contentType: false,
            beforeSend: function() {
                // Se ejecuta antes de enviar la solicitud
                console.log('Enviando solicitud...');
            },
            success: function(response) {
                // Se ejecuta cuando se recibe una respuesta exitosa
                // console.log(response);
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

       
    });
}

function llenarTabla(){
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "Boleta/listarRevisionBoletas",
            dataSrc: "",
        },
        columns: [
            { data: "posicion" },
            { data: "numero" },
            { data: "nombre_trabajador" },
            { data: "fecha_nueva" },
            // { data: "fecha_fin" },
            { data: "hora_salida" },
            { data: "hora_entrada" },
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

function view(id) {

    $.ajax({
        url: base_url + "Boleta/edit/" + id,
        type: 'GET',

        success: function(response) {
                frm.reset();
                resetRequiredFields();
                console.log(response);
                const res = JSON.parse(response); 
                
                idElement.value = res.id;;
                solicitanteElement.value = res.trabajador_id;
             
                fechaInicioElement.value = res.fecha_inicio;
                fechaFinElement.value = res.fecha_fin;
                horaSalidaElement.value =res.hora_salida;
                horaEntradaElement.value = res.hora_entrada;
                observacionElement.value = res.observaciones;
                razonElement.value = res.razon;
                otra_razonElement.value = res.razon_especifica;


              
               
                
               cambiarEstadoInputs();
                titleModal.textContent = "Vizualizar";
                
              
                myModal.show();
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




// function vertabla(){
   
//     $.ajax({
//         url: base_url + "Boleta/listarRevisionBoletas",
//         type: 'GET',

//         success: function(response) {
//                 // datos = JSON.parse(response); 
//                console.log(response);
//         },
//         error: function(xhr, status, error) {
//             console.error(error);
//         }
//     });

// }

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

    
function cambiarEstadoInputs(){


    idElement.disabled = true;
    solicitanteElement.disabled = true;
   
    fechaInicioElement.disabled = true;
    fechaFinElement.disabled = true;
    horaSalidaElement.disabled = true;
    horaEntradaElement.disabled = true;
    razonElement.disabled=true;
    otra_razonElement.disabled=true;
}

const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));

// INPUTS

const idElement = document.querySelector('#id');

const aprobadorElement = document.querySelector('#aprobador');
const fechaInicioElement = document.querySelector('#fecha_inicio');
const fechaFinElement = document.querySelector('#fecha_fin');
const horaSalidaElement = document.querySelector('#hora_salida');
const horaEntradaElement = document.querySelector('#hora_entrada');
const razonElement = document.querySelector('#razon');
const otra_razonElement = document.querySelector('#otra_razon');
// 

let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9 ;
var datos;

// Ajustar el tamaño del modal
 // Establece el ancho máximo del modal


document.addEventListener("DOMContentLoaded", function() {

    llenarTabla();
    // llenarSelectSolicitante();
    llenarSelectAprobador();

  

    //levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nueva Boleta";
        cambiarEstadoInputs(1);
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
        const url = base_url + "Boleta/registrarme";

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

 
    });
});


function llenarTabla(){
    tblUsuario = $("#table-alex").DataTable({
        ajax: {
            url: base_url + "Boleta/listarMisBoletas",
            dataSrc: "",
        },
        columns: [
            { data: "posicion" },
            { data: "numero" },
            { data: "nombre_aprobador" },
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
                cambiarEstadoInputs(1);
                const res = JSON.parse(response); 
               
                idElement.value = res.id;
                // solicitanteElement.value = res.trabajador_id;
                aprobadorElement.value = res.aprobado_por;
                fechaInicioElement.value = res.fecha_inicio;
                fechaFinElement.value = res.fecha_fin;
                horaSalidaElement.value =res.hora_salida;
                horaEntradaElement.value = res.hora_entrada;

                if (res.razon && !['Comsion de Servicio', 'Compensacion Horas', 'Motivos Particulares', 'Enfermedad', 'ESSALUD'].includes(res.razon)) {
                    // Si res.razon es diferente a las opciones disponibles, selecciona la opción "Otra"
                    $('#razon').val('Otra');
                    // Llena el campo Otra_razon con el valor de res.razon
                    $('#otra_razon').val(res.razon);
                } else {
                    // Si res.razon es una de las opciones disponibles, selecciona esa opción
                    $('#razon').val(res.razon);
                    // Limpia el campo Otra_razon
                    $('#otra_razon').val('');
                }

                btnAccion.textContent = 'Actualizar';
                titleModal.textContent = "Actualizar Boleta";


                if (!aprobadorElement.value) {
                    if(aprobadorElement.value==''){
                        removeDefaultOption();
                    }
               
                    var opcion = document.createElement('option');
                    opcion.value = ''; // Cambia 'default_value' al valor predeterminado que desees
                    opcion.text = "Seleccione un Aprobador";
                    opcion.id = 'defaultOption';
                    aprobadorElement.appendChild(opcion);
                    aprobadorElement.value = '';
                    
                   
                
                }
                myModal.show();
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
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
                // solicitanteElement.value = res.trabajador_id;
                aprobadorElement.value = res.aprobado_por;
                fechaInicioElement.value = res.fecha_inicio;
                fechaFinElement.value = res.fecha_fin;
                horaSalidaElement.value =res.hora_salida;
                horaEntradaElement.value = res.hora_entrada;

                

                cambiarEstadoInputs(0);

                if (res.razon && !['Comsion de Servicio', 'Compensacion Horas', 'Motivos Particulares', 'Enfermedad', 'ESSALUD'].includes(res.razon)) {
                    // Si res.razon es diferente a las opciones disponibles, selecciona la opción "Otra"
                    $('#razon').val('Otra');
                    // Llena el campo Otra_razon con el valor de res.razon
                    $('#otra_razon').val(res.razon);
                } else {
                    // Si res.razon es una de las opciones disponibles, selecciona esa opción
                    $('#razon').val(res.razon);
                    // Limpia el campo Otra_razon
                    $('#otra_razon').val('');
                }
               
                
                btnAccion.textContent = 'Actualizar';
                titleModal.textContent = "Vizualizar";
                
                var html = 
                '<div class="form-group">'+
                '<label for="observaciones">Observaciones</label>'+
                '<input type="text" class="form-control" id="observaciones" name="observaciones" value="'+res.observaciones+'" disabled>'+
                '</div>';
               
                $('#resultado').html(html);


                if (!aprobadorElement.value) {
                    $.ajax({
                        url: base_url + "Trabajador/edit/" + res.aprobado_por,
                        type: 'GET',
                    success: function(response) {
                        const res = JSON.parse(response);
                        var opcion = document.createElement('option');
                        opcion.value = res.id; // Cambia 'default_value' al valor predeterminado que desees
                        opcion.text = res.apellido_nombre;
                        aprobadorElement.appendChild(opcion);
                        aprobadorElement.value = opcion.value;
                    
                    }
                });
                
                    
                }
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

function llenarSelectAprobador(){
   
    $.ajax({
        url: base_url + "Boleta/MilistarTrabajadoresPorCargoNivel",
        type: 'GET',

        success: function(response) {
            datos = JSON.parse(response); 
            // Limpiar el select aprobadorElement
            aprobadorElement.innerHTML = '';
            datos.map(function(item) {
                var option = document.createElement('option');
                if (item.trabajador_estado === "Inactivo" ) {
                    option.style.color = "red";
                }
                option.value = item.trabajador_id;
                if(item.trabajador_dni==null){
                    option.text = item.trabajador_nombre;
                }else{
                    option.text = item.trabajador_nombre+ ' - '+ item.trabajador_dni;
                }
                aprobadorElement.appendChild(option);
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

function cambiarEstadoInputs(accion){

    $('#resultado').empty();
    idElement.disabled = false;

    aprobadorElement.disabled = false;
    fechaInicioElement.disabled = false;
    fechaFinElement.disabled = false;
    horaSalidaElement.disabled = false;
    horaEntradaElement.disabled = false;
    razonElement.disabled=false;
    otra_razonElement.disabled=false;
    btnAccion.hidden = false;
    if(accion==0){
        idElement.disabled = true;
       
        aprobadorElement.disabled = true;
        fechaInicioElement.disabled = true;
        fechaFinElement.disabled = true;
        horaSalidaElement.disabled = true;
        horaEntradaElement.disabled = true;
        razonElement.disabled=true;
        otra_razonElement.disabled=true;

        btnAccion.hidden = true;
    }
    // 
}

function removeDefaultOption() {
    const defaultOption = document.getElementById('defaultOption');
    if (defaultOption) {
        defaultOption.remove();
    }
}


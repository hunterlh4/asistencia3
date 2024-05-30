
const btnAccion = document.querySelector("#btnAccion");
// NOMBRE DE MIS INPUTS

const formulario = document.querySelector("#formulario");
const inputHiddenFile = document.querySelector("#nombreArchivoActual") ;
const labelFile = document.querySelector("#nombreArchivo");
const archivo = document.getElementById("archivo");

const botonImportar = document.getElementById("Importar");

const loadingMessage = document.getElementById("loadingMessage");



document.addEventListener("DOMContentLoaded", function() {
    botonImportar.disabled=true;
    
   
});





archivo.addEventListener("change", function(event) {
    // Obtener el nombre del nuevo archivo seleccionado por el usuario
    var nuevoNombreArchivo = event.target.files[0].name;
    var extension = nuevoNombreArchivo.split('.').pop().toLowerCase();
  
    // Actualizar el valor del campo oculto si el nombre del archivo ha cambiado
    if (extension === 'csv' || extension === 'xls'|| extension === 'xlsx') {
        // Actualizar el valor del campo oculto si el nombre del archivo ha cambiado
        if (inputHiddenFile.value !== nuevoNombreArchivo) {
            inputHiddenFile.value = nuevoNombreArchivo;
            labelFile.innerHTML = nuevoNombreArchivo;
            // Habilitar el botón de importar
            botonImportar.disabled = false;
         
            // botonImportar.classList.replace("btn-success", "btn-danger");
            botonImportar.classList.replace("btn-secondary", "btn-success");
        }
    } else {
        // Si el archivo no es CSV o XLSX, deshabilitar el botón de importar
        botonImportar.disabled = true;
        inputHiddenFile.value = null;
        labelFile.innerHTML = 'Seleccione un Archivo';
        botonImportar.classList.replace("btn-success", "btn-secondary");
      
        // Opcional: mostrar un mensaje de error al usuario
        // alert("Por favor, seleccione un archivo CSV o XLSX.");
    }
  });

formulario.addEventListener("submit", function(e) {
    e.preventDefault();

    // loadingMessage.style.display = "block";
    let datosFormulario = new FormData(this);
    $.ajax({
        url: base_url + "Importar/importar",
        type: 'POST',
        data: datosFormulario,
        timeout: 600000, // 600,000 ms = 10 minutos
        processData: false, // Para que jQuery no convierta el FormData en una cadena
        contentType: false,
        beforeSend: function() {
            // Mostrar mensaje de carga antes de enviar la solicitud
            loadingMessage.style.display = "block";
            botonImportar.disabled = true;
            
        },
        success: function(response) {
            console.log(response);
         const res = JSON.parse(response); 
        
         if (res.icono == "success") {
             
             loadingMessage.style.display = "none";
             formulario.reset(); // Limpia el formulario
            //  inputHiddenFile.value = null;
            //  labelFile.innerHTML = 'Seleccione un Archivo';
             // Habilitar el botón de importar
             botonImportar.disabled = false;
             resetRequiredFields();
            
         }
        //  res.post;
        //  console.log(res.post);
         // console.log(res.encabezado);
         Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
           
           
        },
        error: function(xhr, status, error) {
            // console.error(error);
            botonImportar.disabled = false;
            loadingMessage.style.display = "none";
            Swal.fire("Aviso", 'No se cargo Correctamente', 'warning');
        }
    });
});

// formulario.addEventListener("submit", enviarFormulario);

// async function enviarFormulario(e) {
//     e.preventDefault();

//     // Mostrar mensaje de carga y deshabilitar botón
//     loadingMessage.style.display = "block";
//     botonImportar.disabled = true;

//     // Crear un objeto FormData con los datos del formulario
//     let datosFormulario = new FormData(formulario);

//     try {
//         // Hacer la solicitud con fetch
//         let response = await fetch(base_url + "Importar/importar", {
//             method: 'POST',
//             body: datosFormulario,
//             timeout: 600000 // 600,000 ms = 10 minutos
//         });

//         // Esperar la respuesta
//         let res = await response.json();

//         // Procesar la respuesta
//         console.log(res);

//         if (res.icono === "success") {
//             loadingMessage.style.display = "none";
//             formulario.reset(); // Limpia el formulario
//             inputHiddenFile.value = null;
//             labelFile.innerHTML = 'Seleccione un Archivo';
//             // Habilitar el botón de importar
//             botonImportar.disabled = false;
//             resetRequiredFields();
//         }

//         Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);

//     } catch (error) {
//         console.error(error);
//         loadingMessage.style.display = "none";
//     }
// }




function actualizartabla(){
    mytable = $('#table-alex').DataTable();
    // var datosSeleccionados = tabla.rows('.selected').data();
    tabla.ajax.reload();
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
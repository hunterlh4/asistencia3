
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
    if (extension === 'csv' ||  extension === 'xlsx') {
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
    }
  });

function limpiarEncabezado(header) {
    // Mapear caracteres especiales a sus equivalentes correctos
    const caracterCorrecto = {
        "á": "á", "é": "é", "í": "í", "ó": "ó", "ú": "ú",
        "Á": "Á", "É": "É", "Í": "Í", "Ó": "Ó", "Ú": "Ú",
        "ñ": "ñ", "Ñ": "Ñ"
        // Puedes añadir más caracteres según tus necesidades
    };

    // Reemplazar caracteres especiales en cada elemento del encabezado
    let cleanedHeader = header.map(item => {
        return item.replace(/[áéíóúÁÉÍÓÚñÑ]/g, m => caracterCorrecto[m] || m);
    });

    return cleanedHeader;
}

formulario.addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData();
    let file = archivo.files[0];
    formData.append('archivo', file);

    if (!file) {
        Swal.fire("Aviso", 'Por favor, selecciona un archivo CSV o XLS'.toUpperCase(), 'warning');
        return;
    }
    let extension = file.name.split('.').pop().toLowerCase();
    if (extension === 'xlsx') {
        let reader = new FileReader();
        reader.onload = function(e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: 'array' });
            let sheet = workbook.Sheets[workbook.SheetNames[0]];
            let jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

            // Aquí puedes trabajar con jsonData, que es un array de objetos
            // console.log(jsonData[0]);
            console.log(JSON.stringify(jsonData[0]));

            // Continuar con el envío del formulario y el procesamiento en el servidor
            // Aquí puedes continuar con tu lógica de envío AJAX
        };
        reader.readAsArrayBuffer(file);
    }
    if(extension === 'csv'){
        Papa.parse(file, {
            header: true,
            skipEmptyLines: true,
            // delimiter: ";", 
            encoding: "UTF-8",
            complete: function(results) {
                let data = results.data;
                if (data.length === 0) {
                    Swal.fire("Aviso", 'El archivo está vacío o no tiene datos válidos.', 'warning');
                    return;
                }
                let header = Object.keys(data[0]); // Obtener encabezado del CSV/XLS
                let cleanedHeader = limpiarEncabezado(header);
                formData.append('encabezado', JSON.stringify(cleanedHeader)); // Enviar encabezado al servidor para validación
                // console.log(cleanedHeader)
                $.ajax({
                    url: base_url + "Importar/validar_archivo",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        loadingMessage.style.display = "block";
                        botonImportar.disabled = true;
                    },
                    success: function(response) {
                        console.log(response);
                        const res = JSON.parse(response);
                        if (res.validado) {
                            cargarDatos(file,res.tipo); // Llamar a sendBatch con el archivo
                        } else {
                            loadingMessage.style.display = "none";
                            botonImportar.disabled = false;
                            Swal.fire("Aviso", res.msg, 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        botonImportar.disabled = false;
                        loadingMessage.style.display = "none";
                        Swal.fire("Aviso", 'Error al validar el archivo.', 'error');
                    }
                });
            }
        });
    }
    if (extension === 'xls') {
       
        let reader = new FileReader();
        reader.onload = function(e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: 'array' });
            let sheet = workbook.Sheets[workbook.SheetNames[0]];
            let jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

            let header = jsonData[0].map(item => typeof item === 'string' ? item : '');
            let cleanedHeader = limpiarEncabezado(header);

            // console.log(header);
            // console.log(jsonData[0]);
            // console.log(jsonData);
            // console.log(JSON.stringify(jsonData[0]));
            console.log(cleanedHeader);
        }
        reader.readAsArrayBuffer(file);
    }
});


function cargarDatos(file,tipo) {
   
    Papa.parse(file, {
        header: true,
        complete: function(results) {
            let data = results.data;
            if (data.length === 0) {
                Swal.fire("Aviso", 'El archivo está vacío o no tiene datos válidos.', 'warning');
                return;
            }
            
            let batchSize = 1000; // Número de registros por lote
            let totalBatches = Math.ceil(data.length / batchSize);

            function sendBatch(batchNumber) {
                if (batchNumber >= totalBatches) {
                    loadingMessage.style.display = "none";
                    resetRequiredFields();
                    Swal.fire("Aviso", 'Todos los datos han sido enviados.', 'success');
                    return;
                }

                let start = batchNumber * batchSize;
                let end = start + batchSize;
                let batch = data.slice(start, end);

                $.ajax({
                    url: base_url + "Importar/importar_nuevo/"+tipo,
                    type: 'POST',
                    data: JSON.stringify(batch),
                    contentType: 'application/json',
                    processData: false,
                    beforeSend: function() {
                        loadingMessage.style.display = "block";
                        botonImportar.disabled = true;
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        console.log(res);
                        sendBatch(batchNumber + 1);
                    },
                    error: function(xhr, status, error) {
                        botonImportar.disabled = false;
                        loadingMessage.style.display = "none";
                        Swal.fire("Aviso", 'No se cargó correctamente.', 'warning');
                    }
                });
            }

            sendBatch(0); // Iniciar el envío de lotes
        }
    });
};
   
// codigo antiguio

// formulario.addEventListener("submit", function(e) {
//     e.preventDefault();

    

//     // loadingMessage.style.display = "block";
//     let datosFormulario = new FormData(this);
//     $.ajax({
//         url: base_url + "Importar/importar",
//         type: 'POST',
//         data: datosFormulario,
//         timeout: 600000, // 600,000 ms = 10 minutos
//         processData: false, // Para que jQuery no convierta el FormData en una cadena
//         contentType: false,
//         beforeSend: function() {
//             // Mostrar mensaje de carga antes de enviar la solicitud
//             loadingMessage.style.display = "block";
//             botonImportar.disabled = true;
            
//         },
//         success: function(response) {
//             console.log(response);
//          const res = JSON.parse(response); 
        
//          if (res.icono == "success") {
             
//              loadingMessage.style.display = "none";
//              formulario.reset(); // Limpia el formulario
//             //  inputHiddenFile.value = null;
//             //  labelFile.innerHTML = 'Seleccione un Archivo';
//              // Habilitar el botón de importar
//              botonImportar.disabled = false;
//              resetRequiredFields();
            
//          }
//         //  res.post;
//         //  console.log(res.post);
//          // console.log(res.encabezado);
//          Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
           
           
//         },
//         error: function(xhr, status, error) {
//             // console.error(error);
//             botonImportar.disabled = false;
//             loadingMessage.style.display = "none";
//             Swal.fire("Aviso", 'No se cargo Correctamente', 'warning');
//         }
//     });
// });
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
    botonImportar.classList.replace("btn-success", "btn-secondary");
}

// Llamar a la función cuando se abre el modal
function abrirModal() {
    myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
    myModal.hide();
}
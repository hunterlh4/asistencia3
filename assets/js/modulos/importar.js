
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
    
    //levantar modal
    // nuevo.addEventListener("click", function() {
    //     frm.reset();
    //     resetRequiredFields()
    //     btnAccion.textContent = 'Registrar';
    //     titleModal.textContent = "Nuevo Regimen";
    //     document.querySelector('#sueldo').value = 0.00;
    //     document.querySelector('#radio-true').checked = true;
    //     document.querySelector('#id').value = '';
    //     document.querySelectorAll('#estado-grupo').forEach(element => {
    //         element.style.display = 'none';
    //     });
    //     myModal.show();
    // });
    
    //submit usuarios
    formulario.addEventListener("submit", function(e) {
        e.preventDefault();

        // loadingMessage.style.display = "block";
        let datosFormulario = new FormData(this);
        $.ajax({
            url: base_url + "Importar/importar",
            type: 'POST',
            data: datosFormulario,
            processData: false, // Para que jQuery no convierta el FormData en una cadena
            contentType: false,
            beforeSend: function() {
                // Mostrar mensaje de carga antes de enviar la solicitud
                loadingMessage.style.display = "block";
            },
            success: function(response) {
                console.log(response);
             const res = JSON.parse(response); 
            
             if (res.icono == "success") {
                 
                 loadingMessage.style.display = "none";
                 formulario.reset(); // Limpia el formulario
                 inputHiddenFile.value = null;
                 labelFile.innerHTML = 'Seleccione un Archivo';
                 // Habilitar el botón de importar
                 botonImportar.disabled = true;
                 resetRequiredFields();
                
             }
             res.post;
             // console.log(res.encabezado);
             Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
               
               
            },
            error: function(xhr, status, error) {
                console.error(error);
                loadingMessage.style.display = "none";
            }
        });


        // let data = new FormData(this);
        // const url = base_url + "importar/importar";
        // const http = new XMLHttpRequest();
        // http.open("POST", url, true);
        // http.send(data);
        // http.onreadystatechange = function() {
        //     if (this.readyState == 4 && this.status == 200) {
        //         console.log(this.responseText);
        //         const res = JSON.parse(this.responseText);
        //         if (res.icono == "success") {
                    
        //             loadingMessage.style.display = "none";
        //             formulario.reset(); // Limpia el formulario
        //             inputHiddenFile.value = null;
        //             labelFile.innerHTML = 'Seleccione un Archivo';
        //             // Habilitar el botón de importar
        //             botonImportar.disabled = true;
        //             resetRequiredFields();
                   
        //         }
        //         // console.log(res.encabezado);
        //         Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
        //     }
        // }
    });
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
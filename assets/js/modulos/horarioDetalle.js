
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
    

  

    // levantar modal
    nuevo.addEventListener("click", function() {
        frm.reset();
        resetRequiredFields()
        btnAccion.textContent = 'Registrar';
        titleModal.textContent = "Nuevo HorarioDetalle";

        document.querySelector('#radio-true').checked = true;
        document.querySelector('#id').value = '';
        document.querySelectorAll('#estado-grupo').forEach(element => {
            element.style.display = 'none';
        });
        myModal.show();
    });
    
    // submit usuarios
    frm.addEventListener("submit", function(e) {
        e.preventDefault();
        let data = new FormData(this);
        const url = base_url + "HorarioDetalle/registrar";
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
            url: base_url + "horarioDetalle/listar",
            dataSrc: "",
        },
        columns: [
            { data: "id" },
            { data: "nombre" },
            { data: "hora_entrada" },
            { data: "hora_salida" },
            { data: "total" },
            { data: "estado" },
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
                    columns: [0, 1, 2, 3] // Especifica las columnas que deseas exportar a Excel
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


function Edit(id) {
    const url = base_url + "HorarioDetalle/edit/" + id;
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
            document.querySelector('#entrada').value = res.hora_entrada;
            document.querySelector('#salida').value = res.hora_salida;
       
            // document.querySelector('#estrategia').value = res.estrategia;
            
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
            titleModal.textContent = "Modificar equipo";
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


function goBack() {
    window.location.href = base_url + "Horario";
  }
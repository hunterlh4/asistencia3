const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));

let mytable; // = document.querySelector("#table-1");
let tblUsuario;
var data9;
var datos;
let trabajadores = [];
let trabajadores_editar = [];

let frm,
  selectTrabajador,
  selectDireccion,
  nuevo,
  input_id,
  input_dni,
  input_nacimiento,
  input_nombre,
  input_apellido,
  input_usuario,
  input_password;
// Ajustar el tamaño del modal
// Establece el ancho máximo del modal

function inicializarVariables() {
  frm = document.querySelector("#formUsuarios");
  nuevo = document.querySelector("#nuevo_registro");
  selectTrabajador = document.getElementById("selectTrabajadores");
  selectDireccion =  document.getElementById("direccion");
  input_id = document.querySelector("#id");
  input_dni = document.querySelector("#dni");
  input_nacimiento = document.getElementById("fecha_nacimiento");
  input_nombre = document.querySelector("#nombre");
  input_apellido = document.querySelector("#apellido");
  input_usuario = document.querySelector("#username");
  input_password = document.getElementById("password");
  input_nivel = document.querySelector("#nivel");
  // input_estado
 
  llenarTabla();
  cargardatos();
  llenarselectDireccion();
  validarInputs();
 


  //levantar modal
  nuevo.addEventListener("click", function () {
    frm.reset();
    resetRequiredFields();
    llenarselect(trabajadores);
    btnAccion.textContent = "Registrar";
    titleModal.textContent = "NUEVO USUARIO";
    document.getElementById("password").setAttribute("required", "true");
    document.querySelector("#radio-true").checked = true;
    document.querySelector("#id").value = "";
    document.querySelectorAll("#estado-grupo").forEach((element) => {
      element.style.display = "none";
    });
    myModal.show();
  });

  frm.addEventListener("submit", function (e) {
    e.preventDefault();
    validarformulario();
  });


}

function validarInputs() {
  input_dni.addEventListener("input", function (e) {
    // Eliminar todos los caracteres que no sean números
    this.value = this.value.replace(/[^0-9]/g, "");

    // Limitar la longitud a 8 caracteres
    if (this.value.length > 8) {
      this.value = this.value.slice(0, 8);
    }
  });

  input_nombre.addEventListener("input", function (e) {
    // Eliminar todos los caracteres que no sean letras
    this.value = this.value.replace(/[^a-zA-Z\sñÑ]/g, "");

    // Convertir a mayúsculas
    this.value = this.value.toUpperCase();

    // Limitar la longitud a 40 caracteres
    if (this.value.length > 30) {
      this.value = this.value.slice(0, 30);
    }
  });

  input_apellido.addEventListener("input", function (e) {
    // Eliminar todos los caracteres que no sean letras
    this.value = this.value.replace(/[^a-zA-Z\sñÑ]/g, "");

    // Convertir a mayúsculas
    this.value = this.value.toUpperCase();

    // Limitar la longitud a 40 caracteres
    if (this.value.length > 30) {
      this.value = this.value.slice(0, 30);
    }
  });

  input_usuario.addEventListener("input", function (e) {
    // Convertir todo a minúsculas
    this.value = this.value.toLowerCase();

    // Permitir solo letras minúsculas y limitar a 16 caracteres
    this.value = this.value.replace(/[^a-z0-9ñ]/g, "").slice(0, 16);
  });

  input_usuario.addEventListener("keydown", function (e) {
    if (e.key === "Backspace") {
      return;
    }
    if (e.key === "Enter") {
      return;
    }
  });

  const fechaActual = new Date();
  const fechaMinima = new Date();
  fechaMinima.setFullYear(fechaActual.getFullYear() - 85);
  const fechaMaxima = new Date();
  fechaMaxima.setFullYear(fechaActual.getFullYear() - 17);

  const fechaMinimaFormateada = fechaMinima.toISOString().slice(0, 10);
  const fechaMaximaFormateada = fechaMaxima.toISOString().slice(0, 10);

  input_nacimiento.setAttribute("min", fechaMinimaFormateada);
  input_nacimiento.setAttribute("max", fechaMaximaFormateada);
}

function validarformulario() {
  let errores = "";
  if (selectTrabajador.value === "") {
    errores += "Completa el campo <b>Trabajadores</b>.<br>";
  }
  if (selectDireccion.value === "") {
    errores += "Completa el campo <b>Direccion</b>.<br>";
  }
  if (input_dni.value === "") {
    errores += "Completa el campo <b>DNI</b>.<br>";
  }
  if (input_nacimiento.value === "") {
    errores += "Selecciona una <b>fecha</b>.<br>";
  }
  if (input_nombre.value === "") {
    errores += "Completa el campo <b>Nombre</b>.<br>";
  }
  if (input_apellido.value === "") {
    errores += "Completa el campo <b>Apellido</b>.<br>";
  }
  if (input_usuario.value === "") {
    errores += "Completa el campo <b>Usuario</b>.<br>";
  }
  if (input_password.value === "" && input_id.value =="") {
    errores += "Completa el campo <b>Password</b>.<br>";
  }
  // if (input_password2.value === "") {
  //   errores += "Completa el campo <b>Password Confirmation</b>.<br>";
  // }
  // if (input_password.value !== input_password2.value) {
  //   errores += "Las contraseñas no coinciden.<br>";
  // }

  if (errores !== "") {
    alerta("Aviso", errores, "error");
    return;
  }
  registrar(); // Llama a la función que maneja la lógica cuando el DNI es válido
}

function registrar() {
  var formData = new FormData(frm);
  const url = base_url + "usuario/registrar";
  $.ajax({
    type: "POST",
    url: url,
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      console.log(response);
      const res = JSON.parse(response);
      // if(res.icono=="success"){
        if (res.icono == "success") {
          tblUsuario.ajax.reload();
          frm.reset(); // Limpia el formulario
          resetRequiredFields();
          cerrarModal(); // Oculta el modal y el fondo oscuro
        }
      alerta("Aviso",res.msg, res.icono);
    
    },
    error: function (xhr, status, error) {
      console.error("Error al enviar la solicitud: " + error);
    },
  });
}
function cargardatos() {
  $.ajax({
    url: base_url + "usuario/listartrabajadores",
    type: "GET",

    success: function (response) {
      trabajadores = JSON.parse(response);
      // resolve();
      //   console.log(trabajadores);
    },
    error: function (xhr, status, error) {
      console.error(error);
      reject(error);
    },
  });
}

function llenarselect(data) {
  //   console.log("llamado select");
  // const select1 = document.getElementById("selectTrabajadores");
  selectTrabajador.innerHTML = "";
  let opcionInicial = document.createElement("option");
  opcionInicial.value = "";
  opcionInicial.text = "Seleccione un trabajador";
  selectTrabajador.appendChild(opcionInicial);

  data.forEach((trabajador) => {
    let option = document.createElement("option");
    option.value = trabajador.id;

    if (trabajador.dni == null || trabajador.dni === "") {
      option.text = trabajador.apellido_nombre;
    } else {
      option.text = trabajador.apellido_nombre + " " + " - " + trabajador.dni;
    }

    selectTrabajador.appendChild(option);
  });
}

function llenarselectDireccion() {
  $.ajax({
    url: base_url + "Trabajador/listarDireccion",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);

      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción
        option.value = opcion.did;

        if (opcion.destado === "Inactivo" || opcion.eestado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        } else {
          // Restablecer el color de fondo si el valor no es "Inactivo"
          option.style.backgroundColor = ""; // Restablecer el color a su valor por defecto
        }
        if (opcion.enombre == null) {
          option.text = opcion.dnombre;
        } else {
          option.text = opcion.dnombre + " " + opcion.enombre;
        }

        // Agregar la opción al select
        direccion.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function editUser(idUser) {
  const url = base_url + "usuario/edit/" + idUser;

  $.ajax({
    url: url,
    type: "GET",

    success: function (response) {
      const res = JSON.parse(response);
      frm.reset();
      resetRequiredFields();
      console.log(res.usuario);
      const usuario = res.usuario;
      // const trabajadores_editar = res.trabajadores;
      trabajadores_editar = res.trabajadores;
      //   console.table(res.trabajadores)

      llenarselect(trabajadores_editar);

      input_id.value = usuario.id;
      input_usuario.value = usuario.username;
      document.getElementById("password").setAttribute("required", "false");
      input_password.value = ""; //res.password;
      input_nombre.value = usuario.nombre;
      input_apellido.value = usuario.apellido;
      input_nivel.value = usuario.nivel;
      input_dni.value = usuario.dni;

      // const selectTrabajadores = document.querySelector("#selectTrabajadores");
      selectTrabajadores.value = usuario.trabajador_id || "";
      selectDireccion.value = usuario.direccion || "";
      input_nacimiento.value = usuario.nacimiento;


      if (usuario.estado == "Inactivo") {
        document.querySelector("#radio-false").checked = true;
        document.querySelector("#radio-true").checked = false;
      } else {
        
        document.querySelector("#radio-true").checked = true;
        document.querySelector("#radio-false").checked = false;
      }

      document.querySelectorAll("#estado-grupo").forEach((element) => {
        element.style.display = "block";
      });

      btnAccion.textContent = "Actualizar";
      titleModal.textContent = "MODIFICAR USUARIO";
      myModal.show();
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data:", error);
    },
  });
}

function llenarTabla() {
  tblUsuario = $("#table-alex").DataTable({
    ajax: {
      url: base_url + "usuario/listar",
      dataSrc: "",
    },
    columns: [
      // { data: "id" },
      // { data: "nombre" },
      // { data: "apellido" },

      { data: "contador" },
      { data: "usuario_username" },
      { data: "usuario_nombre" },
      { data: "usuario_apellido" },
      // { data: "dni" },
      {
        data: "dni",
        render: function (data, type, row) {
          if (!data && data === null) {
            return "<div class='badge badge-success'>Sin Vincular</div>";
          } else {
            return data;
          }
        },
      },
      { data: "usuario_nivel" },
      { data: "usuario_estado" },
      { data: "accion" },
    ],
    dom: "Bfrtip",

    buttons: [
      {
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], // Especifica las columnas que deseas copiar
        },
      },
      {
        extend: "csv",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], // Especifica las columnas que deseas exportar a CSV
        },
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], // Especifica las columnas que deseas exportar a Excel
        },
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], // Especifica las columnas que deseas exportar a PDF
        },
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6], // Especifica las columnas que deseas imprimir
        },
      },
    ],
  });
}

function actualizartabla() {
  mytable = $("#table-alex").DataTable();
  // var datosSeleccionados = tabla.rows('.selected').data();
  tabla.ajax.reload();
}

function buscar() {
  // Validar si el DNI está vacío

  if (input_dni.value === "") {
    alerta("Aviso", "Ingrese un DNI valido1", "error");
    return;
  }

  // Validar si el DNI tiene exactamente 8 dígitos
  if (input_dni.value.length !== 8 || !/^\d{8}$/.test(input_dni.value)) {
    alerta("Aviso", "Ingrese un DNI valido2", "error");
    return;
  }

  // Si pasa las validaciones, llamar a la función AJAX
  buscardni();
}
function buscardni() {
  if (input_dni.value.length === 8) {
    const url = base_url + "Registro/obtenerDatosPorDNI/" + input_dni.value;

    $.ajax({
      url: url,
      type: "POST",
      // dataType: "json",
      success: function (response) {
        const res = JSON.parse(response);
        console.log(res);

        if (!res.apellidoPaterno || res.apellidoPaterno.length < 1) {
          Swal.fire("Aviso", "No se encontró el apellido paterno", "error");
        } else {
          let apellidoCompleto = res.apellidoPaterno.trim(); // Inicializar con apellido paterno

          if (res.apellidoMaterno && res.apellidoMaterno.length > 0) {
            apellidoCompleto += " " + res.apellidoMaterno.trim(); // Agregar apellido materno si existe
          }

          input_apellido.value = apellidoCompleto.trim();

          // Asignar el nombre completo al campo correspondiente
          input_nombre.value = res.nombres.trim();

          // Asumiendo que esto es para mostrar un mensaje de éxito o esconder el mensaje de error
        }
      },
      error: function (xhr, status, error) {
        console.log("Error al consultar y editar el DNI: ", error);
        Swal.fire(
          "Error",
          "Hubo un problema con la solicitud. Inténtalo de nuevo más tarde.",
          "error"
        );
      },
    });
  }
}

// reiniciar validaciones
function resetRequiredFields() {
  // Obtener todos los elementos de entrada requeridos
  $("#formUsuarios").removeClass("was-validated");
}

// Llamar a la función cuando se abre el modal
function abrirModal() {
  myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
  myModal.hide();
}

function alerta(titulo, msg, icono) {
  Swal.fire(titulo, msg.toUpperCase(), icono);
}
document.addEventListener("DOMContentLoaded", function () {
  inicializarVariables();
});

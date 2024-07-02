const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
const select1 = document.getElementById("trabajador");
let concatenado = "";
// FORMULARIO
let id = document.querySelector("#id");
const licencia = document.querySelector("#licencia");
let trabajador_id = document.querySelector("#trabajador_id");
const entrada = document.querySelector("#entrada");
const salida = document.querySelector("#salida");
const total_reloj = document.querySelector("#total_reloj");
const total_horario = document.querySelector("#total_horario");
const tardanza = document.querySelector("#tardanza");
const tardanza_cantidad = document.querySelector("#tardanza_cantidad");
let justificacion_input = document.querySelector("#justificacion");
const reloj_1 = document.querySelector("#reloj_1");
const reloj_2 = document.querySelector("#reloj_2");
const reloj_3 = document.querySelector("#reloj_3");
const reloj_4 = document.querySelector("#reloj_4");
const reloj_5 = document.querySelector("#reloj_5");
const reloj_6 = document.querySelector("#reloj_6");
const reloj_7 = document.querySelector("#reloj_7");
const reloj_8 = document.querySelector("#reloj_8");
var events = [];

var boleta = [];
var today = new Date();
year = today.getFullYear();
month = today.getMonth();
day = today.getDate();

var monthNames = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Septiembre",
  "Octubre",
  "Noviembre",
  "Diciembre",
];
// var dayNames = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
var dayNames = [
  "Lunes",
  "Martes",
  "Miércoles",
  "Jueves",
  "Viernes",
  "Sábado",
  "Domingo",
];

var dayNameMap = {
  Sunday: "Domingo",
  Monday: "Lunes",
  Tuesday: "Martes",
  Wednesday: "Miércoles",
  Thursday: "Jueves",
  Friday: "Viernes",
  Saturday: "Sábado",
};

const razones = {
  CS: "Comisión de Servicio",
  DHE: "Devolución de Horas",
  AP: "Asuntos Particulares",
  ESS: "ESSALUD",
  CAP: "Capacitación",
  "LM/LP": "Licencia por Maternidad/Paternidad",
  "C.ESP": "Casos Especiales",
  O: "Onomástico",
  D: "Duelo",
  AV: "A cuenta de Vacaciones por Asuntos Personales",
  V: "Vacaciones",
  LE: "Lic. por Enfermedad",
  "LIC. F.G.": "Lic Por Familiar Grave",
  "LIC. GEST.": "Lic. Gestación",
  OTR: "Otro",
};

function obtenerDescripcion(abreviacion) {
  return razones[abreviacion] || "Desconocido";
}

const fechaActual = new Date();
const añoActual = fechaActual.getFullYear();
const fechaInicio = añoActual + "-01-01"; // Primero de enero del año actual
const fechaFin = añoActual + "-12-31"; // Último de diciembre del año actual
var calendar = $("#myEvent").fullCalendar({
  height: "auto",
  defaultView: "month",
  firstDay: 1,
  editable: false,
  selectable: true,
  locate: "es",
  timeFormat: "H:mm",
  displayEventTitle: false,
  displayEventTime: true, // Oculta la hora del evento
  displayEventEnd: true, // Oculta el fin del evento
  header: {
    left: "prev,next today",
    center: "title",
    // right: "month,agendaWeek,agendaDay,listMonth",
    right: "month,listMonth",
  },

  validRange: {
    start: fechaInicio,
    end: fechaFin,
  },
  events: events,

  eventClick: function (calEvent, jsEvent, view) {
    let asistencia_id = calEvent.id;
    let [anio, mes, dia] = calEvent.fecha.split("-");
    let mesNumero = parseInt(mes, 10);
    // let diaNumero = parseInt(dia, 10);
    let fecha = new Date(calEvent.fecha);
    let dayOfWeek = dayNames[fecha.getDay()];
    let month = monthNames[mesNumero - 1];
    let dateString = dayOfWeek + " " + dia + " de " + month + " del " + anio;
    titleModal.textContent = "Asistencia del " + dateString;
    let trabajador = calEvent.trabajador_id;

    $.ajax({
      // data: parametros,
      url: base_url + "Asistencia/edit/" + asistencia_id,
      type: "GET",

      success: function (response) {
        const res = JSON.parse(response);
        id.value = res.id;
        licencia.innerHTML = res.licencia;
        trabajador_id.value = res.trabajador_id;
        entrada.innerHTML = res.entrada;
        salida.innerHTML = res.salida;
        total_reloj.innerHTML = res.total_reloj;
        total_horario.innerHTML = res.total;
        tardanza.innerHTML = res.tardanza;
        tardanza_cantidad.innerHTML = res.tardanza_cantidad;
        justificacion_input.value = res.justificacion;
        // console.log(justificacion_input.value);
        reloj_1.innerHTML = res.reloj_1;
        reloj_2.innerHTML = res.reloj_2;
        reloj_3.innerHTML = res.reloj_3;
        reloj_4.innerHTML = res.reloj_4;
        reloj_5.innerHTML = res.reloj_5;
        reloj_6.innerHTML = res.reloj_6;
        reloj_7.innerHTML = res.reloj_7;
        reloj_8.innerHTML = res.reloj_8;
      },
      error: function (xhr, status, error) {
        // console.error(error);
      },
    });

    frm.reset();
    llenarBoleta(calEvent.fecha, trabajador);
    myModal.show();
  },

  viewRender: function (view, element) {
    // Cambia el texto del encabezado del calendario

    var mesActual = view.intervalStart.month();
    var añoActual = view.intervalStart.year();
    var nombremes = monthNames[mesActual];
    // console.log(concatenado);
    // $(".fc-center").text("<h5>" + nombreMes + ' - ' + añoActual + "</h5>");
    $(".fc-center").text(nombremes + " " + añoActual);

    // Cambia los nombres de los días de la semana
    $(".fc-list-heading-main").text(monthNames[mesActual]);

    $(".fc-day-header").each(function (index) {
      $(this).text(dayNames[index]);
    });

    $(".fc-list-heading-alt").each(function () {
      // Obtener el nombre del día actual
      var englishDayName = $(this).text();

      // Obtener el nombre del día en español desde el mapeo
      var spanishDayName = dayNameMap[englishDayName];

      // Establecer el texto del elemento con el nombre del día de la semana en español
      $(this).text(spanishDayName);
    });

    // Cambia los nombres de los meses
    $(".fc-month-button").text("Mes");
    $(".fc-agendaWeek-button").text("Semana");
    $(".fc-agendaDay-button").text("Día");
    $(".fc-listMonth-button").text("Lista");
    $(".fc-today-button").text("Ahora");

    $(".fc-listMonth-button").click(function () {
      // Cambiar el texto del botón a "Lista"
      $(this).text("Lista");

      modificarCalendario();
    });

    $(".fc-month-button").click(function () {
      // Cambiar el texto del botón a "Mes"
      $(this).text("Mes");

      modificarCalendario();
    });

    // var monthName = moment(view.start).format("MMMM");
    //               $(".fc-center").text(monthName);
  },
  eventRender: function (event, element) {
    if (event.concatenado_span) {
      element.find(".fc-title").html(event.concatenado_span);
    }

    // console.log(event.fecha);
    // console.log(event);
  },
});

function asistencias(moth, year) {
  var trabajadores_id = 1; //$('#trabajadores_id').val()

  // console.log(moth,year,trabajadores_id);
}

var currentDate = $("#myEvent").fullCalendar("getDate");
$(document).ready(function () {
  // Agrega un evento de clic al botón "Anterior"
  $(".fc-prev-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate.subtract(1, "months");
    // Obtiene el nuevo mes y año
    var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var newYear = currentDate.year();
    // Realiza alguna acción con el nuevo mes y año
    asistencias(newMonth, newYear);
    modificarCalendario();
  });

  // Agrega un evento de clic al botón "Siguiente"
  $(".fc-next-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate.add(1, "months");
    // Obtiene el nuevo mes y año
    var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var newYear = currentDate.year();
    // Realiza alguna acción con el nuevo mes y año
    asistencias(newMonth, newYear);
    modificarCalendario();
  });

  // Agrega un evento de clic al botón "Hoy"
  $(".fc-today-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate = $("#myEvent").fullCalendar("getDate");
    // Obtiene el mes y año actual
    var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var currentYear = currentDate.year();
    // Realiza alguna acción con el mes y año actual
    asistencias(currentMonth, currentYear);
    modificarCalendario();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  llenarselect();

  $("#trabajador").on("change", function () {
    // Obtiene el valor seleccionado del select
    var selectedValue = $(this).val();
    // Muestra el valor en la consola
    var currentDate = $("#myEvent").fullCalendar("getDate");
    var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var currentYear = currentDate.year();
    buscarBoleta(selectedValue, currentMonth, currentYear);
  });
});

function tiempoASegundos(tiempo) {
  let partes = tiempo.split(":");
  let horas = parseInt(partes[0], 10);
  let minutos = parseInt(partes[1], 10);
  return horas * 3600 + minutos * 60;
}

function segundosATiempo(segundos) {
  let horas = Math.floor(segundos / 3600);
  segundos %= 3600;
  let minutos = Math.floor(segundos / 60);
  // Formatear las horas y los minutos para que siempre tengan dos dígitos
  horas = String(horas).padStart(2, "0");
  minutos = String(minutos).padStart(2, "0");
  return `${horas}:${minutos}`;
}

function guardar() {
  frm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(frm);
    const url = base_url + "Asistencia/registrar";
    $.ajax({
      url: url,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {},
      success: function (response) {
        const res = JSON.parse(response);
        if (res.icono == "success") {
          $("#myEvent").fullCalendar("refetchEvents");
          modificarCalendario();
          frm.reset();
          cerrarModal();
        }
        Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
      },
      error: function (xhr, status, error) {
        // Se ejecuta si hay algún error en la solicitud
        console.error("Error en la solicitud:", error);
      },
    });
  });
}

function buscarBoleta(id, currentMonth, currentYear) {
  var parametros = {
    trabajador_id: id,
  };
  const url = base_url + "Boleta/buscarPorFechaSola";
  $.ajax({
    data: parametros, //datos que se envian a traves de ajax
    url: url, //archivo que recibe la peticion
    type: "POST", //método de envio
    success: function (response) {
      const res = JSON.parse(response);
      boleta = res;

      verAsistencia(currentMonth, currentYear, id, boleta);
    },
  });
}

function verAsistencia(mes, anio, id, boleta) {
  // Aquí puedes realizar cualquier acción que desees con la fecha del calendario y el valor seleccionado

  // Llama a tu función deseada con estos valores como parámetros
  var parametros = {
    mes: mes,
    anio: anio,
    id: id,
  };

  const url = base_url + "asistencia/listaCalendarioAsistenciaTrabajador/";

  $.ajax({
    data: parametros, //datos que se envian a traves de ajax
    url: url, //archivo que recibe la peticion
    type: "post", //método de envio
    beforeSend: function () {
      // console.log('procesando');
    },
    success: function (response) {
      //una vez que el archivo recibe el request lo procesa y lo devuelve
      // console.log(response);
      events = [];
      const res = JSON.parse(response);
      // console.log(boleta);
      res.forEach((evento) => {
        // console.log(boleta);
        for (let i = 0; i < boleta.length; i++) {
          var boletacalendar = "";
          const boletaFecha = new Date(boleta[i].fecha_inicio);
          const boletaFecha_fin = new Date(boleta[i].fecha_fin);
          const eventoFecha = new Date(evento.fecha);

          // const boletaFecha = boleta[i].fecha_inicio;
          // const boletaFecha_fin = boleta[i].fecha_fin;

          if (
            eventoFecha >= boletaFecha &&
            eventoFecha <= boletaFecha_fin &&
            evento.licencia != "SR"
          ) {
            // console.log(boletaFecha +'|-'+evento.fecha);

            boletacalendar = "-Boleta";
            //   console.log('fecha cambiada'+ boletaFecha + 'segunda'+ evento.fecha);
          }
          evento.licencia = evento.licencia + boletacalendar;
          // console.log(boletaFecha +'|'+evento.fecha);
        }
        if (evento.tardanza_cantidad != "0") {
          evento.licencia = evento.licencia + "-T" + evento.tardanza_cantidad;
        }

        events.push({
          id: evento.aid,
          title: evento.licencia,
          start: new Date(
            evento.anio,
            evento.mes - 1,
            evento.dia,
            evento.hora_entrada,
            evento.minuto_entrada
          ),
          end: new Date(
            evento.anio,
            evento.mes - 1,
            evento.dia,
            evento.hora_salida,
            evento.minuto_salida
          ),

          trabajador_id: evento.tid,
          backgroundColor: "transparent",
          entrada: evento.entrada,
          salida: evento.salida,
          total_reloj: evento.total_reloj,
          total_horario: evento.total_horario,
          tardanza: evento.tardanza,
          tardanza_cantidad: evento.tardanza_cantidad,
          justificacion: evento.justificacion,
          reloj_1: evento.reloj_1,
          reloj_2: evento.reloj_2,
          reloj_3: evento.reloj_3,
          reloj_4: evento.reloj_4,
          reloj_5: evento.reloj_5,
          reloj_6: evento.reloj_6,
          reloj_7: evento.reloj_7,
          reloj_8: evento.reloj_8,
          fecha: evento.fecha,
        });
      });

      // // Ahora events contiene todos tus eventos en el formato deseado
      // console.log(events);

      $("#myEvent").fullCalendar("removeEvents");
      $("#myEvent").fullCalendar("addEventSource", events);

      // Refresca el calendario para mostrar los nuevos eventos
      $("#myEvent").fullCalendar("refetchEvents");
      // modificarCalendario();
      modificarCalendario();
    },
  });
}

function modificarCalendario() {
  const fcContents = document.querySelectorAll(".fc-content");

  fcContents.forEach(function (element) {
    // Encontrar los elementos fc-time y fc-title dentro de este elemento
    var fcTime = element.querySelector(".fc-time");
    var fcTitle = element.querySelector(".fc-title");

    // Verificar si fc-title contiene 'SR' o 'OK-Boleta'
    if (fcTitle.textContent.includes("SR")) {
      // Si fc-title contiene 'SR', vaciar el contenido de fc-time
      fcTime.textContent = "";
    }
    if (/-Boleta/.test(fcTitle.textContent)) {
      // Si fc-title contiene '-Boleta', cambiar el color a azul y establecer el texto según el tipo

      // Obtener el prefijo del título antes de '-Boleta'
      var titlePrefix = fcTitle.textContent.split("-Boleta")[0];

      // Establecer el HTML del título según la parte extraída
      fcTitle.innerHTML =
        '<span style="color: blue; font-weight: bold;">' +
        titlePrefix +
        '</span><br><span style="color: blue; font-weight: bold;">' +
        "1 T" +
        "</span>";
    }
  });
  const fcContentsList = document.querySelectorAll(".fc-list-item");
  fcContentsList.forEach(function (element) {
    // Encontrar los elementos fc-time y fc-title dentro de este elemento
    var fcTime = element.querySelector(".fc-list-item-time");
    var fcTitle = element.querySelector(".fc-list-item-title");

    // Verificar si fc-title contiene 'SR' o 'OK-Boleta'
    if (fcTitle.textContent.includes("SR")) {
      // Si fc-title contiene 'SR', vaciar el contenido de fc-time
      fcTime.textContent = "";
    }
    if (/-Boleta/.test(fcTitle.textContent)) {
      // Si fc-title contiene '-Boleta', cambiar el color a azul y establecer el texto según el tipo

      // Obtener el prefijo del título antes de '-Boleta'
      var titlePrefix = fcTitle.textContent.split("-Boleta")[0];

      // Establecer el HTML del título según la parte extraída
      fcTitle.innerHTML =
        '<span style="color: blue; font-weight: bold;">' +
        titlePrefix +
        "</span> <br>";
    }
  });

  const fcDays = document.querySelectorAll(".fc-day");
  fcDays.forEach((day) => {
    // Obtén el valor del atributo data-date
    const date = day.getAttribute("data-date");

    // Crea un objeto Date a partir del valor de data-date
    const dateObj = new Date(date);

    // Verifica si es sábado (6) o domingo (0)
    if (dateObj.getDay() === 5 || dateObj.getDay() === 6) {
      // Si es sábado o domingo, establece un fondo claro
      day.style.backgroundColor = "#E5E8E8";
    }
  });
}

function llenarselect() {
  $.ajax({
    url: base_url + "usuario/listartrabajadores",
    type: "GET",

    success: function (response) {
      datos = JSON.parse(response);
      datos.forEach((opcion) => {
        // Crear un elemento de opción
        let option = document.createElement("option");
        // Establecer el valor y el texto de la opción

        if (opcion.estado === "Inactivo") {
          // Aplicar estilo al campo seleccionado
          option.style.color = "red"; // Cambiar a tu color deseado
        }
        option.value = opcion.id;
        if (opcion.dni == null) {
          option.text = opcion.apellido_nombre;
        } else {
          option.text = opcion.apellido_nombre + " - " + opcion.dni;
        }
        // Agregar la opción al select
        select1.appendChild(option);
      });
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function llenarBoleta(fecha, trabajador_id) {
  // fecha ='2024-05-01';
  // trabajador_id = 812;

  let fechaformateada = new Date(fecha);
  fechaformateada.setDate(fechaformateada.getDate() + 2); // Sumar 1 día

  let año = fechaformateada.getFullYear();
  let mes = (fechaformateada.getMonth() + 1).toString().padStart(2, "0");
  let dia = fechaformateada.getDate().toString().padStart(2, "0");
  let fechaFormateada = año + "-" + mes + "-" + dia;
  // console.log(fechaFormateada);
  var parametros = {
    fecha: fechaFormateada,
    trabajador_id: trabajador_id,
  };
  $.ajax({
    data: parametros,
    url: base_url + "Boleta/buscarPorFecha",
    type: "POST",
    beforeSend: function () {
      // console.log('procesando llenarBoleta');
    },
    success: function (response) {
      // datos = JSON.parse(response);
      const res = JSON.parse(response);

      var html = "";
      // console.log(datos);
      $("#resultado").empty();
      res.map((datos) => {
        console.log(datos);
        if (datos !== undefined && datos.numero !== undefined) {
          html +=
            '<div class="row text-center">' +
            "<hr>" +
            '<div class="col-12">' +
            '<div class="form-group">' +
            '<h4 for="numero">Boleta N° <span>' +
            datos.numero +
            "</span></h4>" +
            "</div>" +
            "</div>" +
            "</div>" +
            '<div class="row">' +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="aprobado_por">Aprobado por:</label>' +
            '<input type="text" class="form-control" placeholder="Aprobado por" name="aprobado_por" id="aprobado_por" value="' +
            datos.aprobador_nombre +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="fecha_inicio">Desde:</label>' +
            '<input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="' +
            datos.fecha_inicio +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="fecha_fin">Hasta:</label>' +
            '<input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="' +
            datos.fecha_fin +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="hora_salida">Salida:</label>' +
            '<input type="time" class="form-control" name="hora_salida" id="hora_salida" value="' +
            datos.hora_salida +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="hora_entrada">Entrada:</label>' +
            '<input type="time" class="form-control" name="hora_entrada" id="hora_entrada" value="' +
            datos.hora_entrada +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="duracion">Duración:</label>' +
            '<input type="time" class="form-control" name="duracion" id="duracion" value="' +
            datos.duracion +
            '" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="razon">Razón:</label>' +
            '<select class="form-control" name="razon" id="razon" disabled>';
          const descripcionRazon = obtenerDescripcion(datos.razon);
          html +=
            '<option value="' +
            datos.razon +
            '">' +
            descripcionRazon +
            "</option>";
          html +=
            "</select>" +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group" id="otra_razon" >' +
            '<label for="otra_razon_texto">Otra razón:</label>' +
            '<input type="text" class="form-control" value =' +
            datos.razon_especifica +
            ' name="otra_razon_texto" id="otra_razon_texto" disabled>' +
            "</div>" +
            "</div>" +
            '<div class="col-4">' +
            '<div class="form-group">' +
            '<label for="observaciones">Observaciones:</label>' +
            '<textarea class="form-control" name="observaciones" id="observaciones" rows="3" disabled>' +
            datos.observaciones +
            "</textarea>" +
            "</div>" +
            "</div>" +
            "</div>";
          // } else {
          //   html += '<option value="">' + "Otra" + "</option>";
          //   html +=
          //     "</select>" +
          //     "</div>" +
          //     "</div>" +
          //     '<div class="col-4">' +
          //     '<div class="form-group" id="otra_razon" >' +
          //     '<label for="otra_razon_texto">Otra razón:</label>' +
          //     '<input type="text" class="form-control" value =' +
          //     datos.razon +
          //     ' name="otra_razon_texto" id="otra_razon_texto" disabled>' +
          //     "</div>" +
          //     "</div>" +
          //     '<div class="col-4">' +
          //     '<div class="form-group">' +
          //     '<label for="observaciones">Observaciones:</label>' +
          //     '<textarea class="form-control" name="observaciones" id="observaciones" rows="3" disabled>' +
          //     datos.observaciones +
          //     "</textarea>" +
          //     "</div>" +
          //     "</div>" +
          //     "</div>";
          // }
        }
      });
      $("#resultado").html(html);
    },
    error: function (xhr, status, error) {
      console.error(error);
    },
  });
}

function abrirModal() {
  myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
  myModal.hide();
}

function listarAsistencia(id){
  
    tabla_horas = $("#table-detalle-alex").DataTable({
      ajax: {
        url: base_url + "asistencia/listarTrabajadorAsistencia",
        // type: "POST", // Especifica el método HTTP como POST
        dataSrc: "",
      },
      data:{
        function(data) {
          // Aquí agregas los parámetros adicionales que quieres enviar
          data.id = id;
        }
      },
      columns: [
        // Define tus columnas aquí según la estructura de tus datos
        { data: "dia" },
  
        { data: "fecha" },
        { data: "entrada" },
        { data: "salida" },
        // { data: "fecha_fin_formateada" },
        { data: "tardanza_cantidad" },
        { data: "licencia" },
       
      ],
      dom: "Bfrtip",
      select: false,
      order: [],
      buttons: [
        {
          extend: "copy",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5], // Especifica las columnas que deseas copiar
          },
        },
        {
          extend: "csv",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5], // Especifica las columnas que deseas exportar a CSV
          },
        },
        {
          extend: "excel",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5], // Especifica las columnas que deseas exportar a Excel
          },
        },
        {
          extend: "pdf",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5], // Especifica las columnas que deseas exportar a PDF
          },
        },
        {
          extend: "print",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5], // Especifica las columnas que deseas imprimir
          },
        },
      ],
      drawCallback: function(settings) {
        let api = this.api();
        let rows = api.rows().nodes();
        
        rows.each(function(row, index) {
          let data = api.row(row).data();
          let dia = data["dia"];
          let licencia = data['licencia'];
          
          // Comprobar si el día es sábado o domingo
          if (dia === "Sábado" || dia === "Domingo") {
            $(row).addClass("dia"); // Agregar clase de fila especial
          } else {
            $(row).removeClass("fila-normal"); // Quitar clase de fila especial
          }
          if(licencia == "No Marco Salida" || licencia =="+30" || (licencia =="Sin Marcacion" && dia != "Sábado" && dia != "Domingo")){
            $(row).addClass("licencia");
          }
        });
        pintarFilasEspeciales()
      }
    });
  }

function pintarFilasEspeciales() {
    // Seleccionar todas las filas con la clase fila-especial
    $('#table-detalle-alex tbody tr.dia').css('background-color', '#E5E8E8');
    $('#table-detalle-alex tbody tr.licencia').css('background-color', '#F1948A');
  }
  
  
  var boton = document.getElementById('dropdownMenuButton');
  var dropdown = boton.nextElementSibling;
  
  // Mostrar el menú al hacer hover sobre el botón
  boton.addEventListener('mouseenter', function(event) {
    dropdown.classList.add('show');
  });
  
  // Ocultar el menú al salir del botón o del menú
  boton.addEventListener('mouseleave', function(event) {
    setTimeout(function() {
      if (!boton.matches(':hover') && !dropdown.matches(':hover')) {
        dropdown.classList.remove('show');
      }
    }, 100);
  });
  
  dropdown.addEventListener('mouseleave', function(event) {
    setTimeout(function() {
      if (!boton.matches(':hover') && !dropdown.matches(':hover')) {
        dropdown.classList.remove('show');
      }
    }, 100);
  });
  
  dropdown.addEventListener('mouseenter', function(event) {
    dropdown.classList.add('show');
  });
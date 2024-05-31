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
//
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
// calendario click, buttons y renderizado de datos
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
    // }

    // Imprimir los valores en la consola
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
    // console.log(event);
  },
});

var currentDate = $("#myEvent").fullCalendar("getDate");
$(document).ready(function () {
  // Agrega un evento de clic al botón "Anterior"
  $(".fc-prev-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate.subtract(1, "months");
    // Obtiene el nuevo mes y año
    var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var newYear = currentDate.year();
    modificarCalendario();
  });

  // Agrega un evento de clic al botón "Siguiente"
  $(".fc-next-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate.add(1, "months");
    // Obtiene el nuevo mes y año
    var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var newYear = currentDate.year();
    modificarCalendario();
  });

  // Agrega un evento de clic al botón "Hoy"
  $(".fc-today-button").on("click", function () {
    // Obtiene la fecha actual del calendario
    currentDate = $("#myEvent").fullCalendar("getDate");
    // Obtiene el mes y año actual
    var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var currentYear = currentDate.year();
    modificarCalendario();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var currentDate = $("#myEvent").fullCalendar("getDate");
  var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
  var currentYear = currentDate.year();

  var trabajador = miVariable;
  // console.log(miVariable);
  buscarBoleta(trabajador, currentMonth, currentYear);
});

// llenar calendario con boleta
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
      // console.log(boleta);
      // console.log('boleta')

      verAsistencia(currentMonth, currentYear, id, boleta);
    },
  });
}
//  llenar calendario
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
        // console.log(evento.fecha);

        // console.log(boleta);
        let boleta_calendar = "";
        let boleta_particular = "";
        let tardanza_cantidad = "";

        for (let i = 0; i < boleta.length; i++) {
          const boletaFecha = new Date(boleta[i].fecha_inicio);
          const boletaFecha_fin = new Date(boleta[i].fecha_fin);
          const eventoFecha = new Date(evento.fecha);
          boletaFecha_fin.setUTCDate(boletaFecha_fin.getUTCDate() + 0);
          boletaFecha.setUTCDate(boletaFecha.getUTCDate() + 0);
          eventoFecha.setUTCDate(eventoFecha.getUTCDate() + 0);

          const Motivo_particular = boleta[i].razon;

          if (eventoFecha >= boletaFecha && eventoFecha <= boletaFecha_fin) {

            if (Motivo_particular === "Motivos Particulares") {
              boleta_particular = boleta[i].duracion;
              boleta_calendar = 'AP';

              //  console.log(boletaparticular,i,eventoFecha);
            } else {
              boleta_calendar = boleta[i].razon;

            }
          }

          //   evento.licencia = evento.licencia + boletaparticular + boletacalendar;
          //   if(Motivo_particular ==='Motivos Particulares'){
          //     evento.licencia = evento.licencia +'-';
          //     console.log(Motivo_particular)
          //     console.log(boletaFecha)
          //     console.log(boletaFecha_fin)
          //     console.log(eventoFecha)
          //     }

          // console.log(boletaFecha +'|'+evento.fecha);
        }
        if (evento.tardanza !== "00:00") {
          tardanza_cantidad = "1T";
          //   evento.licencia = evento.licencia + "-" + evento.tardanza_cantidad;
        }

        // console.log(evento.tid,evento.fecha);
        // const concatenado = evento.licencia +'|'+boleta_calendar+'|'+boleta_particular+'|'+tardanza_cantidad;
        concatenado =
          "<div>" +
          '<span style="color: black; display: block;">' +
          evento.licencia +
          "</span>" +
          '<span style="color: blue; display: block; font-weight: bold;">' +
          boleta_calendar +
          "</span>" +
          '<span style="color: red; display: block; font-weight: bold;">' +
          boleta_particular +
          "</span>" +
          '<span style="color: orange; display: block; font-weight: bold;">' +
          tardanza_cantidad +
          "</span>" +
          "</div>";

        events.push({
          id: evento.aid,
          title: evento.licencia, // evento.licencia,
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
          boleta_calendar: boleta_calendar,
          boleta_particular: boleta_particular,
          tardanza_cantidad: tardanza_cantidad,
          concatenado_span: concatenado,
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
// modificar datos del calendario
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

    // if (/-Boleta/.test(fcTitle.textContent)) {
    //   var titlePrefix = fcTitle.textContent.split("-Boleta")[0];
    //   fcTitle.innerHTML = titlePrefix + "-B";
    // }

    // if (fcTitle.textContent.includes("+MP")) {
    // }

    // if (/MP/.test(fcTitle.textContent)) {
    //     var titlePrefix = fcTitle.textContent.split('+MP')[0];
    //     fcTitle.innerHTML =  + titlePrefix + '-MP' ;
    // }

    let titulo, color;

    // const obtenerInfo = (indice) =>
    //   ({
    //     OK: { titulo: "OK", color: "blue" },
    //     "OK-B": { titulo: "OK", color: "blue" },
    //     "OK+MP-B": { titulo: "OK", color: "red" },
    //     NMS: { titulo: "NMS", color: "red" },
    //     "NMS-B": { titulo: "NMS", color: "red" },
    //     "NMS+MP-B": { titulo: "NMS", color: "red" },
    //     SR: { titulo: "SR", color: "red" },
    //     "SR-B": { titulo: "SR", color: "red" },
    //     "SR+MP-B": { titulo: "SR", color: "red" },
    //     "+30": { titulo: "+30", color: "red" },
    //     "+30-B": { titulo: "+30", color: "red" },
    //     "+30+MP-B": { titulo: "+30", color: "red" },
    //     NMS: { titulo: "NMS", color: "red" },
    //   }[indice] || { titulo: indice, color: "defaultColor" });

    // let info = obtenerInfo(fcTitle.textContent);
    // fcTitle.innerHTML =
    //   '<span style="color:' +
    //   info.color +
    //   '; font-weight: bold;">' +
    //   fcTitle.textContent +
    //   "</span>";

    // fcTitle.innerHTML = '<span style="color:'+color+'; font-weight: bold;">' + titulo + '</span>'
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

  var eventos = calendar.fullCalendar("clientEvents");
  eventos.forEach(function (evento) {
    // Obtener la fecha del evento
    var fechaEvento = evento.start.format("YYYY-MM-DD");

    // Obtener el contenido del evento
    var contenidoEvento = evento.title; // Suponiendo que el título del evento contiene el contenido
    var contenido_boleta = evento.boleta_calendar;
    var contenido_particular = evento.boleta_particular;
    var contenido_tardanza = evento.tardanza_cantidad;

    var contenidoHTML =
      '<span style="color: blue; font-weight: bold;">' +
      contenidoEvento +
      contenido_boleta +
      '</span><br><span style="color: blue; font-weight: bold;">' +
      "1 T" +
      "</span>";
    evento.title = contenidoHTML;
    // calendar.fullCalendar('updateEvent', evento);

    // Mostrar la fecha y el contenido del evento en la consola

    // console.log("Fecha del evento:", fechaEvento);
    // console.log("Contenido del licencia:", contenidoEvento);
    // console.log("Contenido del boleta:", contenido_boleta);
    // console.log("Contenido del particular:", contenido_particular);
    // console.log("Contenido del tardanza:", contenido_tardanza);
  });
  // calendar.fullCalendar('refetchEvents');
}

//  llenar con la boleta al calendario
function llenarBoleta(fecha, trabajador_id) {
  // fecha ='2024-05-01';
  // trabajador_id = 812;

  // let fechaformateada = new Date(fecha);
  // fechaformateada.setDate(fechaformateada.getDate() +2); // Sumar 1 día

  // let año = fechaformateada.getFullYear();
  // let mes = (fechaformateada.getMonth() + 1).toString().padStart(2, '0');
  // let dia = fechaformateada.getDate().toString().padStart(2, '0');
  // let fechaFormateada = año + "-" + mes + "-" + dia;
  // console.log(fechaFormateada);
  var parametros = {
    fecha: fecha,
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
        // console.log(datos);
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
          // Agregar opciones de razón

          if (
            datos.razon == "Comision de Servicio" ||
            datos.razon == "Compensacion Horas" ||
            datos.razon == "Motivos Particulares" ||
            datos.razon == "Enfermedad" ||
            datos.razon == "ESSALUD"
          ) {
            html +=
              '<option value="' +
              datos.razon +
              '">' +
              datos.razon +
              "</option>";
            html +=
              "</select>" +
              "</div>" +
              "</div>" +
              '<div class="col-4">' +
              '<div class="form-group" id="otra_razon" >' +
              '<label for="otra_razon_texto">Otra razón:</label>' +
              '<input type="text" class="form-control" value =' +
              datos.razon +
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
          } else {
            html += '<option value="">' + "Otra" + "</option>";
            html +=
              "</select>" +
              "</div>" +
              "</div>" +
              '<div class="col-4">' +
              '<div class="form-group" id="otra_razon" >' +
              '<label for="otra_razon_texto">Otra razón:</label>' +
              '<input type="text" class="form-control" value =' +
              datos.razon +
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
          }
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

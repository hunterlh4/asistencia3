const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formulario");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
const select1 = document.getElementById("trabajador");


// FORMULARIO
const id = document.querySelector('#id').value;
const licencia = document.querySelector('#licencia') ;
const trabajador = document.querySelector('#trabajador_id');
const entrada = document.querySelector('#entrada');
const salida = document.querySelector('#salida'); 
const total_reloj = document.querySelector('#total_reloj');
const total_horario= document.querySelector('#total_horario');
const tardanza = document.querySelector('#tardanza');
const tardanza_cantidad= document.querySelector('#tardanza_cantidad');
const justificacion_input = document.querySelector('#prueba');
const reloj_1 = document.querySelector('#reloj_1');
const reloj_2 = document.querySelector('#reloj_2');
const reloj_3 = document.querySelector('#reloj_3');
const reloj_4 = document.querySelector('#reloj_4');
const reloj_5 = document.querySelector('#reloj_5');
const reloj_6 = document.querySelector('#reloj_6');
const reloj_7 = document.querySelector('#reloj_7');
const reloj_8 = document.querySelector('#reloj_8');
// 
var events =[];

var boleta =[];
var today = new Date();
year = today.getFullYear();
month = today.getMonth();
day = today.getDate();

var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
var dayNames = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

var dayNameMap = {
    'Sunday': 'Domingo',
    'Monday': 'Lunes',
    'Tuesday': 'Martes',
    'Wednesday': 'Miércoles',
    'Thursday': 'Jueves',
    'Friday': 'Viernes',
    'Saturday': 'Sábado'
  };
// calendario click, buttons y renderizado de datos
  var calendar = $("#myEvent").fullCalendar({
    height: "auto",
    defaultView: "month",
    editable: false,
    selectable: true,
    locate: 'es',
    displayEventTitle:false,
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
   

    
    let id = calEvent.id;
    let fechaString  = calEvent.fecha;
    var fecha = new Date(fechaString);

    let licencia = calEvent.title;
    let trabajador = calEvent.trabajador_id;
    let entrada = calEvent.entrada;
    let salida = calEvent.salida;
    let total_reloj = calEvent.total_reloj;
    let total_horario = calEvent.total_horario;
    let tardanza= calEvent.tardanza;
    let tardanza_cantidad = calEvent.tardanza_cantidad;
    var justificacion = calEvent.justificacion;
    let reloj_1  = calEvent.reloj_1;
    let reloj_2 = calEvent.reloj_2;
    let reloj_3 = calEvent.reloj_3;
    let reloj_4 = calEvent.reloj_4;
    let reloj_5 = calEvent.reloj_5;
    let reloj_6 = calEvent.reloj_6;
    let reloj_7 = calEvent.reloj_7;
    let reloj_8 = calEvent.reloj_8;
    let fechaformateada = new Date(fechaString)
    let año = fechaformateada.getFullYear();
    let mes = (fechaformateada.getMonth() + 1).toString().padStart(2, '0');
    let dia = (fechaformateada.getDate()).toString().padStart(2, '0');
    fechaFormateada = año + "-" + mes + "-" + dia;
        if(licencia !=='SR'){
        // frm.reset();
        var dayOfWeek = dayNames[fecha.getDay()+1];
        // Obtener el nombre del mes
        var month = monthNames[fecha.getMonth()];

        // Obtener el día del mes
        var dayOfMonth = fecha.getDate()+1;

        // Obtener el año
        var year = fecha.getFullYear();
        // Construir la cadena de fecha
        var dateString = dayOfWeek + " " + dayOfMonth + " de " + month + " del " + year;

    

        titleModal.textContent = "Asistencia del " + dateString ;

        document.querySelector('#id').value = id;
        document.querySelector('#licencia').innerHTML = licencia;
        document.querySelector('#trabajador_id').value = trabajador;
        document.querySelector('#entrada').innerHTML = entrada;
        document.querySelector('#salida').innerHTML = salida; 
        document.querySelector('#total_reloj').innerHTML = total_reloj;
        document.querySelector('#total_horario').innerHTML = total_horario;
        document.querySelector('#tardanza').innerHTML = tardanza;
        document.querySelector('#tardanza_cantidad').innerHTML = tardanza_cantidad;
       
     
        // var pruebaInput = document.querySelector('#justificacion');
        // pruebaInput.innerHTML = "Texto para el input de prueba";
        // var pruebaInput2 = document.querySelector('#justificacion');
        // console.log(pruebaInput2.value);
        document.querySelector('#reloj_1').innerHTML = reloj_1;
        document.querySelector('#reloj_2').innerHTML = reloj_2;
        document.querySelector('#reloj_3').innerHTML = reloj_3;
        document.querySelector('#reloj_4').innerHTML = reloj_4;
        document.querySelector('#reloj_5').innerHTML = reloj_5;
        document.querySelector('#reloj_6').innerHTML = reloj_6;
        document.querySelector('#reloj_7').innerHTML = reloj_7;
        document.querySelector('#reloj_8').innerHTML = reloj_8;
        // console.log(justificacion);
            
        frm.reset();
        llenarBoleta(fechaFormateada,trabajador);
        const textoModificado = licencia.replace(/-Boleta/g, ' ');
        document.querySelector('#licencia').innerHTML = textoModificado;

        document.querySelector('#justificacion').value = justificacion;
        myModal.show();
       
    }

    // Imprimir los valores en la consola
    
},

viewRender: function(view, element) {
  // Cambia el texto del encabezado del calendario
 
  var mesActual = view.intervalStart.month();
  var añoActual = view.intervalStart.year();
  var nombremes = monthNames[mesActual];

 

  // $(".fc-center").text("<h5>" + nombreMes + ' - ' + añoActual + "</h5>");
  $(".fc-center").text(nombremes+' '+añoActual);

  // Cambia los nombres de los días de la semana
  $(".fc-list-heading-main").text(monthNames[mesActual]);

  $(".fc-day-header").each(function(index) {
      $(this).text(dayNames[index]);
  });

   
    $(".fc-list-heading-alt").each(function() {
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

  $(".fc-listMonth-button").click(function() {
    // Cambiar el texto del botón a "Lista"
    $(this).text("Lista");
    // Llamar a la función hola()
    modificarCalendario();
});

$(".fc-month-button").click(function() {
    // Cambiar el texto del botón a "Mes"
    $(this).text("Mes");
    // Llamar a la función hola()
    modificarCalendario();
});


  // var monthName = moment(view.start).format("MMMM");
  //               $(".fc-center").text(monthName);
},

customButtons: {
  prev1: {
      text: 'Anterior',
      click: function() {
          // $('#myEvent').fullCalendar('prev');
        //   console.log("Se hizo clic en el botón Anterior");
          // var prevDate = $('#myEvent').fullCalendar('getDate').subtract(1, 'months');
          // asistencias(prevDate.month() + 1, prevDate.year());
               
      }
  },
  next2: {
      text: 'Siguiente',
      click: function() {
          // $('#myEvent').fullCalendar('next');
        //   console.log("Se hizo clic en el botón Siguiente");
          // var nextDate = $('#myEvent').fullCalendar('getDate').add(1, 'months');
          // asistencias(nextDate.month() + 1, nextDate.year());
      }
  },
  today3: {
      text: 'Hoy',
      click: function() {
          // $('#myEvent').fullCalendar('today');
        //   console.log("Se hizo clic en el botón Hoy");
          // var todayDate = new Date();
          // asistencias(todayDate.getMonth() + 1, todayDate.getFullYear());
               
      }
  }
}


});


//   function asistencias(moth,year){
//     var trabajadores_id = 1; //$('#trabajadores_id').val()
  
//     // console.log(moth,year,trabajadores_id);
  
//   }

  var currentDate = $('#myEvent').fullCalendar('getDate');
$(document).ready(function() {
  // Agrega un evento de clic al botón "Anterior"
  $('.fc-prev-button').on('click', function() {
    // Obtiene la fecha actual del calendario
    currentDate.subtract(1, 'months');
        // Obtiene el nuevo mes y año
        var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
        var newYear = currentDate.year();
        // Realiza alguna acción con el nuevo mes y año
        // asistencias(newMonth, newYear);
        modificarCalendario();
});

// Agrega un evento de clic al botón "Siguiente"
$('.fc-next-button').on('click', function() {
    // Obtiene la fecha actual del calendario
    currentDate.add(1, 'months');
    // Obtiene el nuevo mes y año
    var newMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
    var newYear = currentDate.year();
    // Realiza alguna acción con el nuevo mes y año
    // asistencias(newMonth, newYear);
    modificarCalendario();
});

// Agrega un evento de clic al botón "Hoy"
$('.fc-today-button').on('click', function() {
    // Obtiene la fecha actual del calendario
    currentDate = $('#myEvent').fullCalendar('getDate');
        // Obtiene el mes y año actual
        var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
        var currentYear = currentDate.year();
        // Realiza alguna acción con el mes y año actual
        // asistencias(currentMonth, currentYear);
        modificarCalendario();
});
});


document.addEventListener("DOMContentLoaded", function() {
  
        var currentDate = $('#myEvent').fullCalendar('getDate');
        var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
        var currentYear = currentDate.year();
   
        var trabajador = miVariable;
        // console.log(miVariable);
        buscarBoleta(trabajador,currentMonth,currentYear);
});

// actualizar justificacion
// function guardar(){
//         frm.addEventListener("submit", function(e) {
//             e.preventDefault();
           
//             const formData = new FormData(frm);
//             const url = base_url + "Asistencia/registrar";
          
//             $.ajax({
//                 url: url,
//                 method: 'POST',
//                 data: formData,
//                 processData: false, 
//                 contentType: false,
//                 beforeSend: function() {
//                     // Se ejecuta antes de enviar la solicitud
//                     // console.log('Enviando solicitud...');
//                 },
//                 success: function(response) {
//                     // Se ejecuta cuando se recibe una respuesta exitosa
//                     // console.log(response);
                    
//                     const res = JSON.parse(response);
//                     if (res.icono == "success") {
                        
//                         // calendar.ajax.reload();

                     
//                         $('#myEvent').fullCalendar('refetchEvents');
//                         // modificarCalendario();
//                         modificarCalendario();
//                         frm.reset();
//                         cerrarModal(); 
//                     }
//                     Swal.fire("Aviso", res.msg.toUpperCase(), res.icono);
                  
//                 },
//                 error: function(xhr, status, error) {
//                     // Se ejecuta si hay algún error en la solicitud
//                     console.error('Error en la solicitud:', error);
//                 }
//             });
    
           
//         });
    
// }

// llenar calendario con boleta
function buscarBoleta(id,currentMonth,currentYear){
    
    var parametros = {
                        
        "trabajador_id": id 
    };
    const url = base_url + "Boleta/buscarPorFechaSola";
    $.ajax({
        data:  parametros, //datos que se envian a traves de ajax
        url:   url, //archivo que recibe la peticion
        type:  'POST', //método de envio
        success:  function (response) {
            const res = JSON.parse(response);
            boleta = res;
            // console.log(boleta);
            // console.log('boleta')
           
            verAsistencia(currentMonth,currentYear, id,boleta);
                   
        },
        
    });
}
//  llenar calendario
function verAsistencia(mes,anio,id,boleta) {
    // Aquí puedes realizar cualquier acción que desees con la fecha del calendario y el valor seleccionado
 
    // Llama a tu función deseada con estos valores como parámetros
    var parametros = {
        "mes": mes,
        "anio": anio,
        "id": id
    };

  
    const url = base_url + "asistencia/listaCalendarioAsistenciaTrabajador/";
   
        $.ajax({
            data:  parametros, //datos que se envian a traves de ajax
            url:   url, //archivo que recibe la peticion
            type:  'post', //método de envio
            beforeSend: function () {
                    // console.log('procesando');
            },
            success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    // console.log(response);
                    events = [];
                    const res = JSON.parse(response);
                    // console.log(boleta);
                    res.forEach((evento) => {

                        
                        // console.log(boleta);
                        for (let i = 0; i < boleta.length; i++) {
                            var boletacalendar ='';
                            const boletaFecha = boleta[i].fecha_inicio;
                           
                             if (boletaFecha == evento.fecha) {
                                
                                // console.log(boletaFecha +'|-'+evento.fecha);
                                
                              boletacalendar ='-Boleta';
                            //   console.log('fecha cambiada'+ boletaFecha + 'segunda'+ evento.fecha);
                             } 
                             evento.licencia = evento.licencia + boletacalendar;
                            // console.log(boletaFecha +'|'+evento.fecha);
                        }
                        
                        

                        events.push({
                            id: evento.aid,
                            title: evento.licencia,
                            start: new Date(evento.anio, evento.mes-1, evento.dia,evento.hora_entrada,evento.minuto_entrada),
                            end: new Date(evento.anio, evento.mes-1, evento.dia,evento.hora_salida,evento.minuto_salida),
                       
                            trabajador_id: evento.tid,
                            backgroundColor: "transparent",
                            entrada: evento.entrada,
                            salida: evento.salida,
                            total_reloj: evento.total_reloj,
                            total_horario: evento.total_horario,
                            tardanza: evento.tardanza,
                            tardanza_cantidad : evento.tardanza_cantidad,
                            justificacion : evento.justificacion,
                            reloj_1 : evento.reloj_1,
                            reloj_2 : evento.reloj_2,
                            reloj_3 : evento.reloj_3,
                            reloj_4 : evento.reloj_4,
                            reloj_5 : evento.reloj_5,
                            reloj_6 : evento.reloj_6,
                            reloj_7 : evento.reloj_7,
                            reloj_8 : evento.reloj_8,
                            fecha :  evento.fecha
                            
                        });
                       
                    });
                    
                    // // Ahora events contiene todos tus eventos en el formato deseado
                    // console.log(events);
                        

                    $('#myEvent').fullCalendar('removeEvents');
                    $('#myEvent').fullCalendar('addEventSource', events);

                    // Refresca el calendario para mostrar los nuevos eventos
                    $('#myEvent').fullCalendar('refetchEvents');
                    // modificarCalendario();
                    modificarCalendario();
                    
                    
            }
    });
    
   
}
// modificar datos del calendario
function modificarCalendario(){
    
    const timeElements = document.querySelectorAll('.fc-time');
    timeElements.forEach((element) => {
        // Obtén el texto del elemento
        // console.log(element);
        let timeText = element.innerText.trim();
        const titleElement = element.parentElement.querySelector('.fc-title');
        const contenido = titleElement.innerText.trim();
    
        
        // Divide el texto en horas y minutos
        if (timeText.includes('12a')) {
            if (titleElement && contenido === 'SR') {
                timeText = ''; // Si es "12a" y fc-title contiene "SR", convierte el texto en una cadena vacía
            }
        } else if (timeText.includes('12p')) {
            timeText = timeText.replace('12p', '12:00p'); // Si es "12p", agrega ":00"
        } else {
            // Divide el texto en horas y minutos
            const [hour, minute, period] = timeText.split(/:- /);
    
            // Verifica si es antes o después del mediodía
            if (period === 'a' || period === 'p') {
                // Modifica la hora para que esté en formato de 12 horas
                let modifiedHour = parseInt(hour) % 12;
                if (modifiedHour === 0) {
                    modifiedHour = 12;
                }
    
                // Crea la nueva hora en formato deseado
                timeText = `${modifiedHour}:${minute}${period}`;
            }
        }
        // Asigna el nuevo texto al elemento
        element.innerText = timeText;  
    });
    

    $(".fc-list-item").each(function() {
        // Encuentra el hijo .fc-list-item-title a
        const titleElement = $(this).find('.fc-list-item-title a');
        // Verifica si el texto del enlace es "SR"
        if (titleElement.length && titleElement.text().trim() === 'SR') {
            // Encuentra el elemento .fc-list-item-time y establece su texto como una cadena vacía
            const timeElement = $(this).find('.fc-list-item-time');
            timeElement.text('');
        }
        let texto = titleElement.text().trim();
        if (texto.endsWith('-Boleta')) {
            // Reemplaza "-Boleta" por el nuevo contenido deseado
            const textoModificado = texto.replace(/-Boleta/g, ' ');
            titleElement.html('<span style="color: orange; font-weight: bold;">' + textoModificado + '</span>');
            
            // Asigna el nuevo contenido al elemento
            // titleElement.html(texto);
        }
    });

    $(".fc-title").each(function() {
        // Verifica si el texto del título termina con "-Boleta"
        var texto ='';
         texto = $(this).text().trim();
        if (texto.endsWith('-Boleta')) {
            // Reemplaza "-Boleta" por el nuevo contenido deseado
            const textoModificado = texto.replace(/-Boleta/g, ' ');
            $(this).html('<span style="color: blue; font-weight: bold;">' +  textoModificado +'</span>');

            // console.log('cambio');
        }
    });
    
}
//  llenar con la boleta al calendario
function llenarBoleta(fecha,trabajador_id){
    // fecha ='2024-05-01';
    // trabajador_id = 812;

    let fechaformateada = new Date(fecha);
    fechaformateada.setDate(fechaformateada.getDate() +2); // Sumar 1 día

    let año = fechaformateada.getFullYear();
    let mes = (fechaformateada.getMonth() + 1).toString().padStart(2, '0');
    let dia = fechaformateada.getDate().toString().padStart(2, '0');
    let fechaFormateada = año + "-" + mes + "-" + dia;
    // console.log(fechaFormateada);
    var parametros = {
        "fecha": fechaFormateada,
        "trabajador_id": trabajador_id
    };
    $.ajax({
        data:  parametros,
        url: base_url + "Boleta/buscarPorFecha",
        type: 'POST',
        beforeSend: function () {
            // console.log('procesando llenarBoleta');
        },
        success: function(response) {
                // datos = JSON.parse(response);
                const res = JSON.parse(response);
                
                var html=''; 
                // console.log(datos);
                $('#resultado').empty();
                res.map(datos => {
                    // console.log(datos);
                    if (datos !== undefined && datos.numero !== undefined) {
                       
                            html += '<div class="row text-center">' +
                            '<hr>' +
                            '<div class="col-12">' +
                                '<div class="form-group">' +
                                    '<h4 for="numero">Boleta N° <span>' + datos.numero + '</span></h4>' +
                                '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                '<label for="aprobado_por">Aprobado por:</label>' +
                                    '<input type="text" class="form-control" placeholder="Aprobado por" name="aprobado_por" id="aprobado_por" value="' + datos.aprobado_por + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="fecha_inicio">Desde:</label>' +
                                    '<input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="' + datos.fecha_inicio + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="fecha_fin">Hasta:</label>' +
                                    '<input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="' + datos.fecha_fin + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="hora_salida">Salida:</label>' +
                                    '<input type="time" class="form-control" name="hora_salida" id="hora_salida" value="' + datos.hora_salida + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="hora_entrada">Entrada:</label>' +
                                    '<input type="time" class="form-control" name="hora_entrada" id="hora_entrada" value="' + datos.hora_entrada + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="duracion">Duración:</label>' +
                                    '<input type="time" class="form-control" name="duracion" id="duracion" value="' + datos.duracion + '" disabled>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-4">' +
                                '<div class="form-group">' +
                                    '<label for="razon">Razón:</label>' +
                                    '<select class="form-control" name="razon" id="razon" disabled>';
                                    // Agregar opciones de razón
                                
                                    
                                    if(datos.razon=='Comision de Servicio'|| datos.razon=='Compensacion Horas'|| datos.razon=='Motivos Particulares'||datos.razon=='Enfermedad'||datos.razon=='ESSALUD'){
                                        html += '<option value="' + datos.razon + '">' + datos.razon + '</option>';
                                        html += '</select>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-4">' +
                                    '<div class="form-group" id="otra_razon" >' +
                                    '<label for="otra_razon_texto">Otra razón:</label>' +
                                    '<input type="text" class="form-control" value ='+datos.razon+' name="otra_razon_texto" id="otra_razon_texto" disabled>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-4">' +
                                    '<div class="form-group">' +
                                    '<label for="observaciones">Observaciones:</label>' +
                                    '<textarea class="form-control" name="observaciones" id="observaciones" rows="3" disabled>' + datos.observaciones + '</textarea>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                    }else{
                                        html += '<option value="">' + 'Otra' + '</option>';
                                        html += '</select>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-4">' +
                                    '<div class="form-group" id="otra_razon" >' +
                                    '<label for="otra_razon_texto">Otra razón:</label>' +
                                    '<input type="text" class="form-control" value ='+datos.razon+' name="otra_razon_texto" id="otra_razon_texto" disabled>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="col-4">' +
                                    '<div class="form-group">' +
                                    '<label for="observaciones">Observaciones:</label>' +
                                    '<textarea class="form-control" name="observaciones" id="observaciones" rows="3" disabled>' + datos.observaciones + '</textarea>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                }
                            }
                        });
                        $('#resultado').html(html);
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
            

function abrirModal() {
    myModal.show();
}

// Función para cerrar el modal
function cerrarModal() {
    myModal.hide();
}
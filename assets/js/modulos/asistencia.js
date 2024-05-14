const nuevo = document.querySelector("#nuevo_registro");
const frm = document.querySelector("#formUsuarios");
const titleModal = document.querySelector("#titleModal");
const btnAccion = document.querySelector("#btnAccion");
const myModal = new bootstrap.Modal(document.getElementById("nuevoModal"));
const select1 = document.getElementById("trabajador");
var events =[];

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
   
    // console.log('cambio de pagina');
    // let id = $("#id").val(calEvent.id);
    // Obtener el valor del campo titulo
    // let licencia = $("#titulo").val(calEvent.title);
    
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
    let dia = fechaformateada.getDate().toString().padStart(2, '0');
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
        document.querySelector('#reloj_1').innerHTML = reloj_1;
        document.querySelector('#reloj_2').innerHTML = reloj_2;
        document.querySelector('#reloj_3').innerHTML = reloj_3;
        document.querySelector('#reloj_4').innerHTML = reloj_4;
        document.querySelector('#reloj_5').innerHTML = reloj_5;
        document.querySelector('#reloj_6').innerHTML = reloj_6;
        document.querySelector('#reloj_7').innerHTML = reloj_7;
        document.querySelector('#reloj_8').innerHTML = reloj_8;
        // console.log(fechaFormateada +'|'+trabajador);
        llenarBoleta(fechaFormateada,trabajador);


        myModal.show();
       
    }

    // Imprimir los valores en la consola
    
},

viewRender: function(view, element) {
  // Cambia el texto del encabezado del calendario
 
  var mesActual = view.intervalStart.month();
  var añoActual = view.intervalStart.year();
  var nombremes = monthNames[mesActual];
  // element.find('.fc-time').after('<br>');
  // console.log('Mes actual:', mesActual);
 

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


  // var monthName = moment(view.start).format("MMMM");
  //               $(".fc-center").text(monthName);
},

customButtons: {
  prev1: {
      text: 'Anterior',
      click: function() {
          // $('#myEvent').fullCalendar('prev');
          console.log("Se hizo clic en el botón Anterior");
          // var prevDate = $('#myEvent').fullCalendar('getDate').subtract(1, 'months');
          // asistencias(prevDate.month() + 1, prevDate.year());
               
      }
  },
  next2: {
      text: 'Siguiente',
      click: function() {
          // $('#myEvent').fullCalendar('next');
          console.log("Se hizo clic en el botón Siguiente");
          // var nextDate = $('#myEvent').fullCalendar('getDate').add(1, 'months');
          // asistencias(nextDate.month() + 1, nextDate.year());
      }
  },
  today3: {
      text: 'Hoy',
      click: function() {
          // $('#myEvent').fullCalendar('today');
          console.log("Se hizo clic en el botón Hoy");
          // var todayDate = new Date();
          // asistencias(todayDate.getMonth() + 1, todayDate.getFullYear());
               
      }
  }
}


});




  
  function asistencias(moth,year){
    var trabajadores_id = 1; //$('#trabajadores_id').val()
  
    // console.log(moth,year,trabajadores_id);
  
  }

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
        asistencias(newMonth, newYear);
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
    asistencias(newMonth, newYear);
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
        asistencias(currentMonth, currentYear);
        modificarCalendario();
});
});


document.addEventListener("DOMContentLoaded", function() {
    llenarselect();

    $('#trabajador').on('change', function() {
        // Obtiene el valor seleccionado del select
        var selectedValue = $(this).val();
        // Muestra el valor en la consola
        var currentDate = $('#myEvent').fullCalendar('getDate');
        var currentMonth = currentDate.month() + 1; // Sumamos 1 porque los meses son indexados desde 0
        var currentYear = currentDate.year();
        verAsistencia(currentMonth,currentYear, selectedValue);
        // verAsistencia();
    });

});

// function verAsistencia() {
//     // Aquí puedes realizar cualquier acción que desees con la fecha del calendario y el valor seleccionado
//     // console.log('Fecha del calendario:', anio +'-'+mes);
//     // console.log('Valor seleccionado del combo:', valorSeleccionado);
//     // Llama a tu función deseada con estos valores como parámetros
//     id = 812;
//     anio = '2024';
//     mes = '5';

//     const url = base_url + `asistencia/listaCalendarioAsistenciaTrabajador/812/${anio}/${mes}`;
//     const http = new XMLHttpRequest();
//     http.open("GET", url, true);
//     http.send();

//     http.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             // frm.reset();
//             // resetRequiredFields();
//             console.log(this.responseText);
//             const res = JSON.parse(this.responseText);
           
//         }else{
//             console.log('no llego');
//         }
//     }
// }

function verAsistencia(mes,anio,id) {
    // Aquí puedes realizar cualquier acción que desees con la fecha del calendario y el valor seleccionado
    // console.log('Fecha del calendario:', anio +'-'+mes);
    // console.log('Valor seleccionado del combo:', valorSeleccionado);
    // Llama a tu función deseada con estos valores como parámetros
    var parametros = {
        "mes": mes,
        "anio": anio,
        "id": id
    };


    const url = base_url + "asistencia/listaCalendarioAsistenciaTrabajador/";
    var boleta=[];
        $.ajax({
            data:  parametros, //datos que se envian a traves de ajax
            url:   url, //archivo que recibe la peticion
            type:  'post', //método de envio
            beforeSend: function () {
                    console.log('procesando');
            },
            success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                    // console.log(response);
                    events = [];
                    const res = JSON.parse(response);
                   
                    var parametros = {
                        
                        "trabajador_id": res[0].tid 
                    };
                    const url = base_url + "Boleta/buscarPorFechaSola";
                    $.ajax({
                        data:  parametros, //datos que se envian a traves de ajax
                        url:   url, //archivo que recibe la peticion
                        type:  'POST', //método de envio
                        success:  function (responseboleta) {
                            const resb = JSON.parse(responseboleta);
                            boleta = resb;
                            
                            // console.log(boleta);
                            
                        }
                    });
                    res.forEach((evento) => {
                        console.log(boleta);
                        for (let i = 0; i < boleta.length; i++) {
                            const boletaFecha = boleta[i].fecha_inicio;
                            
                            if (boletaFecha === evento.fecha) {
                                
                                console.log(boletaFecha +'|-'+evento.fecha);
                              
                            } 
                            console.log(boletaFecha +'|'+evento.fecha);
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
                    modificarCalendario();

                    
                    
            }
    });
    
   
}

function modificarCalendario(){
    const timeElements = document.querySelectorAll('.fc-time');
    timeElements.forEach((element) => {
        // Obtén el texto del elemento
        // console.log(element);
        let timeText = element.innerText.trim();
        const titleElement = element.parentElement.querySelector('.fc-title');
        // Divide el texto en horas y minutos
        if (timeText.includes('12a')) {
            if (titleElement && titleElement.innerText.trim() === 'SR') {
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
}



function llenarselect(){
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
                select1.appendChild(option);
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}





function llenarBoleta(fecha,trabajador_id){
    // fecha ='2024-05-01';
    // trabajador_id = 812;
    var parametros = {
        "fecha": fecha,
        "trabajador_id": trabajador_id
    };
    $.ajax({
        data:  parametros,
        url: base_url + "Boleta/buscarPorFecha",
        type: 'POST',
        beforeSend: function () {
            console.log('procesando llenarBoleta');
        },
        success: function(response) {
                // datos = JSON.parse(response);
                const res = JSON.parse(response);
                const datos = res[0];
               console.log(datos);

               $('#resultado').empty();
               var html = '<div class="row text-center">' +
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
                        
                        $('#resultado').html(html);
            
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}




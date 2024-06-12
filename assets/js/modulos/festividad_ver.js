let calendario = document.getElementById('Calendario');

const datos = [
    // {
    //     title: 'Evento 1',
    //     start: '2024-06-10',
    //     end: '2024-06-12',
    //     backgroundColor:'green'
    // },
    // {
    //     title: 'Evento 2',
    //     start: '2024-06-15',
    //     allDay: true
    // },
    // {
    //     title: 'Evento 3',
    //     start: '2024-06-20T14:30:00',
    //     allDay: true
    // }
];

const dayNames2 = {
    Sunday: "Domingo",
    Monday: "Lunes",
    Tuesday: "Martes",
    Wednesday: "Miércoles",
    Thursday: "Jueves",
    Friday: "Viernes",
    Saturday: "Sábado",
    Sun: "Domingo",
    Mon: "Lunes",
    Tue: "Martes",
    Wed: "Miércoles",
    Thu: "Jueves",
    Fri: "Viernes",
    Sat: "Sábado",
  };

  const dayNames = [
    "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes",
    "Sábado",
    "Domingo",
  ];

const monthNames = [
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


function ModificarCalendario() {
    // // Modificar los días de la semana

    // const diasSemana = document.querySelectorAll('.fc-day-header');
    // diasSemana.forEach(elemento  => {
      
    //     const textoOriginal = elemento.textContent.trim();
    //     // Buscar el texto original en el objeto dayNames y obtener su equivalente en español
    //     const nuevoTexto = dayNames[textoOriginal] || textoOriginal;
    //     elemento.textContent = nuevoTexto;
    // });


    // // Modificar los nombres de los meses
    // const meses = document.querySelectorAll('.fc-toolbar-title');
    // meses.forEach((mes, index) => {
    //     const nombreMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    //     mes.textContent = nombreMeses[index];
    // });

  
}

function obtenerNumeroMes(nombreMes) {
    return monthNames.findIndex(mes => mes === nombreMes) + 1;
}

function MostrarCalendario(){

    const fechaActual = new Date();
    const añoActual = fechaActual.getFullYear();
    // const añoActual = 2025;
    const fechaInicio = añoActual + '-01-01'; // Primero de enero del año actual
    const fechaFin = (añoActual + 1) + '-01-01'; // Primer día del próximo año


    const url = base_url + "Festividades/listar";
    $.ajax({
        data: [], //datos que se envian a traves de ajax
        url: url, //archivo que recibe la peticion
        type: "post", //método de envio
        beforeSend: function () {
          // console.log('procesando');
        },
        success: function (response) {
            const res = JSON.parse(response);
            

            res.forEach(valores => {
                let diaFormateado = String(valores.dia_inicio).padStart(2, '0');
                let mesFormateado = String(valores.mes_inicio).padStart(2, '0');

                let diaFormateado_2 = String(valores.dia_fin).padStart(2, '0');
                let mesFormateado_2 = String(valores.mes_fin).padStart(2, '0');
                // let fechaFormateada = mesFormateado + '-' + diaFormateado;
                // const fecha = añoActual +'-'+ valores.mes+'-'+ valores.dia;
                const fecha_inicio = añoActual +'-'+ mesFormateado+'-'+ diaFormateado;
                const fecha_fin = añoActual +'-'+ mesFormateado+'-'+ diaFormateado;
                // const fecha = '2024-06-12';
                const evento = {
                    title: valores.nombre,
                    start: fecha_inicio,
                    end: fecha_fin,
                    id: valores.id,
                    
                    allDay: true,
                    backgroundColor: valores.tipo === 'feriado' ? 'orange' : (valores.tipo === 'institucional' ? 'green' : undefined)
                };
                datos.push(evento);
            });

            $("#Calendario").fullCalendar("removeEvents");
            $("#Calendario").fullCalendar("addEventSource", datos);
      
            // Refresca el calendario para mostrar los nuevos eventos
            $("#Calendario").fullCalendar("refetchEvents");

        },
    });
    console.log(datos);

   


   const calendar = $("#Calendario").fullCalendar({
    // calendario.fullCalendar({
        selectable: true,
        editable: true,
        locale: 'es',
        firstDay: 1,
        
        // initialView: 'dayGridMonth',
        defaultView: "month",
        height: 'auto',
        showNonCurrentDates: true,
        events: datos,
        header: {
            right: "prev,next today",
            center: "title",
            left: "",
        },
        validRange: {
            start: fechaInicio,
            end: fechaFin
        },
        eventClick: function (calEvent, jsEvent, view) {
            console.log(calEvent.id);
        },
        
        viewRender: function (view, element) {
            console.log('se hizo cambio')
            // cambiarIdiomaCalendario();
            var mesActual = view.intervalStart.month();
            var añoActual = view.intervalStart.year();
            var nombremes = monthNames[mesActual];
            // CAMBIAR NOMBRE DEL MES
            $(".fc-center").text(nombremes + " " + añoActual);
            // CAMBIAR NOMBRE DEL DIA

            $(".fc-day-header").each(function (index) {
            $(this).text(dayNames[index]);
            });

            $(".fc-month-button").text("Mes");
            $(".fc-today-button").text("Ahora");
            
        },
        eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
            handleEventChange(event);
        },
        eventResize: function(event, delta, revertFunc, jsEvent, ui, view) {
            handleEventChange(event);
        },
    });
   
}

function handleEventChange(event) {
    console.clear();
    const calendario_id = event.id;
    let calendario_inicio = event.start.format('YYYY-MM-DD');
    let calendario_fin = event.end ? event.end.clone().subtract(1, 'days').format('YYYY-MM-DD') : calendario_inicio;

    console.log('Se ha movido/redimensionado un evento:', calendario_id);
    console.log('inicio antes:', calendario_inicio);
    console.log('fin antes:', calendario_fin);
    let inicio = calendario_inicio.split('-');
    let fin = calendario_fin.split('-');
    let dia_Inicio = parseInt(inicio[2], 10).toString(); // Obtienes el día 
    let mes_Inicio = parseInt(inicio[1], 10).toString(); // Obtienes el mes
    let dia_Fin = parseInt(fin[2], 10).toString(); // Obtienes el día
    let mes_Fin = parseInt(fin[1], 10).toString();; // Obtienes el mes

    const url = base_url + "Festividades/actualizarCalendar";
    var parametros = {
        id: calendario_id,
        dia_inicio: dia_Inicio,
        mes_inicio: mes_Inicio,
        dia_fin:dia_Fin,
        mes_fin: mes_Fin,
      };
      console.log(parametros);
    // $.ajax({
    //     data: parametros, //datos que se envian a traves de ajax
    //     url: url, //archivo que recibe la peticion
    //     type: "post", //método de envio
    //     beforeSend: function () {
    //       // console.log('procesando');
    //     },
    //     success: function (response) {
    //         const res = JSON.parse(response);
    //     },
    // })

    // Obtén el evento actualizado del calendario
    // var eventoEnCalendario = $('#Calendario').fullCalendar('clientEvents', event.id)[0];
    // let calendario_inicio_actual = eventoEnCalendario.start.format('YYYY-MM-DD');
    // let calendario_fin_actual = eventoEnCalendario.end ? eventoEnCalendario.end.clone().subtract(1, 'days').format('YYYY-MM-DD') : calendario_inicio_actual;

    // console.log('Nueva posición en el inicio:', calendario_inicio_actual);
    // console.log('Nueva posición en el fin:', calendario_fin_actual);
}

document.addEventListener('DOMContentLoaded', function() {
    
    
    MostrarCalendario();
    ModificarCalendario();
    
   
});







// Función para cambiar el idioma del calendario




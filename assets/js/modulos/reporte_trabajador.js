// reporte de los trabajadores por asistencia temprana - tarde - faltas por fechas  enero a abril
const trabajador = document.querySelector('#trabajador');
const mes = document.querySelector('#mes');
const anio = document.querySelector('#anio');
const currentYear = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function() {
    llenarSelectAnio();
    llenarSelectMes();
    llenarSelectTrabajador();
})

function generar2(){
    const trabajadores = Array.from(trabajador.selectedOptions).map(option => option.value);
    const meses = Array.from(mes.selectedOptions).map(option => option.value);
    const anioSeleccionado = anio.value;

    // console.log(trabajadores);
    // console.log(meses);
    // console.log(anioSeleccionado);

    $.ajax({
        url: base_url + "Reporte/generar_trabajador",
        type: 'POST',
        data: { trabajadores: trabajadores, meses:meses, anio:anioSeleccionado }, // Puedes enviar datos adicionales si es necesario
        success: function(response) {
            // datos = JSON.parse(response); 
            // Limpiar el select aprobadorElement
           console.log(response);
        //    console.log(datos);
           
           
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function generar(){
    const trabajadores = Array.from(trabajador.selectedOptions).map(option => option.value);
    const meses = Array.from(mes.selectedOptions).map(option => option.value);
    const anioSeleccionado = anio.value;

    trabajadores.forEach(trabajador => {
        // Dentro de cada trabajador, recorrer el array de meses
        meses.forEach(mes => {
            // Imprimir trabajador, mes y año seleccionado
          

            $.ajax({
                url: base_url + "Reporte/generar_trabajador",
                type: 'POST',
                data: { trabajador: trabajador, mes:mes, anio:anioSeleccionado }, // Puedes enviar datos adicionales si es necesario
                success: function(response) {
                    // datos = JSON.parse(response); 
                    // Limpiar el select aprobadorElement
                   console.log(response);
                //    console.log(datos);
                   
                   
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

        });
    });

}
function llenarSelectAnio() {
    anio.innerHTML = '';
    // Generate the options for current year, previous year, and year before that
    for (let i = 0; i < 3; i++) {
        const option = document.createElement('option');
        option.value = currentYear - i;
        option.textContent = currentYear - i;
        anio.appendChild(option);
    }
}
function llenarSelectMes() {
   
    mes.innerHTML = '';
    
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    // Generate the options for current year, previous year, and year before that
    for (let i = 0; i < 12; i++) {
        const option = document.createElement('option');
        option.value = i+1;
        option.textContent = monthNames[i];
        mes.appendChild(option);
       
    }
}
function llenarSelectTrabajador() {
    $.ajax({
        url: base_url + "Trabajador/listar",
        type: 'POST',

        success: function(response) {
            datos = JSON.parse(response); 
            // console.log(response);
            // Limpiar el select aprobadorElement
            trabajador.innerHTML = '';
            datos.map(function(item) {
                var option = document.createElement('option');
                if (item.testado === "Inactivo" ) {
                    option.style.color = "red";
                }
                option.value = item.tid;
                if(item.tdni==null){
                    option.text = item.tnombre;
                }else{
                    option.text = item.tnombre+ ' - '+ item.tdni;
                }
                trabajador.appendChild(option);
                
                });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
# programacion
 crear un modulo donde se registre una programacion en el que reemplazara al horario de entrada y salida fija

 primero validar si existe uno, en caso no exista no pasa nada , en caso exista, el horario y salida fija cambiaran a lo sque hay en la programacion y  se registrara como siempre

 usar : http://localhost/plantilla-ui/forms-advanced-form.html tags para programarle un horario

# en configuracion
- colocar el tiempo de tardanza maximo, 5 minutos modificable 
- vacaciones por detalle trabajador
- reportes
- el importar de samu


# horarios
Validar si solamente existira un horario digamos
1 persona no puede venir 7:30 - 10:30 | 12:30 a 15:30 | 15:40

siempre asi
vigilancia 1 7:30 - 10:30
vigilancia 2 7:30 - 10:30
vigilancia 3 7:30 - 10:30

# reportes

--TARDE
SELECT 
    COUNT(*) AS cantidad_total
FROM 
    asistencia 
WHERE 
    (tardanza_cantidad > 0 OR licencia = '+30') AND 
    fecha = '2024-05-02';
--TEMPRANO
SELECT COUNT(*) FROM asistencia 
WHERE entrada !='00:00:00' and licencia != '+30' AND tardanza_cantidad =0 AND fecha ='2024-05-02';

--SIN REGISTRO
SELECT COUNT(*)  FROM asistencia 
WHERE  fecha ='2024-05-02' AND entrada ='00:00:00';
--TOTALES
SELECT COUNT(*) FROM asistencia 
WHERE  fecha ='2024-05-02';





----------------------------- cantidad de tempranos DIRECCION 

SELECT 
    d.nombre AS nombre_direccion,
    COUNT(*) AS cantidad_total
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
WHERE 
    a.tardanza_cantidad <= 0 AND 
    a.fecha = '2024-05-02' AND  
    a.licencia != '+30' AND
    a.entrada != '00:00:00'
GROUP BY 
    d.nombre;
    
------------------------------------------tarde por direccion

SELECT 
    d.nombre AS nombre_direccion,
    COUNT(*) AS cantidad_total
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
WHERE 
    (a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0)) AND 
    a.fecha = '2024-05-02' AND  
    a.entrada != '00:00:00'
GROUP BY 
    d.nombre;
    
    
    --------------------------------------SIN REGISTRO
    
    SELECT 
    d.nombre AS nombre_direccion,
    COUNT(*) AS cantidad_total
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
WHERE 
    a.fecha = '2024-05-02' AND  
    a.entrada = '00:00:00'
GROUP BY 
    d.nombre;
    
    -----------------TOTAL
     SELECT 
    d.nombre AS nombre_direccion,
    COUNT(*) AS cantidad_total
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
WHERE 
    a.fecha = '2024-05-02' 
GROUP BY 
    d.nombre;
    
    
<!-- -----------------------------------------ARRIBA CANTIDAD EN NUMEROS , ABAJO REGISTROS AGRUPADOS -->
    

----------------------------- cantidad de tempranos DIRECCION COMPLETO 

SELECT 
    t.apellido_nombre , d.nombre,e.nombre,a.entrada,a.salida
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    a.tardanza_cantidad <= 0 AND 
    a.fecha = '2024-05-02' AND  
    a.licencia != '+30' AND
    a.entrada != '00:00:00' ORDER BY d.nombre ASC  
    


    
------------------------------------------tarde por direccion

SELECT 
    t.apellido_nombre , d.nombre,e.nombre,a.entrada,a.salida,a.tardanza,a.tardanza_cantidad
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    (a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0)) AND 
    a.fecha = '2024-05-02' AND  
    a.entrada != '00:00:00'

    
    
    --------------------------------------SIN REGISTRO
    
    SELECT 
    t.apellido_nombre , d.nombre,e.nombre,a.entrada,a.salida
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    a.fecha = '2024-05-02' AND  
    a.entrada = '00:00:00'

    
    -----------------TOTAL
     SELECT 
     t.apellido_nombre , d.nombre,e.nombre,a.entrada,a.salida,a.tardanza,a.tardanza_cantidad,a.licencia
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    a.fecha = '2024-05-02' 

<!-- reporte completo temprano tarde sin registro -->

SELECT 
    d.nombre AS Direccion,
    SUM(CASE WHEN a.tardanza_cantidad <= 0 AND a.fecha = '2024-05-02' AND a.licencia != '+30' AND a.entrada != '00:00:00' THEN 1 ELSE 0 END) AS cantidad_temprano,
    SUM(CASE WHEN (a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0)) AND a.fecha = '2024-05-02' AND a.entrada != '00:00:00' THEN 1 ELSE 0 END) AS cantidad_tarde,
    SUM(CASE WHEN a.fecha = '2024-05-02' AND a.entrada = '00:00:00' THEN 1 ELSE 0 END) AS cantidad_sin_registro
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
GROUP BY 
    d.nombre;
<!-- ------------------------- -->


--HOME LO QUE SALDRA

SELECT 
    d.nombre AS Direccion,
    SUM(CASE WHEN a.tardanza_cantidad <= 0 AND a.fecha = '2024-05-02' AND a.licencia != '+30' AND a.entrada != '00:00:00' THEN 1 ELSE 0 END) AS cantidad_temprano,
    SUM(CASE WHEN (a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0)) AND a.fecha = '2024-05-02' AND a.entrada != '00:00:00' THEN 1 ELSE 0 END) AS cantidad_tarde,
    SUM(CASE WHEN a.fecha = '2024-05-02' AND a.entrada = '00:00:00' THEN 1 ELSE 0 END) AS cantidad_sin_registro
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
GROUP BY 
    d.nombre;
    
-- VISTA DEL BOTON
    
SELECT 
    d.nombre AS Direccion,
    e.nombre AS equipo,
    t.apellido_nombre AS trabajador ,
    a.entrada,
    a.salida,
    CASE
        WHEN a.tardanza_cantidad <= 0 AND a.fecha = '2024-05-02' AND a.licencia != '+30' AND a.entrada != '00:00:00' THEN 'Temprano'
        WHEN a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0) THEN 'Tarde'
        ELSE 'Sin Registro'
    END AS tipo_registro,
    SUM(CASE WHEN a.tardanza_cantidad > 0 THEN a.tardanza_cantidad ELSE 0 END) AS total_tardanza
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    a.fecha = '2024-05-02' AND (a.entrada != '00:00:00' OR a.tardanza_cantidad > 0 OR a.licencia = '+30')
GROUP BY 
    d.nombre, e.nombre, t.apellido_nombre, a.entrada, a.salida,a.tardanza_cantidad,a.fecha,a.licencia;


-- POSIBLE SEGUNDA VISTA

SELECT 
    d.nombre AS Direccion,
    e.nombre AS equipo,
    t.apellido_nombre AS trabajador ,
    a.entrada,
    a.salida,
    CASE
        WHEN a.tardanza_cantidad <= 0 AND a.fecha = '2024-05-02' AND a.licencia != '+30' AND a.entrada != '00:00:00' THEN 'Temprano'
        WHEN a.tardanza_cantidad > 0 OR (a.licencia = '+30' AND a.tardanza_cantidad = 0) THEN 'Tarde'
        ELSE 'Sin Registro'
    END AS tipo_registro,
    SUM(CASE WHEN a.tardanza_cantidad > 0 THEN a.tardanza_cantidad ELSE 0 END) AS total_tardanza
FROM 
    asistencia AS a 
INNER JOIN 
    trabajador AS t ON t.id = a.trabajador_id
INNER JOIN 
    direccion AS d ON d.id = t.direccion_id
LEFT JOIN
    equipo AS e ON e.id = d.equipo_id
WHERE 
    a.fecha = '2024-05-02'
GROUP BY 
    d.nombre, e.nombre, t.apellido_nombre, a.entrada, a.salida,a.tardanza_cantidad,a.fecha,a.licencia ;

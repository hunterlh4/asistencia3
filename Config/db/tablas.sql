
SET TIME ZONE 'America/Lima';
--buscar uso
-- CREATE TABLE area_trabajo (
--     id serial primary key,
--     nombre text not null UNIQUE,
--     estado BOOLEAN NOT NULL DEFAULT TRUE,
--     create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     update_at TIMESTAMP null
-- );

CREATE TABLE equipo (
    id serial primary key,
    nombre varchar(100) not null,
    estrategia varchar(100) null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

create table cargo(
    id serial primary key,
    nombre varchar(100) not null,
    nivel int default 0,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);COMMENT ON COLUMN cargo.nivel IS '0 ,1 personal comun, 2 jefes, 3 jefe de jefes';

--https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
CREATE TABLE regimen (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    sueldo NUMERIC(6,2) null default '0.00'
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

-- en las asistencias abreviaturas https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE licencias (
    id serial PRIMARY KEY,
    nombre varchar(100) not null ,
    abreviatura varchar(5) not null UNIQUE,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

--https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
CREATE TABLE direccion (
    id serial primary key,
    nombre varchar(100) null,
    equipo_id int not null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (equipo_id) REFERENCES equipo(id) ON DELETE CASCADE
);

CREATE TABLE horario (
    id serial primary key,
    nombre varchar(100) null,
    comentario varchar(100) null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

CREATE TABLE horario_detalle (
    id serial primary key,
    horario_id int not null,
    nombre varchar(100) null,
    hora_entrada interval(6) null default '00:00:00',
    hora_salida interval(6) null default '00:00:00',
    total interval(6) null default '00:00:00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (horario_id) REFERENCES horario(id) ON DELETE CASCADE
);


-- el primero usa a la tabla trabajadores el segundo biotrabajadores
-- https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
--https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE trabajadores (
    id serial primary key,
    dni varchar(10) not null UNIQUE,
    apellido_nombre varchar(100) not null,
    direccion_id int not null,
    regimen_id int not null,
    horario_id int not null,
    cargo_id int not null,
    email text  null,
    -- documento text null,
    dias_particulares int null default 0,
    telefono text  null,
    fecha_inicio date null,
    fecha_expiracion date null,
    nro_tarjeta text  null,
    sexo CHAR  null,
    fecha_nacimiento date null,
    estado_trabajo varchar(100) null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (direccion_id) REFERENCES direccion(id) ON DELETE CASCADE,
    FOREIGN KEY (regimen_id) REFERENCES regimen(id) ON DELETE CASCADE,
    FOREIGN KEY (horario_id) REFERENCES horario(id) ON DELETE CASCADE,
    FOREIGN KEY (cargo_id) REFERENCES cargo(id) ON DELETE CASCADE
);

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    nombre VARCHAR(50) NULL,
    apellido VARCHAR(50) NULL,
    nivel INT NOT NULL,
    trabajador_id INT  NULL,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id) ON DELETE CASCADE
);COMMENT ON COLUMN usuarios.nivel IS '0 sin vistas, 1 administrador, 2 jefe de oficina, 3 vizualizador, 4 portero';

-- al hacer un update, insert, delete, generar reporte
CREATE TABLE log (
    id SERIAL PRIMARY KEY,
    usuario_id INT not null,
    tipo_accion VARCHAR(20) not null,
    tabla_afectada VARCHAR(20) not null,
    detalles TEXT,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)  ON DELETE CASCADE
);

-- al hacer login
CREATE TABLE usuarios_conectados (
    id serial PRIMARY KEY,
    usuario_id int not null UNIQUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN key (usuario_id) REFERENCES usuarios(id)
);
CREATE TABLE vacaciones (
    id serial PRIMARY KEY,
    trabajador_id int not null,
    dias_vacaciones int null default 0,
    dias_usados int null default 0,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

-- https://asistencia.diresatacna.gob.pe/tab_auth_salida_new.php
CREATE TABLE boletas (
    id SERIAL PRIMARY KEY,
    numero VARCHAR(20) NOT NULL,
    trabajador_id INT NOT NULL,
    aprobado_por INT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    hora_salida interval(6) NULL,
    hora_entrada interval(6) NULL,
    duracion interval(6) NULL,
    razon VARCHAR(150) NOT NULL,
    observaciones TEXT NULL,
    estado_tramite varchar(20) null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id),
    FOREIGN KEY (aprobado_por) REFERENCES trabajadores(id)
);

create table asistencias(
    id SERIAL PRIMARY KEY,
    trabajador_id INT NOT NULL,
    licencias_id int null default 1,
    fecha date not null,
    entrada interval(6)  null default '00:00:00',
    salida interval(6)  null default '00:00:00',
    tardanza interval(6)  null default '00:00:00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id),
    FOREIGN KEY (licencias_id) REFERENCES licencias(id),
    UNIQUE (trabajador_id, fecha)
);

create table historia_trabajadores(
    id SERIAL PRIMARY KEY,
    trabajador_id int  not null,
    regimen_id int  not null,
    direccion_id int  not null,
    cargo_id int  not null,
    documento text null,
    sueldo NUMERIC(6,2) null default '0.00',
    fecha_inicio date not null,
    fecha_fin date not null,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id),
    FOREIGN KEY (regimen_id) REFERENCES regimen(id),
    FOREIGN KEY (direccion_id) REFERENCES direccion(id),
    FOREIGN KEY (cargo_id) REFERENCES cargo(id)

)


-- https://asistencia.diresatacna.gob.pe/tab_rol_turnos.php


-- SELECT * FROM area_trabajo;
SELECT * FROM equipo;
SELECT * FROM direccion;
SELECT * FROM licencias;
-- SELECT * FROM modalidades;
SELECT * FROM regimen;
SELECT * FROM trabajadores;
SELECT * FROM usuarios;








/**

area_trabajo    -> ?
modadlidad      ->solo
equipo_ejec     ->solo
log          ->usuarios        ->direccion_eject
trabajador   ->regimen
             ->direccion_eject -> equipo_eject


*//
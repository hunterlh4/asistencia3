
SET TIME ZONE 'America/Lima';
--buscar uso
-- CREATE TABLE area_trabajo (
--     id serial primary key,
--     nombre text not null UNIQUE,
--     estado BOOLEAN NOT NULL DEFAULT TRUE,
--     create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     update_at TIMESTAMP null
-- );
CREATE DATABASE postgres1
  WITH ENCODING='UTF8'
  LC_COLLATE='en_US.utf8'
  LC_CTYPE='en_US.utf8'
  TEMPLATE=template0;
-- update pg_database set encoding = pg_char_to_encoding('utf8') where datname = 'postgres'

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
    sueldo NUMERIC(6,2) null default '0.00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

-- en las asistencias abreviaturas https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE licencia (
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
    equipo_id int null,
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

CREATE TABLE horarioDetalle (
    id serial primary key,
    horario_id int not null,
    nombre varchar(100) null,
    hora_entrada interval(6) null default '00:00:00',
    hora_salida interval(6) null default '00:00:00',
    total interval(6) null default '00:00:00',
    hora_entrada2 interval(6) null default '00:00:00',
    hora_salida2 interval(6) null default '00:00:00',
    total2 interval(6) null default '00:00:00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (horario_id) REFERENCES horario(id) ON DELETE CASCADE
);


-- el primero usa a la tabla trabajadores el segundo biotrabajadores
-- https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
--https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE trabajador (
    id serial primary key,
    dni varchar(10)  null UNIQUE,
    telefono_id varchar(10)  null UNIQUE,
    apellido_nombre varchar(100) not null,
    direccion_id int null,
    regimen_id int null,
    horarioDetalle_id int null default 1,
    cargo_id int  null,
    email text  null,
    -- documento text null,
    -- cantidad de dias particilares que lleva
    dias_particulares int null default 0,
    telefono text  null,
    -- fecha_inicio date null, se pasa a historial
    -- fecha_expiracion date null,
    tarjeta text  null,
    sexo CHAR  null,
    fecha_nacimiento date null default null,
    -- presencial-remoto-vacaciones
    institucion varchar(100) null default 'DIRESA',
    modalidad_trabajo varchar(100) null default 'Presencial',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (direccion_id) REFERENCES direccion(id) ON DELETE CASCADE,
    FOREIGN KEY (regimen_id) REFERENCES regimen(id) ON DELETE CASCADE,
    FOREIGN KEY (horarioDetalle_id) REFERENCES horarioDetalle(id) ON DELETE CASCADE,
    FOREIGN KEY (cargo_id) REFERENCES cargo(id) ON DELETE CASCADE
);

CREATE TABLE usuario (
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
    FOREIGN KEY (trabajador_id) REFERENCES trabajador(id) ON DELETE CASCADE
);COMMENT ON COLUMN usuario.nivel IS '0 sin vistas, 1 administrador, 2 jefe de oficina, 3 vizualizador, 4 portero';

-- al hacer un update, insert, delete, generar reporte
CREATE TABLE log (
    id SERIAL PRIMARY KEY,
    usuario_id INT not null,
    tipo_accion VARCHAR(20) not null,
    tabla_afectada VARCHAR(20) not null,
    detalles TEXT,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)  ON DELETE CASCADE
);

-- al hacer login
CREATE TABLE usuario_conectado (
    id serial PRIMARY KEY,
    usuario_id int not null UNIQUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN key (usuario_id) REFERENCES usuario(id)
);
CREATE TABLE vacacion (
    id serial PRIMARY KEY,
    trabajador_id int not null,
    dias_vacaciones int null default 0,
    dias_usados int null default 0,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN key (trabajador_id) REFERENCES trabajador(id)
);

-- https://asistencia.diresatacna.gob.pe/tab_auth_salida_new.php
CREATE TABLE boleta (
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
    FOREIGN KEY (trabajador_id) REFERENCES trabajador(id),
    FOREIGN KEY (aprobado_por) REFERENCES trabajador(id)
);

create table asistencia(
    id SERIAL PRIMARY KEY,
    trabajador_id INT NOT NULL,
    licencia varchar(50) null ,
    fecha date not null,
    entrada interval(6)  null default '00:00:00',
    salida interval(6)  null default '00:00:00',
    total_reloj interval(6)  null default '00:00:00',
    total interval(6)  null default '00:00:00',
    tardanza interval(6)  null default '00:00:00',
    tardanza_cantidad int null default '0',
    justificacion varchar(10) NULL,
    comentario text null default null,
    reloj_1 interval(6)  null default '00:00:00',
    reloj_2 interval(6)  null default '00:00:00',
    reloj_3 interval(6)  null default '00:00:00',
    reloj_4 interval(6)  null default '00:00:00',
    reloj_5 interval(6)  null default '00:00:00',
    reloj_6 interval(6)  null default '00:00:00',
    reloj_7 interval(6)  null default '00:00:00',
    reloj_8 interval(6)  null default '00:00:00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajador(id),
    UNIQUE (trabajador_id, fecha)
);
-- en caso sea mas de 1 horario por fecha
-- ALTER TABLE asistencia DROP CONSTRAINT asistencia_trabajador_id_fecha_key;

create table seguimientoTrabajador(
    id SERIAL PRIMARY KEY,
    trabajador_id int  not null,
    regimen varchar(100)  not null,
    direccion varchar(100)  not null,
    cargo varchar(100)  not null,
    documento text null,
    sueldo NUMERIC(6,2) null default '0.00',
    fecha_inicio date not null,
    fecha_fin date not null,
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajador(id)
    -- FOREIGN KEY (regimen_id) REFERENCES regimen(id),
    -- FOREIGN KEY (direccion_id) REFERENCES direccion(id),
    -- FOREIGN KEY (cargo_id) REFERENCES cargo(id)
)

create table programacion(
    id SERIAL PRIMARY KEY,
    programador_id int not null,
    trabajador_id int  not null,
    fecha date not null,
    entrada interval(6)  null default '00:00:00',
    salida interval(6)  null default '00:00:00',
    total interval(6)  null default '00:00:00',
    estado varchar(10) NOT NULL DEFAULT 'Activo',
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY(programador_id) REFERENCES trabajador(id),
    FOREIGN KEY(trabajador_id) REFERENCES trabajador(id)
)

-- create table pruebaasistencia(
--     id SERIAL PRIMARY KEY,
--     trabajador_id INT NOT NULL,
--     licencia VARCHAR(10) null default 1,
--     fecha date not null,
--     entrada INTERVAL(4)  null default '00:00',
--     salida INTERVAL(4)  null default '00:00',
--     total_reloj INTERVAL(4)  null default '00:00',
--     total INTERVAL(4)  null default '00:00',
--     tardanza INTERVAL(4)  null default '00:00',
--     tardanza_cantidad int null default '0',
--     justificacion varchar(10) NULL,
--     comentario text null default null,
--     reloj_1 INTERVAL(4)  null default '00:00',
--     reloj_2 INTERVAL(4)  null default '00:00',
--     reloj_3 INTERVAL(4)  null default '00:00',
--     reloj_4 INTERVAL(4)  null default '00:00',
--     reloj_5 INTERVAL(4)  null default '00:00',
--     reloj_6 INTERVAL(4)  null default '00:00',
--     reloj_7 INTERVAL(4)  null default '00:00',
--     reloj_8 INTERVAL(4)  null default '00:00',
--     estado varchar(10) NOT NULL DEFAULT 'Activo',
--     create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     update_at TIMESTAMP null,
--     FOREIGN KEY (trabajador_id) REFERENCES trabajador(id),
--     UNIQUE (trabajador_id, fecha)
-- );


-- https://asistencia.diresatacna.gob.pe/tab_rol_turnos.php


-- SELECT * FROM area_trabajo;
SELECT * FROM equipo;
SELECT * FROM direccion;
SELECT * FROM licencia;
-- SELECT * FROM modalidades;
SELECT * FROM regimen;
SELECT * FROM trabajador;
SELECT * FROM usuario;








/**

area_trabajo    -> ?
modadlidad      ->solo
equipo_ejec     ->solo
log          ->usuario        ->direccion_eject
trabajador   ->regimen
             ->direccion_eject -> equipo_eject


*//
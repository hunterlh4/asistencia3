
SET TIME ZONE 'America/Lima';
--buscar uso
CREATE TABLE area_trabajo (
    id serial primary key,
    nombre text not null UNIQUE,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

CREATE TABLE equipo (
    id serial primary key,
    nombre varchar(100) not null,
    estrategia varchar(100) null,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);

--https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
CREATE TABLE direccion (
    id serial primary key,
    nombre varchar(100) null,
    equipo_id int not null,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (equipo_id) REFERENCES equipo(id) ON DELETE CASCADE
);

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    nombre VARCHAR(50) NULL,
    apellido VARCHAR(50) NULL,
    nivel INT NOT NULL,
    direccion_id INT NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (direccion_id) REFERENCES direccion(id) ON DELETE CASCADE
);COMMENT ON COLUMN usuarios.nivel IS '0 sin vistas, 1 administrador, 2 jefe de oficina, 3 vizualizador, 4 portero';

--https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
CREATE TABLE regimen (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  estado BOOLEAN NOT NULL DEFAULT TRUE,
  create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  update_at TIMESTAMP null
);
-- el primero usa a la tabla trabajadores el segundo biotrabajadores
-- https://asistencia.diresatacna.gob.pe/tab_search_detallado.php
--https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE trabajadores (
    id serial primary key,
    dni varchar(8) not null UNIQUE,
    apellido_nombre varchar(100) not null,
    direccion_id int not null,
    regimen_id int not null,
    area_id int not null,
    email text  null,
    documento text null,
    telefono text  null,
    fecha_inicio date null,
    fecha_expiracion date null,
    nro_tarjeta text  null,
    sexo CHAR  null,
    fecha_nacimiento date null,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (direccion_id) REFERENCES direccion(id) ON DELETE CASCADE,
    FOREIGN KEY (regimen_id) REFERENCES regimen(id) ON DELETE CASCADE,
    FOREIGN KEY (regimen_id) REFERENCES regimen(id) ON DELETE CASCADE,
);
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
    fecha_creacion DATE NOT NULL,
    observaciones TEXT NULL,
    estado_tramite varchar(20) null,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN KEY (trabajador_id) REFERENCES trabajadores(id),
    FOREIGN KEY (aprobado_por) REFERENCES trabajadores(id)
);
-- en las asistencias abreviaturas https://asistencia.diresatacna.gob.pe/tab_search_new.php
CREATE TABLE licencias (
    id serial PRIMARY KEY,
    nombre varchar(100) not null ,
    abreviatura varchar(5) not null UNIQUE,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);
-- al hacer login
CREATE TABLE usuarios_conectados (
    id serial PRIMARY KEY,
    usuario_id int not null UNIQUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null,
    FOREIGN key (usuario_id) REFERENCES usuarios(id)
);

-- https://asistencia.diresatacna.gob.pe/tab_rol_turnos.php
CREATE TABLE modalidades (
    id serial PRIMARY KEY,
    nombre varchar(50) not null,
    abreviatura varchar(5) not null ,
    descripcion varchar(100) null,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP null
);
-- https://asistencia.diresatacna.gob.pe/tab_rol_turnos.php


SELECT * FROM area_trabajo;
SELECT * FROM equipo;
SELECT * FROM direccion;
SELECT * FROM licencias;
SELECT * FROM modalidades;
SELECT * FROM regimen;
SELECT * FROM trabajadores;
SELECT * FROM usuarios;



CREATE TABLE horario (
  "id_horario" int4 NOT NULL DEFAULT nextval('horario_id_horario_seq'::regclass),
  "creado_por" int4 NOT NULL,
  "creado_tiempo" timestamptz(6) NOT NULL,
  "modificado_por" int4,
  "modificado_tiempo" timestamptz(6),
  "estado" bool NOT NULL DEFAULT true,
  "etiqueta" text COLLATE "pg_catalog"."default" NOT NULL,
  "comentario" text COLLATE "pg_catalog"."default",
  CONSTRAINT "horario_pkey" PRIMARY KEY ("id_horario")
);
ALTER TABLE "horario" OWNER TO "postgres";

CREATE TABLE "horario_detalle" (
  "id_horario_detalle" int4 NOT NULL DEFAULT nextval('horario_detalle_id_horario_seq'::regclass),
  "id_horario" int4 NOT NULL,
  "hora_entrada" interval(6) NOT NULL,
  "hora_salida" interval(6) NOT NULL,
  "creado_por" int4 NOT NULL,
  "creado_tiempo" timestamptz(6) NOT NULL,
  "modificado_por" int4,
  "modificado_tiempo" timestamptz(6),
  "estado" bool NOT NULL DEFAULT true,
  "etiqueta" text COLLATE "pg_catalog"."default" NOT NULL,
  "horas_totales" interval(6),
  CONSTRAINT "horario_detalle_pkey" PRIMARY KEY ("id_horario_detalle")
);






/**

area_trabajo    -> ?
modadlidad      ->solo
equipo_ejec     ->solo
log          ->usuarios        ->direccion_eject
trabajador   ->regimen
             ->direccion_eject -> equipo_eject


*//
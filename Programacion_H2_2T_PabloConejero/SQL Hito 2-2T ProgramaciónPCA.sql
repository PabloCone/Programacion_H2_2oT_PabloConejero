drop database if exists hito2;
create database hito2;
use hito2;
create table usuarios (
    id int auto_increment primary key,
    nombre varchar(100) not null,
    apellidos varchar(100) not null,
    email varchar(100) not null unique,
    contrasena varchar(100) not null unique,
    edad int not null,
	ciudad varchar(100)
);
create table tareas(
	autor varchar (100),
    id int auto_increment primary key,
    nombre varchar(100) not null,
    descripcion varchar(1000) not null,
    dia date not null ,
    hora time not null,
	estado varchar(100),
    foreign key (autor) references usuarios (email)
);
select * from usuarios;
select * from tareas;


-- Insertar usuario Laura García Pérez
INSERT INTO usuarios (nombre, apellidos, email, contrasena, edad, ciudad)
VALUES ('Laura', 'García Pérez', 'laura@gmail.com', 'laura123', 32, 'Barcelona');

-- Insertar usuario Mario Rodríguez Gómez
INSERT INTO usuarios (nombre, apellidos, email, contrasena, edad, ciudad)
VALUES ('Mario', 'Rodríguez Gómez', 'mario@gmail.com', 'mario123', 30, 'Sevilla');

-- Tareas de Laura García Pérez
INSERT INTO tareas (autor, nombre, descripcion, dia, hora, estado)
VALUES 
('laura@gmail.com', 'Planificar evento', 'Organizar los detalles del evento de la próxima semana.', '2025-02-18', '09:00', 'Pendiente'),
('laura@gmail.com', 'Preparar presupuesto', 'Hacer una estimación de los costos para el nuevo proyecto.', '2025-02-19', '16:00', 'En progreso'),
('laura@gmail.com', 'Revisar documentos legales', 'Leer y firmar los documentos legales del acuerdo.', '2025-02-20', '15:00', 'Completada');

-- Tareas de Mario Rodríguez Gómez
INSERT INTO tareas (autor, nombre, descripcion, dia, hora, estado)
VALUES 
('mario@gmail.com', 'Crear propuesta', 'Desarrollar una propuesta para el nuevo cliente.', '2025-02-21', '10:00', 'Pendiente'),
('mario@gmail.com', 'Implementar cambios', 'Realizar ajustes en el software según el feedback recibido.', '2025-02-22', '13:00', 'En progreso'),
('mario@gmail.com', 'Revisión de código', 'Hacer una revisión completa del código del proyecto Y.', '2025-02-23', '12:00', 'Completada');


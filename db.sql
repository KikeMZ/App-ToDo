create table usuario
(
    id int PRIMARY KEY not null AUTO_INCREMENT,
    nombre varchar(100) not null,
    apellidos varchar(200) not null,
    email varchar(100) not null,
    pass varchar(200) not null
);

create table tareas
(
	id int PRIMARY KEY not null AUTO_INCREMENT,
    id_user int not null,
    nombre varchar(100) not null,
    descrip varchar(200) not null,
    fecha date not null,
    materia varchar(100) not null,
    estado int(1) DEFAULT 0 not null,
    CONSTRAINT fk_usuario_tareas FOREIGN KEY (id_user) REFERENCES usuario(id) ON DELETE CASCADE
);

create table historial
(
	id int PRIMARY KEY not null AUTO_INCREMENT,
    accion varchar(100) not null,
    fecha datetime not null
);
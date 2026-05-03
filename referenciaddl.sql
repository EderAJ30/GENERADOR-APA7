SET NAMES 'utf8mb4';
DROP DATABASE IF EXISTS referenciasico;
CREATE DATABASE referenciasico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE referenciasico;

CREATE TABLE roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE areas (
    id_area INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE paises (
    id_pais INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    codigo_iso VARCHAR(5) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tipos_referencia (
    id_tipo_referencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre_usuario VARCHAR(100) NOT NULL,
    paterno_usuario VARCHAR(100) NOT NULL,
    materno_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    remember_token VARCHAR(100) NULL,
    estatus BOOLEAN NOT NULL DEFAULT 1,
    password VARCHAR(255) NOT NULL,
    rol_id INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles FOREIGN KEY (rol_id) REFERENCES roles(id_rol)
) ENGINE=InnoDB;

CREATE TABLE temas (
    id_tema INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    id_area INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    semestre TINYINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_materias_areas FOREIGN KEY (id_area) REFERENCES areas(id_area)
) ENGINE=InnoDB;

CREATE TABLE editoriales (
    id_editorial INT AUTO_INCREMENT PRIMARY KEY,
    id_pais INT NULL,
    nombre VARCHAR(150) NOT NULL,
    ciudad_sede VARCHAR(100) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_editoriales_paises FOREIGN KEY (id_pais) REFERENCES paises(id_pais)
) ENGINE=InnoDB;

CREATE TABLE referencias (
    id_referencia INT AUTO_INCREMENT PRIMARY KEY,
    id_tipo_referencia INT NOT NULL,
    id_usuario INT NOT NULL,
    id_editorial INT NULL,
    titulo VARCHAR(255) NOT NULL,
    anio_publicacion SMALLINT NOT NULL,
    fecha_exacta DATE NULL,
    volumen VARCHAR(20) NULL,
    numero VARCHAR(20) NULL,
    paginas VARCHAR(50) NULL,
    isbn_issn VARCHAR(20) NULL,
    doi VARCHAR(100) NULL UNIQUE,
    url VARCHAR(500) NULL,
    resumen TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_referencias_titulo (titulo),
    CONSTRAINT fk_referencias_tipos FOREIGN KEY (id_tipo_referencia) REFERENCES tipos_referencia(id_tipo_referencia),
    CONSTRAINT fk_referencias_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_referencias_editoriales FOREIGN KEY (id_editorial) REFERENCES editoriales(id_editorial)
) ENGINE=InnoDB;

CREATE TABLE referencia_tema (
    id_referencia INT NOT NULL,
    id_tema INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_referencia, id_tema),
    CONSTRAINT fk_reftema_referencia FOREIGN KEY (id_referencia) REFERENCES referencias(id_referencia) ON DELETE CASCADE,
    CONSTRAINT fk_reftema_tema FOREIGN KEY (id_tema) REFERENCES temas(id_tema) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE materia_referencia (
    id_materia INT NOT NULL,
    id_referencia INT NOT NULL,
    tipo_bibliografia VARCHAR(20) NOT NULL DEFAULT 'basica',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_materia, id_referencia),
    CONSTRAINT fk_matref_materia FOREIGN KEY (id_materia) REFERENCES materias(id_materia) ON DELETE CASCADE,
    CONSTRAINT fk_matref_referencia FOREIGN KEY (id_referencia) REFERENCES referencias(id_referencia) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE referencia_autor (
    id_referencia INT NOT NULL,
    id_autor INT NOT NULL,
    orden TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_referencia, id_autor),
    CONSTRAINT fk_refautor_referencia FOREIGN KEY (id_referencia) REFERENCES referencias(id_referencia) ON DELETE CASCADE,
    CONSTRAINT fk_refautor_autor FOREIGN KEY (id_autor) REFERENCES autores(id_autor) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE archivos (
    id_archivo INT AUTO_INCREMENT PRIMARY KEY,
    id_referencia INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_storage VARCHAR(500) NOT NULL UNIQUE,
    formato VARCHAR(10) NOT NULL DEFAULT 'pdf',
    tamano_bytes BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_archivos_referencias FOREIGN KEY (id_referencia) REFERENCES referencias(id_referencia) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE colecciones (
    id_coleccion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_referencia INT NOT NULL,
    fecha_agregado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comentario_personal TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_colecciones_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_colecciones_referencias FOREIGN KEY (id_referencia) REFERENCES referencias(id_referencia) ON DELETE CASCADE
) ENGINE=InnoDB;
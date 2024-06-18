# Portal de Alumnos: Apoyo Emocional

Este proyecto es un portal web diseñado para brindar apoyo emocional a estudiantes del Instituto Tecnológico de Ciudad Madero. El objetivo principal es promover la conciencia sobre la salud mental y proporcionar recursos y herramientas para ayudar a los estudiantes a enfrentar los desafíos emocionales durante su vida académica.

## Contenido del Proyecto

El proyecto incluye los siguientes archivos y carpetas:

- `index.html`: Página principal del portal.
- `css/`: Carpeta que contiene archivos CSS para el diseño y estilo del portal.
- `img/`: Carpeta que contiene imágenes utilizadas en el portal.
- `js/`: Carpeta que contiene archivos JavaScript para la funcionalidad interactiva del portal.
- `php/`: Carpeta que contiene archivos PHP para funcionalidades dinámicas (como un blog, por ejemplo).
- `readme.md`: Este archivo, que proporciona información sobre el proyecto.

## Características Principales

- **Secciones Informativas**: El portal incluye secciones informativas sobre salud mental, bienestar integral y buenos hábitos.
- **Recursos y Herramientas**: Proporciona enlaces a recursos útiles, como artículos, consejos y servicios de apoyo psicológico.
- **Interactividad**: Ofrece funcionalidades interactivas, como carruseles de imágenes y ventanas modales, para mejorar la experiencia del usuario.
- **Diseño Responsivo**: El diseño del portal está optimizado para verse correctamente en dispositivos móviles y de escritorio.

# Blog

Este blog tiene como objetivo proporcionar un espacio virtual para discutir temas relacionados con la salud mental en un entorno universitario. Permite a los usuarios realizar preguntas, recibir respuestas y buscar información sobre otros usuarios registrados.

## Funcionalidades

- **Registro y Autenticación de Usuarios:** Los usuarios pueden registrarse, iniciar sesión y actualizar su perfil.
- **Publicación de Preguntas:** Los usuarios pueden realizar preguntas que serán visibles para otros usuarios.
- **Respuestas y Comentarios:** Los usuarios pueden responder a las preguntas y comentar las respuestas de otros usuarios.
- **Búsqueda de Usuarios:** Existe la capacidad de buscar usuarios por nombre o apellido.
- **Gestión de Preguntas y Respuestas:** Los usuarios pueden editar y eliminar sus propias preguntas, así como gestionar respuestas.

## Tecnologías Utilizadas

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Base de Datos:** MySQL
- **Inteligencia Artificial:** Se utilizó OpenAI para detectar errores y optimizar la funcionalidad del sistema.

## Colaboradores

- **Elizabeth Cortez Razo** Docente del Departamento de Sistemas y Computación
- **Carlos Alberto García Mireles** Residente

## ¿Cómo abrir el proyecto?
- **1-Descarga e instala XAMPP** https://www.apachefriends.org/es/download.html
- **2-Iniciar XAMPP** Habilita los modulos Apache y MySQL
- **3-Ingresa la carpeta del proyecto a:** C:\xampp\htdocsC:\xampp\htdocs
- **4-Ingresa a phpmyadmin** http://localhost/phpmyadmin/
- **5-Ejecuta las siguientes sentencias SQL** 

CREATE TABLE usuarios ( 
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(50) NOT NULL, 
password VARCHAR(255) NOT NULL, 
nombre VARCHAR(255) DEFAULT NULL, 
foto VARCHAR(255) DEFAULT NULL, 
apellidos VARCHAR(255) DEFAULT NULL, 
correo_electronico VARCHAR(255) DEFAULT NULL
); 

CREATE TABLE preguntas ( 
id INT(11) AUTO_INCREMENT PRIMARY KEY, 
usuario VARCHAR(255) NOT NULL, 
pregunta TEXT NOT NULL, 
nombre VARCHAR(255), 
fecha_pregunta DATETIME DEFAULT NULL, 
fecha_edicion DATETIME DEFAULT NULL, 
editado TINYINT(1) DEFAULT 0 
);

CREATE TABLE respuestas ( 
id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
id_pregunta INT(11) NOT NULL, 
respuesta TEXT NOT NULL, 
fecha_respuesta DATETIME NOT NULL, 
usuario VARCHAR(255) NOT NULL 
);

- **6-Ingresa en tu navegador a:** http://localhost/Proyecto-Salud-Mental/
- **7- Listo. :D**
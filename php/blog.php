<?php
// Inicia sesión si no está iniciada
session_start();

// Verifica si el usuario está autenticado
if (isset($_SESSION['username'])) {
    // Conectarse a la base de datos (ajusta los datos según tu configuración)
    $servername = "localhost";
    $username_db = "root"; // Usuario por defecto en XAMPP
    $password_db = ""; // Contraseña por defecto en XAMPP
    $dbname = "usuarios"; // Nombre de la base de datos que has creado en PHPMyAdmin

    // Crear conexión
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obtener el nombre de usuario de la sesión
    $username = $_SESSION['username'];

    // Consulta SQL para obtener más información del usuario si es necesario
    // Aquí puedes hacer consultas adicionales según tus necesidades

    // Cerrar conexión
    $conn->close();
} else {
    // Redirige al usuario al formulario de login si no está autenticado
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styles.css">
  <title>Blog</title>
  <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
  <div id="encabezado">
    <h1>Salud mental en un ambiente universitario • Blog</h1>
		<a href="https://www.cdmadero.tecnm.mx">
			<img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
		</a>
		<a href="https://www.tecnm.mx">
			<img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
		</a>
  </div>
  <div class="navbar">
    <a href="../index.html"><img src="../img/home white.png" alt="Inicio" width="38px"></a> 
    <a href="logout.php">Cerrar sesión</a>
  </div> 

  <!-- Mostrar el nombre de usuario -->
  <h4>Bienvenido, <?php echo $username; ?></h4>

  <!-- Contenido del blog -->
  <div class="content">
    <!-- Aquí puedes mostrar las publicaciones del blog -->
    <!-- Ejemplo de una publicación -->
    <div class="post">
      <h2>Título del post</h2>
      <p>Contenido del post.</p>
      <span>Autor</span>
      <span>Fecha de publicación</span>
      
      <!-- Aquí podrías añadir sección de comentarios -->
      <div class="comments">
        <h3>Comentarios</h3>
        <!-- Formulario para enviar comentarios -->
        <form action="procesar_comentario.php" method="POST">
          <textarea name="comment" rows="4" cols="50" placeholder="Escribe tu comentario aquí..."></textarea><br>
          <input type="submit" value="Comentar">
        </form>
        <!-- Aquí podrías mostrar los comentarios existentes -->
        <div class="comment">
          <p>Comentario 1</p>
          <span>Autor del comentario</span>
        </div>
        <div class="comment">
          <p>Comentario 2</p>
          <span>Otro autor</span>
        </div>
        <!-- Puedes repetir este bloque para mostrar más comentarios -->
      </div>
    </div>
    <!-- Puedes repetir este bloque para más publicaciones -->
  </div>

</body>
</html>

<?php
// Inicia sesión
session_start();

// Verifica si ya hay una sesión iniciada
if (isset($_SESSION['username'])) {
    // Si ya hay una sesión iniciada, redirige a blog.php directamente
    header("Location: blog.php");
    exit();
}

// Variable para almacenar el mensaje de error
$error_message = "";

// Verificar si se envió el formulario de inicio de sesión estándar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $servername = "localhost";
    $username_db = "root"; // Usuario por defecto en XAMPP
    $password_db = ""; // Contraseña por defecto en XAMPP
    $dbname = "usuarios"; // Nombre de la base de datos que has creado en PHPMyAdmin

    // Establecer la conexión
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener los datos del formulario de login estándar
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Inicio de sesión exitoso
        $_SESSION['username'] = $username;
        header("Location: blog.php"); // Redirige a tu página principal o panel de control
        exit();
    } else {
        // Login fallido
        $error_message = "Usuario o contraseña incorrectos.";
    }

    $conn->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['anonymous'])) {
    // Iniciar sesión anónima
    $_SESSION['username'] = 'Anónimo';
    header("Location: blog.php"); // Redirige a tu página principal o panel de control
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/styles.css">
  <title>Iniciar Sesión</title>
  <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>

<body>
  <div id="encabezado">
    <h1>Blog • Iniciar sesión</h1>
		<a href="https://www.cdmadero.tecnm.mx">
			<img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
		</a>
		<a href="https://www.tecnm.mx">
			<img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
		</a>
  </div>

  <div class="navbar">
    <a href="../index.html"><img src="../img/home white.png" alt="Inicio" width="38px"></a> 
  </div> 

  <?php
  if (!empty($error_message)) {
      echo "<div class='mensaje-error'>$error_message</div>";
  }
  ?>
  <form method="post" class="formulario-login">
    <label for="username">Usuario:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="boton" name="login">Iniciar sesión</button>
  </form>

  <div class="mensaje-registro">
    <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>.</p>
  </div>

  <!-- Formulario para inicio de sesión anónima -->
  <form method="post" class="formulario-login">
    <button type="submit" class="boton" name="anonymous">Iniciar sesión como anónimo</button>
  </form>
</body>
</html>

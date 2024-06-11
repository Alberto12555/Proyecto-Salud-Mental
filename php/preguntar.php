<?php
session_start();

if (isset($_SESSION['username'])) {
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "saludmental";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];

    $conn->close();
} else {
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
  <link rel="stylesheet" href="../css/blog.css">
  <link rel="stylesheet" href="../css/styles_mobile.css">
  <title>Blog ITCM • Realizar pregunta</title>
  <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
  <div id="encabezado">
    <h1>Realizar pregunta</h1>
    <a href="https://www.cdmadero.tecnm.mx">
      <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
    </a>
    <a href="https://www.tecnm.mx">
      <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
    </a>
  </div>
  <div class="navbar">
    <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
    <a href="logout.php">Cerrar sesión</a>
  </div> 

  <div id="preguntas">
    <h2>Realiza tu pregunta</h2>
    <form action="procesar_pregunta.php" method="post">
      <label for="pregunta">Tu pregunta:</label>
      <textarea id="pregunta" name="pregunta" rows="5" cols="50" required></textarea>
      <button type="submit">Enviar pregunta</button>
    </form>
  </div>
</body>
</html>
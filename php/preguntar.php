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

    // Procesar el formulario de crear pregunta
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pregunta'])) {
        $pregunta = $conn->real_escape_string($_POST['pregunta']);
        $usuario = $_SESSION['username'];

        $sql = "INSERT INTO preguntas (pregunta, usuario, fecha_pregunta) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $pregunta, $usuario);

        if ($stmt->execute()) {
            header("Location: blog.php");
            exit();
        } else {
            echo "Error al realizar la pregunta: " . $stmt->error;
        }

        $stmt->close();
    }

    // Preparar para editar pregunta si se ha pasado un ID de pregunta
    $pregunta = ""; // Inicializar la variable
    $id_pregunta = $_GET['id_pregunta'] ?? null;

    if ($id_pregunta) {
        $sql = "SELECT pregunta FROM preguntas WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $stmt->bind_result($pregunta);
        $stmt->fetch();
        $stmt->close();
    }

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
  <link rel="stylesheet" href="css/styles_mobile.css">
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
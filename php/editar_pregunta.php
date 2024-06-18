<?php
session_start();

date_default_timezone_set('America/Mexico_City');

if (isset($_SESSION['username'])) {
  $servername = "localhost";
  $username_db = "root";
  $password_db = "";
  $dbname = "saludmental";

  $conn = new mysqli($servername, $username_db, $password_db, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pregunta = $_POST['id_pregunta'];
    $username = $_SESSION['username'];

    $sql = "SELECT pregunta FROM preguntas WHERE id=? AND usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id_pregunta, $username);
    $stmt->execute();
    $stmt->bind_result($pregunta);
    $stmt->fetch();
    $stmt->close();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nueva_pregunta'])) {
      $nueva_pregunta = $_POST['nueva_pregunta'];
      $fecha_edicion = date("Y-m-d H:i:s");
      $sql_update = "UPDATE preguntas SET pregunta=?, fecha_edicion=?, editado=TRUE WHERE id=? AND usuario=?";
      $stmt_update = $conn->prepare($sql_update);
      $stmt_update->bind_param("ssis", $nueva_pregunta, $fecha_edicion, $id_pregunta, $username);

      if ($stmt_update->execute()) {
        header("Location: blog.php");
        exit();
      } else {
        echo "Error al actualizar la pregunta: " . $stmt_update->error;
      }

      $stmt_update->close();
    }
  } else {
    header("Location: blog.php");
    exit();
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
  <link rel="stylesheet" href="../css/styles_mobile.css">
  <title>Editar Pregunta</title>
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
    <form action="editar_pregunta.php" method="post">
      <input type="hidden" name="id_pregunta" value="<?php echo $id_pregunta; ?>">
      <label for="nueva_pregunta">Editar Pregunta:</label><br>
      <textarea name="nueva_pregunta" rows="4" cols="50"
        required><?php echo htmlspecialchars($pregunta); ?></textarea><br>
      <button type="submit">Actualizar Pregunta</button>
    </form>
  </div>
</body>

</html>
<?php
session_start();

if (isset($_SESSION['username'])) {
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "usuarios";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];

    $sql = "SELECT id, usuario, pregunta, nombre FROM preguntas";
    $result = $conn->query($sql);

    $sql_respuestas = "SELECT id_pregunta, respuesta FROM respuestas";
    $result_respuestas = $conn->query($sql_respuestas);

    $respuestas = [];
    while ($row_respuesta = $result_respuestas->fetch_assoc()) {
        $respuestas[$row_respuesta['id_pregunta']][] = $row_respuesta['respuesta'];
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
    <a href="preguntar.php">Realizar pregunta</a> 
    <a href="">Editar perfil</a> 
    <a href="logout.php">Cerrar sesión</a>
  </div> 

  <h4>Bienvenido, <?php echo $username; ?></h4>

  <div id="preguntas">
    <h2>Preguntas</h2>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='pregunta'>";
            echo "<h3>" . htmlspecialchars($row['pregunta']) . "</h3>";
            if ($row['nombre']) {
                echo "<p>Por: " . htmlspecialchars($row['nombre']) . "</p>";
            }
            echo "<form action='procesar_respuesta.php' method='post'>";
            echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
            echo "<textarea name='respuesta' rows='2' cols='50' required></textarea>";
            echo "<button type='submit'>Responder</button>";
            echo "</form>";

            if (isset($respuestas[$row['id']])) {
                echo "<div class='respuestas'>";
                foreach ($respuestas[$row['id']] as $respuesta) {
                    echo "<p>" . htmlspecialchars($respuesta) . "</p>";
                }
                echo "</div>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>No hay preguntas aún.</p>";
    }
    ?>
  </div>
</body>
</html>

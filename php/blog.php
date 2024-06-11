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

    // Consulta SQL para obtener todas las preguntas y sus datos relacionados, ordenadas por fecha de pregunta (más recientes primero)
    $sql = "SELECT p.id, p.pregunta, p.fecha_pregunta, u.nombre, u.apellidos, u.foto, p.usuario
            FROM preguntas p
            JOIN usuarios u ON p.usuario = u.username
            ORDER BY p.fecha_pregunta DESC"; // Ordenar por fecha de pregunta, más recientes primero

    $result = $conn->query($sql);

    // Consulta SQL para obtener todas las respuestas junto con la información del usuario que respondió
    $sql_respuestas = "SELECT r.id_pregunta, r.respuesta, r.fecha_respuesta, u.nombre, u.apellidos, u.foto 
                       FROM respuestas r
                       JOIN usuarios u ON r.usuario = u.username";
    $result_respuestas = $conn->query($sql_respuestas);

    // Inicialización del arreglo para almacenar las respuestas por pregunta
    $respuestas = [];
    while ($row_respuesta = $result_respuestas->fetch_assoc()) {
        $respuestas[$row_respuesta['id_pregunta']][] = $row_respuesta;
    }

    // Obtener nombre y apellidos del usuario de la sesión
    $nombre = $_SESSION['nombre'];
    $apellidos = $_SESSION['apellidos'];
    $username = $_SESSION['username'];

    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ITCM</title>
    <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/styles_mobile.css">
    <script src="../js/mostrarmenu.js"></script>
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
    <a href="mis_preguntas.php">Mis preguntas</a>
    <a href="buscar_usuario.php">Buscar usuario</a>
    <a href="editarperfil.php">Editar perfil</a>
    <a href="logout.php">Cerrar sesión</a>
</div>

<center><h4>Bienvenido, <?php echo $nombre . ' ' . $apellidos; ?> &#128578;</h4></center>

<div id="preguntas">
    <h2>Preguntas</h2>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='pregunta'>";
            echo "<div class='user-info'>";
            echo "<img src='" . $row['foto'] . "' alt='Foto de perfil' class='user-foto'>";
            echo "<div class='user-details'>";
            echo "<p>" . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) . "</p>";
            echo "<p>" . htmlspecialchars($row['fecha_pregunta']) . "</p>";
            echo "</div>"; // Cierre de div.user-details
            echo "</div>"; // Cierre de div.user-info
            echo "<h3>" . htmlspecialchars($row['pregunta']) . "</h3>";
            echo "<div class='botones-pregunta'>"; // Contenedor para los botones
            
            if ($row['usuario'] === $username) {
                // Botones de editar y eliminar solo si la pregunta es del usuario actual
                echo "<form action='editar_pregunta.php' method='post'>";
                echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
                echo "<button type='submit'>Editar</button>";
                echo "</form>";

                echo "<form action='eliminar_pregunta.php' method='post'>";
                echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
                echo "<button type='submit'>Eliminar</button>";
                echo "</form>";
            }
            echo "</div>"; // Cierre de div.botones-pregunta

            // Mostrar respuestas si existen
            if (isset($respuestas[$row['id']])) {
                echo "<div class='respuestas'>";
                foreach ($respuestas[$row['id']] as $respuesta) {
                    echo "<div class='respuesta'>";
                    echo "<div class='user-info'>";
                    echo "<img src='" . $respuesta['foto'] . "' alt='Foto de perfil' class='user-foto'>";
                    echo "<div class='user-details'>";
                    echo "<p>" . htmlspecialchars($respuesta['nombre']) . ' ' . htmlspecialchars($respuesta['apellidos']) . "</p>";
                    echo "<p>" . htmlspecialchars($respuesta['fecha_respuesta']) . "</p>";
                    echo "</div>"; // Cierre de div.user-details
                    echo "</div>"; // Cierre de div.user-info
                    echo "<p>" . htmlspecialchars($respuesta['respuesta']) . "</p>";
                    echo "</div>"; // Cierre de div.respuesta
                }
                echo "</div>"; // Cierre de div.respuestas
            }

            // Formulario para responder
            echo "<form action='procesar_respuesta.php' method='post'>";
            echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
            echo "<textarea name='respuesta' rows='2' cols='50' placeholder='Escribe tu respuesta aquí' required></textarea>";
            echo "<button type='submit'>Responder</button>";
            echo "</form>";

            echo "</div>"; // Cierre de div.pregunta
        }
    } else {
        echo "<p>No hay preguntas aún.</p>";
    }
    ?>
</div>

<footer>
    <h2>Instituto Tecnológico de Ciudad Madero</h2>
    <p>&copy; <?php echo date('Y'); ?> Todos los derechos reservados.</p>
</footer>

</body>
</html>

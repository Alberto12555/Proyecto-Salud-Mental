<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "saludmental";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Consulta para obtener todas las preguntas realizadas por el usuario actual
$sql = "SELECT p.id, p.pregunta, p.fecha_pregunta, u.nombre, u.apellidos, u.foto
        FROM preguntas p
        JOIN usuarios u ON p.usuario = u.username
        WHERE p.usuario = '$username'
        ORDER BY p.fecha_pregunta DESC"; // Ordenar por fecha de pregunta, más reciente primero

$result = $conn->query($sql);

// Consulta para obtener las respuestas asociadas a cada pregunta
$sql_respuestas = "SELECT r.id, r.id_pregunta, r.respuesta, r.fecha_respuesta, u.nombre, u.apellidos, u.foto
                  FROM respuestas r
                  JOIN usuarios u ON r.usuario = u.username
                  WHERE r.id_pregunta IN (SELECT id FROM preguntas WHERE usuario = '$username')
                  ORDER BY r.fecha_respuesta ASC"; // Ordenar respuestas por fecha ascendente

$result_respuestas = $conn->query($sql_respuestas);

// Almacenar respuestas por pregunta en un array asociativo
$respuestas_por_pregunta = [];
while ($row_respuesta = $result_respuestas->fetch_assoc()) {
    $id_pregunta = $row_respuesta['id_pregunta'];
    if (!isset($respuestas_por_pregunta[$id_pregunta])) {
        $respuestas_por_pregunta[$id_pregunta] = [];
    }
    $respuestas_por_pregunta[$id_pregunta][] = $row_respuesta;
}

$es_anonimo = strpos($username, 'anonimo#') !== false;

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ITCM • Mis preguntas</title>
    <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/styles_mobile.css">
</head>
<body>
<div id="encabezado">
    <h1>Mis preguntas</h1>
    <a href="https://www.cdmadero.tecnm.mx">
        <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
    </a>
    <a href="https://www.tecnm.mx">
        <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
    </a>
</div>
<div class="navbar">
    <!-- Menú principal visible en dispositivos de escritorio -->
    <div class="menu-items">
        <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
        <a href="preguntar.php">Realizar pregunta</a>
        <?php
        // Mostrar editar perfil solo si no es un usuario anónimo
        if (isset($_SESSION['username']) && !$es_anonimo) {
            echo '<a href="editarperfil.php">Editar perfil</a>';
        }
        ?>
        <a href="logout.php">Cerrar sesión</a>
    </div>

    <!-- Menú desplegable para dispositivos móviles -->
    <div class="dropdown">
        <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
        <button onclick="toggleDropdown()">Opciones</button>
        <div id="myDropdown" class="dropdown-content">
            <a href="preguntar.php">Realizar pregunta</a>
            <?php
        // Mostrar editar perfil solo si no es un usuario anónimo
        if (isset($_SESSION['username']) && !$es_anonimo) {
            echo '<a href="editarperfil.php">Editar perfil</a>';
        }
        ?>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</div>


<div id="preguntas">
    <h2>Mis Preguntas</h2>
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
            // Botones de editar y eliminar
            echo "<form action='editar_pregunta.php' method='post'>";
            echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
            echo "<button type='submit'>Editar</button>";
            echo "</form>";

            echo "<form action='eliminar_pregunta.php' method='post'>";
            echo "<input type='hidden' name='id_pregunta' value='" . $row['id'] . "'>";
            echo "<button type='submit'>Eliminar</button>";
            echo "</form>";
            echo "</div>"; // Cierre de div.botones-pregunta

            // Mostrar respuestas si existen
            if (isset($respuestas_por_pregunta[$row['id']])) {
                echo "<div class='respuestas'>";
                foreach ($respuestas_por_pregunta[$row['id']] as $respuesta) {
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

            echo "</div>"; // Cierre de div.pregunta
        }
    } else {
        echo "<p>No has realizado preguntas aún.</p>";
    }
    ?>
</div>

</body>
</html>

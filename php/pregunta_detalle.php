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
    // Verificar si la sesión está iniciada y obtener el nombre de usuario
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    if ($username === null) {
        // Manejar la situación cuando el usuario no está autenticado
        header("Location: login.php");
        exit();
    }
    // Obtener el id de la pregunta desde la URL
    $id_pregunta = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id_pregunta !== null) {
        // Consulta para obtener la pregunta específica
        $sql_pregunta = "SELECT p.id, p.pregunta, p.fecha_pregunta, p.fecha_edicion, p.editado, u.nombre, u.apellidos, u.foto, p.usuario
                         FROM preguntas p
                         JOIN usuarios u ON p.usuario = u.username
                         WHERE p.id = $id_pregunta";
        $result_pregunta = $conn->query($sql_pregunta);

        // Consulta para obtener respuestas relacionadas con la pregunta específica
        $sql_respuestas = "SELECT r.respuesta, r.fecha_respuesta, u.nombre, u.apellidos, u.foto, r.usuario
        FROM respuestas r
        JOIN usuarios u ON r.usuario = u.username
        WHERE r.id_pregunta = $id_pregunta
        ORDER BY r.fecha_respuesta ASC";
        $result_respuestas = $conn->query($sql_respuestas);
    } else {
        exit();
    }

    // Cerrar conexión a la base de datos
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
        <h1>Salud mental en un ambiente universitario • Blog ITCM</h1>
        <a href="https://www.cdmadero.tecnm.mx">
            <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
        </a>
        <a href="https://www.tecnm.mx">
            <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
        </a>
    </div>
    <div class="navbar">
        <div class="menu-items">
            <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
            <a href="mis_preguntas.php">Mis preguntas</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
        <div class="dropdown">
            <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
            <button onclick="toggleDropdown()">Opciones</button>
            <div id="myDropdown" class="dropdown-content">

                <a href="mis_preguntas.php">Mis preguntas</a>
                <a href="logout.php">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <div id="preguntas">
        <h2>Pregunta</h2>
        <?php
        if ($result_pregunta->num_rows > 0) {
            while ($row = $result_pregunta->fetch_assoc()) {
                echo "<div class='pregunta'>";
                echo "<div class='user-info'>";
                echo "<img src='" . (empty($row['foto']) ? '../img/nophoto.png' : htmlspecialchars($row['foto'])) . "' alt='Foto de perfil' class='user-foto'>";
                echo "<div class='user-details'>";
                if (strpos($row['usuario'], 'anonimo#') !== false) {
                    echo "<p>" . htmlspecialchars($row['usuario']) . "</p>";
                } else {
                    echo "<p>" . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) . "</p>";
                }
                echo "<p>" . htmlspecialchars($row['fecha_pregunta']);
                if ($row['editado']) {
                    echo " • Editado a las " . htmlspecialchars($row['fecha_edicion']);
                }
                echo "</p>";
                echo "</div>"; // Cierre de div.user-details
                echo "</div>"; // Cierre de div.user-info
                echo "<h3>" . htmlspecialchars($row['pregunta']) . "</h3>";

                // Botones de edición y eliminación
                echo "<div class='botones-pregunta'>";
                if ($row['usuario'] === $username) {
                    echo "<form action='editar_pregunta.php' method='post'>";
                    echo "<input type='hidden' name='id_pregunta' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<button type='submit'>Editar</button>";
                    echo "</form>";

                    echo "<form action='eliminar_pregunta.php' method='post'>";
                    echo "<input type='hidden' name='id_pregunta' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<button type='submit'>Eliminar</button>";
                    echo "</form>";
                }
                echo "</div>"; // Cierre de botones-pregunta
        
                // Comprobar si hay respuestas y mostrarlas
                if ($result_respuestas->num_rows > 0) {
                    while ($respuesta = $result_respuestas->fetch_assoc()) {
                        echo "<div class='respuesta'>";
                        echo "<div class='user-info'>";
                        echo "<img src='" . (empty($respuesta['foto']) ? '../img/nophoto.png' : htmlspecialchars($respuesta['foto'])) . "' alt='Foto de perfil' class='user-foto'>";
                        echo "<div class='user-details'>";
                        if (strpos($respuesta['usuario'], 'anonimo#') !== false) {
                            echo "<p>" . htmlspecialchars($respuesta['usuario']) . "</p>";
                        } else {
                            echo "<p>" . htmlspecialchars($respuesta['nombre']) . ' ' . htmlspecialchars($respuesta['apellidos']) . "</p>";
                        }
                        echo "<p>" . htmlspecialchars($respuesta['fecha_respuesta']) . "</p>";
                        echo "</div>"; // Cierre de div.user-details
                        echo "</div>"; // Cierre de div.user-info
                        echo "<p>" . htmlspecialchars($respuesta['respuesta']) . "</p>";
                        echo "</div>"; // Cierre de div.respuesta
                    }
                    // Formulario para responder
                    echo "<div class='formulario-responder'>";
                    echo "<form action='procesar_respuesta.php' method='post'>";
                    echo "<input type='hidden' name='id_pregunta' value='" . htmlspecialchars($row['id']) . "'>";
                    echo "<textarea name='respuesta' class='respuesta-textarea' placeholder='Escribe tu respuesta aquí...' required></textarea>";
                    echo "<button type='submit' class='btn-responder'>Responder</button>";
                    echo "</form>";
                    echo "</div>"; // Cierre de formulario-responder
                } else {
                    echo "<p class='no-respuestas'>No hay respuestas aún.</p>";
                }
                echo "</div>"; // Cierre de div.pregunta
            }
        } else {
            echo "<p class='no-pregunta'>No se encontró la pregunta solicitada.</p>";
        }
        ?>
    </div>
</body>

</html>
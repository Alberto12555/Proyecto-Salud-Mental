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

$usuario = $_GET['usuario'];

// Consulta para obtener las preguntas del usuario específico
$sql = "SELECT p.id, p.pregunta, p.fecha_pregunta, u.nombre, u.apellidos, u.foto, u.username as username
        FROM preguntas p
        JOIN usuarios u ON p.usuario = u.username
        WHERE u.username = ?
        ORDER BY p.fecha_pregunta DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

// Consulta para obtener las respuestas asociadas a las preguntas del usuario
$sql_respuestas = "SELECT r.id, r.id_pregunta, r.respuesta, r.fecha_respuesta, u.username as usuario, u.nombre, u.apellidos, u.foto
                  FROM respuestas r
                  JOIN usuarios u ON r.usuario = u.username
                  WHERE r.id_pregunta IN (SELECT id FROM preguntas WHERE usuario = ?)
                  ORDER BY r.fecha_respuesta ASC";

$stmt_respuestas = $conn->prepare($sql_respuestas);
$stmt_respuestas->bind_param("s", $usuario);
$stmt_respuestas->execute();
$result_respuestas = $stmt_respuestas->get_result();

// Almacenar respuestas por pregunta en un array asociativo
$respuestas_por_pregunta = [];
while ($row_respuesta = $result_respuestas->fetch_assoc()) {
    $id_pregunta = $row_respuesta['id_pregunta'];
    if (!isset($respuestas_por_pregunta[$id_pregunta])) {
        $respuestas_por_pregunta[$id_pregunta] = [];
    }
    $respuestas_por_pregunta[$id_pregunta][] = $row_respuesta;
}

$conn->close();
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
        <!-- Menú principal visible en dispositivos de escritorio -->
        <div class="menu-items">
            <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
            <a href="mis_preguntas.php">Mis preguntas</a>
            <a href="buscar_usuario.php">Buscar usuario</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>

        <!-- Menú desplegable para dispositivos móviles -->
        <div class="dropdown">
            <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
            <button onclick="toggleDropdown()">Opciones</button>
            <div id="myDropdown" class="dropdown-content">
                <a href="mis_preguntas.php">Mis preguntas</a>
                <a href="buscar_usuario.php">Buscar usuario</a>
                <a href="logout.php">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <div id="preguntas">
        <h2>Preguntas de <?php echo htmlspecialchars($usuario); ?></h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='pregunta'>";
                echo "<div class='user-info'>";
                if (!empty($row['foto'])) {
                    echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto de perfil' class='user-foto'>";
                } else {
                    echo "<img src='../img/nophoto.png' alt='Foto de perfil' class='user-foto'>";
                }
                echo "<div class='user-details'>";
                // Mostrar nombre completo o nombre de usuario para anónimos
                if (strpos($row['username'], 'anonimo#') !== false) {
                    echo "<p>" . htmlspecialchars($row['username']) . "</p>";
                } else {
                    echo "<p>" . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) . "</p>";
                }
                echo "<p>" . htmlspecialchars($row['fecha_pregunta']) . "</p>";
                echo "</div>"; // Cierre de div.user-details
                echo "</div>"; // Cierre de div.user-info
                echo "<h3>" . htmlspecialchars($row['pregunta']) . "</h3>";

                // Mostrar respuestas asociadas a la pregunta
                if (isset($respuestas_por_pregunta[$row['id']])) {
                    foreach ($respuestas_por_pregunta[$row['id']] as $respuesta) {
                        echo "<div class='respuesta'>";
                        echo "<div class='user-info'>";
                        if (!empty($respuesta['foto'])) {
                            echo "<img src='" . htmlspecialchars($respuesta['foto']) . "' alt='Foto de perfil' class='user-foto'>";
                        } else {
                            echo "<img src='../img/nophoto.png' alt='Foto de perfil' class='user-foto'>";
                        }
                        echo "<div class='user-details'>";
                        // Mostrar nombre completo o nombre de usuario para anónimos
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
                }
                echo "<form action='procesar_respuesta.php' method='post'>";
                echo "<input type='hidden' name='id_pregunta' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<textarea name='respuesta' required></textarea>";
                echo "<button type='submit'>Responder</button>";
                echo "</form>";

                echo "</div>"; // Cierre de div.pregunta
            }
        } else {
            echo "<p>No hay preguntas de este usuario.</p>";
        }
        ?>
    </div>
</body>

</html>
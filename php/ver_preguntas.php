<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username_db = "root";
$password_db = ""; // Asegúrate de que esta línea contenga la contraseña correcta si existe
$dbname = "saludmental";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$usuario = $_GET['usuario'];

$sql = "SELECT p.id, p.pregunta, p.fecha_pregunta, u.nombre, u.apellidos, u.foto
        FROM preguntas p
        JOIN usuarios u ON p.usuario = u.username
        WHERE u.username = ?
        ORDER BY p.fecha_pregunta DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

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
    <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
    <a href="mis_preguntas.php">Mis preguntas</a>
    <a href="buscar_usuario.php">Buscar usuario</a>
    <a href="logout.php">Cerrar sesión</a>
</div>

<?php
if ($result->num_rows > 0) {
    echo "<div id='preguntas'>";
    echo "<h2>Preguntas</h2>";

    while ($row = $result->fetch_assoc()) {
        echo "<div class='pregunta'>";
        echo "<div class='user-info'>";
        echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto de perfil' class='user-foto'>";
        echo "<div class='user-details'>";
        echo "<p>" . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) . "</p>";
        echo "<p>" . htmlspecialchars($row['fecha_pregunta']) . "</p>";
        echo "</div>"; // Cierre de div.user-details
        echo "</div>"; // Cierre de div.user-info
        echo "<h3>" . htmlspecialchars($row['pregunta']) . "</h3>";
        echo "</div>"; // Cierre de div.pregunta
    }

    echo "</div>"; // Cierre de div#preguntas
} else {
    echo "<p>No hay preguntas de este usuario.</p>";
}
?>

</body>
</html>

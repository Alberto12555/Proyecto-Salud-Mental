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

$search = $_POST['search'];

$sql = "SELECT * FROM usuarios WHERE (nombre LIKE ? OR apellidos LIKE ?) AND username NOT LIKE 'anonimo%'";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='usuario'>";
        echo "<img src='" . (empty($row['foto']) ? '../img/nophoto.png' : htmlspecialchars($row['foto'])) . "' alt='Foto de perfil' class='user-foto'>";
        echo "<div class='user-details'>";
        echo "<p>" . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellidos']) . "</p>";
        echo "<a href='ver_preguntas.php?usuario=" . urlencode($row['username']) . "'>Ver preguntas de este usuario</a>";
        echo "</div>"; // Cierre de div.user-details
        echo "</div>"; // Cierre de div.usuario
    }
} else {
    echo "<p>No se encontraron usuarios.</p>";
}

$conn->close();
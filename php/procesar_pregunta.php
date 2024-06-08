<?php
// Inicia sesión si no está iniciada
session_start();

// Verifica si el usuario está autenticado
if (isset($_SESSION['username'])) {
    // Conectarse a la base de datos (ajusta los datos según tu configuración)
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "saludmental";

    // Crear conexión
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obtener la pregunta y el nombre del formulario
    $pregunta = $conn->real_escape_string($_POST['pregunta']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $username = $_SESSION['username'];

    // Insertar la pregunta en la base de datos
    $sql = "INSERT INTO preguntas (usuario, pregunta, nombre) VALUES ('$username', '$pregunta', '$nombre')";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a blog.php después de guardar la pregunta
        header("Location: blog.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar conexión
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>

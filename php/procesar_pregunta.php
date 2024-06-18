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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener y limpiar el contenido del campo 'pregunta'
        $pregunta = trim($_POST['pregunta']);

        // Validar que la pregunta no esté vacía antes de continuar
        if (empty($pregunta)) {
            echo "Error: La pregunta no puede estar vacía.";
            exit();
        }

        // Obtener el nombre completo del usuario
        $username = $_SESSION['username'];
        $sql_user = "SELECT nombre, apellidos FROM usuarios WHERE username=?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $username);
        $stmt_user->execute();
        $stmt_user->bind_result($nombre, $apellidos);
        $stmt_user->fetch();
        $nombre_completo = $nombre . ' ' . $apellidos;
        $stmt_user->close();

        // Insertar la pregunta en la base de datos
        $sql = "INSERT INTO preguntas (usuario, pregunta, nombre, fecha_pregunta) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $pregunta, $nombre_completo);

        if ($stmt->execute()) {
            header("Location: blog.php");
            exit();
        } else {
            echo "Error al insertar la pregunta: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Método de solicitud incorrecto.";
    }

    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
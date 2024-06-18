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

    date_default_timezone_set('America/Mexico_City');

    // Obtener la respuesta y el ID de la pregunta del formulario
    $id_pregunta = $conn->real_escape_string($_POST['id_pregunta']);
    $respuesta = $conn->real_escape_string($_POST['respuesta']);
    $usuario = $_SESSION['username'];
    $fecha_respuesta = date('Y-m-d H:i:s');

    // Insertar la respuesta en la base de datos
    $sql = "INSERT INTO respuestas (id_pregunta, respuesta, usuario, fecha_respuesta) 
    VALUES ('$id_pregunta', '$respuesta', '$usuario', '$fecha_respuesta')";

    if ($conn->query($sql) === TRUE) {
        // Obtener el ID de la respuesta insertada
        $id_respuesta = $conn->insert_id;

        // Redirigir a pregunta_detalle.php después de guardar la respuesta
        header("Location: pregunta_detalle.php?id=$id_pregunta#$id_respuesta");
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
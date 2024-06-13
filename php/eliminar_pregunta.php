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
        $id_pregunta = $_POST['id_pregunta'];
        $username = $_SESSION['username'];

        // Iniciar una transacción
        $conn->begin_transaction();

        try {
            // Verificar que el usuario es el propietario de la pregunta
            $sql_check_owner = "SELECT usuario FROM preguntas WHERE id=?";
            $stmt_check_owner = $conn->prepare($sql_check_owner);
            $stmt_check_owner->bind_param("i", $id_pregunta);
            $stmt_check_owner->execute();
            $stmt_check_owner->bind_result($usuario_propietario);
            $stmt_check_owner->fetch();
            $stmt_check_owner->close();

            if ($usuario_propietario !== $username) {
                throw new Exception("No tienes permiso para eliminar esta pregunta.");
            }

            // Eliminar las respuestas asociadas a la pregunta
            $sql_delete_respuestas = "DELETE FROM respuestas WHERE id_pregunta=?";
            $stmt_delete_respuestas = $conn->prepare($sql_delete_respuestas);
            $stmt_delete_respuestas->bind_param("i", $id_pregunta);
            $stmt_delete_respuestas->execute();
            $stmt_delete_respuestas->close();

            // Eliminar la pregunta
            $sql_delete_pregunta = "DELETE FROM preguntas WHERE id=? AND usuario=?";
            $stmt_delete_pregunta = $conn->prepare($sql_delete_pregunta);
            $stmt_delete_pregunta->bind_param("is", $id_pregunta, $username);
            $stmt_delete_pregunta->execute();
            $stmt_delete_pregunta->close();

            // Confirmar la transacción
            $conn->commit();

            header("Location: blog.php");
            exit();
        } catch (Exception $exception) {
            $conn->rollback();
            echo "Error al eliminar la pregunta y sus respuestas: " . $exception->getMessage();
        }
    } else {
        header("Location: blog.php");
        exit();
    }

    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
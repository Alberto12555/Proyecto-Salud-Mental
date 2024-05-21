<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registro</title>
    <link rel="icon" href="/images/logo_itcm.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
    <div id="encabezado">
        <h1>Salud mental en un ambiente universitario • Registro</h1>
        <img src="img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
        <img src="img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
    </div>

    <div class="navbar">
    <a href="index.html"><img src="img/home white.png" alt="Inicio" width="38px"></a> 
    </div> 

    <form method="post" class="formulario-login">
        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="boton">Registrarse</button>
    </form>

    <div class="mensaje-registro">
        <p>¿Ya tienes una cuenta? <a href="login.php">Ingresa ahora</a>.</p>
    </div>
        
    <?php
    // Conexión a la base de datos utilizando PHPMyAdmin y XAMPP
    $servername = "localhost";
    $username = "root"; // Usuario por defecto en XAMPP
    $password = ""; // Contraseña por defecto en XAMPP
    $dbname = "usuarios"; // Nombre de la base de datos que has creado en PHPMyAdmin

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Procesar los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Insertar datos en la tabla
        $sql = "INSERT INTO usuarios (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Redirigir después de un registro exitoso
            header("Location: blog.php");
            exit();
        } else {
            echo "Error al registrar: " . $conn->error;
        }
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/styles_mobile.css">
    <title>Registro</title>
    <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
    <div id="encabezado">
        <h1>Blog • Registro</h1>
        <a href="https://www.cdmadero.tecnm.mx">
            <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
        </a>
        <a href="https://www.tecnm.mx">
            <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
        </a>
    </div>

    <div class="navbar">
        <a href="../index.html"><img src="../img/home white.png" alt="Inicio" width="38px"></a> 
    </div> 

    <form method="post" class="formulario-login">
        <label for="nombre">Nombre(s):</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellidos">Apellido(s):</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="correo_electronico">Correo electrónico:</label>
        <input type="email" id="correo_electronico" name="correo_electronico" required>

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
    // Procesar los datos del formulario cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conexión a la base de datos utilizando PHPMyAdmin y XAMPP
        $servername = "localhost";
        $username_db = "root"; // Usuario por defecto en XAMPP
        $password_db = ""; // Contraseña por defecto en XAMPP
        $dbname = "saludmental"; // Nombre de la base de datos que has creado en PHPMyAdmin

        // Crear la conexión
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Obtener los datos del formulario
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $correo_electronico = $_POST["correo_electronico"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Insertar datos en la tabla usuarios
        $sql = "INSERT INTO usuarios (nombre, apellidos, correo_electronico, username, password) 
                VALUES ('$nombre', '$apellidos', '$correo_electronico', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Redirigir después de un registro exitoso
            header("Location: login.php");
            exit();
        } else {
            echo "Error al registrar: " . $conn->error;
        }

        // Cerrar la conexión
        $conn->close();
    }
    ?>
</body>
</html>
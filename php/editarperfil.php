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

    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo_electronico = $_POST['correo_electronico'];
        $foto = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_folder = "uploads/" . basename($foto);

        // Verificar si el directorio uploads existe
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Subir la foto
        if (!empty($foto) && move_uploaded_file($foto_tmp, $foto_folder)) {
            $sql = "UPDATE usuarios SET nombre=?, apellidos=?, correo_electronico=?, foto=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $apellidos, $correo_electronico, $foto_folder, $username);
        } else {
            $sql = "UPDATE usuarios SET nombre=?, apellidos=?, correo_electronico=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $apellidos, $correo_electronico, $username);
        }

// Ejecutar la declaración
if ($stmt->execute()) {
  $mensaje = "Perfil actualizado correctamente";
  $mensaje_clase = "mensaje-exito";
} else {
  $mensaje = "Error actualizando el perfil: " . $conn->error;
  $mensaje_clase = "mensaje-error";
}
        $stmt->close();
    }

    $sql = "SELECT nombre, apellidos, correo_electronico, foto FROM usuarios WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellidos, $correo_electronico, $foto);
    $stmt->fetch();
    $stmt->close();
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
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/blog.css">
  <link rel="stylesheet" href="../css/styles_mobile.css">
  <title>Blog ITCM • Editar perfil</title>
  <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
  <div id="encabezado">
    <h1>Editar perfil</h1>
    <a href="https://www.cdmadero.tecnm.mx">
      <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
    </a>
    <a href="https://www.tecnm.mx">
      <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
    </a>
  </div>

  <div class="navbar">
    <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
    <a href="logout.php">Cerrar sesión</a>
  </div> 
  <br>
  <div id="editarperfil">
  <?php if (isset($mensaje) && isset($mensaje_clase)): ?>
            <div class="<?php echo $mensaje_clase; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
    <form action="editarperfil.php" method="post" enctype="multipart/form-data">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

      <label for="apellidos">Apellidos:</label>
      <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>" required>

      <label for="correo_electronico">Correo electrónico:</label>
      <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($correo_electronico); ?>" required>

      <label for="foto">Foto de perfil:</label>
      <?php if ($foto): ?>
        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="user-foto">
      <?php endif; ?>
      <input type="file" id="foto" name="foto">

      <button type="submit">Actualizar perfil</button>
    </form>
  </div>
</body>
</html>
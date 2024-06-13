<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog ITCM • Buscar usuario</title>
    <link rel="icon" href="../img/logo-itcm-icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/styles_mobile.css">
</head>

<body>
    <div id="encabezado">
        <h1>Buscar usuario</h1>
        <a href="https://www.cdmadero.tecnm.mx">
            <img src="../img/logo-itcm.png" alt="Logo ITCM" class="logo-img">
        </a>
        <a href="https://www.tecnm.mx">
            <img src="../img/pleca_tecnm.jpg" alt="Logo TECNM" class="logo-img-tecnm">
        </a>
    </div>
    <div class="navbar">
        <a href="./blog.php"><img src="../img/return.png" alt="Inicio" width="38px"></a>
    </div>

    <div id="buscar-usuario">
        <form id="searchForm">
            <label for="search">Buscar por nombre o apellido:</label>
            <input type="text" id="search" name="search" required onkeyup="buscarUsuarios()">
            <button type="button" onclick="buscarUsuarios()">Buscar</button>
        </form>

        <div id="results">
            <!-- Aquí se mostrarán los resultados de la búsqueda -->
        </div>
    </div>

    <script>
        function buscarUsuarios() {
            var search = document.getElementById("search").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "buscar_usuario_ajax.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("results").innerHTML = xhr.responseText;
                }
            };
            xhr.send("search=" + search);
        }
    </script>
</body>

</html>
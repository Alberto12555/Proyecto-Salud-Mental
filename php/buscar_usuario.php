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
    <style>
        /* Estilos para el contenedor de búsqueda de usuarios */
        #buscar-usuario {
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el encabezado de la página */
        h4 {
            color: #1B396A;
            margin-bottom: 20px;
        }

        /* Estilos para el formulario de búsqueda */
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        form label {
            margin-bottom: 5px;
        }

        form input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        form button {
            align-self: flex-start;
            background-color: #1B396A;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #144573;
        }

        /* Estilos para los resultados de búsqueda */
        #results {
            margin-top: 20px;
        }

        .usuario {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .usuario h3 {
            margin: 0 0 10px;
        }

        .usuario p {
            margin: 5px 0;
            color: #666;
        }

        /* Estilos para la imagen de perfil del usuario */
        .user-foto {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
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
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("results").innerHTML = xhr.responseText;
        }
    };
    xhr.send("search=" + search);
}
</script>

</body>
</html>
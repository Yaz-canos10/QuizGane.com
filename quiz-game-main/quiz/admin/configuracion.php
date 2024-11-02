<?php
session_start();

// Si el usuario no está logeado, lo enviamos al login
if (!isset($_SESSION['usuarioLogeado'])) {
    header("Location: login.php");
    exit();
}

include("funciones.php");

$config = obtenerConfiguracion();

/******************************************************* */
// ACTUALIZAMOS LA CONFIGURACION
if (isset($_GET['actualizar'])) {
    // Nos conectamos a la base de datos
    require_once("conexion.php");
    global $bd; // Asegúrate de usar la variable de conexión correcta

    // Tomamos los datos que vienen del formulario
    $usuario = $_GET['usuario'];
    $password = $_GET['password'];
    $totalPreguntas = $_GET['totalPreguntas'];

    // Armamos el query para actualizar en la tabla configuración
    $query = "UPDATE config SET usuario='$usuario', password='$password', totalPreguntas='$totalPreguntas' WHERE id='1'";

    // Actualizamos en la tabla configuración
    if (mysqli_query($bd, $query)) { // Se actualizó correctamente
        $mensaje = "La configuración se actualizó correctamente";
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "No se pudo actualizar en la BD: " . mysqli_error($bd);
    }
}

// ELIMINAR PREGUNTAS
if (isset($_GET['eliminarPreguntas'])) {
    // Nos conectamos a la base de datos
    require_once("conexion.php");
    global $bd; // Asegúrate de usar la variable de conexión correcta

    // Sentencia para eliminar los datos de la tabla
    $query = "TRUNCATE TABLE preguntas";

    // Eliminamos los datos de la tabla preguntas
    if (mysqli_query($bd, $query)) { // Se eliminó correctamente
        $mensaje = "Se eliminaron los datos de la tabla preguntas";
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "No se pudo eliminar en la BD: " . mysqli_error($bd);
    }
}

// ELIMINAMOS LAS PREGUNTAS Y LAS CATEGORIAS
if (isset($_GET['eliminarTodo'])) {
    // Nos conectamos a la base de datos
    require_once("conexion.php");
    global $bd; // Asegúrate de usar la variable de conexión correcta

    // Sentencias para eliminar los datos de las tablas
    $query1 = "TRUNCATE TABLE preguntas";
    $query2 = "TRUNCATE TABLE temas";

    // Eliminamos los datos de la tabla preguntas y luego las categorías
    if (mysqli_query($bd, $query1)) { // Se eliminó correctamente
        if (mysqli_query($bd, $query2)) { // Se eliminó correctamente
            $mensaje = "Se eliminaron las preguntas y las categorías";
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "No se pudo eliminar las categorías en la BD: " . mysqli_error($bd);
        }
    } else {
        $mensaje = "No se pudo eliminar en la BD: " . mysqli_error($bd);
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="estilo.css">
    <title>Configuración - Quiz Game</title>
</head>
<body>
    <div class="contenedor">
        <header>
            <h1>QUIZ GAME</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?>
            <div class="panel">
                <h2>Configuración del Administrador</h2>
                <hr>
                <section id="configuracion">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                        <div class="fila">
                            <label for="">Usuario:</label>
                            <input type="text" name="usuario" value="<?php echo $config['usuario'] ?>" required>
                        </div>
                        <div class="fila">
                            <label for="">Password</label>
                            <input type="text" name="password" value="<?php echo $config['password'] ?>" required>
                        </div>
                        <div class="fila">
                            <label for="">Total Preguntas por Juego</label>
                            <input type="number" name="totalPreguntas" value="<?php echo $config['totalPreguntas'] ?>" required>
                        </div>
                        <hr>
                        <input type="submit" value="Actualizar Configuración" name="actualizar" class="btn-actualizar">
                    </form>
                </section>

                <h2>Preguntas y Categorías</h2>
                <hr>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" class="form-eliminar">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <input type="submit" value="Eliminar Preguntas (Solo se eliminarán las preguntas)" name="eliminarPreguntas" class="btn-eliminar">
                    <input type="submit" value="Eliminar Preguntas y Categorías" name="eliminarTodo" class="btn-eliminar">
                </form>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <script>paginaActiva(3);</script> 
</body>
</html>
<?php
session_start();

//Si el usuario no esta logeado lo enviamos al index
if (!$_SESSION['usuario']) {
    header("Location:index.php");
    exit();
}

include("admin/funciones.php");

$confi = obtenerConfiguracion();
$totalPreguntasPorJuego = $confi['totalPreguntas'];

// Variables que controlan la partida

if (isset($_GET['siguiente'])) { // Ya esta jugando
    // Aumento 1 en las estadísticas
    aumentarRespondidas();

    // Controlar si la respuesta está bien y guardar detalles de la pregunta
    $respuestaUsuario = $_GET['respuesta'];
    $preguntaActual = obtenerPreguntaPorId($_SESSION['idPreguntas'][$_SESSION['numPreguntaActual']]);

    if ($preguntaActual) {
        $_SESSION['respuestas'][] = [
            'pregunta' => $preguntaActual['pregunta'],
            'respuesta_usuario' => $respuestaUsuario,
            'respuesta_correcta' => $preguntaActual['correcta'],
            'es_correcta' => ($respuestaUsuario == $preguntaActual['correcta']),
            'opcion_usuario_texto' => $preguntaActual['opcion_' . strtolower($respuestaUsuario)],
            'opcion_correcta_texto' => $preguntaActual['opcion_' . strtolower($preguntaActual['correcta'])]
        ];
    }

    if ($_SESSION['respuesta_correcta'] == $respuestaUsuario) {
        $_SESSION['correctas'] = $_SESSION['correctas'] + 1;
    }

    $_SESSION['numPreguntaActual'] = $_SESSION['numPreguntaActual'] + 1;
    if ($_SESSION['numPreguntaActual'] < count($_SESSION['idPreguntas'])) {
        $preguntaActual = obtenerPreguntaPorId($_SESSION['idPreguntas'][$_SESSION['numPreguntaActual']]);
        $_SESSION['respuesta_correcta'] = $preguntaActual['correcta'];
    } else {
        // Lo enviamos a la página de los resultados
        $_SESSION['incorrectas'] = count($_SESSION['idPreguntas']) - $_SESSION['correctas'];
        $_SESSION['nombreCategoria'] = obtenerNombreTema($_SESSION['idCategoria']);
        $_SESSION['score'] = ($_SESSION['correctas'] * 100) / count($_SESSION['idPreguntas']);
        header("Location: final.php");
        exit();
    }

} else { // Comenzó a jugar
    $_SESSION['correctas'] = 0;
    $_SESSION['numPreguntaActual'] = 0;
    $_SESSION['preguntas'] = obtenerIdsPreguntasPorCategoria($_SESSION['idCategoria']);
    $_SESSION['idPreguntas'] = array();
    $_SESSION['respuestas'] = array();

    foreach ($_SESSION['preguntas'] as $idPregunta) {
        array_push($_SESSION['idPreguntas'], $idPregunta['id']); // Item agregado
    }
    
    // Desordeno el arreglo
    shuffle($_SESSION['idPreguntas']);
    $preguntaActual = obtenerPreguntaPorId($_SESSION['idPreguntas'][0]);
    $_SESSION['respuesta_correcta'] = $preguntaActual['correcta'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ GAME</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container-juego" id="container-juego">
        <header class="header">
            <div class="categoria">
                <?php echo obtenerNombreTema($preguntaActual['tema']) ?>
            </div>
            <a href="index.php">Quizgame.com</a>
        </header>
        <div class="info">
            <div class="estadoPregunta">
                Pregunta <span class="numPregunta"><?php echo $_SESSION['numPreguntaActual'] + 1?></span> de <?php echo $totalPreguntasPorJuego ?>
            </div>
            <h3>
                <?php echo $preguntaActual['pregunta']?>
            </h3>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                <div class="opciones">
                    <label for="respuesta1" onclick="seleccionar(this)" class="op1">
                        <p><?php echo $preguntaActual['opcion_a']?></p>
                        <input type="radio" name="respuesta" value="A" id="respuesta1" required>
                    </label>
                    <label for="respuesta2" onclick="seleccionar(this)" class="op2">
                        <p><?php echo $preguntaActual['opcion_b']?></p>
                        <input type="radio" name="respuesta" value="B" id="respuesta2" required>
                    </label>
                    <label for="respuesta3" onclick="seleccionar(this)" class="op3">
                        <p><?php echo $preguntaActual['opcion_c']?></p>
                        <input type="radio" name="respuesta" value="C" id="respuesta3" required>
                    </label>
                </div>
                <div class="boton">
                    <input type="submit" value="Siguiente" name="siguiente">
                </div>
            </form>
        </div>
    </div>
    <script src="juego.js"></script>
</body>
</html>

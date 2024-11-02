<?php
session_start();

//Si el usuario no esta logeado lo enviamos al index
if (!$_SESSION['usuario']) {
    header("Location:index.php");
    exit();
}

include("admin/funciones.php");

aumentarCompletados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="estilo.css">
    <title>QUIZ GAME</title>
    <style>
        .boton-detalles {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .boton-detalles:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container-final" id="container-final">
        <div class="info">
            <h2>RESULTADO FINAL</h2>
            <div class="estadistica">
                <div class="acierto">
                    <span class="correctas numero"> <?php echo isset($_SESSION['correctas']) ? $_SESSION['correctas'] : 0; ?></span>
                    CORRECTAS
                </div>
                <div class="acierto">
                    <span class="incorrectas numero"> <?php echo isset($_SESSION['incorrectas']) ? $_SESSION['incorrectas'] : 0; ?></span>
                    INCORRECTAS
                </div>
            </div>
            <div class="score">
                <div class="box">
                    <div class="chart" data-percent="<?php echo isset($_SESSION['score']) ? round($_SESSION['score']) : 0; ?>">
                       <?php echo isset($_SESSION['score']) ? round($_SESSION['score']) : 0; ?>%
                    </div>
                    <h2>SCORE</h2>
                </div>
            </div>

            <a href="index.php" class="boton-detalles">Ir al Menú</a>
            <a href="detalles.php" class="boton-detalles">Ver Detalles de Respuestas</a>
        </div>
    </div>
    <script src="juego.js"></script>
</body>
</html>

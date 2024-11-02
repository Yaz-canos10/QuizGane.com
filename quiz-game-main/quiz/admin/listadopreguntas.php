<?php
// Incluir las funciones para trabajar con la base de datos
require_once 'funciones.php';

// Obtener todas las preguntas
$resultado_preguntas = obtenerTodasLasPreguntas();

// Verificar si se obtuvieron preguntas correctamente
if (!$resultado_preguntas) {
    die("Error al obtener las preguntas: " . mysqli_error($bd));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="estilo.css">
    <title>Quiz Game</title>
</head>
<body>
    <div class="contenedor">
        <header>
            <h1>QUIZ GAME</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?>
            <div class="panel">
                <h2>Listado de Preguntas</h2>
                <hr>
                <section id="listadoPreguntas">
                    <?php while ($row = mysqli_fetch_assoc($resultado_preguntas)) : ?>
                        <div class="contenedor-pregunta">
                            <header>
                                <span class="tema"><?php echo obtenerNombreTema($row['tema'])?></span>
                                <div class="opciones">
                                    <i class="fa-solid fa-pen-to-square" onclick="editarPregunta(<?php echo $row['id']?>)"></i>
                                    <i class="fa-solid fa-trash" onclick="abrirModalEliminar(<?php echo $row['id']?>)"></i>
                                </div>
                            </header>
                            <p class="pregunta"><?php echo $row['pregunta']?></p>
                            <div class="opcion">
                                <div class="caja <?php if($row['correcta'] == 'A'){ echo 'pintarVerde'; } ?>">
                                    A
                                </div>
                                <span class="texto"><?php echo $row['opcion_a']?></span>
                            </div>
                            <div class="opcion">
                                <div class="caja <?php if($row['correcta'] == 'B'){ echo 'pintarVerde'; } ?>">B</div>
                                <span class="texto"><?php echo $row['opcion_b']?></span>
                            </div>
                            <div class="opcion">
                                <div class="caja <?php if($row['correcta'] == 'C'){ echo 'pintarVerde'; } ?>">C</div>
                                <span class="texto"><?php echo $row['opcion_c']?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </section>
            </div>
        </div>
    </div>
    <!-- The Modal para la eliminación de una Pregunta -->
    <div id="modalPregunta" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <p>¿Está seguro que desea eliminar la Pregunta?</p>
            <button onclick="eliminarPregunta()" class="btn">Sí</button>
            <button onclick="cerrarEliminar()" class="btn">Cancelar</button>
        </div>
    </div>
    <script src="script.js"></script>
    <script>paginaActiva(2);</script>   
</body>
</html>

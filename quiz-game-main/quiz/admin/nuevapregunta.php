<?php
session_start();

// Si el usuario no está logeado, lo enviamos al login
if (!isset($_SESSION['usuarioLogeado'])) {
    header("Location:login.php");
    exit();
}

include("funciones.php");

// Se presionó el botón Nuevo Tema
if (isset($_POST['nuevoTema'])) {
    // Tomamos los datos que vienen del formulario
    $tema = $_POST['nombreTema'];
    $mensaje = agregarNuevoTema($tema);
    header("Location: nuevapregunta.php");
    exit();
}

// Se presionó el botón Eliminar Tema
if (isset($_POST['eliminar_tema'])) {
    // Tomamos el tema seleccionado a eliminar
    $temaAEliminar = $_POST['tema_a_eliminar'];
    if (!empty($temaAEliminar)) {
        eliminarTema($temaAEliminar);
    }
    header("Location: nuevapregunta.php");
    exit();
}

// GUARDAMOS LA PREGUNTA
if (isset($_POST['guardar'])) {
    // Nos conectamos a la base de datos
    include("conexion.php");

    // Tomamos los datos que vienen del formulario
    $pregunta = htmlspecialchars($_POST['pregunta']);
    $opcion_a = htmlspecialchars($_POST['opcion_a']);
    $opcion_b = htmlspecialchars($_POST['opcion_b']);
    $opcion_c = htmlspecialchars($_POST['opcion_c']);
    $id_tema = $_POST['tema'];
    $correcta = $_POST['correcta'];

    // Armamos el query para insertar en la tabla preguntas
    $query = "INSERT INTO preguntas (id, tema, pregunta, opcion_a, opcion_b, opcion_c, correcta)
              VALUES (NULL, '$id_tema', '$pregunta', '$opcion_a', '$opcion_b', '$opcion_c', '$correcta')";

    // Insertamos en la tabla preguntas
    if (mysqli_query($bd, $query)) { // Se insertó correctamente
        $mensaje = "La pregunta se insertó correctamente";
    } else {
        $mensaje = "No se pudo insertar en la BD: " . mysqli_error($bd);
    }
}

// Obtengo todos los temas de la bd
$temas = obtenerTodosLosTemas();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="nuevapregunta.css">
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
                <h2>Complete la Pregunta</h2>
                <hr>
                <section id="nuevaPregunta">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="fila">
                            <label for="tema">Tema: </label>
                            <select name="tema" id="tema">
                                <?php while ($row = mysqli_fetch_assoc($temas)) : ?>
                                    <option value="<?php echo $row['id'] ?>">
                                        <?php echo $row['nombre'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <span class="agregarTema" onclick="agregarTema()">
                                <i class="fa-solid fa-circle-plus"></i>
                            </span>
                            <span class="eliminarTema" onclick="abrirModalEliminar()">
                                <i class="fa-solid fa-circle-minus"></i>
                            </span>
                        </div>
                        <div class="fila">
                            <label for="pregunta">Pregunta:</label>
                            <textarea name="pregunta" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="opciones">
                            <div class="opcion">
                                <label for="opcion_a">Opción A</label>
                                <input type="text" name="opcion_a" required>
                            </div>
                            <div class="opcion">
                                <label for="opcion_b">Opción B</label>
                                <input type="text" name="opcion_b" required>
                            </div>
                            <div class="opcion">
                                <label for="opcion_c">Opción C</label>
                                <input type="text" name="opcion_c" required>
                            </div>
                        </div>
                        <div class="opcion">
                            <label for="correcta">Correcta</label>
                            <select name="correcta" class="correcta">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <hr>
                        <input type="submit" value="Guardar Pregunta" name="guardar" class="btn-guardar">
                    </form>

                    <?php if (isset($mensaje)) : ?>
                        <span> <?php echo $mensaje ?></span>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>

    <!-- Ventana Modal para nuevo Tema -->
    <div id="modalTema" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarTema()">&times;</span>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <label for="nombreTema">Agregar Nuevo Tema</label>
                <input type="text" name="nombreTema" required>
                <input type="submit" name="nuevoTema" value="Guardar Tema" class="btn">
            </form>
        </div>
    </div>

    <!-- Ventana Modal para eliminar un tema -->
    <div id="modalEliminar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalEliminar()">&times;</span>
            <form action="" method="post">
                <label for="tema_a_eliminar_modal">Selecciona el tema a eliminar</label>
                <select name="tema_a_eliminar" id="tema_a_eliminar_modal" required>
                    <option value="">Selecciona un tema</option>
                    <?php foreach ($temas as $tema): ?>
                        <option value="<?php echo $tema['id']; ?>"><?php echo htmlspecialchars($tema['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="eliminar_tema" class="boton boton-eliminar" style="margin-top: 10px;">Eliminar Tema</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function abrirModalEliminar() {
            document.getElementById('modalEliminar').style.display = 'block';
        }
        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').style.display = 'none';
        }
        paginaActiva(1);
    </script>
</body>
</html>

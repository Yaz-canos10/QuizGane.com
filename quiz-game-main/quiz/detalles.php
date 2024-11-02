<?php
session_start();

//Si el usuario no esta logeado lo enviamos al index
if (!$_SESSION['usuario']) {
    header("Location:index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="estilosrespuestas.css">
    <title>Detalles de Respuestas</title>
</head>
<body>
    <div class="detalles-container">
        <h2 class="titulo">Detalles de las Respuestas</h2>
        <?php if (isset($_SESSION['respuestas']) && !empty($_SESSION['respuestas'])): ?>
            <ul class="lista-respuestas">
                <?php foreach ($_SESSION['respuestas'] as $respuesta): ?>
                    <li class="respuesta <?php echo $respuesta['es_correcta'] ? 'correcto' : 'incorrecto'; ?>">
                        <p><strong>Pregunta:</strong> <?php echo htmlspecialchars($respuesta['pregunta']); ?></p>
                        <p><strong>Tu Respuesta:</strong> <?php echo htmlspecialchars($respuesta['respuesta_usuario']); ?></p>
                        <p><strong>Respuesta Correcta:</strong> <?php echo htmlspecialchars($respuesta['respuesta_correcta']); ?></p>
                        <p>
                            <strong>Resultado:</strong> 
                            <?php echo $respuesta['es_correcta'] ? '<span class="correcto">Correcta</span>' : '<span class="incorrecto">Incorrecta</span>'; ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay respuestas disponibles para mostrar.</p>
        <?php endif; ?>
        <a href="index.php" class="boton-menu">Volver al Menú</a>
    </div>
</body>
</html>
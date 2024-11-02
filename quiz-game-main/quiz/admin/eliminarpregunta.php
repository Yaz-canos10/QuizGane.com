<?php

    include("conexion.php");
    $id = $_GET['idPregunta'];

    $query = "DELETE FROM preguntas WHERE id = '$id'";
    mysqli_query($bd, $query);
?>
<script>
    window.location.href = 'listadopreguntas.php';
</script>
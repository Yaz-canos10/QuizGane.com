<?php
require_once 'conexion.php'; // Incluir la conexión una sola vez

// Función para obtener el registro de la configuración del sitio
function obtenerConfiguracion() {
    global $bd; // Usar la conexión de base de datos definida en conexion.php

    // Comprobamos si existe el registro 1 que mantiene la configuración
    $query = "SELECT COUNT(*) AS total FROM config";
    $result = mysqli_query($bd, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row['total'] == '0') {
            // No existe el registro 1 - Insertamos el registro por primera vez
            $query = "INSERT INTO config (id, usuario, password, totalPreguntas)
                      VALUES (NULL, 'admin', 'admin', '3')";
            if (!mysqli_query($bd, $query)) {
                echo "No se pudo insertar en la BD: " . mysqli_error($bd);
            }
        }

        // Selecciono el registro de la configuración
        $query = "SELECT * FROM config WHERE id='1'";
        $result = mysqli_query($bd, $query);
        if ($result) {
            return mysqli_fetch_assoc($result);
        }
    }

    return null;
}

// Función para agregar un nuevo tema a la BD
function agregarNuevoTema($tema) {
    global $bd;
    $query = "INSERT INTO temas (id, nombre) VALUES (NULL, '$tema')";

    if (mysqli_query($bd, $query)) {
        header("Location: index.php");
        exit();
    } else {
        return "No se pudo insertar en la BD: " . mysqli_error($bd);
    }
}

function obtenerTodosLosTemas() {
    global $bd;
    $query = "SELECT * FROM temas";
    $result = mysqli_query($bd, $query);
    if ($result) {
        return $result;
    }
    return null;
}

// Función para obtener el nombre de un tema por ID
function obtenerNombreTema($id) {
    global $bd;
    $query = "SELECT * FROM temas WHERE id = '$id'";
    $result = mysqli_query($bd, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $tema = mysqli_fetch_assoc($result);
        return $tema['nombre'];
    }
    return null;
}

function obtenerTodasLasPreguntas() {
    global $bd;
    $query = "SELECT * FROM preguntas";
    $result = mysqli_query($bd, $query);
    if ($result) {
        return $result;
    }
    return null;
}

// Función para obtener una pregunta por ID
function obtenerPreguntaPorId($id) {
    global $bd;
    $query = "SELECT * FROM preguntas WHERE id = $id";
    $result = mysqli_query($bd, $query);
    if ($result) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Función para obtener el total de preguntas
function obtenerTotalPreguntas() {
    global $bd;
    $query = "SELECT COUNT(*) AS total FROM preguntas";
    $result = mysqli_query($bd, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    return 0;
}

// Función para obtener el total de preguntas por categoría
function totalPreguntasPorCategoria($tema) {
    global $bd;
    $query = "SELECT COUNT(*) AS total FROM preguntas WHERE tema = '$tema'";
    $result = mysqli_query($bd, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    }
    return 0;
}

// Función para obtener las categorías
function obtenerCategorias() {
    global $bd;
    $query = "SELECT tema, COUNT(DISTINCT tema) FROM preguntas GROUP BY tema";
    $result = mysqli_query($bd, $query);
    if ($result) {
        return $result;
    }
    return null;
}

// Función para obtener los IDs de preguntas por categoría
function obtenerIdsPreguntasPorCategoria($tema) {
    global $bd;
    $query = "SELECT id FROM preguntas WHERE tema = $tema";
    $result = mysqli_query($bd, $query);
    if ($result) {
        return $result;
    }
    return null;
}

// Función para aumentar las visitas
function aumentarVisita() {
    global $bd;
    $query = "UPDATE estadisticas SET visitas = visitas + 1 WHERE id='1'";
    mysqli_query($bd, $query);
}

// Función para aumentar las preguntas respondidas
function aumentarRespondidas() {
    global $bd;
    $query = "UPDATE estadisticas SET respondidas = respondidas + 1 WHERE id='1'";
    mysqli_query($bd, $query);
}

// Función para aumentar los cuestionarios completados
function aumentarCompletados() {
    global $bd;
    $query = "UPDATE estadisticas SET completados = completados + 1 WHERE id='1'";
    mysqli_query($bd, $query);
}

function eliminarTema($idTema) {
    global $bd; // Utiliza la conexión a la base de datos

    // Primero elimina las preguntas asociadas al tema
    $queryPreguntas = "DELETE FROM preguntas WHERE tema = '$idTema'";
    if (!mysqli_query($bd, $queryPreguntas)) {
        return "Error al eliminar las preguntas del tema: " . mysqli_error($bd);
    }

    // Luego elimina el tema en sí
    $queryTema = "DELETE FROM temas WHERE id = '$idTema'";
    if (mysqli_query($bd, $queryTema)) {
        return "Tema y preguntas eliminados correctamente";
    } else {
        return "Error al eliminar el tema: " . mysqli_error($bd);
    }
}


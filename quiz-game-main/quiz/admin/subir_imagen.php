<?php
session_start();
include("conexion.php"); // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagenPerfil'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $archivo = $_FILES['imagenPerfil'];
    $targetFile = $targetDir . uniqid() . "." . strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Comprobar si el archivo es una imagen
    $check = getimagesize($archivo["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Verificar tamaño del archivo (5MB máximo)
    if ($archivo["size"] > 5000000) {
        $uploadOk = 0;
    }

    // Limitar formatos de archivo permitidos
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Verificar si la subida es correcta
    if ($uploadOk == 1) {
        if (move_uploaded_file($archivo["tmp_name"], $targetFile)) {
            // Guardar la ruta en la base de datos
            $usuario = $_SESSION['usuarioLogeado'];
            $sql = "UPDATE usuarios SET imagen_perfil = ? WHERE nombre = ?";
            $stmt = $bd->prepare($sql);
            $stmt->bind_param("ss", $targetFile, $usuario);
            if ($stmt->execute()) {
                // Guardar la ruta en la sesión para que persista durante la sesión actual
                $_SESSION['imagenPerfil'] = $targetFile;
                echo json_encode(['exito' => true, 'ruta' => $targetFile]);
            } else {
                echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar la base de datos.']);
            }
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Hubo un error al mover el archivo.']);
        }
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Archivo no permitido o demasiado grande.']);
    }
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Solicitud no válida.']);
}
?>

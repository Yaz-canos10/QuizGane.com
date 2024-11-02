<?php
$servername = "localhost";  // Nombre del servidor
$username = "root";         // Nombre de usuario de MySQL (en XAMPP usualmente es root)
$password = "";             // Contraseña de MySQL (por defecto suele estar vacía en XAMPP)
$dbname = "quiz_game";        // Nombre de la base de datos

// Crear conexión
$bd = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($bd->connect_error) {
    die("Error de conexión: " . $bd->connect_error);
}
?>

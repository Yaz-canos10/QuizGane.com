<?php
session_start();
include ("funciones.php");
require 'conexion.php'; // Incluimos la conexión a la base de datos

//pregunto si se presionó el boton ingresar (login)
if (isset($_POST['login'])) {
    //tomo los datos que vienen del cliente
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Verifico los datos del usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $bd->prepare($sql);
    $stmt->bind_param("s", $usuario); // El email será el usuario
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifico la contraseña
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuarioLogeado'] = $user['nombre'];
            header("Location: index.php");
            exit();
        } else {
            $mensaje = "* El nombre de usuario o la contraseña son incorrectos";
        }
    } else {
        $mensaje = "* El nombre de usuario o la contraseña son incorrectos";
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
    <title>Quiz Game</title>
</head>
<body>
    <div class="contenedor-login">
        <h1>QUIZ GAME</h1>
        <div class="contenedor-form">
            <h3>Iniciar Sesión</h3>
            <hr>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="fila">
                    <label for="">Correo Electrónico</label>
                    <div class="entrada">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="usuario" required>
                    </div>
                </div>
                <div class="fila">
                    <label for="">Contraseña</label>
                    <div class="entrada">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="password" required>
                    </div>
                </div>
                <hr>
                <input type="submit" name="login" value="Ingresar" class="btn">
            </form>

            <!-- Mensaje que se mostrará cuando se haya procesado la solicitud en el servidor -->
            <?php if (isset($mensaje)) : ?>
                <span class="msj-error-input"> <?php echo $mensaje ?></span>
            <?php endif ?>

            <p>¿No tienes una cuenta? <a href="../admin/registro.php">Regístrate aquí</a>.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="estilo.css">
    <title>Registro de Usuario - Quiz Game</title>
</head>
<body>
    <?php
    // Incluir la conexión a la base de datos
    require '../admin/conexion.php';

    $mensaje = "";

    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña

        // Verificar si el correo electrónico ya está registrado
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $bd->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $mensaje = "El correo electrónico ya está registrado. Por favor, utiliza otro correo.";
        } else {
            // Insertar datos en la base de datos
            $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
            $stmt = $bd->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $nombre, $email, $password);
                if ($stmt->execute()) {
                    header('Location: ../admin/login.php');
                    exit();
                } else {
                    $mensaje = "Error: " . $stmt->error;
                }
            } else {
                $mensaje = "Error en la preparación de la consulta: " . $bd->error;
            }
        }
    }
    ?>

    <div class="contenedor-login">
        <h1>QUIZ GAME</h1>
        <div class="contenedor-form">
            <h3>Registro de Usuario</h3>
            <hr>
            <form action="registro.php" method="post">
                <div class="fila">
                    <label for="nombre">Nombre</label>
                    <div class="entrada">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="nombre" required>
                    </div>
                </div>
                <div class="fila">
                    <label for="email">Correo Electrónico</label>
                    <div class="entrada">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="fila">
                    <label for="password">Contraseña</label>
                    <div class="entrada">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" name="password" required>
                    </div>
                </div>
                <hr>
                <input type="submit" name="registrar" value="Registrarse" class="btn">
            </form>

            <!-- Mensaje que se mostrará cuando se haya procesado la solicitud en el servidor -->
            <?php if (isset($_POST['registrar']) && !empty($mensaje)) : ?>
                <span class="msj-error-input"> <?php echo $mensaje ?></span>
            <?php endif ?>

            <p>¿Ya tienes una cuenta? <a href="../admin/login.php">Inicia sesión aquí</a>.</p>
        </div>
    </div>
</body>
</html>
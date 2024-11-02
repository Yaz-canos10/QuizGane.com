<?php
session_start();
include("conexion.php"); // Asegúrate de incluir tu archivo de conexión a la base de datos

// Si el usuario no está logueado lo enviamos al login
if (!isset($_SESSION['usuarioLogeado'])) {
    header("Location:login.php");
    exit();
}

include("funciones.php");

$usuarioNombre = $_SESSION['usuarioLogeado'];

// Recuperar la ruta de la imagen de perfil desde la base de datos
$sql = "SELECT imagen_perfil FROM usuarios WHERE nombre = ?";
$stmt = $bd->prepare($sql);
$stmt->bind_param("s", $usuarioNombre);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$imagenPerfilRuta = $usuario['imagen_perfil'] ? $usuario['imagen_perfil'] : 'ruta/a/tu/imagen.jpg';

$totalPreguntas = obtenerTotalPreguntas();
$categorias = obtenerCategorias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="estilo.css">
    <title>QUIZ GAME</title>
</head>

<body>
    <div class="contenedor">
        <header>
            <div class="usuario-info" style="position: absolute; top: 30px; left: 20px; display: flex; align-items: center; height: 80px;">
                <div class="imagen-perfil" style="width: 60px; height: 60px; border-radius: 50%; background-color: #ccc; overflow: hidden; margin-right: 20px; position: relative; cursor: pointer;">
                    <img id="imagenPerfil" src="<?php echo htmlspecialchars($imagenPerfilRuta); ?>" alt="Imagen de perfil" style="width: 100%; height: 100%; object-fit: cover;">
                    <input type="file" id="inputImagenPerfil" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="cambiarImagenPerfil(event)">
                </div>
                <h2 style="margin: 0; font-size: 2.5em; color: #c0c0c0;"> <?php echo htmlspecialchars($usuarioNombre); ?> </h2>
            </div>
            <h1 style="text-align: center;">QUIZ GAME</h1>
        </header>
        <div class="contenedor-info">
            <?php include("nav.php") ?>
            <div class="panel">
                <h2>Dashboard</h2>
                <hr>
                <div id="dashboard">
                    <div class="card gradiente3">
                        <span class="tema">Total</span>
                        <span class="cantidad"><?php echo $totalPreguntas ?></span>
                        <span> Preguntas</span>
                    </div>

                    <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
                    <div class="card gradiente1">
                        <span class="tema"><?php echo obtenerNombreTema($cat['tema']); ?></span>
                        <span class="cantidad"> <?php echo totalPreguntasPorCategoria($cat['tema']); ?></span>
                        <span> Preguntas</span>
                    </div>
                    <?php endwhile ?>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <script>paginaActiva(0);</script>   
<script>
    const imagenPerfil = document.getElementById('imagenPerfil');
    const inputImagenPerfil = document.getElementById('inputImagenPerfil');

    imagenPerfil.addEventListener('click', () => {
        inputImagenPerfil.click();
    });

    function cambiarImagenPerfil(event) {
        const archivo = event.target.files[0];
        if (archivo) {
            const formData = new FormData();
            formData.append('imagenPerfil', archivo);

            fetch('subir_imagen.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.exito) {
                    imagenPerfil.src = data.ruta;
                } else {
                    alert('Hubo un error al subir la imagen.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>
</body>
</html>
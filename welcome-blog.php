<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    echo "BIENVENID@: {$_SESSION['user']['name']} <br>";
} else {
    // Redirigir a la página de inicio si no está autenticado
    header('Location: index-blog.php');
    exit;
}

// Verificar si ya existen posts en la cookie
$posts = isset($_COOKIE['posts']) ? json_decode($_COOKIE['posts'], true) : [];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo']) && isset($_POST['contenido'])) {
    $nuevo_post = [
        'titulo' => $_POST['titulo'],
        'contenido' => $_POST['contenido'],
        'autor' => $username,
        'fecha' => date('Y-m-d H:i:s')
    ];

    $posts[] = $nuevo_post;


    setcookie('posts', json_encode($posts), time() + (1* 24 * 60 * 60), "/"); // 1 dia
}

$posts = isset($_SESSION['posts']) ? $_SESSION['posts'] : []; // Obtener los posts almacenados en la sesión

// Verificar si se envió el formulario de creación de post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['titulo']) && isset($_POST['contenido'])) {
        // Validar el contenido del post
        $titulo = trim($_POST['titulo']);
        $contenido = trim($_POST['contenido']);
        
        if (!empty($titulo) && !empty($contenido)) {
            // Crear un nuevo post
            $nuevo_post = [
                'titulo' => htmlspecialchars($titulo),
                'contenido' => htmlspecialchars($contenido),
                'fecha' => date('Y-m-d H:i:s'),
                'autor' => $_SESSION['user']['name'] // Asignar el nombre del usuario como autor
            ];

            // Añadir el nuevo post al arreglo y guardarlo en la sesión
            $posts[] = $nuevo_post;
            $_SESSION['posts'] = $posts;
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }

    // Verificar si se ha solicitado eliminar un post
    if (isset($_POST['eliminar_post'])) {
        $indice = intval($_POST['eliminar_post']); // Convertir el índice a entero
        if (isset($posts[$indice])) {
            // Eliminar el post del arreglo
            array_splice($posts, $indice, 1);
            $_SESSION['posts'] = $posts; // Actualizar la sesión con los posts restantes
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="./estilos.css"> 
</head>
<body>

<div class="container">
    <h1>WELCOME</h1>
    <p><?php echo "Bienvenido: " . $_SESSION['user']['name']; ?></p>

    <!-- Formulario para crear un nuevo post -->
    <form action="welcome-blog.php" method="post">
        <label for="titulo">Título del Post:</label>
        <input type="text" id="titulo" name="titulo" placeholder="Ingresa el título del post" required>

        <label for="contenido">Contenido del Post:</label>
        <textarea id="contenido" name="contenido" placeholder="Escribe el contenido aquí" rows="5" required></textarea>

        <input type="submit" value="Crear Post">
    </form>

    <h2>Posts Recientes</h2>
    <ul>
        <?php
        if (!empty($posts)) {
            foreach ($posts as $index => $post) {
                echo "<li>";
                echo "<h3>" . htmlspecialchars($post['titulo']) . "</h3>";
                echo "<p>" . htmlspecialchars($post['contenido']) . "</p>";
                echo "<small>Por: " . htmlspecialchars($post['autor']) . " el " . $post['fecha'] . "</small>";

                // Formulario para eliminar el post
                echo '<form action="welcome-blog.php" method="post" style="display:inline">';
                echo '<input type="hidden" name="eliminar_post" value="' . $index . '">';
                echo '<input type="submit" value="Eliminar">';
                echo '</form>';

                echo "</li>";
            }
        } else {
            echo "<p>No hay posts aún.</p>";
        }
        ?>
        
    </ul>
    <!-- Botones para cerrar sesión -->
    <form action="logout-blog.php" method="post">
        <input type="submit" value="Cerrar sesión">
    </form>

    <!-- Botones de bloquear -->
    <form action="bloqueo.php" method="post">
        <input type="submit" value="Bloqueo">
    </form>
</div>

<?php include './footer-blog.php'; ?>

</body>
</html>
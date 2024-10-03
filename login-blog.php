<?php
session_start();
require_once './db-blog/db-blog.php';
// Optener los datos del formulario por POST
if (
    isset($_POST['user']) && !empty($_POST['user'])
    && isset($_POST['password']) && !empty($_POST['password'])
) {
    $row = 0;
    $user_name = $_POST['user'];
    $pass = $_POST['password'];
    foreach ($users as $user) {
        // echo $user['user'] . '<br>';
        if ($user['user'] == $user_name && $user['password'] == $pass) {
            $row = 1;
            break;
        }
    }
    if ($row == 1) {
        $_SESSION['user'] = $user;
        // creamos la cookie para almacenar el user name del usuario
        setcookie('user', $user['user'], time() + 3600);
        /**
         * Para establecer una cookie con una duración de una semana, un mes o un año, 
         * puedes ajustar el tercer parámetro de la función setcookie 
         * de la siguiente manera:
         * Una semana: time() + 7 * 24 * 60 * 60
         * Un mes: time() + 30 * 24 * 60 * 60
         * Un año: time() + 365 * 24 * 60 * 60
         */

        // Una semana
        // setcookie('user', $user['name'], time() + 7 * 24 * 60 * 60);
        // Un mes
        // setcookie('user', $user['name'], time() + 30 * 24 * 60 * 60);
        // Un año
        // setcookie('user', $user['name'], time() + 365 * 24 * 60 * 60);

        header('Location: welcome-blog.php');
    } else {
        echo "Usuario o contraseña incorrectos";
    }
} else {
    echo "Los campos están vacíos";
}

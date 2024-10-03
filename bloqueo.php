<?php
session_start(); //Aseguramos que inicie sesion

echo "Sesion bloqueada: {$_SESSION['user']['name']} <br>";

echo 'Ingrese la contraseña para ingresar nuevamente'

?>

<form action="welcome-blog.php" method="post">
    <input type="password" name="password" placeholder="Contraseña">
    <input type="submit" value="Iniciar sesión">
</from>
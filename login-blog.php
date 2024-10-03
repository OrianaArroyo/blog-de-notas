<?php
session_start();
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


    setcookie('posts', json_encode($posts), time() + (7 * 24 * 60 * 60), "/"); // 1 semana
}
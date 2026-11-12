<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Strategy</title>
    <!-- Requisito: CSS num ficheiro externo ligado com <link> -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div id="container-principal">
        <header>
            <h1>Memory Strategy</h1>
            <p>Estudo adaptativo e blocos de vocabulário inteligente</p>
        </header>
        <hr>
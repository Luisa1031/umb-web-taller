<?php
session_start(); // Inicia o reanuda la sesión

// Si se recibe un nombre de usuario por POST, se inicia la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'])) {
    $_SESSION["usuario"] = $_POST['usuario'];
    echo "Sesión iniciada para " . htmlspecialchars($_SESSION["usuario"]);
    // Si usaste la tabla 'usuarios', aquí podrías validar el usuario contra la BD antes de crear la sesión.
} 
// Mostrar el usuario actual de la sesión (si existe)
elseif (isset($_SESSION["usuario"])) {
    echo "Usuario actual: " . htmlspecialchars($_SESSION["usuario"]);
} 
// Para destruir la sesión (logout) se usaría:
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_destroy();
    echo "Sesión cerrada.";
}
else {
    echo "No hay sesión iniciada.";
}
?>
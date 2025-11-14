<?php
require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["nombre"] ?? null;
$email = $data["email"] ?? null;

if (!$nombre || !$email) {
    die(json_encode(["error" => "Faltan datos"]));
}

$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
$stmt->execute([$nombre, $email]);

echo json_encode(["mensaje" => "Usuario registrado"]);

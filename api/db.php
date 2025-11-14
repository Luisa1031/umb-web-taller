<?php
// === LEYENDO VARIABLES DE ENTORNO (SECRETS) ===
// Render nos pedirá configurar estas variables. 
$host = getenv('PLANETSCALE_HOST'); 
$dbname = getenv('PLANETSCALE_DBNAME');
$user = getenv('PLANETSCALE_USER');
$password = getenv('PLANETSCALE_PASS');

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    
    throw new \PDOException("Error de conexión a la BD: Asegúrate de configurar las variables de entorno (secrets). Mensaje: " . $e->getMessage(), (int)$e->getCode());
}
?>
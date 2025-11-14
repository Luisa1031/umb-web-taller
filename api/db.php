<?php
try {
    // Lectura de variables de entorno de Render
    $host = getenv('PLANETSCALE_HOST');
    $db_name = getenv('PLANETSCALE_DBNAME');
    $username = getenv('PLANETSCALE_USER');
    $password = getenv('PLANETSCALE_PASS');

    // CONEXIÓN A PLANETSCALE (PDO con SSL)
    $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        // Configuración para usar SSL (PlanetScale)
        PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt', // Ruta común en entornos Linux/Docker
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch (PDOException $e) {
    // =================================================================
    // CRÍTICO: Si la conexión falla, emitimos un JSON de error y terminamos.
    // Esto evita que React reciba HTML.
    // =================================================================

    // Enviamos cabeceras JSON
    header("Content-Type: application/json; charset=UTF-8");
    
    // Enviamos código de error 500 (Internal Server Error)
    http_response_code(500); 

    // Imprimimos el mensaje de error en JSON
    echo json_encode([
        'error' => 'Database connection failed', 
        'message' => 'Asegúrate de configurar las variables de entorno correctamente.',
        // NO incluyas $e->getMessage() en producción por seguridad
    ]);
    
    // Terminamos la ejecución del script
    exit();
}

// $pdo está disponible globalmente para modelo.php
?>
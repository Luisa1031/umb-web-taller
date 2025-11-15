<?php



try {
   
    $host = getenv('DB_HOST');
    $db_name = getenv('DB_NAME');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');

    // 2. CONEXIÓN A PLANETSCALE (PDO con SSL)
    $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
    
    // Opciones de configuración de PDO
    $options = [
        PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en caso de error
        PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,       // Obtener resultados como array asociativo por defecto
        PDO::ATTR_EMULATE_PREPARES     => false,                  // Usar prepared statements nativos
        
        // 3. Configuración SSL para PlanetScale (Requerido)
        // Opción A: Usar la ruta del certificado del sistema (más seguro si la ruta existe)
        PDO::MYSQL_ATTR_SSL_CA         => '/etc/ssl/certs/ca-certificates.crt', 
        
        // Opción B (Alternativa/Menos Segura): Si la Opción A falla, puedes probar
        // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Si la conexión es exitosa, $pdo estará disponible para otros archivos
    
} catch (PDOException $e) {
    // =================================================================
    // CRÍTICO: Si la conexión falla, emitimos un JSON de error y terminamos.
    // Esto es vital para APIs que interactúan con clientes como React.
    // =================================================================

    // Enviamos cabeceras JSON
    header("Content-Type: application/json; charset=UTF-8");
    
    // Enviamos código de error 500 (Internal Server Error)
    http_response_code(500); 

    // Imprimimos el mensaje de error en JSON
    echo json_encode([
        'error' => 'Database connection failed', 
        'message' => 'Asegúrate de configurar las variables de entorno correctamente (HOST, USER, PASS, DBNAME).',
        // ¡ADVERTENCIA DE SEGURIDAD! Desactiva/Comenta la línea siguiente en Producción
        'debug_error' => $e->getMessage() 
    ]);
    
    // Terminamos la ejecución del script
    exit();
}

// $pdo está disponible globalmente para modelo.php

?>
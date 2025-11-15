<?php
// Incluimos el archivo de conexión. 
// Si hay un error de conexión, db.php ya se encarga de mostrar un JSON de error y salir.
require_once 'db.php'; 

// Si la ejecución llega a este punto, significa que db.php se ejecutó sin errores 
// en el bloque try/catch, y la variable $pdo está disponible.

header("Content-Type: application/json; charset=UTF-8");
http_response_code(200);

echo json_encode([
    'status' => 'success', 
    'message' => '¡Conexión a la base de datos PlanetScale establecida correctamente con PDO y SSL!',
    'pdo_class' => get_class($pdo) // Debería mostrar 'PDO'
]);

// Opcional: Ejecutar una consulta simple para confirmación extra
try {
    $stmt = $pdo->query("SELECT 1");
    $result = $stmt->fetchColumn();
    
    // Si la consulta es exitosa, se añade un mensaje de confirmación
    echo json_encode(['db_query_test' => ($result == 1) ? 'Consulta SELECT 1 exitosa' : 'Error en consulta simple']);
} catch (Exception $e) {
    // Si la consulta falla (ej: tabla incorrecta, aunque SELECT 1 no usa tablas)
    echo json_encode(['db_query_error' => $e->getMessage()]);
}

?>
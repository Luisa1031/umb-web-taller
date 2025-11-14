<?php
require_once 'db.php'; // Usa la conexión PDO

// CREATE (Crear Tarea)
function crearTarea($titulo) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tareas (titulo) VALUES (?)");
    // Usar execute() con un array de parámetros es la forma segura
    $stmt->execute([$titulo]); 
    // Devuelve el ID de la tarea recién creada
    return $pdo->lastInsertId();
}

// READ (Leer Todas las Tareas)
function obtenerTareas() {
    global $pdo;
    // Consulta simple, no requiere prepared statement
    $stmt = $pdo->query("SELECT id, titulo, completada FROM tareas ORDER BY id DESC");
    return $stmt->fetchAll();
}

// UPDATE (Actualizar Tarea - título o estado)
function actualizarTarea($id, $titulo = null, $completada = null) {
    global $pdo;
    $sql_parts = [];
    $params = [];

    if ($titulo !== null) {
        $sql_parts[] = "titulo = ?";
        $params[] = $titulo;
    }
    
    // Convertir el valor a 0 o 1, PlanetScale usa BOOLEAN/TINYINT(1)
    if ($completada !== null) {
        $completada_val = filter_var($completada, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $sql_parts[] = "completada = ?";
        $params[] = $completada_val;
    }

    if (empty($sql_parts)) {
        return false; // No hay datos para actualizar
    }

    $sql = "UPDATE tareas SET " . implode(", ", $sql_parts) . " WHERE id = ?";
    $params[] = $id; // El ID siempre es el último parámetro

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// DELETE (Eliminar Tarea)
function eliminarTarea($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = ?");
    return $stmt->execute([$id]);
}
?>
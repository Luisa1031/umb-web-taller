<?php
require_once 'db.php'; // Conexión PDO

// =========================
//   CREATE
// =========================
function crearTarea($titulo) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tareas (titulo) VALUES (?)");
    $stmt->execute([$titulo]);
    return $pdo->lastInsertId();
}

// =========================
//   READ
// =========================
function obtenerTareas() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, titulo, completada FROM tareas ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // 🔥 Importante
}

// =========================
//   UPDATE
// =========================
function actualizarTarea($id, $titulo = null, $completada = null) {
    global $pdo;

    $sql_parts = [];
    $params = [];

    if ($titulo !== null) {
        $sql_parts[] = "titulo = ?";
        $params[] = $titulo;
    }

    if ($completada !== null) {
        // Manejo correcto de valores 0 o 1
        $completada_val = ($completada == 1) ? 1 : 0;
        $sql_parts[] = "completada = ?";
        $params[] = $completada_val;
    }

    if (empty($sql_parts)) {
        return false;  // Nada para actualizar
    }

    $sql = "UPDATE tareas SET " . implode(", ", $sql_parts) . " WHERE id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

// =========================
//   DELETE
// =========================
function eliminarTarea($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = ?");
    return $stmt->execute([$id]);
}
?>

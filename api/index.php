<?php
// === CABECERAS CORS Y CONTENIDO ===

// Permitir solicitudes desde cualquier origen (el frontend React)
header("Access-Control-Allow-Origin: *");
// Indicar que la respuesta es JSON
header("Content-Type: application/json; charset=UTF-8");
// Permitir las cabeceras comunes
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejo de la solicitud OPTIONS (pre-flight request de navegadores)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    http_response_code(200);
    exit();
}

require_once 'modelo.php';

// ESTA LÍNEA ES CRÍTICA: Limpia cualquier output accidental (Buffer Output)
if (ob_get_length()) {
    ob_clean();
}

// Obtener el método de la petición
$metodo = $_SERVER['REQUEST_METHOD'];

// Para POST, PUT, DELETE, se leen los datos del body (JSON)
$datos = json_decode(file_get_contents('php://input'), true);

switch ($metodo) {
    case 'GET':
        // **READ:** Obtener todas las tareas
        $tareas = obtenerTareas();
        echo json_encode($tareas);
        break;

    case 'POST':
        // **CREATE:** Crear una nueva tarea
        if (isset($datos['titulo'])) {
            // Intentar crear la tarea y obtener el ID
            $id_nueva = crearTarea($datos['titulo']);
            http_response_code(201); // 201 Created
            echo json_encode(['mensaje' => 'Tarea creada', 'id' => $id_nueva, 'titulo' => $datos['titulo']]);
        } else {
            http_response_code(400); // 400 Bad Request
            echo json_encode(['error' => 'Falta el campo "titulo"']);
        }
        break;

    case 'PUT':
        // **UPDATE:** Actualizar una tarea existente (título o estado)
        if (isset($datos['id'])) {
            $id = (int)$datos['id'];
            $titulo = $datos['titulo'] ?? null;
            $completada = $datos['completada'] ?? null;

            if (actualizarTarea($id, $titulo, $completada)) {
                echo json_encode(['mensaje' => "Tarea $id actualizada"]);
            } else {
                http_response_code(404); // Podría ser 404 Not Found o 304 Not Modified
                echo json_encode(['error' => "No se pudo actualizar la tarea $id o no hay datos nuevos"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Falta el campo "id"']);
        }
        break;

    case 'DELETE':
        // **DELETE:** Eliminar una tarea
        if (isset($datos['id'])) {
            $id = (int)$datos['id'];
            if (eliminarTarea($id)) {
                echo json_encode(['mensaje' => "Tarea $id eliminada"]);
            } else {
                http_response_code(404); // 404 Not Found
                echo json_encode(['error' => "No se encontró la tarea $id"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Falta el campo "id"']);
        }
        break;

    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['mensaje' => 'Método no permitido']);
        break;
}
?>
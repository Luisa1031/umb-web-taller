<?php
// =======================
//   CABECERAS CORS
// =======================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Manejar petición OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// =======================
//   IMPORTAR MODELO
// =======================
require_once "modelo.php";

// Limpia cualquier salida inesperada
if (ob_get_length()) {
    ob_clean();
}

// Obtener método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];
$datos = json_decode(file_get_contents("php://input"), true);

// =======================
//   ENRUTADOR SIMPLE API
// =======================
switch ($metodo) {

    // ========== READ ==========
    case "GET":
        $tareas = obtenerTareas();
        echo json_encode($tareas);
        break;

    // ========== CREATE ==========
    case "POST":
        if (!isset($datos["titulo"]) || trim($datos["titulo"]) === "") {
            http_response_code(400);
            echo json_encode(["error" => "El campo 'titulo' es obligatorio"]);
            exit();
        }

        $titulo = trim($datos["titulo"]);
        $idNueva = crearTarea($titulo);

        http_response_code(201);
        echo json_encode([
            "mensaje" => "Tarea creada",
            "id" => $idNueva,
            "titulo" => $titulo
        ]);
        break;

    // ========== UPDATE ==========
    case "PUT":
        if (!isset($datos["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "Falta el campo 'id'"]);
            exit();
        }

        $id = intval($datos["id"]);
        $titulo = $datos["titulo"] ?? null;
        $completada = $datos["completada"] ?? null;

        if (actualizarTarea($id, $titulo, $completada)) {
            echo json_encode(["mensaje" => "Tarea $id actualizada"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "La tarea no existe o no se pudo actualizar"]);
        }
        break;

    // ========== DELETE ==========
    case "DELETE":
        if (!isset($datos["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "Falta el campo 'id'"]);
            exit();
        }

        $id = intval($datos["id"]);

        if (eliminarTarea($id)) {
            echo json_encode(["mensaje" => "Tarea $id eliminada"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró la tarea"]);
        }
        break;

    // ========== MÉTODO NO PERMITIDO ==========
    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>

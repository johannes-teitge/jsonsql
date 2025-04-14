<?php
// JsonSQL-API.php
header('Content-Type: application/json');

require_once __DIR__ . '/../src/JsonSQL.php';
use Src\JsonSQL;

// Datenbank konfigurieren
$db = new JsonSQL(['api' => __DIR__ . '/../testdb']);
$db->use('api');

// Tabelle bestimmen
$table = $_GET['table'] ?? null;
if (!$table) {
    http_response_code(400);
    echo json_encode(['error' => 'No table specified']);
    exit;
}

$db->from($table);

// HTTP-Methode bestimmen
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $db->where([['id', '=', (int)$_GET['id']]])->get();
            echo json_encode($result[0] ?? null);
        } else {
            echo json_encode($db->get());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            exit;
        }
        $db->insert($data);
        echo json_encode(['status' => 'inserted', 'id' => $db->getLastInsertId()]);
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID for update']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            exit;
        }
        $count = $db->where([['id', '=', (int)$_GET['id']]])->update($data);
        echo json_encode(['status' => 'updated', 'count' => $count]);
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID for delete']);
            exit;
        }
        $count = $db->where([['id', '=', (int)$_GET['id']]])->delete();
        echo json_encode(['status' => 'deleted', 'count' => $count]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

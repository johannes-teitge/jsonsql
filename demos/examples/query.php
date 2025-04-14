<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/src/JsonSQL.php';

use Src\JsonSQL;

$input = json_decode(file_get_contents('php://input'), true);

$db = new JsonSQL(['main' => __DIR__ . '/testdb']);
$db->use('main')->from($input['table']);

if (!empty($input['where'])) {
    $parts = explode('&', $input['where']);
    $filters = [];
    foreach ($parts as $part) {
        // Unterstützte Operatoren – längere zuerst
        $operators = ['>=', '<=', '!=', '==', '=', '>', '<', 'like'];
        $matched = false;

        foreach ($operators as $op) {
            $pattern = '/^(.+?)\s*' . preg_quote($op, '/') . '\s*(.+)$/i';
            if (preg_match($pattern, $part, $matches)) {
                $filters[] = [trim($matches[1]), strtolower($op), trim($matches[2])];
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            // Optional: Ignorierte oder ungültige Bedingungen loggen
            error_log("Unbekannte Bedingung: $part");
        }
    }

    if (!empty($filters)) {
        $db->where($filters, 'AND');
    }
}

if (!empty($input['select'])) {
    $db->select($input['select']);
}

if (!empty($input['order'])) {
    [$col, $dir] = explode(' ', $input['order'] . ' ASC');
    $db->orderBy(trim($col), strtoupper(trim($dir)));
}

if (!empty($input['limit'])) {
    [$limit, $offset] = explode(',', $input['limit'] . ',0');
    $db->limit((int)$limit, (int)$offset);
}

echo json_encode($db->get(), JSON_PRETTY_PRINT);

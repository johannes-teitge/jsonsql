<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../src/JsonSQL.php';
use Src\JsonSQL;

try {


    // Init DB
    $db = new JsonSQL(['main' => __DIR__ . '/db']);
    $db->use('main')->from('passwords');

    // Optionaler Filter
    $search = $_GET['search'] ?? '';
    file_put_contents(__DIR__ . '/../debug.log', "Search: " . $search . "\n", FILE_APPEND);

    if ($search) {
        $db->where([
            ['title', 'like', $search],
            ['username', 'like', $search]
        ], 'OR');
    }

    // file_put_contents(__DIR__ . '/../debug.log', print_r($db, true), FILE_APPEND);


    // Daten holen
    $result = $db->get();
  
    file_put_contents(__DIR__ . '/../debug.log', print_r($result, true), FILE_APPEND);       

    echo json_encode([
        'success' => true,
        'data' => $result
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    // Fehlerbehandlung
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

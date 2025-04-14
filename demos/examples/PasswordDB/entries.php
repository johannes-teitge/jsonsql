<?php
require_once __DIR__ . '/../../src/JsonSQL.php';
use Src\JsonSQL;

header('Content-Type: application/json');

// Init
$db = new JsonSQL(['main' => __DIR__ . '/db']);
$db->use('main')->from('passwoerter');

// Optional: Filter über Query-Parameter
$search = $_GET['search'] ?? '';
if ($search) {
    $db->where([
        ['bezeichnung', 'like', $search],
        ['benutzer', 'like', $search]
    ], 'OR');
}

echo json_encode($db->get(), JSON_PRETTY_PRINT);

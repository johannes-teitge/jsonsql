<?php
header('Content-Type: application/json'); // Immer JSON liefern
$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    echo json_encode(['status' => 'error', 'message' => '❌ JsonSQL-Datei nicht gefunden!']);
    exit;
}
require_once $JsonSQLpath;

use Src\JsonSQL;

try {
    $db = new JsonSQL(['main' => __DIR__ . '/CarDB']);
    $db->use('main');
    $table = 'cars';
    $db->setBackupMode(true);    

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = $_GET['id'] ?? null;
        $field = $_GET['field'] ?? '';
        $value = $_GET['value'] ?? null; // 👉 auch '0' oder '' erlaubt

        if ($id === null || $field === '' || $value === null) {
            throw new Exception("Fehlende Parameter.");
        }

        $updateData = [$field => $value];

        $db->setTable($table);
        $updatedCount = $db->from($table)->where([['id', '=', $id]])->update($updateData);

        // Nur bei Erfolg:
        echo json_encode([
            'status' => 'success',
            'message' => 'Feld erfolgreich aktualisiert.',
            'updated' => $updatedCount
        ]);
    } else {
        throw new Exception("Ungültige Anfrageart.");
    }

} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

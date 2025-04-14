<?php
// JsonSQL Performance Test mit einer JSON-Datenbank
header('Content-Type: application/json');

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

use Src\JsonSQL;


try {
    // JsonSQL-Datenbank verwenden
    $db = new JsonSQL(['demo' => __DIR__ . '/../testdb']); // Pfad zur JSON-Datenbank
    $db->use('demo'); // Beispiel für die Verwendung der 'demo'-Datenbank

    // Eine einfache SELECT-Abfrage ausführen
    $result = $db->from('stresstest') // Ersetze 'stresstest' mit deinem Tabellennamen in der JSON-Datenbank
                 ->limit(1)  // Begrenzung auf eine Zeile
                 ->get();

    // Wenn die Abfrage erfolgreich war, die Daten zurückgeben
    if ($result && count($result) > 0) {
        // Erfolgreiche Antwort zurückgeben, mit den ersten Datensatz-Daten
        $response = [
            'status' => 'success',
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $result[0], // Daten des ersten Datensatzes
        ];
    } else {
        // Keine Daten gefunden
        $response = [
            'status' => 'error',
            'message' => 'Keine Daten gefunden.',
        ];
    }
} catch (Exception $e) {
    // Fehlerbehandlung für JsonSQL-Abfragen
    $response = [
        'status' => 'error',
        'message' => 'Fehler: ' . $e->getMessage(),
    ];
}

// Antwort als JSON ausgeben
echo json_encode($response);
?>

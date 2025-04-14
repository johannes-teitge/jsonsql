<?php
// Fehlerbericht aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;


use Src\JsonSQL;


// Verbindung zur DB herstellen
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$farbverlaufTabelle = 'farbverlaeufe';

// POST-Request überprüfen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfe die POST-Daten und decodiere sie
    $inputData = json_decode(file_get_contents('php://input'), true);

    if ($inputData === null) {
        // Fehlerbehandlung: Wenn die JSON-Daten ungültig sind
        echo json_encode(['status' => 'error', 'message' => 'Ungültige JSON-Daten empfangen.']);
        exit;  // Ende der Ausführung
    }

    $id = $inputData['id'] ?? null; // ID (falls vorhanden)

    if ($id) {
        // Tabelle setzen
        $db->setTable($farbverlaufTabelle);

        // Löschen des Farbverlaufs
        $deletedCount = $db->from($farbverlaufTabelle)->where([['id', '=', $id]])->delete();

        if ($deletedCount > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Farbverlauf erfolgreich gelöscht!', 'deletedCount' => $deletedCount]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Farbverlauf konnte nicht gelöscht werden. Entweder existiert er nicht oder ein Fehler ist aufgetreten.', 'deletedCount' => 0]);
        }
    } else {
        // Wenn keine gültige ID übergeben wurde
        echo json_encode(['status' => 'error', 'message' => 'Keine gültige ID übergeben.', 'deletedCount' => 0]);
    }
} else {
    // Fehlerbehandlung für nicht-POST-Anfragen
    echo json_encode(['status' => 'error', 'message' => 'Ungültige Anfrage. Es wurde keine POST-Anfrage empfangen.', 'deletedCount' => 0]);
}

exit; // Wichtiger Punkt: Verhindert, dass zusätzliche Ausgaben gemacht werden.
?>

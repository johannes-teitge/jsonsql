<?php
$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;


use Src\JsonSQL;


// Verbindung zur Datenbank herstellen
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$farbverlaufTabelle = 'farbverlaeufe';

// Überprüfen, ob die POST-Daten gesetzt wurden
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Die übergebenen Daten auslesen
    $inputData = json_decode(file_get_contents('php://input'), true);
    $id = $inputData['id'] ?? null; // ID (falls vorhanden)
    $title = $inputData['title'] ?? '';
    $color1 = $inputData['color1'] ?? '';
    $color2 = $inputData['color2'] ?? '';

    // Sicherstellen, dass alle Felder ausgefüllt sind
    if ($title && $color1 && $color2) {
        // Farbverlauf Daten vorbereiten
        $farbverlaufData = [
            'title' => $title,
            'color1' => $color1,
            'color2' => $color2,
       // 'created_at' und 'updated_at' werden automatisch von der JsonSQL-Klasse gesetzt
        ];

        if ($id) {
            // 1. Update (falls ID vorhanden)
            $db->setTable($farbverlaufTabelle);
            $updatedCount = $db->from($farbverlaufTabelle)->where([['id', '=', $id]])->update($farbverlaufData);

            if ($updatedCount > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Farbverlauf erfolgreich aktualisiert!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Es gab ein Problem beim Aktualisieren des Farbverlaufs.']);
            }
        } else {
            // 2. Insert (falls keine ID vorhanden)
            $db->setTable($farbverlaufTabelle);

            // Farbverlauf in der Tabelle speichern
            $db->from($farbverlaufTabelle)->insert($farbverlaufData);

            echo json_encode(['status' => 'success', 'message' => 'Farbverlauf erfolgreich erstellt!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bitte alle Felder ausfüllen.']);
    }
}
?>

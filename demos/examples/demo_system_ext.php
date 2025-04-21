<?php
$pageTitle = "JsonSQL Demo: Systemfelder dynamisch hinzufügen und entfernen";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Datenbank initialisieren
$db = new JsonSQL(['main' => __DIR__ . '/SystemDemoDB']);
$db->use('main');
$table = 'users';

// 1. Tabelle überprüfen und ggf. anlegen
$db->Truncate($table); // Tabelle wird gelöscht und neu geladen

// Validierungsfunktionen
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateNumber($number) {
    return is_numeric($number);
}

function validateEnum($value, $enumValues) {
    $values = explode(',', $enumValues);
    return in_array($value, $values);
}

// Methode zum Hinzufügen eines Systemfeldes
function addSystemField($fieldName, $definition) {
    global $db;

    // Überprüfen, ob das Feld bereits existiert
    $existingField = isset($db->getRawSystemData()['fields'][$fieldName]);

    // Validierung durchführen
    if ($definition['dataType'] === 'email' && !validateEmail($definition['defaultValue'])) {
        return ["⚠️ Ungültige E-Mail-Adresse!", 'error'];
    }

    if ($definition['dataType'] === 'integer' && !validateNumber($definition['defaultValue'])) {
        return ["⚠️ Standardwert muss eine Zahl sein!", 'error'];
    }

    // Überprüfen, ob enum-Werte vorhanden sind, und nur dann die Enum-Validierung durchführen
    if (isset($definition['enumValues']) && $definition['dataType'] === 'enum' && !validateEnum($definition['defaultValue'], $definition['enumValues'])) {
        return ["⚠️ Der Standardwert muss ein gültiger ENUM-Wert sein!", 'error'];
    }

    // Wenn das Feld existiert, aktualisieren wir es
    if ($existingField) {
        $db->addFieldDefinition($fieldName, $definition); // Dies wird automatisch gespeichert
        return ["✅ Das '$fieldName'-Feld wurde erfolgreich aktualisiert!", 'success'];
    }

    // Wenn das Feld nicht existiert, fügen wir es hinzu
    $db->addFieldDefinition($fieldName, $definition); // Dies wird automatisch gespeichert
    return ["✅ Das '$fieldName'-Feld wurde erfolgreich hinzugefügt!", 'success'];
}



// Methode zum Entfernen eines Systemfeldes
function removeSystemField($fieldName) {
    global $db;
    $db->removeFieldDefinition($fieldName); // Dies wird automatisch gespeichert
    return "❌ Das '$fieldName'-Feld wurde erfolgreich entfernt!";
}

// Initialisierung der Felder für die Demo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_field'])) {
        // Beispiel: Systemfeld 'email' hinzufügen
        $response = addSystemField($_POST['fieldName'], [
            'dataType' => $_POST['dataType'],
            'length' => $_POST['length'] ?? null,
            'allowNULL' => isset($_POST['allowNull']) ? true : false,
            'defaultValue' => $_POST['defaultValue'] ?? null,
            'comment' => $_POST['comment'] ?? null,
            'enumValues' => $_POST['enumValues'] ?? null,
        ]);

        $snackbarMessage = $response[0]; // Die Nachricht
        $snackbarClass = $response[1];   // Die Klasse für Erfolg oder Fehler
    } elseif (isset($_POST['remove_field'])) {
        // Beispiel: Systemfeld 'email' entfernen
        $responseMessage = removeSystemField($_POST['remove_field']);
        
        $snackbarClass = 'info';
        $snackbarMessage = $responseMessage;
    }
}

// Rohdaten aus der JSON-Datei
$rawJson = json_encode($db->getRawSystemData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$fields = $db->getRawSystemData()['fields']; // Alle Felder aus der Systemkonfiguration holen
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Dynamische Systemfeld-Verwaltung</h2>
    <p>
        Mit dieser Demo kannst du Systemfelder hinzufügen, anpassen und löschen. 
        Wenn du versuchst, ein bereits bestehendes Feld hinzuzufügen, wird dieses 
        <strong>aktualisiert</strong>, andernfalls wird es neu erstellt. 
        Ein erfolgreich hinzugefügtes oder entferntes Feld wird in einer 
        <strong>Snackbar</strong> angezeigt.
    </p>

    <hr class="my-4">
    
    <!-- Erfolg oder Fehler Snackbar -->
    <?php if (isset($snackbarMessage)): ?>
        <div id="snackbar" class="snackbar <?= $snackbarClass ?> show"><?= htmlspecialchars($snackbarMessage) ?></div>
    <?php endif; ?>

    <!-- Formular zum Hinzufügen eines Systemfeldes -->
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="fieldName">Feldname:</label>
            <input type="text" id="fieldName" name="fieldName" class="form-control" required>
        </div>
        <div class="form-group my-3">
            <label for="dataType">Datentyp:</label>
            <select id="dataType" name="dataType" class="form-control" required>
                <option value="string">String</option>
                <option value="text">Text</option>                
                <option value="integer">Integer</option>
                <option value="boolean">Boolean</option>
                <option value="enum">Enum</option>
            </select>
        </div>
        <div class="form-group my-3" id="enumValues" style="display: none;">
            <label for="enumValuesInput">Enum-Werte (durch Komma getrennt):</label>
            <input type="text" id="enumValuesInput" name="enumValues" class="form-control">
        </div>
        <div class="form-group my-3">
            <label for="length">Länge:</label>
            <input type="number" id="length" name="length" class="form-control">
        </div>
        <div class="form-check">
            <input type="checkbox" id="allowNull" name="allowNull" class="form-check-input">
            <label class="form-check-label" for="allowNull">Allow NULL</label>
        </div>
        <div class="form-group my-3">
            <label for="defaultValue">Standardwert:</label>
            <input type="text" id="defaultValue" name="defaultValue" class="form-control">
        </div>
        <div class="form-group my-3">
            <label for="comment">Kommentar:</label>
            <input type="text" id="comment" name="comment" class="form-control">
        </div>
        <button type="submit" name="add_field" class="btn btn-success my-3">🔒 Systemfeld hinzufügen</button>
    </form>

    <hr class="my-4">

    <!-- Liste der existierenden Felder -->
    <h3>Vorhandene Felder</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Feldname</th>
                <th>Datentyp</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fields as $fieldName => $fieldConfig): ?>
                <tr>
                    <td><?= htmlspecialchars($fieldName) ?></td>
                    <td><?= htmlspecialchars($fieldConfig['dataType'] ?? 'Nicht festgelegt') ?></td>
                    <td>
                        <!-- Löschen-Button für jedes Feld -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="remove_field" value="<?= htmlspecialchars($fieldName) ?>">
                            <button type="submit" class="btn btn-danger">❌ Entfernen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr class="my-4">

    <!-- Anzeige der aktuellen Systemkonfiguration (system.json) -->
    <h3 class="mt-5">📄 Aktuelle Systemkonfiguration (system.json):</h3>
    <pre class="bg-light p-3 rounded border"><code><?= htmlspecialchars($rawJson) ?></code></pre>
</div>

<!-- Snackbar-Styles und JavaScript -->
<style>
.snackbar {
    visibility: hidden;
    min-width: 250px;
    color: white;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    top: -100px;  /* Startet außerhalb des sichtbaren Bereichs (oben) */
    transform: translateX(-50%);
    font-size: 17px;
    transition: top 0.5s ease, visibility 0.5s ease;
}

.snackbar.show {
    visibility: visible;
    top: 30px; /* Position im sichtbaren Bereich */
    animation: snack_fadein 0.5s ease forwards;
}

.snackbar.hide {
    animation: snack_fadeout 0.5s ease forwards;
}

@keyframes snack_fadein {
    from { top: -100px; }
    to { top: 30px; }
}

@keyframes snack_fadeout {
    from { top: 30px; }
    to { top: -100px; }
}

.snackbar.success {
    background-color: #4CAF50;
}

.snackbar.info {
    background-color: #2196F3;
}

.snackbar.error {
    background-color: #f44336;
}
</style>

<script>
// Funktion zum Anzeigen der Snackbar
function showSnackbar() {
    var snackbar = document.getElementById("snackbar");
    snackbar.classList.remove("hide");
    snackbar.classList.add("show");

    setTimeout(function() {
        snackbar.classList.remove("show");
        snackbar.classList.add("hide");
    }, 3000); // Snackbar bleibt für 3 Sekunden sichtbar
}

document.getElementById('dataType').addEventListener('change', function() {
    var enumValuesField = document.getElementById('enumValues');
    if (this.value === 'enum') {
        enumValuesField.style.display = 'block'; // Zeige Enum-Werte-Feld
    } else {
        enumValuesField.style.display = 'none'; // Verstecke Enum-Werte-Feld
    }
});


</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

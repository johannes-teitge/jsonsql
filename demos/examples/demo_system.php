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

// Methode zum Hinzufügen eines Systemfeldes
function addSystemField($fieldName, $definition) {
    global $db;
    $db->addFieldDefinition($fieldName, $definition); // Dies wird automatisch gespeichert
}

// Methode zum Entfernen eines Systemfeldes
function removeSystemField($fieldName) {
    global $db;
    $db->removeFieldDefinition($fieldName); // Dies wird automatisch gespeichert
}


// Initialisierung der Felder für die Demo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_field'])) {
        // Beispiel: Systemfeld 'email' hinzufügen
        addSystemField("email", [
            'dateType' => 'string',
            'length' => 120,
            'allowNULL' => false,
            'defaultValue' => null,
            'comment' => 'Benutzer-E-Mail'
        ]);
        // Erfolgsnachricht für Erstellen
        if ($message = $db->getLastMessage()) {
            $successMessage = '✅  ' . $message['message'];
            $actionType = $message['action'];
        }    

    } elseif (isset($_POST['remove_field'])) {
        // Beispiel: Systemfeld 'email' entfernen
        removeSystemField("email");
        
        // Überprüfen, ob ein Fehler aufgetreten ist
        if ($error = $db->getLastError()) {
            $errorMessage = '⚠️ '.$error['errorMessage'];
        } else {
            // Erfolgsnachricht für Entfernen
            $successMessage = "❌ Das 'email'-Feld wurde erfolgreich entfernt!";
            $actionType = 'delete'; // Aktion für Entfernen
        }
    }

    $debugger->dump($successMessage,$errorMessage,$db);

    // Fehlerbehandlung: Prüfen, ob ein Fehler aufgetreten ist
    if (isset($errorMessage)) {
        // Fehler: Snackbar für die Fehleranzeige
        $snackbarClass = "error";
        $snackbarMessage = $errorMessage;
    } elseif (isset($successMessage)) {
        // Erfolg: Snackbar für die Erfolgsanzeige
        $snackbarClass = $actionType === 'create' ? "success" : "info"; // Grün für Erstellen, Blau für Update
        $snackbarMessage = $successMessage;
    }
}

// Rohdaten aus der JSON-Datei
$rawJson = json_encode($db->getRawSystemData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>

<div class="container mt-5">
<h2 class="text-center mb-4">Dynamische Systemfeld-Verwaltung</h2>
    <p>
        Die Demo ermöglicht es dir, Systemfelder hinzuzufügen oder zu entfernen. 
        Wenn du versuchst, ein bereits bestehendes Feld hinzuzufügen, wird dieses 
        <strong>aktualisiert</strong> und nicht erneut erstellt. 
        Nach jeder Aktion wird eine <strong>Snackbar</strong> mit der entsprechenden Nachricht angezeigt, 
        die über den Erfolg oder Fehler informiert. 
        Wenn du ein Feld erfolgreich hinzufügst oder entfernst, erhältst du eine 
        grüne Erfolgsnachricht oder eine rote Fehlermeldung, je nach Ergebnis der Operation. 
        Beachte, dass das <strong>Nachrichtensystem</strong> die letzte Aktion speichert und anzeigt, 
        ob das Systemfeld erfolgreich erstellt oder aktualisiert wurde oder ob ein Fehler aufgetreten ist.
    </p>

    <hr class="my-4">
    
    <!-- Erfolg oder Fehler Snackbar -->
    <?php if (isset($snackbarMessage)): ?>
        <div id="snackbar" class="snackbar <?= $snackbarClass ?> show"><?= htmlspecialchars($snackbarMessage) ?></div>
    <?php endif; ?>


    <!-- Formular zum Hinzufügen und Entfernen von Systemfeldern -->
    <form method="POST">
        <div class="mb-3">
            <button type="submit" name="add_field" class="btn btn-success">🔒 Systemfeld 'email' hinzufügen</button>
        </div>
        <div class="mb-3">
            <button type="submit" name="remove_field" class="btn btn-danger">❌ Systemfeld 'email' entfernen</button>
        </div>
    </form>

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
    transition: top 0.5s ease, visibility 0.5s ease; /* Nur Übergang für Position */
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);    
}

/* Snackbar wird angezeigt */
.snackbar.show {
    visibility: visible;
    top: 30px;  /* Position im sichtbaren Bereich */
    animation: snack_fadein 0.5s ease forwards; /* Animation für das Hineinfahren */
}

/* Fadeout-Animation für das Hinausfahren der Snackbar */
.snackbar.hide {
    animation: snack_fadeout 0.5s ease forwards; /* Animation für das Hinausfahren */
}

/* Animation für das Hineinfahren der Snackbar */
@keyframes snack_fadein {
    from {
        top: -100px; /* Startet außerhalb des sichtbaren Bereichs oben */
    }
    to {
        top: 180px; /* Position im sichtbaren Bereich */
    }
}

/* Fadeout-Animation für das Hinausfahren der Snackbar */
@keyframes snack_fadeout {
    from {
        top: 180px; /* Position im sichtbaren Bereich */
    }
    to {
        top: -100px; /* Geht wieder nach oben, außerhalb des sichtbaren Bereichs */
    }
}

/* Erfolg */
.snackbar.success {
    background-color: #4CAF50; /* Grün für Erfolg */
    background: linear-gradient(to bottom, #4CAF50,rgb(42, 133, 45));   
    border: 2px solid rgb(42, 133, 45) ; 
}

/* Info */
.snackbar.info {
    background-color: #2196F3; /* Blau für andere Aktionen (z.B. Update) */
    background: linear-gradient(to bottom, #2196F3,rgb(18, 106, 177)); 
    border: 2px solid rgb(18, 106, 177) ;         
}

/* Fehler */
.snackbar.error {
    background-color: #f44336; /* Rot für Fehler */
    background: linear-gradient(to bottom, #f44336,rgb(177, 39, 29));  
    border: 2px solid rgb(177, 39, 29) ;               
}




</style>

<script>

// Funktion zum Anzeigen der Snackbar
function showSnackbar() {
    var snackbar = document.getElementById("snackbar");
    snackbar.classList.remove("hide");        
    snackbar.classList.add("show"); // Snackbar erscheint

    // Nach 3 Sekunden die Snackbar wieder ausblenden
    setTimeout(function() {
        snackbar.classList.remove("show");  // Entfernt 'show' für Fadeout
        snackbar.classList.add("hide");  // Fügt 'hide' hinzu, um sie nach oben fahren zu lassen
    }, 3000);  // Snackbar bleibt für 3 Sekunden sichtbar

    // Entfernt 'hide' nach der Animation
    setTimeout(function() {
        snackbar.classList.remove("hide");  // Entfernt die 'hide' Klasse nach Animation
    }, 3500);  // Entfernt 'hide' nach 3,5 Sekunden (Animation abgeschlossen)
}

// Hier wird die Funktion zum Beispiel beim Laden der Seite oder nach der Form-Submittierung aufgerufen:
showSnackbar();


</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = "JsonSQL Demo: Auto-Datenbank Demo - Systemfelder";
$baseUrl = dirname($_SERVER['PHP_SELF']);

$headerBG = dirname($_SERVER['SCRIPT_NAME']) . '/images/CarDB/header.webp'; // Beispiel: Hintergrundbild für Header
$headerHeight = '440px'; // Feste Höhe für den Header

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

try {

// Datenbank initialisieren
$db = new JsonSQL(['main' => __DIR__ . '/CarDB']);
$db->use('main');
$table = 'cars';

// 1. Tabelle überprüfen und ggf. anlegen, zurücksetzen
$db->Truncate($table); // Tabelle wird gelöscht und neu geladen


$db->addAutoincrementField('id')
->addField('id', ['comment' => 'Automatisch generierte ID'])
->addAutoHashField('hash', 'md5')
->addField('brand', ['dataType' => 'string', 'length' => 40, 'required' => true, 'defaultValue' => '', 'comment' => 'Marke des Autos'])
->addField('model', ['dataType' => 'string', 'length' => 100, 'comment' => 'Modelltyp des Autos'])
->addField('year_built', ['dataType' => 'integer', 'comment' => 'Baujahr des Fahrzeugs'])
->addField('displacement', ['dataType' => 'integer', 'comment' => 'Hubraum'])
->addField('power', ['dataType' => 'integer', 'comment' => 'Leistung', 'unit' => 'kwh'])
->addField('fuel', [
    'dataType' => 'enum',
    'enumValues' => 'Benzin,Diesel,Strom',
    'defaultValue' => 'Benzin'
])
->addField('transmission', [
    'dataType' => 'enum',
    'enumValues' => 'Automatik,5-Gang,6-Gang'
])
->addField('doors', ['dataType' => 'integer','min' => '4','max' => '6'])
->addField('price', ['dataType' => 'float', 'unit' => '€'])
->addField('datum', ['dataType' => 'datetime'])
->addField('description', ['dataType' => 'string'])
->addField('logo', ['dataType' => 'string', 'defaultValue' => '']) // z. B. URL zum Markenlogo
->addCreatedAtField('created_at')
->addUpdatedAtField('updated_at');


echo "✅ Tabelle '{$table}' wurde neu initialisiert und mit Systemfeldern konfiguriert.";

?>

<div class="demo-info-container">
  <div class="demo-info-text">
    <h2>Diese Demo ist noch in Entwicklung…</h2>
    <p>
      Aber gute Dinge brauchen eben ihre Zeit.<br>
      Unser Handwerker ist schon fleißig am Werk – du darfst gespannt sein!
    </p>
  </div>
  <div class="demo-info-image">
    <img src="<?php echo $baseUrl ?>/../assets/images/handwerker.webp" alt="Fleißiger Handwerker">
  </div>
</div>

<style>
.demo-info-container {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 40px;
  padding: 20px;
}

.demo-info-text {
  color: var(--main-color);
  flex: 1;
  min-width: 280px;
  max-width: 500px;
  text-align: left;
}

.demo-info-text h2 {
  color: #d63384;
  font-size: 1.7em;
  margin-bottom: 10px;
  font-weight: bold;
}

.demo-info-text p {
  font-size: 1.1em;
  line-height: 1.6;
}

.demo-info-image {
  flex: 1;
  min-width: 180px;
  max-width: 230px;
  text-align: center;
}

.demo-info-image img {
  width: 100%;
}
</style>




<!-- Neuer Tab für JSON-Dateien -->
<div class="container mt-5 mb-3">
  <div class="accordion" id="jsonAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingJson">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJson" aria-expanded="false" aria-controls="collapseJson">
          📄 JSON-Dateien anzeigen
        </button>
      </h2>
      <div id="collapseJson" class="accordion-collapse collapse" aria-labelledby="headingJson" data-bs-parent="#jsonAccordion">
        <div class="accordion-body">        
          <h4>JsonSQL System Datei: /CarDB/cars.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/CarDB/cars.system.json'));          
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
<?php




$db->insert(['doors' => 100,'year_built' => 2024, 'brand' => 'dsf dsf das fds fds af dsg dsfg fds gsf gf dsg fsdgfdsgdsgfdsgfdgfd gfdsgfdsgfdsg fsdgfsdg' ]);

// Aktuelles Datum und Uhrzeit erstellen
$date = new DateTime();

// Formatieren auf MySQL-kompatibles Format 'Y-m-d H:i:s'
$formattedDate = $date->format('Y-m-d H:i:s');

// In die Datenbank einfügen
$db->insert(['datum' => $formattedDate, 'price' => 120000.55]);

echo "✅ Testdaten eingefügt";

?>
<!-- Neuer Tab für JSON-Dateien -->
<div class="container mt-5 mb-3">
  <div class="accordion" id="jsonAccordion2">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingJson">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJson2" aria-expanded="false" aria-controls="collapseJson">
          📄 JSON-Dateien anzeigen
        </button>
      </h2>
      <div id="collapseJson2" class="accordion-collapse collapse" aria-labelledby="headingJson" data-bs-parent="#jsonAccordion2">
        <div class="accordion-body">        

        Hier sieht man gut, wie alle fehlenden Felder automatisch durch die Systemdefinition ergänzt werden.
Für kleinere Experimente oder einfache Tabellen kann man darauf verzichten.
Wir empfehlen jedoch, die Datenbank stets sauber zu definieren – vor allem im Hinblick auf die Datenkonsistenz.
So wird auch beim Einfügen neuer Datensätze eine zuverlässige Validierung sichergestellt.
        <pre class="code-block"><code>
$db->insert(['doors' => 100,'year_built' => 2024, 'brand' => 'dsf dsf das fds fds af dsg dsfg fds gsf gf dsg fsdgfdsgdsgfdsgfdgfd gfdsgfdsgfdsg fsdgfsdg' ]);

// Aktuelles Datum und Uhrzeit erstellen
$date = new DateTime();

// Formatieren auf MySQL-kompatibles Format 'Y-m-d H:i:s'
$formattedDate = $date->format('Y-m-d H:i:s');

// In die Datenbank einfügen
$db->insert(['datum' => $formattedDate, 'price' => 120000.55]);
        </code></pre>

          <h4>JsonSQL System Datei: /CarDB/cars.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/CarDB/cars.json'));          
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
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

<?php 

} catch (\Exception $e) {
    // Fange die Exception und gebe die Fehlermeldung aus
    echo "<div class='alert alert-danger'>
    <i class='bi bi-exclamation-circle'></i> Fehler: <strong>" . $e->getMessage() . "</strong>
  </div>";
    // Ausgabe des gesamten Stack-Trace für detaillierte Fehlersuche
    echo "<pre><code>" . $e->__toString() . "</code></pre>";  // Ausgabe des vollständigen Stack-Trace    
} finally {
    // Dieser Block wird immer ausgeführt, auch wenn eine Exception geworfen wurde
    // Hier kannst du den Footer immer laden
    require_once __DIR__ . '/../includes/footer.php';
}

 

?>

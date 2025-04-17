<?php
/**
 * ============================================================
 * 🚀 JsonSQL Demo #1 – "Hello JsonSQL"
 * ============================================================
 * 
 * Zweck:
 * Dies ist die erste einfache Demo zur JsonSQL-Klasse. Sie zeigt:
 * - wie man JsonSQL initialisiert,
 * - wie man eine Tabelle erstellt (bzw. leert),
 * - wie man einfache Datensätze einfügt,
 * - wie man Daten abruft und anzeigt.
 * 
 * Zielgruppe:
 * Entwickler:innen, die ohne klassische SQL-Datenbank arbeiten möchten
 * und eine einfache JSON-basierte Alternative suchen.
 * 
 * Hinweis:
 * Die JsonSQL-Klasse arbeitet rein mit Dateien (keine Datenbank nötig!).
 * Tabellen werden als JSON-Dateien gespeichert.
 * 
 * Datei: hello-json-sql.php
 * Pfad: examples/hello-json-sql.php
 * 
 * Setup-Voraussetzung:
 * - src/JsonSQL.php vorhanden
 * - testdb/-Verzeichnis beschreibbar
 * - includes/header.php und footer.php vorhanden
 * 
 * Autor: Dscho Teitge (https://teitge.de)
 * Stand: <?= date('Y-m-d') ?>
 * ============================================================
 */

//  Definition des Titels für header.php
$pageTitle = "🚀 Erste JsonSQL-Demo: Hello JsonSQL";

// 💡 Pfad zur JsonSQL-Klasse definieren
$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei '$JsonSQLpath' nicht gefunden! Bitte sicherstellen, dass die Datei vorhanden ist.");
}
require_once $JsonSQLpath;

// 💡 Lade das gemeinsame Layout (Header, Navigation etc.)
require_once __DIR__ . '/../includes/header.php';

// 💡 Namespace importieren
use Src\JsonSQL;

try {

// ===============================
// ✅ Initialisierung der Datenbank
// ===============================

// Erstelle ein neues JsonSQL-Objekt mit Pfad zur Datenbank (testdb/)
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);

// Wähle die Datenbank "demo"
$db->use('demo');

// Tabellenname festlegen
$table = 'hello';

// Tabelle leeren (neu erstellen oder bestehende Inhalte löschen)
$db->truncate($table);


// ===============================
// ✍️ Systemfelder aktivieren
// ===============================
// Diese Felder werden automatisch von JsonSQL bei jedem Insert ergänzt:
//
// - ID: fortlaufende Nummer (Autoincrement), eindeutig für jeden Datensatz
// - Erstellt am: Zeitstempel, wann der Datensatz angelegt wurde
// - Geändert am: Zeitstempel, wann der Datensatz zuletzt verändert wurde
//
// Diese Definition wird automatisch in der system.json der Tabelle gespeichert.
// Wichtig: Die Namen dieser Felder (z. B. "ID" statt "id") kannst du frei wählen.
$db->addAutoincrementField('ID');
$db->addCreatedAtField('Erstellt am');
$db->addUpdatedAtField('Geändert am');


// ===============================
// ✍️ Einfache Demo-Daten einfügen
// ===============================

// 1. Datensatz: Alice
// Einfügen eines einzelnen Datensatzes mit Angabe von Name und E-Mail
$db->from($table)->insert([
  'name' => 'Alice',
  'email' => 'alice@example.com'
]);

// 2. Datensatz: Bob (mit bewusst falscher E-Mail zum späteren Update-Test)
$db->insert([
  'name' => 'Bob',
  'email' => 'bab@example.com' // Tippfehler wird später korrigiert
]);

// 3. und 4. Datensätze: Carol & Dave (Mehrfacheinfügen in einem Rutsch)
// JsonSQL erkennt das automatisch anhand eines Arrays von Arrays
$db->insert([
  [
    'name' => 'Carol',
    'email' => 'carol@example.com'
  ],
  [
    'name' => 'Dave',
    'email' => 'dave@example.com'
  ]
]);


// ===============================
// 📥 Daten aktualisieren (Update)
// ===============================
// Ziel: Die E-Mail-Adresse von Bob korrigieren (bab → bob)
//
// Hinweis: setTable($table, true) lädt die Tabelle neu aus der Datei,
// um sicherzustellen, dass Änderungen (z. B. durch vorherige Inserts)
// auch in der aktuellen Abfrage zur Verfügung stehen.
$db->setTable($table, true); // Autoload true = Tabelle frisch laden

// Suche nach dem Datensatz mit name = 'Bob' und korrigiere die E-Mail
$db->select('*')->from($table)
  ->where([['name', '=', 'Bob']]) // Bedingung als 3er-Array im Array (korrektes Format)
  ->update(['email' => 'bob@example.com']); // Neue, korrekte Adresse


// ===============================
// 📥 Daten abrufen (Select)
// ===============================
// Ziel: Alle aktuell gespeicherten Datensätze ausgeben

$db->setTable($table, true); // Tabelle neu laden (Autoload = true)
$rows = $db->select('*')->get(); // Alle Felder abrufen

// ===============================
// 🐞 Debug-Ausgaben (nur sichtbar bei aktiviertem Debug-Modus)
// ===============================

$debugger->addInfoText('JsonSQL Objekt');
$debugger->dump($db);

$debugger->addInfoText('Die fertigen Daten');
$debugger->dump($rows);

$debugger->addInfoText('Die einzelnen Datenzeilen');
?>

<!-- ===============================
🎨 HTML-Ausgabe der Daten
=============================== -->
<div class="container mt-4">
  <h1 class="mb-4">🚀 Erste JsonSQL-Demo: <code>hello</code></h1>

  <p class="lead">
  Diese Mini-Demo zeigt dir, wie einfach du mit <strong>JsonSQL</strong> durchstarten kannst – ganz ohne klassische Datenbank wie MySQL oder SQLite.
  </p>

  <p>
    JsonSQL speichert deine Daten in einfachen <code>.json</code>-Dateien und ermöglicht dir dennoch typische Datenbank-Funktionen wie <code>INSERT</code>, <code>SELECT</code>, <code>UPDATE</code> und <code>DELETE</code>. Ideal für kleine Tools, Adminbereiche, Prototypen oder portable Anwendungen, bei denen du keinen Datenbankserver aufsetzen willst oder kannst.
  </p>

  <p>
    In dieser Demo lernst du:
    <ul>
      <li>🆕 wie du eine neue "Tabelle" erstellst (in Wirklichkeit eine JSON-Datei),</li>
      <li>📝 wie du Datensätze einfügst,</li>
      <li>🔍 und wie du sie anschließend wieder ausliest.</li>
    </ul>
  </p>

  <p>
    Die gesamte Logik läuft direkt im Code – kein SQL-Server, keine Datenbankverbindung, keine Einrichtung notwendig. Du kannst sofort loslegen!  
  </p>

  <p>
    Wenn du also schon immer mal Daten strukturiert speichern wolltest – aber ohne den Overhead klassischer Datenbanken – dann ist <strong>JsonSQL</strong> genau das Richtige für dich.
  </p>

  <div class="alert alert-info mt-4" role="alert">
    📄 <strong>Tipp für Einsteiger:innen:</strong> Am Ende dieser Seite findest du den vollständigen Quellcode dieser Demo zum Aufklappen.<br>
    Er enthält viele Kommentare und ist ideal geeignet, um Schritt für Schritt zu verstehen, wie JsonSQL funktioniert.
  </div>  


  <h4 class="mt-4">📦 Daten in <code>hello.json</code>:</h4>
  <ul class="list-group">
    <?php foreach ($rows as $row):       
      $debugger->dump($row); // Optionaler Debug-Dump jeder Zeile      
    ?>
      <li class="list-group-item">
        <strong><?= htmlspecialchars($row['name']) ?></strong> &mdash; <?= htmlspecialchars($row['email']) ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>


<?php
$scriptName = basename(__FILE__);

// Entferne die Exclude-Tags aus dem Quellcode, aber lasse den eigenen Codeabschnitt aus
$scriptContent = file_get_contents(__FILE__);

// Verhindern, dass der Codeblock selbst ersetzt wird, indem wir ihn in einen temporären Kommentar umwandeln
$scriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $scriptContent);

// Hier verwenden wir einen temporären Platzhalter, um den Code zu umgehen, der ersetzt wird.
$scriptContent = str_replace('<!-- Exclude Begin -->', '<!-- Exclude Begin Temp -->', $scriptContent);
$scriptContent = str_replace('<!-- Exclude End -->', '<!-- Exclude End Temp -->', $scriptContent);

?>


<!-- Exclude Begin -->
<!-- ===============================
🔍 Anzeige der JSON SQL Dateien
=============================== -->
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
          <h4>JsonSQL Datei: datetime_demo.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/hello.json'));
          ?></code></pre>
          
          <h4>JsonSQL System Datei: datetime_demo.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/hello.system.json'));
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ===============================
🔍 Quellcode-Anzeige für Lernzwecke
=============================== -->
<div class="container mt-5 mb-3">
  <div class="accordion" id="codeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCode">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
          📄 Quellcode dieser Demo anzeigen (demo_first.php)
        </button>
      </h2>
      <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
        <div class="accordion-body">
          <pre class="code-block"><code><?php echo htmlspecialchars($scriptContent); ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Exclude End -->


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
    // ✅ Footer der Seite einfügen
    require_once __DIR__ . '/../includes/footer.php';
}


?>
<div class="container">
  <h1><i class="bi bi-gear"></i> Installation von JsonSQL</h1>
  <p>Die Installation von JsonSQL ist simpel und flexibel – du kannst es direkt in deine bestehende Projektstruktur einbauen oder als Standalone verwenden. JsonSQL benötigt keine externe Datenbank und arbeitet direkt mit JSON-Dateien.</p>
</div>

<!-- Schritt 1: JsonSQL einrichten -->
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2>Schritt 1: JsonSQL in dein Projekt integrieren</h2>
      <p>JsonSQL ist in einem <code>vendor/JsonSQL</code>-Ordner organisiert. Falls dein Projekt bereits einen <code>vendor/</code>-Ordner verwendet, kannst du einfach den <code>JsonSQL</code>-Ordner dorthin kopieren.</p>
      <p>Falls du <strong>noch keinen</strong> <code>vendor/</code>-Ordner hast, kannst du den gesamten mitgelieferten Ordner übernehmen:</p>
      <pre><code>
/dein_projekt/
├── vendor/
│   └── JsonSQL/
│       └── src/
│           ├── JsonSQL.php
│           └── ...
├── testdb/
│   └── aggregat_demo/
│       ├── aggregat_demo_data.json
│       └── aggregat_demo_data.system.json
└── index.php
      </code></pre>
    </div>
  </div>
</section>

<!-- Schritt 2: JsonSQL in PHP einbinden -->
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2>Schritt 2: JsonSQL in deinem Skript einbinden</h2>
      <p>Füge in deinem PHP-Skript folgenden Code ein, um JsonSQL korrekt zu laden:</p>
      <pre><code class="language-php">
// Pfad zur JsonSQL-Klasse setzen
$JsonSQLpath = __DIR__ . '/../vendor/JsonSQL/src/JsonSQL.php';

if (!file_exists($JsonSQLpath)) {
    die("❌ JsonSQL.php nicht gefunden!");
}

require_once $JsonSQLpath;

// Wichtig: Namensraum verwenden
use Src\JsonSQL;

// Instanzierung und Datenbank-Zuordnung
$db = new JsonSQL([
    'aggregat_demo' => __DIR__ . '/../testdb/aggregat_demo'
]);

$table = 'aggregat_demo_data';
$db->use('aggregat_demo')->setTable($table);

// Jetzt kannst du Abfragen starten!
$data = $db->get(); // Alle Datensätze holen
      </code></pre>

      <p><strong>Hinweis:</strong> Falls du mit mehreren Datenbanken oder Tabellen arbeitest, kannst du über <code>use()</code> die aktive Datenbank und über <code>setTable()</code> die Tabelle wählen. Mehr dazu in den folgenden Abschnitten.</p>
    </div>
  </div>
</section>

<!-- Schritt 3: Verständnis für den Konstruktor -->
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2>Schritt 3: Was passiert im Konstruktor?</h2>
      <p>Die <code>JsonSQL</code>-Klasse erwartet im Konstruktor ein Array mit dem Namen und Pfad deiner Datenbanken. Beispiel:</p>
      <pre><code class="language-php">
new JsonSQL([
  'demo' => '/pfad/zur/demo-datenbank',
  'produkte' => '/pfad/zur/produktdatenbank'
]);
      </code></pre>
      <p>Danach kannst du über <code>use('demo')</code> die gewünschte Datenbank aktivieren.</p>
    </div>
  </div>
</section>

<!-- Schritt 4: Sicherheit & Fehlerbehandlung -->
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2>Schritt 4: Sicherheit und Fehlerbehandlung</h2>
      <p>JsonSQL bietet dir maximale Kontrolle – aber du solltest ein paar Dinge beachten:</p>
      <ul>
        <li><strong>try/catch:</strong> Um dein Projekt robuster zu machen, empfiehlt sich eine globale Fehlerbehandlung:</li>
      </ul>
      <pre><code class="language-php">
try {
    require_once __DIR__ . '/../vendor/JsonSQL/src/JsonSQL.php';
    use Src\JsonSQL;

    $db = new JsonSQL([
        'demo' => __DIR__ . '/../testdb/demo'
    ]);
    $db->use('demo')->setTable('testdata');

    $data = $db->get();
    // Ergebnis anzeigen oder weiterverarbeiten
} catch (Throwable $e) {
    echo "❌ Fehler beim Zugriff auf JsonSQL: " . $e->getMessage();
}
      </code></pre>
      <ul>
        <li><strong>Dateiberechtigungen:</strong> Stelle sicher, dass der Webserver <code>read/write</code>-Zugriff auf die Datenordner hat.</li>
        <li><strong>Verzeichnisse trennen:</strong> Lege Daten außerhalb des <code>public_html</code>-Ordners ab, wenn du JsonSQL produktiv nutzt.</li>
        <li><strong>Backup:</strong> Nutze regelmäßige Backups deiner JSON-Daten, z. B. über eine automatische Kopie bei jedem <code>insert()</code> oder <code>update()</code>.</li>
      </ul>
    </div>
  </div>
</section>

<!-- Weiterführende Hinweise -->
<section class="container">
  <div class="row">
    <div class="col-md-12">
      <h2>Weiterführende Nutzung</h2>
      <p>Mehr Infos zur Nutzung von <code>insert()</code>, <code>update()</code>, Abfragen, Gruppierungen, Verschlüsselung, Auto-Feldern und Co findest du in den weiteren Doku-Abschnitten oder unter <a href="../examples/" target="_blank">https://teitge.de/jsonsql-demos/</a>.</p>
    </div>
  </div>
</section>

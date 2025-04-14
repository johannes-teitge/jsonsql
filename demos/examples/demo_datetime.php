<?php
$pageTitle = "JsonSQL Datetime Demo";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

$db = new JsonSQL(['products' => __DIR__ . '/../testdb']);
$table = 'datetime_demo';
$db->use('products');

// 2. Tabelle leeren / neu anlegen
$db->truncate($table);

// Optional: Key setzen, falls noch keiner vorhanden
if (!$db->hasEncryptionKey()) {
    $db->setEncryptionKey('MeinGeheimerKey123!');
    echo "<p>🔐 Neuer Encryption-Key gesetzt (Default): <code>MeinGeheimerKey123!</code></p>";
}

// 1. Systemkonfiguration initialisieren
if (!$db->isEncryptedField('hash')) {    
    $db->addEncryptedField('hash');
    echo "<div class='alert alert-success'>🔧 <strong>system.json wurde erzeugt</strong> und Feld <code>hash</code> zur Verschlüsselung hinzugefügt.</div>";
}

if (!$db->isEncryptedField('title')) {    
    $db->addEncryptedField('title');
}  

// 3. Füge Felder mit DateTime, Date, Time und Timestamp hinzu, falls nicht vorhanden
if (!$db->hasField('created_at')) {
    $db->addFieldDefinition('created_at', [
        'dataType' => 'datetime', 
        'defaultValue' => date('Y-m-d H:i:s'), // Setze das Standard-Datum
        'comment' => 'Erstellungsdatum des Artikels'
    ]);
}

if (!$db->hasField('published_on')) {
    $db->addFieldDefinition('published_on', [
        'dataType' => 'date', 
        'defaultValue' => date('Y-m-d'), // Setze das Standard-Datum
        'comment' => 'Veröffentlichungsdatum des Artikels'
    ]);
}

if (!$db->hasField('event_time')) {
    $db->addFieldDefinition('event_time', [
        'dataType' => 'time', 
        'defaultValue' => date('H:i:s'), // Setze die Standard-Uhrzeit
        'comment' => 'Veranstaltungszeit'
    ]);
}

if (!$db->hasField('updated_at')) {
    $db->addFieldDefinition('updated_at', [
        'dataType' => 'timestamp', 
        'defaultValue' => date('Y-m-d H:i:s'), // Setze den Standard-Timestamp
        'comment' => 'Aktualisierungszeitpunkt des Artikels'
    ]);
}

// 4. Beispiel-Datensätze einfügen (Datumswerte werden entsprechend formatiert)
$demoData = [
    [
        'id' => 1, 
        'title' => 'Artikel 1', 
        'price' => 1.49, 
        'hash' => 'Kugel', 
        'created_at' => '2025-04-10 12:00:00', 
        'published_on' => '2025-04-10', 
        'event_time' => '15:30:00',
        'updated_at' => '2025-04-10 12:00:00'
    ],
    [
        'id' => 2, 
        'title' => 'Artikel 2', 
        'price' => 5.99, 
        'hash' => 'Note', 
        'created_at' => '2025-04-10 12:30:00', 
        'published_on' => '2025-04-11', 
        'event_time' => '16:00:00',
        'updated_at' => '2025-04-10 12:30:00'
    ],
    [
        'id' => 3, 
        'title' => 'Artikel 3', 
        'price' => 8.49, 
        'hash' => 'USB', 
        'created_at' => '2025-04-10 13:00:00', 
        'published_on' => '2025-04-12', 
        'event_time' => '17:00:00',
        'updated_at' => '2025-04-10 13:00:00'
    ],
];

foreach ($demoData as $item) {
    $db->from($table)->insert($item);
}

echo "<h2>✅ Tabelle <code>$table</code> wurde angelegt und mit Beispieldaten gefüllt.</h2>";

// 5. Artikel lesen
$articles = $db->from($table)->select(['id', 'title', 'price', 'created_at', 'published_on', 'event_time', 'updated_at', 'hash'])->orderBy('id')->get();

echo "<h3>📦 Artikelübersicht:</h3>";
echo "<ul class='list-group'>";
foreach ($articles as $article) {
    // Konvertiere den Timestamp 'updated_at' in das richtige Datumsformat
    $timestamp = (int)$article['updated_at'];
    $updatedAtFormatted = date('Y-m-d H:i:s', $timestamp);

    echo "<li class='list-group-item'>";
    echo "<strong>[{$article['id']}] {$article['title']}</strong> – {$article['price']} €<br>";
    echo "<small class='text-muted'>
    Erstellt am: {$article['created_at']}<br>
    Veröffentlicht am: {$article['published_on']}<br>
    Veranstaltungszeit: {$article['event_time']}<br>
    Aktualisiert am: $updatedAtFormatted {$article['updated_at']}<br>
    🔑 Hash: {$article['hash']}</small>";
    echo "</li>";
}
echo "</ul>";




?>
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
          <h4>JsonSQL Datei: datetime_demo.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/datetime_demo.json'));
          ?></code></pre>
          
          <h4>JsonSQL System Datei: datetime_demo.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/datetime_demo.system.json'));
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
<?php




// 6. Code-Viewer anzeigen
$scriptName = basename(__FILE__);
?>

<div class="container mt-5 mb-3">
  <div class="accordion" id="codeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCode">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
          📄 Quellcode dieser Demo anzeigen (<?= htmlspecialchars($scriptName) ?>)
        </button>
      </h2>
      <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
        <div class="accordion-body">
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__FILE__));
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div> <!-- Container hier sauber geschlossen -->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

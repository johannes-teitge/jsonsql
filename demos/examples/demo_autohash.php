<?php
$pageTitle = "JsonSQL AutoHash Demo: Artikel";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Datenbank und Tabelle definieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'artikel_hash';

// 1. Tabelle leeren oder anlegen
$db->truncate($table);

// 2. AutoHash setzen, wenn noch nicht aktiv
if (!$db->isAutohashField('id')) {
    $db->addAutohashField('id', 'sha256');
    echo "<div class='alert alert-info'>⚙️ AutoHash (sha256) für Feld 'hash' wurde gesetzt.</div>";
}

// 3. Beispielartikel einfügen (ohne 'hash' – wird automatisch erzeugt)
$produkte = [
    ['title' => 'Mauspad XXL', 'price' => 9.99],
    ['title' => 'Gaming-Maus', 'price' => 19.95],
    ['title' => 'Monitorhalterung', 'price' => 34.90],
];

echo "<h2>🛒 Neue Produkte eingetragen:</h2>";
echo "<ul class='list-group'>";
foreach ($produkte as $produkt) {
    $db->from($table)->insert($produkt);
    $newId = $db->getLastInsertId();
    echo "<li class='list-group-item'>✅ Produkt [ID $newId] wurde gespeichert: {$produkt['title']}</li>";
}
echo "</ul>";



// 4. Aktuelle Daten ausgeben
$rows = $db->from($table)->select('*')->orderBy('id')->get();

$debugger->dump($rows,$db);

echo "<h3 class='mt-5'>📦 Aktuelle Inhalte der Tabelle '$table':</h3>";
echo "<ul class='list-group'>";
foreach ($rows as $row) {
    echo "<li class='list-group-item'>";
    echo "<strong>🔐#ID: {$row['id']}</strong><br>{$row['title']} – {$row['price']} €<br>";
    echo "</li>";
}
echo "</ul>";





// 5. Quellcode anzeigen
$scriptName = basename(__FILE__); 

?>
<div class="container mt-5 mb-3">
  <hr class="shadow-lg rounded">
  <div class="accordion" id="codeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCode">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
          📄 Quellcode dieser Demo anzeigen (<?= htmlspecialchars($scriptName) ?>)
        </button>
      </h2>
      <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
        <div class="accordion-body">
          <pre class="code-block"><code><?php echo htmlspecialchars(file_get_contents(__FILE__)); ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>



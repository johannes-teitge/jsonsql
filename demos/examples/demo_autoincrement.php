<?php
$pageTitle = "JsonSQL Autoincrement Demo: Witze";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Instanz und Datenbank aktivieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'witze';

// Tabelle aktivieren
$db->from($table);

// Systemdatei prüfen
if ($db->hasSystemConfig()) {
    echo "<div class='alert alert-success'>✅ <code>$table.system.json</code> wurde gefunden und geladen.</div>";
} else {
    echo "<div class='alert alert-warning'>⚠️ <code>$table.system.json</code> existiert noch nicht – wird automatisch beim Speichern angelegt.</div>";
}

// Tabelle leeren oder anlegen
$db->truncate($table);

// Autoincrement für "id" setzen, wenn nicht vorhanden
if (!$db->isAutoincrementField('id')) {
    $db->addAutoincrementField('id', 1);
    echo "<div class='alert alert-info'>⚙️ Autoincrement für 'id' wurde gesetzt (Startwert 1).</div>";
}

// Beispiel-Witze einfügen
$witze = [
    ['text' => "Warum können Geister so schlecht lügen? Weil man durch sie hindurchsieht."],
    ['text' => "Was ist orange und läuft durch den Wald? Eine Wanderine."],
    ['text' => "Was macht ein Pirat am Computer? Er drückt die Enter-Taste."],
];

echo "<h2>📝 Neue Witze eingetragen:</h2><ul class='list-group'>";
foreach ($witze as $eintrag) {
    $db->from($table)->insert($eintrag);
    $newId = $db->getLastInsertId();
    echo "<li class='list-group-item'>✅ Witz <strong>[ID $newId]</strong> wurde gespeichert: {$eintrag['text']}</li>";
}
echo "</ul>";

// Tabelle anzeigen
echo "<h3 class='mt-5'>📋 Aktuelle Inhalte der Tabelle '$table':</h3><ul class='list-group'>";
$rows = $db->from($table)->select('*')->orderBy('id')->get();
foreach ($rows as $row) {
    echo "<li class='list-group-item'><strong>[#{$row['id']}]</strong> {$row['text']}</li>";
}
echo "</ul>";

// Code-Ansicht
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

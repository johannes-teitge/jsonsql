<?php
$pageTitle = "JsonSQL Export Tabelle Demo";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Instanz initialisieren mit Datenbankverzeichnis
$db = new JsonSQL([
    'demo' => __DIR__ . '/../testdb/demo',
]);

$table = 'demo_users'; // Beispieltabelle
$export = $db->use('demo')->exportTable($table);

// Kompletten Export als JSON in ein verstecktes Feld legen
echo '<input type="hidden" id="jsonExportData" value="' . htmlspecialchars(json_encode($export, JSON_UNESCAPED_UNICODE)) . '">';


// HTML-Ausgabe
echo "<h1>Exportierte Tabelle: <code>{$export['table']}</code></h1>";
echo "<p><strong>Datei:</strong> {$export['filename']}</p>";
echo "<p><strong>Ordner:</strong> {$export['folder']}</p>";
echo "<p><strong>Datensätze:</strong> {$export['count']}</p>";
echo "<p><strong>Letzte Änderung:</strong> {$export['last_modified']}</p>";

// Systemkonfiguration anzeigen
echo "<h2>Systemkonfiguration</h2>";
echo '<pre class="scrollbox"><code class="language-json">' .
     htmlspecialchars(json_encode($export['system'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) .
     '</code></pre>';

// Daten anzeigen
echo "<h2>Tabellendaten</h2>";
echo '<pre class="scrollbox"><code class="language-json">' .
     htmlspecialchars(json_encode($export['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) .
     '</code></pre>';


echo '<button onclick="downloadJson()" style="margin-top:10px;">💾 Save as JSON</button>';


// Quellcode anzeigen
$scriptName = basename(__FILE__);

// Entferne die Exclude-Tags aus dem Quellcode
$scriptContent = file_get_contents(__FILE__);
$scriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $scriptContent);
?>

<!-- Exclude Begin -->
<div class="container mt-5 mb-3">
  <hr class="shadow-lg rounded">
  <div class="accordion" id="codeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCode">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
          📄 Quellcodeauszug dieser Demo anzeigen (<?= htmlspecialchars($scriptName) ?>)
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

<style>
.scrollbox {
    max-height: 400px;
    overflow: auto;
    background: #f9f9f9;
    padding: 10px;
    border: 1px solid #ccc;
}
</style>

<script>

function downloadJson() {
    const input = document.getElementById('jsonExportData');
    if (!input) {
        alert('❌ Hidden JSON-Daten nicht gefunden!');
        return;
    }

    try {
        const raw = JSON.parse(input.value); // Sicherstellen, dass es valide ist
        const json = JSON.stringify(raw, null, 2); // Fancy JSON

        const blob = new Blob([json], { type: 'application/json' });
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = 'exported_full_pretty.json';
        a.click();

        URL.revokeObjectURL(url);
    } catch (e) {
        alert("❌ Fehler beim Parsen des JSON-Exports");
        console.error(e);
    }
}


</script>



<?php

require_once __DIR__ . '/../includes/footer.php';

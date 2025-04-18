<?php
/**
 * ============================================================
 * 🧪 JsonSQL Demo #AnalyzeTable – Validierung gegen Systemtabelle
 * ============================================================
 * 
 * Zweck:
 * Analysiert eine Tabelle auf fehlende oder zu viele Felder im Vergleich
 * zur system.json-Konfiguration und zeigt die Probleme an.
 * 
 * Datei: demo_analyzeTable.php
 * Pfad: demos/demo_analyzeTable.php
 * 
 * Autor: Dscho Teitge (https://teitge.de)
 * Stand: <?= date('Y-m-d') ?>
 * ============================================================
 */

$pageTitle = "🧪 JsonSQL-Demo: Tabellenanalyse (analyzeTable)";
$pageDescription = "In dieser JsonSQL-Demo prüfen wir, ob alle Datensätze mit der Systemtabelle übereinstimmen – inkl. fehlender oder zusätzlicher Felder.";

require_once __DIR__ . '/../../src/JsonSQL.php';
require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

try {

// Init
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'analyze_demo';
$db->truncate($table,true); // Daten & System zurücksetzen

// Systemdefinition
$db->addFieldDefinition('firstname', ['dataType' => 'string', 'required' => true, 'length' => 50]);
$db->addFieldDefinition('lastname', ['dataType' => 'string', 'required' => true, 'length' => 50]);
$db->addFieldDefinition('email', ['dataType' => 'string', 'required' => true, 'length' => 64]);
$db->addFieldDefinition('phone', ['dataType' => 'string', 'length' => 64]);
$db->addFieldDefinition('created_at', ['dataType' => 'datetime', 'auto_create_timestamp' => true]);
$db->setSystemOption('analyze_demo','allowAdditionalFields', false);

// Testdatensätze
$testData = [
    [ // ok
        'firstname' => 'Erika',
        'lastname' => 'Mustermann',
        'email' => 'erika@example.com',
        'phone' => '0154 345 67'
    ],
    [ // fehlt lastname
        'firstname' => 'Max',
        'lastname' => 'Muster',        
        'email' => 'max@example.com',
        'phone' => '0123 456 78'
    ],
    [ // hat extra Feld "foo"
        'firstname' => 'Anna',
        'lastname' => 'Beispiel',
        'email' => 'anna@example.com',
        'phone' => '089 123 456',
        'foo' => 'EXTRA'
    ],
    [ // fehlt phone (ok), aber hat bar (extra)
        'firstname' => 'Karl',
        'lastname' => 'Überraschung',
        'email' => 'karl@example.com',
        'bar' => '!!!'
    ]
];

foreach ($testData as $entry) {
    try {
        $db->insert($entry);
    } catch (Exception $e) {
        echo "<div class='alert alert-warning'>Insert-Fehler: " . $e->getMessage() . "</div>";
    }
}


/**
 * Simuliert das Entfernen eines Feldes aus einem Datensatz (z. B. um einen Pflichtfeldfehler zu erzeugen)
 */
function simulateMissingField(JsonSQL $db, string $field, int $rowIndex = 0): void {
    $data = $db->loadTable();
    if (isset($data[$rowIndex][$field])) {
        unset($data[$rowIndex][$field]);
        file_put_contents($db->getCurrentTableFile(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "<div class='alert alert-info'>🧪 Feld <code>$field</code> aus Zeile $rowIndex entfernt (Simuliert).</div>";
    }
}

/**
 * Simuliert das Einfügen eines zusätzlichen (nicht erlaubten) Feldes
 */
function simulateExtraField(JsonSQL $db, string $field, $value = 'EXTRA', int $rowIndex = 0): void {
    $data = $db->loadTable();
    if (isset($data[$rowIndex])) {
        $data[$rowIndex][$field] = $value;
        file_put_contents($db->getCurrentTableFile(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "<div class='alert alert-info'>🧪 Zusatzfeld <code>$field</code> in Zeile $rowIndex eingefügt (Simuliert).</div>";
    }
}

// 🧪 Manuelle Manipulation zum Testen
simulateMissingField($db, 'email', 0);         // Entfernt 'email' aus erstem Datensatz
simulateMissingField($db, 'firstname', 3);         // Entfernt 'email' aus erstem Datensatz
simulateMissingField($db, 'lastname', 3);         // Entfernt 'email' aus erstem Datensatz
simulateExtraField($db, 'debug', 'X', 1);      // Fügt Feld 'debug' in zweiten Datensatz ein
simulateExtraField($db, 'demo', 'true', 1);      // Fügt Feld 'debug' in zweiten Datensatz ein



$result = $db->analyzeTable('analyze_demo', true); // zeigt auch erlaubte Zusatzfelder

$db->setTable($table,true); // Tabelle neu laden
$rows = $db->select('*')->get();

?>
<div class="container mt-4">
  <h1>🧪 Demo: Analyse der Tabelle <code><?= $table ?></code></h1>
  <p class="lead">Diese Seite prüft alle Einträge auf Übereinstimmung mit der <code>system.json</code>. Dabei werden fehlende Pflichtfelder und unerlaubte Zusatzfelder erkannt.</p>

  <?php if (empty($result)): ?>
    <div class="alert alert-success">✅ Alle Datensätze sind vollständig und korrekt!</div>
  <?php else: ?>
    <div class="alert alert-danger"><strong>⚠️ Es wurden <?= count($result) ?> fehlerhafte Einträge gefunden:</strong></div>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Fehlende Felder</th>
          <th>Zusätzliche Felder</th>
          <th>Auszug</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $entry): ?>
        <tr>
          <td><?= $entry['row'] ?></td>
          <td><?= implode(", ", $entry['missing'] ?? []) ?></td>
          <td><?= implode(", ", $entry['extra'] ?? []) ?></td>
          <td><code><?= htmlspecialchars($entry['excerpt']) ?></code></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <h4 class="mt-5">📋 Aktuelle Datensätze:</h4>
  <ul class="list-group">
    <?php foreach ($rows as $row): ?>
      <li class="list-group-item">
        <?= htmlspecialchars($row['firstname'] ?? '?') ?> <?= htmlspecialchars($row['lastname'] ?? '') ?> – <?= htmlspecialchars($row['email'] ?? '') ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>




<?php
$scriptContent = file_get_contents(__FILE__);
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
          <h4>System Datei:analyze_demo.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/analyze_demo.system.json'));
          ?></code></pre>

          <h4>JsonSQL Datei: analyze_demo.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/analyze_demo.json'));
          ?></code></pre>          

        </div>
      </div>
    </div>
  </div>
</div>


<!-- 🔍 Quellcode-Anzeige -->
<div class="container mt-1 mb-3">
    <div class="accordion" id="codeAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCode">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
                    📄 Quellcode dieser Demo anzeigen
                </button>
            </h2>
            <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
                <div class="accordion-body">
                    <pre class="code-block"><code><?= htmlspecialchars($scriptContent) ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
















<?php
require_once __DIR__ . '/../includes/footer.php';
} catch (Exception $e) {
  echo "<div class='alert alert-danger'>Fehler: " . $e->getMessage() . "</div>";
  echo "<pre><code>" . $e->__toString() . "</code></pre>";
}
?>

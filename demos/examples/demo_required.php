<?php
/**
 * ============================================================
 * 🧪 JsonSQL Demo #Required – Validierung von Pflichtfeldern
 * ============================================================
 * 
 * Zweck:
 * Zeigt, wie `required: true` Felder in system.json beim Insert
 * geprüft werden und wie man Fehler elegant abfängt.
 * 
 * Datei: demo_required.php
 * Pfad: demos/demo_required.php
 * 
 * Autor: Dscho Teitge (https://teitge.de)
 * Stand: <?= date('Y-m-d') ?>
 * ============================================================
 */

$pageTitle = "🧪 JsonSQL-Demo: Pflichtfelder (required)";

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

$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'required_demo';
$db->truncate($table,true); // neue leere Tabelle anlegen

// 🛠️ Systemfelder definieren mit einem required-Feld
$db->addFieldDefinition('firstname', [
    'dataType' => 'string',
    'required' => true,
    'defaultValue' => NULL,
    'length' => 50
]);

$db->addFieldDefinition('lastname', [
    'dataType' => 'string',
    'required' => true,
    'defaultValue' => NULL,
    'length' => 50
]);

$db->addFieldDefinition('email', [
    'dataType' => 'string',
    'length' => 100,
    'required' => true,    
    'defaultValue' => NULL,
    'length' => 64    
]);

$db->addFieldDefinition('phone', [
    'dataType' => 'string',
    'length' => 100,    
    'defaultValue' => NULL,
    'length' => 64    
]);

$db->addFieldDefinition('created_at', [
    'dataType' => 'datetime',
    'auto_create_timestamp' => true
]);


// ❌ 1. Versuch: Insert ohne das required-Feld "name"
try {
    $db->from($table)->insert([
        'email' => 'no-name@example.com',
        'phone' => '0154 345 67'
    ]);
    echo "<div class='alert alert-success'>Insert ohne Fehler (sollte nicht passieren)</div>";
} catch (\Exception $e) {
    echo "<div class='alert alert-danger'><strong>Fehler beim Insert (erwartet):</strong> " . $e->getMessage() . "</div>";
} 


// ❌ 2. Versuch: Insert ohne das required-Feld "name"
try {
    // ✅ Korrektes Insert mit vollständigen Daten
    $db->insert([
        'firstname' => 'Erika',
        'lastname' => 'Mustermann',        
        'email' => 'erika@example.com',
        'phone' => '0154 345 67'        
    ]);
    echo "<div class='alert alert-success'>Insert ohne Fehler (sollte nicht passieren)</div>";
} catch (\Exception $e) {
    echo "<div class='alert alert-danger'><strong>Fehler beim Insert (erwartet):</strong> " . $e->getMessage() . "</div>";
} 




$db->setTable($table, true);
$rows = $db->select('*')->get();

?>

<div class="container mt-4">
    <h1>🧪 Demo: Pflichtfelder in JsonSQL</h1>

    <p class="lead">
        Diese Demo zeigt mehrere neue Funktionen und Best Practices im Umgang mit <strong>JsonSQL</strong>:
    </p>
    <ul>
        <li>🛡️ Wie Pflichtfelder (<code>required: true</code>) in der <code>system.json</code> definiert werden und welche Validierungsregeln beim <code>insert()</code> greifen.</li>
        <li>🧪 Es wird demonstriert, wie ein fehlerhafter Insert (ohne erforderliche Felder wie <code>firstname</code> und <code>lastname</code>) zuverlässig erkannt und über eine Exception abgefangen wird.</li>
        <li>🔄 Seit dem <strong>17.04.2025</strong> ist es möglich, beim Aufruf von <code>truncate('tabelle', true)</code> nicht nur die Datentabelle, sondern optional auch die zugehörige <code>system.json</code> automatisch zurückzusetzen.</li>
        <li>⚙️ Anschließend zeigen wir, wie Systemfelder per Code hinzugefügt werden – inklusive eines automatisch gepflegten Datumsfeldes (<code>created_at</code>) mit <code>auto_create_timestamp: true</code>.</li>
        <li>📋 Nach dem erneuten Laden der Tabelle werden die Daten mit einem leserlichen deutschen Datumsformat (<code>d.m.Y H:i:s</code>) ausgegeben.</li>
    </ul>
    <p>
        Damit liefert diese Demo nicht nur einen praxisnahen Einstieg in die Validierung von Pflichtfeldern, sondern auch in moderne Features wie dynamisches Feldmanagement und sauberes Fehler-Handling in JsonSQL.
    </p>
    <p class="mt-3">
    🔍 Am Ende der Seite kannst du dir außerdem die aktuell gespeicherten Daten sowie die <code>system.json</code>-Konfiguration anschauen – beide lassen sich per Klick bequem aufklappen.
</p>  

<br>


    <h4>📦 Inhalt von <code><?= $table ?>.json</code>:</h4>
    <ul class="list-group">
        <?php foreach ($rows as $row): 
            $dt = new DateTime($row['created_at']);   
            
            ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname'])  ?></strong><br>                                
                E-Mail: <?= htmlspecialchars($row['email']) ?><br>
                Telefon: <?= htmlspecialchars($row['phone']) ?><br>    
                Erstellt am: <?= $dt->format('d.m.Y H:i:s') ?><br>               
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="alert alert-info mt-4">
        ✅ Nur vollständige Datensätze mit allen erforderlichen Feldern werden gespeichert.
    </div>
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
          <h4>System Datei: required_demo.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/required_demo.system.json'));
          ?></code></pre>

          <h4>JsonSQL Datei: required_demo.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/required_demo.json'));
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
} catch (\Exception $e) {
    echo 'erxception aussen';
    echo "<div class='alert alert-danger'><strong>Fehler:</strong> " . $e->getMessage() . "</div>";
    echo "<pre><code>" . $e->__toString() . "</code></pre>";
} finally {
    require_once __DIR__ . '/../includes/footer.php';
}
?>

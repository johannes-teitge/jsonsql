<?php
$pageTitle = "JsonSQL SetTable Demo: Tabelle mit Auto-Feldern";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;



?>
<!-- Exclude Begin -->
  <p>Diese Demo zeigt dir die Funktionsweise und die Stärke von JsonSQL, indem wir automatisch definierte Felder, 
    wie zum Beispiel Zeitstempel und Autoincrement-IDs, in einer Tabelle anlegen und die entsprechenden Informationen zur 
    Tabelle anzeigen. Keine teuren Lizenzgebühren oder komplexe Datenbankserver – JsonSQL setzt alles in einem kompakten 
    und effizienten Format um.</p> <p>Sieh dir an, wie leicht es ist, mit JsonSQL Tabellen zu verwalten, Datensätze zu 
      erstellen und die benötigten Felder automatisch zu definieren. Es ist einfach, flexibel und vor allem praktisch!</p> 
<p>Überzeuge dich selbst, wie JsonSQL dir hilft, Datenbankoperationen unkompliziert, schnell und einfach durchzuführen – 
  direkt auf deinen JSON-Dateien!</p><br><hr>
<!-- Exclude End -->
<?php


// Datenbank und Tabellen definieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'dai_table'; // Die Tabelle, die wir anlegen möchten

// 1. Tabelle leeren, wenn sie bereits existiert, und Autoincrement sowie Timestamps definieren
$db->truncate($table);

// 2. Autoincrement für 'id' setzen, wenn nicht bereits gesetzt
if (!$db->isAutoincrementField('id')) {
    $db->addAutoincrementField('id', 1);
    echo "<div class='alert_ alert-info'>⚙️ Autoincrement für 'id' wurde gesetzt (Startwert 1).</div>";
} else {
    echo "<div class='alert_ alert-success'>✅ Autoincrement für 'id' ist gesetzt</div>";
}

// 3. "created_at" und "updated_at" Felder setzen, falls nicht bereits gesetzt
if (!$db->isCreatedAtField('created_at')) {
    $db->addCreatedAtField('created_at');
    echo "<div class='alert_ alert-info'>⚙️ 'created_at' Feld wurde gesetzt.</div>";
} else {
    echo "<div class='alert_ alert-success'>✅ 'created_at' Feld ist gesetzt.</div>";
}

if (!$db->isUpdatedAtField('updated_at')) {
    $db->addUpdatedAtField('updated_at');
    echo "<div class='alert_ alert-info'>⚙️ 'updated_at' Feld wurde gesetzt.</div>";
} else {
    echo "<div class='alert_ alert-success'>✅ 'updated_at' Feld ist gesetzt.</div>";
}

// 4. Beispielhafte Daten für die Tabelle
$records = [
    ['name' => 'Max Mustermann', 'email' => 'max@example.com'],
    ['name' => 'Erika Musterfrau', 'email' => 'erika@example.com'],
    ['name' => 'John Doe', 'email' => 'john@example.com'],
];

// 5. Daten in die Tabelle einfügen
echo "<div class='alert_ alert-info'>⚙️ Neue Datensätze werden eingefügt...</div>";
foreach ($records as $record) {
    $db->insert($record);
    echo "<div class='alert_ alert-success'>✅ Datensatz für {$record['name']} wurde gespeichert.</div>";
}



// 6. Tabelle leeren oder anlegen und automatisch Felder setzen
$db->setTable($table);

// 7. Tabelle mit den Datensätzen ausgeben
$allRecords = $db->from($table)->get();

// 8. Tabelle-Infos (Dateigröße, letzte Änderung, Felder und Datensätze) anzeigen
$tableInfo = $db->getTableInfo();
?>



<h3 style="margin-top: 25px;">📦 Tabelleninformationen:</h3>
<div class="table-info card p-4">
<h5>🛠️ Automatisch definierte Felder:</h5>
<ul>
    <li><strong>Autoincrement:</strong> <?= $tableInfo['autoincrement'] ? 'Ja, Feldname: ' . $tableInfo['autoincrement'] : 'Nicht definiert' ?></li>
    <li><strong>AutoCreated:</strong> <?= $tableInfo['autocreated'] ? 'Ja, Feldname: ' . $tableInfo['autocreated'] : 'Nicht definiert' ?></li>
    <li><strong>AutoUpdated:</strong> <?= $tableInfo['autoupdated'] ? 'Ja, Feldname: ' . $tableInfo['autoupdated'] : 'Nicht definiert' ?></li>
    <li><strong>AutoHash:</strong> <?= $tableInfo['autohash'] ? 'Ja, Feldname: ' . $tableInfo['autohash'] : 'Nicht definiert' ?></li>
    <li><strong>AutoUUID:</strong> <?= $tableInfo['autouuid'] ? 'Ja, Feldname: ' . $tableInfo['autouuid'] : 'Nicht definiert' ?></li>
</ul>

<h5>📊 Tabelleninformationen:</h5>
<ul>
    <li><strong>Tabellenname:</strong> <?= $tableInfo['table_name'] ?></li>
    <li><strong>Tabellenpfad:</strong> <?= $tableInfo['table_path'] ?></li>
    <li><strong>Dateigröße:</strong> <?= $tableInfo['file_size'] ?> bytes</li>
    <li><strong>Letzte Änderung:</strong> <?= $tableInfo['last_modified'] ?></li>
    <li><strong>Anzahl der Datensätze:</strong> <?= $tableInfo['record_count'] ?></li>
    <li><strong>Anzahl der Felder (inkl. systemdefinierter):</strong> <?= $tableInfo['system_fields_count'] ?> Systemfelder, <?= $tableInfo['real_fields_count'] ?> echte Felder</li>
</ul>
</div>


<?php
// 9. Datensätze in einer Tabelle anzeigen
echo "<h3>📦 Alle Datensätze aus der Tabelle:</h3>";
echo "<table class='table table-striped table-bordered'>";
echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th><th>Updated At</th></tr></thead>";
echo "<tbody>";

foreach ($allRecords as $record) {
    echo "<tr>";
    echo "<td>{$record['id']}</td>";
    echo "<td>{$record['name']}</td>";
    echo "<td>{$record['email']}</td>";
    echo "<td>{$record['created_at']}</td>";
    echo "<td>{$record['updated_at']}</td>";
    echo "</tr>";
}

echo "</tbody></table>";


// 10. Debugging-Ausgabe
$debugger->dump($allRecords, $db);

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

<style>::after.table-info {
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table-info li {
    font-size: 1rem;
    margin-bottom: 10px;
}

.table-info li strong {
    font-weight: 600;
    color: #007bff;
}

.table-info ul {
    list-style-type: none;
    padding-left: 0;
}

.table-info ul li {
    margin-bottom: 5px;
}

.table-info ul li i {
    margin-right: 8px;
    color: #6c757d;
}

/* Icons */
.bi {
    font-size: 1.2rem;
    vertical-align: middle;
}

.card {
    border: none;
}

.card p {
    font-size: 1rem;
}
</style>

<!-- Exclude End -->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

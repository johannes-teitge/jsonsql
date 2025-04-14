<?php
$pageTitle = "JsonSQL Artikel-Demo";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

$db = new JsonSQL(['products' => __DIR__ . '/../testdb']);
$table = 'articles';
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





// 3. Beispiel-Datensätze einfügen (Hash wird automatisch verschlüsselt)
$demoData = [
    ['id' => 1, 'title' => 'Kugelschreiber', 'price' => 1.49, 'hash' => 'Kugel'],
    ['id' => 2, 'title' => 'Notebook',       'price' => 5.99, 'hash' => 'Note'],
    ['id' => 3, 'title' => 'USB-Stick',      'price' => 8.49, 'hash' => 'USB'],
];
foreach ($demoData as $item) {
    $db->from($table)->insert($item);
}

echo "<h2>✅ Tabelle <code>$table</code> wurde angelegt und mit Beispieldaten gefüllt.</h2>";

// 4. Anzeige: ist "hash" verschlüsselt?
echo "<p>";
echo $db->isEncryptedField('hash')
    ? "🔐 Feld <code>hash</code> ist <strong>verschlüsselt</strong>."
    : "🔓 Feld <code>hash</code> ist <strong>nicht verschlüsselt</strong>.";
echo "</p>";

// 5. Artikel lesen
$articles = $db->from($table)->select(['id', 'title', 'price', 'hash'])->orderBy('id')->get();

echo "<h3>📦 Artikelübersicht:</h3>";
echo "<ul class='list-group'>";
foreach ($articles as $article) {
    echo "<li class='list-group-item'>";
    echo "<strong>[{$article['id']}] {$article['title']}</strong> – {$article['price']} €<br>";
    echo "<small class='text-muted'>🔑 Hash: {$article['hash']}</small>";
    echo "</li>";
}
echo "</ul>";

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

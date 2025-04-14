<?php
$pageTitle = "JsonSQL Encrypt Demo: Passwortverschlüsselung";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Datenbank initialisieren
$db = new JsonSQL(['main' => __DIR__ . '/PasswordDB/api/db']);
$db->use('main');
$table = 'users';

// Tabelle leeren oder anlegen
$db->truncate($table);

// Verschlüsselung für Passwortfeld aktivieren, falls nicht gesetzt
if (!$db->isEncryptedField('password')) {
    $db->addEncryptedField('password');
    echo "<div class='alert alert-info'>🔐 Passwortfeld wurde zur Verschlüsselung konfiguriert.</div>";
} else {
    echo "<div class='alert alert-success'>🔒 Passwortfeld ist bereits verschlüsselt.</div>";
}

// Beispiel-Datensätze einfügen
$users = [
    ['username' => 'admin', 'password' => 'geheim123', 'email' => 'admin@example.com'],
    ['username' => 'testuser', 'password' => 'abc123',   'email' => 'test@example.com'],
];

echo "<h2>👥 Neue Benutzer wurden hinzugefügt:</h2>";
echo "<ul class='list-group'>";
foreach ($users as $user) {
    $db->from($table)->insert($user);
    echo "<li class='list-group-item'>✅ Benutzer <strong>{$user['username']}</strong> gespeichert.</li>";
}
echo "</ul>";

// Gefilterte Abfrage (z. B. Benutzername enthält "admin")
$results = $db->from($table)->select('*')
             ->where([['username', 'like', '%admin%']])
             ->get();

// Ausgabe
echo "<h3 class='mt-5'>🔍 Gefundene Benutzer mit 'admin':</h3>";
if (count($results)) {
    echo "<ul class='list-group'>";
    foreach ($results as $row) {
        echo "<li class='list-group-item'>";
        echo "<strong>{$row['username']}</strong> – {$row['email']}<br>";
        echo "<small class='text-muted'>Passwort (entschlüsselt): {$row['password']}</small>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='text-muted'>Keine Benutzer gefunden.</p>";
}

// Rohdaten zeigen (zur Veranschaulichung der Verschlüsselung)
$rawJson = $db->from($table)->getRawTableData();
echo "<h4 class='mt-5'>🧾 Rohdaten aus der JSON-Datei ({$table}.json):</h4>";
echo "<pre class='bg-light p-3 rounded border'><code>" . htmlspecialchars($rawJson) . "</code></pre>";

// Code anzeigen
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

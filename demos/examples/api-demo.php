<?php
$pageTitle = "🔌 JsonSQL API-Demo";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Init DB
$db = new JsonSQL(['api' => __DIR__ . '/../testdb']);
$db->use('api');
$table = 'api_demo';

// Tabelle vorbereiten (falls nicht vorhanden)
if (!file_exists(__DIR__ . '/../testdb/' . $table . '.json')) {
    $db->truncate($table);
    $demo = [
        ['id' => 1, 'title' => 'API Test 1', 'status' => 'active'],
        ['id' => 2, 'title' => 'API Test 2', 'status' => 'inactive'],
        ['id' => 3, 'title' => 'API Test 3', 'status' => 'archived'],
    ];
    foreach ($demo as $d) {
        $db->from($table)->insert($d);
    }
}

$apiUrl = basename(dirname(__DIR__)) . '/api/JsonSQL-API.php';
?>

<div class="container py-4">
  <h1 class="mb-4">🔌 JsonSQL API-Demo</h1>
  <p class="lead">Diese Demo zeigt, wie du von extern auf deine JSON-Datenbank zugreifen kannst – per REST API.</p>

  <div class="card p-3 mb-4">
    <h5 class="card-title">📥 Beispiel: Daten auslesen (GET)</h5>
    <pre class="bg-dark text-light p-3 rounded"><code>GET <?= htmlspecialchars($apiUrl) ?>?table=api_demo</code></pre>
    <p>Optional mit ID: <code>?table=api_demo&amp;id=2</code></p>
  </div>

  <div class="card p-3 mb-4">
    <h5 class="card-title">➕ Beispiel: Eintrag hinzuf&uuml;gen (POST)</h5>
    <pre class="bg-dark text-light p-3 rounded"><code>POST <?= htmlspecialchars($apiUrl) ?>
Content-Type: application/json
{
  "table": "api_demo",
  "record": {
    "title": "Neu via API",
    "status": "pending"
  }
}</code></pre>
  </div>

  <div class="card p-3 mb-4">
    <h5 class="card-title">✏️ Beispiel: Eintrag aktualisieren (PUT)</h5>
    <pre class="bg-dark text-light p-3 rounded"><code>PUT <?= htmlspecialchars($apiUrl) ?>?table=api_demo&amp;id=1
Content-Type: application/json
{
  "record": {
    "status": "done"
  }
}</code></pre>
  </div>

  <div class="card p-3 mb-4">
    <h5 class="card-title">🗑️ Beispiel: Eintrag l&ouml;schen (DELETE)</h5>
    <pre class="bg-dark text-light p-3 rounded"><code>DELETE <?= htmlspecialchars($apiUrl) ?>?table=api_demo&amp;id=3</code></pre>
  </div>

  <div class="alert alert-info mt-4">
    💡 Du kannst das <strong>API-Script direkt einbinden</strong> oder als Microservice auf deinem Webserver nutzen.<br>
    Der Pfad sollte korrekt auf <code>/api/JsonSQL-API.php</code> zeigen.
  </div>

  <h5 class="mt-5">📦 Aktuelle Daten</h5>
  <ul class="list-group">
    <?php foreach ($db->from($table)->orderBy('id')->get() as $row): ?>
      <li class="list-group-item">
        <strong>#<?= $row['id'] ?></strong>: <?= htmlspecialchars($row['title']) ?> <small class="text-muted float-end">Status: <?= $row['status'] ?></small>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php
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
          <pre class="code-block"><code><?php echo htmlspecialchars(file_get_contents(__FILE__)); ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

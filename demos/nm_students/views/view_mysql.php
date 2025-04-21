<?php

$tables = [
  $table_courses,
  $table_teachers,
  $table_classes,
  $table_students,
  $table_enrollments
];

$exportOutput = '';
$selectedTable = null;


// SQL-Export vorbereiten (einzeln oder komplett)
if (isset($_GET['table']) && in_array($_GET['table'], $tables)) {
  $selectedTable = $_GET['table'];

  if (isset($_GET['full'])) {
      // Struktur + Daten
      $exportOutput =
          $db->ExportMySQLCreate($selectedTable)
          . "\n\n"
          . $db->ExportMySQLData($selectedTable);
  } elseif (isset($_GET['data'])) {
      // Nur Daten
      $exportOutput = $db->ExportMySQLData($selectedTable);
  } else {
      // Nur Struktur
      $exportOutput = $db->ExportMySQLCreate($selectedTable);
  }

} elseif (isset($_GET['all'])) {
  $selectedTable = '*';

  if (isset($_GET['create'])) {
    $exportOutput = $db->ExportMySQLCreateAll();
    $exportTitle = 'CREATE aller Tabellen';
  } elseif (isset($_GET['data'])) {
    $exportOutput = $db->ExportMySQLDataAll();
    $exportTitle = 'INSERTs aller Tabellen';
  } elseif (isset($_GET['full'])) {
    $exportOutput = $db->ExportMySQLFullAll();
    $exportTitle = 'Kompletter Export aller Tabellen';
  } else {
    $exportOutput = "-- ⚠️ Kein Exporttyp angegeben. Nutze ?all=1&create=1, ?all=1&data=1 oder ?all=1&full=1";
    $exportTitle = 'Fehler';
  }
}


$tables = $db->listTables();

foreach ($tables as $table) {
  //  echo "<h5 class='mt-4'>🗂️ <code>$table</code></h5>";

    $info = [
        'JSON-Datei'         => $db->getTableFilePath($table),
        'System-Definition'  => $db->getTableSystemFilePath($table),
        'System vorhanden?'  => $db->hasSystemTable($table) ? '✅ Ja' : '❌ Nein',
    ];

  //  $db->dump($info, 'Systeminfos');
}
?>


<h2 class="mb-4">🛠️ MySQL Export</h2>
<p>Exportiere die aktuelle Datenstruktur als <code>CREATE TABLE</code>-Statements im MySQL-Format.</p>

<?php if (!empty($exportOutput)): ?>
  <h4 class="mb-3">
    <?= $selectedTable === '*' ? 'Gesamte Datenbankstruktur' : 'Tabelle: ' . htmlspecialchars($selectedTable) ?>
  </h4>
  <pre class="line-numbers sql-export-box"><code class="language-sql"><?= htmlspecialchars($exportOutput) ?></code></pre>
<?php endif; ?>

<table class="table table-striped table-bordered align-middle mb-5">
  <thead class="table-light">
    <tr>
      <th>Tabelle</th>
      <th>Aktion</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach ($tables as $table): ?>
  <?php $hasSystem = $db->hasSystemTable($table); ?>
  <tr>
    <td>🗂️ <code class="language-sql"><?= $table ?></code></td>
    <td class="d-flex gap-2 flex-wrap">
      <a href="?view=mysql&table=<?= urlencode($table) ?>" class="btn btn-sm btn-outline-primary">
        📄 CREATE
      </a>

      <?php if ($hasSystem): ?>
        <a href="?view=mysql&table=<?= urlencode($table) ?>&data=1" class="btn btn-sm btn-outline-secondary">
          🧾 INSERTs
        </a>

        <a href="?view=mysql&table=<?= urlencode($table) ?>&full=1" class="btn btn-sm btn-outline-success">
          📦 Komplett (CREATE + INSERT)
        </a>
      <?php else: ?>
        <button class="btn btn-sm btn-outline-secondary" disabled title="Systemdefinition fehlt">
          🧾 INSERTs nicht verfügbar
        </button>
      <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>




  <!-- Zusatzzeile für Gesamtexport -->
  <tr class="table-info fw-bold">
    <td>🛢️ <code class="language-sql">Alle Tabellen</code></td>
    <td class="d-flex gap-2 flex-wrap">
      <a href="?view=mysql&all=1&create=1" class="btn btn-sm btn-outline-primary">
        📄 CREATE (alle)
      </a>

      <a href="?view=mysql&all=1&data=1" class="btn btn-sm btn-outline-secondary">
        🧾 INSERTs (alle)
      </a>

      <a href="?view=mysql&all=1&full=1" class="btn btn-sm btn-outline-success">
        📦 Komplett (alle)
      </a>
    </td>
  </tr>

  </tbody>
</table>

<style>
    .sql-export-box {
        display: _inline-block;          
  background: #f8f9fa;
  padding: 1em;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.9em;
  max-height: 400px;
  overflow: auto;
  white-space: pre;
}






</style>



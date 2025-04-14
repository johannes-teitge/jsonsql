<?php
$pageTitle = "🛠️ Datenbank-Tools: Tabellen löschen, Info & Testtabellen";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// Datenbankpfad (Ordner!)
$dbPath = __DIR__ . '/../testdb/my_demo_db';

// Instanz mit Alias
$db = new JsonSQL(['demo' => $dbPath]);

$results = [];
$db->use('demo');

// 🧹 clearDatabase
if (isset($_GET['clear'])) {
    try {
        $count = $db->clearDatabase();
        $results[] = "🧹 Es wurden $count Tabellen-Dateien gelöscht.";
    } catch (\Exception $e) {
        $results[] = "❗ Fehler beim Löschen: " . $e->getMessage();
    }
}

// 📦 Testtabellen anlegen
if (isset($_GET['create_tables'])) {
    try {
        $db->setTable('users')->insert([
            'id' => 1,
            'name' => 'Alice',
            'email' => 'alice@example.com'
        ]);

        $db->setTable('products')->insert([
            'id' => 100,
            'title' => 'Kaffeemaschine',
            'price' => 79.90
        ]);

        $db->setTable('orders')->insert([
            'id' => 5000,
            'user_id' => 1,
            'product_id' => 100,
            'quantity' => 2
        ]);

        $results[] = "✅ Testtabellen wurden erfolgreich angelegt.";
    } catch (\Exception $e) {
        $results[] = "❗ Fehler beim Anlegen der Tabellen: " . $e->getMessage();
    }
}

// ℹ️ getDatabaseInfo
if (isset($_GET['info'])) {
    try {
        $info = $db->getDatabaseInfo();
        $results[] = [
            'type' => 'info-box',
            'info' => $info
        ];
    } catch (\Exception $e) {
        $results[] = "❗ Fehler: " . $e->getMessage();
    }
}

?>

<h2><?= $pageTitle ?></h2>

<div class="d-flex gap-3 flex-wrap mb-4">
    <a href="?create_tables" class="btn btn-primary">
        📦 Testtabellen anlegen
    </a>
    <a href="?clear" class="btn btn-warning">
        🧹 Alle Tabellen löschen
    </a>
    <a href="?info" class="btn btn-info text-white">
        ℹ️ Datenbank-Info
    </a>
</div>

<?php foreach ($results as $r): ?>
    <?php if (is_array($r) && $r['type'] === 'info-box'): ?>
        <div class="mb-4">
            <h4 class="mb-2">📊 Datenbank-Info</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Pfad:</strong> <?= $r['info']['path'] ?></li>
                <li class="list-group-item"><strong>Anzahl Tabellen:</strong> <?= $r['info']['table_count'] ?></li>
                <li class="list-group-item"><strong>Gesamtgröße:</strong> <?= number_format($r['info']['total_size']) ?> Bytes</li>
            </ul>

            <h5>📁 Tabellen</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Datei</th>
                            <th>Größe</th>
                            <th>Letzte Änderung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($r['info']['tables'] as $table): ?>
                            <tr>
                                <td><?= $table['name'] ?></td>
                                <td><?= number_format($table['size']) ?> B</td>
                                <td><?= $table['last_modified'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary"><?= $r ?></div>
    <?php endif; ?>
<?php endforeach; ?>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>

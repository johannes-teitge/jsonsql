<?php
/**
 * ============================================================
 * 🧪 JsonSQL Demo #AnalyzeSystemTable – Prüfung der Systemdefinition
 * ============================================================
 * 
 * Zweck:
 * Analysiert die system.json einer Tabelle auf ungültige Datentypen
 * und nicht erlaubte Feldoptionen.
 * 
 * Datei: demo_analyzeSystemTable.php
 * Pfad: demos/demo_analyzeSystemTable.php
 * 
 * Autor: Dscho Teitge (https://teitge.de)
 * Stand: 2025-04-18
 * ============================================================
 */

$pageTitle = "🧪 JsonSQL-Demo: Systemprüfung (analyzeSystemTable)";
$pageDescription = "Diese Demo prüft die system.json der Tabelle auf ungültige dataType-Werte und unzulässige Feld-Properties.";

require_once __DIR__ . '/../../src/JsonSQL.php';
require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

try {

    // Init
    $db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
    $db->use('demo');
    $table = 'analyzesystem_demo';
    $db->setTable($table);

    $db->writeSystemConfig([
        'fields' => [
            'firstname' => [
                'dataType' => 'strng',           // ❌ ungültig
                'length' => 50,
                'required' => true
            ],
            'email' => [
                'dataType' => 'string',
                'length' => 64,
                'required' => true,
                'foobar' => true                 // ❌ ungültig
            ]
        ],
        'allowAdditionalFields' => false
    ]);    

    // Prüfung starten
    $result = $db->analyzeSystemTable(true, true);

    ?>
    <div class="container mt-4">
        <h1>🧪 Demo: Systemprüfung für Tabelle <code><?= $table ?></code></h1>
        <p class="lead">Diese Seite analysiert die <code>system.json</code> und zeigt ungültige Definitionen an.</p>

        <?php if (empty($result['invalidTypes']) && empty($result['invalidProperties'])): ?>
            <div class="alert alert-success">✅ Die Systemdefinition ist vollständig korrekt!</div>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>⚠️ Fehlerhafte Einträge in der <code>system.json</code>:</strong>
                <ul class="mt-2">
                    <?php foreach ($result['invalidTypes'] as $item): ?>
                        <li>❌ Feld <code><?= $item['field'] ?></code>: ungültiger <strong>dataType</strong> <code><?= $item['dataType'] ?></code></li>
                    <?php endforeach; ?>
                    <?php foreach ($result['invalidProperties'] as $item): ?>
                        <li>❌ Feld <code><?= $item['field'] ?></code>: ungültige <strong>Property</strong> <code><?= $item['property'] ?></code></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h4 class="mt-5">📄 Aktuelle <code>system.json</code></h4>
        <pre class="code-block"><code><?= htmlspecialchars(file_get_contents($db->getSystemFilePath())) ?></code></pre>
    </div>

    <?php
    $scriptContent = file_get_contents(__FILE__);
    ?>

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

<?php
// Seitentitel und Includes
$pageTitle = "JsonSQL Demo: WHERE IN Filter mit JOIN & Alias";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

// JsonSQL-Instanz
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');

$categoryTable = 'st3_categories';
$productTable  = 'st3_products';

// Demo-Daten vorbereiten
if (!file_exists(__DIR__ . '/../testdb/' . $categoryTable . '.json')) {
    $db->truncate($categoryTable);
    $cats = ['Büro', 'Technik', 'Haushalt', 'Freizeit', 'Gesundheit'];
    foreach ($cats as $i => $cat) {
        $db->from($categoryTable)->insert([
            'cat_id' => $i + 1,
            'cat_title' => $cat
        ]);
    }
}

if (!file_exists(__DIR__ . '/../testdb/' . $productTable . '.json')) {
    $db->truncate($productTable);
    for ($i = 1; $i <= 25; $i++) {
        $db->from($productTable)->insert([
            'pro_id' => $i,
            'pro_title' => "Produkt $i",
            'pro_price' => rand(100, 1000) / 10,
            'pro_cat_id' => rand(1, 5)
        ]);
    }
}

// Filter aus der URL
$filterIds = isset($_GET['filter']) 
    ? array_map('intval', is_array($_GET['filter']) ? $_GET['filter'] : explode(',', $_GET['filter'])) 
    : [];

// Kategorien laden
$categories = $db->from($categoryTable)->select('*')->orderBy('cat_title')->get();

// Produkte mit JOIN auf Kategorien – jetzt explizit mit unterschiedlichen Spalten
$db->from($productTable)->join($categoryTable, ['local' => 'pro_cat_id', 'foreign' => 'cat_id'], 'LEFT');

if (!empty($filterIds)) {
    $db->where([['pro_cat_id', 'IN', $filterIds]]);
}

$products = $db->select([
        'pro_title',
        'pro_price',
        'cat_title'
    ])
    ->orderBy('pro_title')
    ->get();
?>

<div class="container">
<h1 class="my-4">🎯 WHERE IN Filter + LEFT JOIN</h1>
<p class="text-muted">
  In dieser Demo filtern wir Produkte anhand gewählter Kategorien.<br>
  Die zugehörige Kategorie wird per <code>LEFT JOIN</code> eingeblendet – auch wenn keine passende Kategorie vorhanden ist.<br><br>

  <strong>Besonderheit:</strong> Unsere JOIN-Funktion unterstützt auch <u>unterschiedlich benannte Felder</u>.<br>
  Statt dass in beiden Tabellen die Spalte gleich heißt (z. B. <code>category_id</code>), 
  können wir gezielt angeben, welche Felder verknüpft werden sollen – mit:
</p>

<pre class="bg-light p-3 rounded">
->join('st3_categories', ['local' => 'pro_cat_id', 'foreign' => 'cat_id'], 'LEFT')
</pre>

<p class="text-muted">
  🔄 <code>local</code> bezeichnet das Feld in der aktuellen Tabelle („Produkte“),<br>
  🆚 <code>foreign</code> das Feld in der verknüpften Tabelle („Kategorien“).<br>
  Damit funktioniert der JOIN auch dann, wenn die Feldnamen nicht übereinstimmen – z. B. <code>pro_cat_id</code> ⇄ <code>cat_id</code>.
</p>


  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card shadow-sm p-3 mb-3">
        <h5 class="card-title">📁 Kategorien filtern</h5>
        <form method="get">
          <?php foreach ($categories as $cat): ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="filter[]" value="<?= $cat['cat_id'] ?>" id="cat<?= $cat['cat_id'] ?>" <?= in_array($cat['cat_id'], $filterIds) ? 'checked' : '' ?>>
              <label class="form-check-label" for="cat<?= $cat['cat_id'] ?>">
                <?= htmlspecialchars($cat['cat_title']) ?>
              </label>
            </div>
          <?php endforeach; ?>
          <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-sm btn-primary">Filter anwenden</button>
            <a href="?" class="btn btn-sm btn-outline-secondary">Zurücksetzen</a>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">📦 Gefilterte Produkte</h5>
        <?php if (empty($products)): ?>
          <p class="text-muted">Keine Produkte gefunden.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>Produkt</th>
                  <th>Kategorie</th>
                  <th class="text-end">Preis</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $prod): ?>
                  <tr>
                    <td><?= htmlspecialchars($prod['pro_title']) ?></td>
                    <td><?= isset($prod['cat_title']) ? htmlspecialchars($prod['cat_title']) : '<span class="text-muted">keine Kategorie</span>' ?></td>
                    <td class="text-end">💶 <?= number_format($prod['pro_price'], 2) ?> €</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Quellcode anzeigen -->
  <?php $scriptName = basename(__FILE__); ?>
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
            <pre class="code-block"><code><?= htmlspecialchars(file_get_contents(__FILE__)); ?></code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

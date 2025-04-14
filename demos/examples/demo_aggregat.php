
<?php
$pageTitle = "JsonSQL Aggregat-Demo mit Filter";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;



function fakeAggregatRow(string $product, int $id): array {
    $vendors = ['Amazon', 'MediaMarkt', 'eBay', 'Otto', 'Saturn', 'Lidl', 'Real', 'Aldi'];
    $price = match ($product) {
        'Kaffeemaschine' => rand(75, 105) + rand(0, 99) / 100,
        'Wasserkocher'   => rand(35, 55) + rand(0, 99) / 100,
        'Toaster'        => rand(19, 30) + rand(0, 99) / 100,
    };
    return [
        'id' => $id,
        'product' => $product,
        'rating' => rand(1, 5),
        'price' => round($price, 2),
        'vendor' => $vendors[array_rand($vendors)],
        'stock' => rand(0, 5000),        
        'date' => date('Y-m-d', strtotime("2024-11-01 +" . rand(0, 150) . " days"))
    ];
}

// Init
$db = new JsonSQL(['aggregat_demo' => __DIR__ . '/../testdb/aggregat_demo']);
$table = 'aggregat_demo_data';
$db->use('aggregat_demo')->setTable($table);



// Daten neu generieren, wenn "reset=1"
if (isset($_GET['reset']) && $_GET['reset'] == 1) {

// Anzahl Einträge pro Produkt holen
  $entriesPerProduct = isset($_GET['entriesPerProduct']) ? max(1, (int)$_GET['entriesPerProduct']) : 2;  

  // Tabelle leeren und neue Einträge erzeugen
  $db->truncate($table);

  $id = 1;

  $useTransaction = isset($_GET['useTransaction']);


  if ($useTransaction) {
    $db->transact();
  }

  foreach (['Kaffeemaschine', 'Wasserkocher', 'Toaster'] as $product) {
      for ($i = 0; $i < $entriesPerProduct; $i++) {
          $db->insert(fakeAggregatRow($product, $id++));
      }
  }
  if ($useTransaction) {
      $db->commit();
  }

  // Nach dem Erzeugen: Redirect, um Parameter aus URL zu entfernen
  $url = strtok($_SERVER["REQUEST_URI"], '?'); // Basis-URL ohne Parameter
  // header("Location: $url");
  // exit;  
} else {
  $db->setTable($table,true);
}  


// Filterwerte holen
$filterProduct = trim($_GET['product'] ?? '');
if ($filterProduct !== '') {
    $db->where([['product', '=', $filterProduct]]);
}
$filterVendor = trim($_GET['vendor'] ?? '');
if ($filterVendor !== '') {
    $db->where([['vendor', '=', $filterVendor]]);
}

// Daten laden und ggf. filtern
$db->select()->from($table)->get();

// Summe berechnen
$column = 'price';
$result_sum_all = $db->sum($column);
$result_avg_all = $db->avg($column);

$resultcount_all = $db->count();



$vendors = $db->distinct('vendor');
$products = $db->distinct('product');
$debugger->dump($vendors,$products);


$db->select()->from($table);
$conditions = [];

if ($filterVendor) {
    $conditions[] = ['vendor', '=', $filterVendor];
}
if ($filterProduct) {
    $conditions[] = ['product', '=', $filterProduct];
}

$debugger->addInfoText('Filter');


if (!empty($conditions)) {
   $db->where($conditions); // wirkt als OR-Verknüpfung
}

$debugger->dump($conditions,$db);



// Summe berechnen
$column = 'price';
$result_sum = $db->sum($column);
$result_avg = $db->avg($column);

$resultcount = $db->count();


$data = $db->get();
// Distinct für Dropdowns
// $vendors = $db->distinct('vendor');
// $products = $db->distinct('product');

$numericFields = ['price', 'stock', 'rating'];
$stats_all = [];
$stats_filtered = [];

$db->select()->from($table);

foreach ($numericFields as $field) {
    // Ohne Filter

    $stats_all[$field] = $db->stats($field);

    // Mit Filter

    if (!empty($conditions)) {
        $db->where($conditions);
    }
    $stats_filtered[$field] = $db->stats($field);
}




?>

<div class="container">
  <h1 class="mb-4">📊 JsonSQL Aggregat-Demo mit Filter</h1>

  <div class="mb-4">
  <p class="lead">
    Diese interaktive Demo zeigt die Möglichkeiten von <strong>JsonSQL</strong> zur Analyse strukturierter JSON-Daten – ganz ohne klassische Datenbank.
  </p>
  <p>
    Über das komfortable Filtermenü kannst du gezielt Produkte und Händler auswählen. Anschließend werden dir automatisch alle relevanten Statistiken wie <strong>Summe, Durchschnitt, Median, Minimum, Maximum, Spannweite, Varianz</strong> und <strong>Standardabweichung</strong> für numerische Felder wie Preis, Lagerbestand und Bewertung angezeigt – jeweils vor und nach dem Filter.
  </p>
  <p>
    Zusätzlich kannst du Testdaten generieren und optional den <strong>Transaktionsmodus</strong> aktivieren, um Datenänderungen gesammelt zu speichern.
  </p>
</div>

<hr>



  <form method="get" class="row g-3 mb-4">
  <input type="hidden" name="reset" value="1">

  <div class="col-md-3">
    <label for="entriesPerProduct" class="form-label">Anzahl pro Produkt:</label>
    <input type="number" class="form-control" id="entriesPerProduct" name="entriesPerProduct"
           value="<?= htmlspecialchars($_GET['entriesPerProduct'] ?? 5) ?>" min="1" max="1000">
  </div>

  <div class="col-md-3">
    <label for="useTransaction" class="form-label">Transaktionsmodus:</label>
    <div class="form-check pt-2">
      <input class="form-check-input" type="checkbox" name="useTransaction" value="1" id="useTransaction"
             <?= isset($_GET['useTransaction']) ? 'checked' : '' ?>>
      <label class="form-check-label" for="useTransaction">
        Verwenden
      </label>
    </div>
  </div>

  <div class="col-md-3 align-self-end">
    <button type="submit" class="btn btn-warning">🔁 Daten neu generieren</button>
  </div>
</form>



<hr>


  <form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
      <label for="vendor" class="form-label">Händler:</label>
      <select id="vendor" name="vendor" class="form-select">
        <option value="">Alle</option>
        <?php foreach ($vendors as $v): ?>
          <option value="<?= $v ?>" <?= $v === $filterVendor ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label for="product" class="form-label">Produkt:</label>
      <select id="product" name="product" class="form-select">
        <option value="">Alle</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p ?>" <?= $p === $filterProduct ? 'selected' : '' ?>><?= $p ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6 align-self-end">
  <div class="d-flex gap-2">
    <button class="btn btn-outline-secondary" type="submit">🔍 Filtern</button>
    <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-outline-secondary">⏮️ Zurücksetzen</a>
  </div>
</div> 
  </form>

  <h5>📋 Datentabelle (<?= count($data) ?> Einträge)</h5>
  <?php if (!empty($data) && is_array(reset($data))): ?>
  <div class="table-wrapper mb-4">
    <table class="table table-sm table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <?php foreach (array_keys(reset($data)) as $key): ?>
            <th><?= htmlspecialchars($key) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>

          <?php foreach ($row as $key => $value): ?>
  <?php if ($key === 'rating'): ?>
    <?php $stars = (int) $value; ?>
    <td title="<?= $stars ?> Sterne im Rating">
      <div class="rating-stars">
        <?php
          for ($i = 0; $i < $stars; $i++) {
            echo '<i class="bi bi-star-fill text-warning"></i>';
          }
          for ($i = $stars; $i < 5; $i++) {
            echo '<i class="bi bi-star text-muted"></i>';
          }
        ?>
      </div>
    </td>
  <?php else: ?>
    <td><?= htmlspecialchars((string) $value) ?></td>
  <?php endif; ?>
<?php endforeach; ?>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <div class="alert alert-warning">Keine Daten gefunden für die aktuelle Auswahl.</div>
<?php endif; ?>

<?php if (!empty($column)): ?>



<h4 class="mt-5">📈 Statistiken pro Spalte (vor / nach Filter)</h4>

<div class="row">
  <div class="col-12 col-lg-8">
    <div class="stats-table-wrapper mb-5">
      <table class="table table-sm table-striped table-bordered table-hover align-middle">
        <thead class="table-light text-center">
          <tr>
            <th>Spalte</th>
            <th>Statistik</th>
            <th>🔄 Vor Filter</th>
            <th>🔍 Nach Filter</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Formatierungsfunktion
          function fmt($val): string {
              if (is_array($val)) return json_encode($val, JSON_UNESCAPED_UNICODE);
              if (is_numeric($val)) return number_format((float) $val, 2, ',', '.');
              return htmlspecialchars((string) $val);
          }
          ?>

          <?php foreach ($numericFields as $field): ?>
            <?php foreach ($stats_all[$field] as $key => $val): ?>
              <tr>
                <td><?= htmlspecialchars($field) ?></td>
                <td><?= htmlspecialchars($key) ?></td>
                <td class="text-end"><?= fmt($val) ?></td>
                <td class="text-end"><?= fmt($stats_filtered[$field][$key] ?? null) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>





<?php 

endif;
$scriptName = basename(__FILE__);
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

<style>
.table-wrapper {
  max-height: 400px;
  overflow-y: auto;
}

.table-wrapper table {
  border-collapse: separate;
  width: 100%;
}

.table-wrapper thead th {
  position: sticky;
  top: 0;
  background: #f8f9fa;
  z-index: 2;
}

.stats-table-wrapper {
    max-height: 400px;
    overflow-y: auto;
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: .375rem;
  }

  .stats-table-wrapper table {
    width: 100%;
    border-collapse: separate;
  }

  .stats-table-wrapper thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 5;
    box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
  }

.rating-stars i:hover {
  color:rgb(255, 176, 7); /* bleibt golden, aber du könntest auch z. B. orange nehmen */
  text-shadow: 0 0 5px rgb(225, 101, 0);
}


</style>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

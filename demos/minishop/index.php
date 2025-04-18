<?php
// demos/minishop/index.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../../src/JsonSQL.php';

// 💡 Namespace importieren
use Src\JsonSQL;

// Erstelle ein neues JsonSQL-Objekt mit Pfad zur Datenbank (testdb/)
$db = new JsonSQL(['demo' => __DIR__ . '/testdb']);
$cattable = 'categories';

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;


$db->setTable($cattable,true);
$categories = $db->get();

if ($categoryId) {
    $productIds = $db->table('category_product')
        ->where('category_id', $categoryId)
        ->pluck('product_id');
    $products = $db->table('products')->whereIn('id', $productIds)->get();
} else {
    $products = $db->table('products')->get();
}
?>
<div class="container py-4">
  <h1 class="mb-4">MiniShop</h1>

  <form method="get" class="mb-4">
    <select name="category_id" class="form-select" onchange="this.form.submit()">
      <option value="">Alle Kategorien</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $categoryId) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['title']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['title']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
            <p class="card-text fw-bold">Preis: <?= number_format($product['price'], 2) ?> €</p>
            <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-primary">In den Warenkorb</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

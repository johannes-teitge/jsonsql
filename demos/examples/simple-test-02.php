<?php
$pageTitle = "JsonSQL Artikel-Demo mit Kategorien (TreeView)";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;

$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');

$categoryTable = 'st2_groups';
$articleTable  = 'st2_articles';

function getCategoryPath(array $categories, int $groupId): string {
  $map = [];
  foreach ($categories as $cat) {
      $map[$cat['id']] = $cat;
  }

  $path = [];
  $current = $map[$groupId] ?? null;

  while ($current) {
      array_unshift($path, $current['title']);
      $current = isset($current['parent_id']) ? $map[$current['parent_id']] ?? null : null;
  }

  return implode(' > ', $path);
}

// Demo-Daten
if (!file_exists(__DIR__ . '/../testdb/' . $categoryTable . '.json')) {
    $db->truncate($categoryTable);
    $demoGroups = [
        ['id' => 1, 'title' => 'Bürobedarf', 'parent_id' => null],
        ['id' => 2, 'title' => 'Technik',     'parent_id' => null],
        ['id' => 3, 'title' => 'Papier',      'parent_id' => 1],
        ['id' => 4, 'title' => 'Stifte',      'parent_id' => 1],
        ['id' => 5, 'title' => 'Computer',    'parent_id' => 2],
        ['id' => 6, 'title' => 'Zubehör',     'parent_id' => 2],
    ];
    foreach ($demoGroups as $g) {
        $db->from($categoryTable)->insert($g);
    }
}

if (!file_exists(__DIR__ . '/../testdb/' . $articleTable . '.json')) {
    $db->truncate($articleTable);

    // Autoincrement für "id" setzen, wenn nicht vorhanden
    if (!$db->isAutoincrementField('id')) {
      $db->addAutoincrementField('id', 1);
      echo "<div class='alert alert-info'>⚙️ Autoincrement für 'id' wurde gesetzt (Startwert 1).</div>";
    }    

    $sampleTitles = ['Kugelschreiber','Heftgerät','Tackerklammern','Notizblock','Collegeblock','Bleistift','Textmarker','Korrekturroller','USB-Stick','Mauspad','Monitorhalterung','HDMI-Kabel','LAN-Kabel','Tastatur','Maus','Notebook-Ständer','Druckerpapier','Etiketten','Schere','Locher','Ordner','Trennblätter','Briefumschläge','Aktenvernichter','Whiteboardmarker','Magnete','USB-Hub','Headset','Webcam','Externe Festplatte'];
    foreach ($sampleTitles as $title) {
        $db->from($articleTable)->insert([
            'title'    => $title,
            'price'    => round(rand(100, 5000) / 100, 2),
            'group_id' => rand(1, 6)
        ]);
    }
}

$selectedGroupId = $_GET['group'] ?? null;
$categories = $db->from($categoryTable)->select('*')->get();

$categoryPaths = [];
foreach ($categories as $cat) {
    $categoryPaths[$cat['id']] = getCategoryPath($categories, $cat['id']);
}
$categoryPathsJson = json_encode($categoryPaths);

// Artikel zählen
$articleCounts = [];
foreach ($categories as $cat) {
    $count = $db->from($articleTable)->where([['group_id', '=', $cat['id']]])->count();
    $articleCounts[$cat['id']] = $count;
}

// Artikel laden
$db->select('*')->from($articleTable);
if ($selectedGroupId) {
    $db->where([['group_id', '=', (int)$selectedGroupId]]);
}
$articles = $db->orderBy('title')->get();

// TreeView
function renderCategoryTree(array $categories, array $counts, $parentId = null): string {
    $html = '<ul>';
    foreach ($categories as $cat) {
        if ($cat['parent_id'] == $parentId) {
            $hasChildren = hasChildCategories($categories, $cat['id']);
            $class = $hasChildren ? 'has-children' : '';
            $active = (isset($_GET['group']) && $_GET['group'] == $cat['id']) ? 'active' : '';
            $count = $counts[$cat['id']] ?? 0;

            $html .= "<li class='$class $active' data-id='{$cat['id']}'>";
            $html .= $hasChildren
                ? "<span class='toggle'>▸</span>"
                : "<span class='toggle empty'></span>";
            $html .= "<a href='?group={$cat['id']}'>" . htmlspecialchars($cat['title']) . " ($count)</a>";
            if ($hasChildren) {
                $html .= renderCategoryTree($categories, $counts, $cat['id']);
            }
            $html .= "</li>";
        }
    }
    $html .= '</ul>';
    return $html;
}

function hasChildCategories(array $categories, int $parentId): bool {
    foreach ($categories as $cat) {
        if ($cat['parent_id'] == $parentId) return true;
    }
    return false;
}

?>

<div class="alert alert-secondary mt-3">
  <h5 class="mb-2">🧠 Was zeigt diese Demo?</h5>
  <p>
    Diese Demo veranschaulicht, wie du mit <strong>JsonSQL</strong> eine strukturierte Artikelverwaltung mit <strong>Kategorien in Baumstruktur</strong> umsetzen kannst – ganz ohne klassische Datenbank!
  </p>
  <ul>
    <li>📁 Die linke Seite zeigt die <strong>Artikelgruppen</strong> als hierarchischen <em>TreeView</em>.</li>
    <li>📦 Rechts werden die <strong>zugehörigen Artikel</strong> bei Klick auf eine Gruppe angezeigt.</li>
    <li>🔄 Der <strong>Auf-/Zu-Zustand</strong> der Kategorien bleibt erhalten – dank <em>LocalStorage</em>.</li>
    <li>🧩 Die Artikelanzahl pro Gruppe wird in Klammern angezeigt.</li>
    <li>✨ Alle Daten werden mit <code>JsonSQL</code> verwaltet – direkt in JSON-Dateien.</li>
  </ul>
  <p class="mb-0">
    Du kannst dieses Prinzip für eigene Produktkataloge, Wissensdatenbanken, Menüstrukturen und vieles mehr einsetzen.
  </p>
</div>

<div class="container">
  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card shadow-sm p-3 mb-3 category-tree">
        <h5 class="card-title">📁 Artikelgruppen</h5>
        <?= renderCategoryTree($categories, $articleCounts) ?>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card shadow-sm p-3">
        <h5 class="card-title">📦 Artikel<?= $selectedGroupId ? " für Gruppe #$selectedGroupId" : '' ?></h5>
        <?php if (empty($articles)): ?>
          <p class="text-muted">Keine Artikel gefunden.</p>
        <?php else: ?>
          <ul class="list-group">
            <?php foreach ($articles as $article): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <button 
                  class="btn btn-link text-decoration-none show-article p-0 text-start"
                  data-bs-toggle="modal"
                  data-bs-target="#articleModal"
                  data-title="<?= htmlspecialchars($article['title']) ?>"
                  data-price="<?= number_format($article['price'], 2) ?>"
                  data-id="<?= $article['id'] ?? '-' ?>"
                  data-group="<?= $article['group_id'] ?>"
                >
                  <strong><?= htmlspecialchars($article['title']) ?></strong>
                </button>
                <span>💶 <?= number_format($article['price'], 2) ?> €</span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Artikel-Detailmodal -->
<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="articleModalLabel">📝 Artikeldetails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Titel:</dt>
          <dd class="col-sm-8" id="modalTitle"></dd>

          <dt class="col-sm-4">Preis:</dt>
          <dd class="col-sm-8" id="modalPrice"></dd>

          <dt class="col-sm-4">Artikel-ID:</dt>
          <dd class="col-sm-8" id="modalId"></dd>

          <dt class="col-sm-4">Kategorie-ID:</dt>
          <dd class="col-sm-8" id="modalGroup"></dd>

          <dt class="col-sm-4">Kategoriepfad:</dt>
          <dd class="col-sm-8" id="modalPath"></dd>  
        </dl>
      </div>
    </div>
  </div>
</div>

<?php
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


<style>
  .category-tree ul {
    list-style: none;
    padding-left: 1rem;
  }
  .category-tree li {
    margin: 4px 0;
    position: relative;
  }
  .category-tree li .toggle {
    display: inline-block;
    width: 1rem;
    cursor: pointer;
    user-select: none;
    font-weight: bold;
    color: #555;
  }
  .category-tree li .toggle.empty {
    color: transparent;
    cursor: default;
  }
  .category-tree li ul {
    display: none;
    margin-left: 1rem;
  }
  .category-tree li.open > ul {
    display: block;
  }
  .category-tree li.active > a {
    font-weight: bold;
    color: #0d6efd;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const prefix = 'jsonsql_tree_open_';

    // Restore open categories from localStorage
    document.querySelectorAll(".category-tree li.has-children").forEach(li => {
      const catId = li.dataset.id;
      if (localStorage.getItem(prefix + catId) === '1') {
        li.classList.add('open');
        const toggle = li.querySelector('.toggle');
        if (toggle) toggle.textContent = "▾";
      }
    });

    // Click events for toggles
    document.querySelectorAll(".category-tree .toggle").forEach(toggle => {
      toggle.addEventListener("click", function (e) {
        e.preventDefault();
        const li = this.closest("li");
        const catId = li.dataset.id;
        li.classList.toggle("open");
        const isOpen = li.classList.contains("open");
        this.textContent = isOpen ? "▾" : "▸";
        localStorage.setItem(prefix + catId, isOpen ? '1' : '0');
      });
    });
  });

  const categoryPaths = <?= json_encode($categoryPaths, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  document.addEventListener("DOMContentLoaded", function () {
    // Modal befüllen
    document.querySelectorAll(".show-article").forEach(btn => {
      btn.addEventListener("click", () => {
        const groupId = btn.dataset.group;
        document.getElementById("modalTitle").textContent = btn.dataset.title;
        document.getElementById("modalPrice").textContent = btn.dataset.price + " €";
        document.getElementById("modalId").textContent = btn.dataset.id;
        document.getElementById("modalGroup").textContent = groupId;
        document.getElementById("modalPath").textContent = categoryPaths[groupId] || "Unbekannt";
      });
    });
  });
</script>


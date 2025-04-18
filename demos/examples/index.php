<?php
$demos = require_once __DIR__ . '/../includes/demos.meta.php';
require_once __DIR__ . '/../includes/demo-functions.php';

$pageTitle = "Übersicht aller Demos";
$pageDescription = "Diese Demo prüft die system.json der Tabelle auf ungültige dataType-Werte und unzulässige Feld-Properties.";
$removeOverview = true;

require_once __DIR__ . '/../includes/header.php';

// 🔍 Alle Tags sammeln
$allTags = [];
foreach ($demos as $demo) {
    if (!empty($demo['tags'])) {
        $tags = explode(',', $demo['tags']);
        foreach ($tags as $tag) {
            $tag = trim($tag);
            $allTags[$tag] = true;
        }
    }
}
$tagList = array_keys($allTags);
?>


<!-- 🔍 Suchfeld -->
<div class="search-bar mb-3">
  <input type="text" id="demoSearch" class="form-control form-control-sm" placeholder="🔍 Nach Titel, Beschreibung oder Stichworten suchen...">
</div>


<!-- 🏷️ Tag-Filter oben -->
<div class="tag-filter mb-4">
  <button class="tag-btn active" data-tag="all">Alle</button>
  <?php foreach ($tagList as $tag): ?>
    <button class="tag-btn" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></button>
  <?php endforeach; ?>
</div>

<!-- 🔍 Demo-Cards -->
<div class="d-flex flex-wrap gap-4">
  <?php foreach ($demos as $demo) {
    addDemoCard($demo);
  } ?>
</div>


<style>
.tag-btn {
  margin: 0 4px 4px 0;
  padding: 3px 8px;
  font-size: 0.75rem;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
  cursor: pointer;
  border-radius: 15px;
}
.tag-btn.active {
  background-color: #0d6efd;
  color: white;
}


.badge {
  font-size: 0.75rem;
  padding: 0.35em 0.6em;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const tagButtons = document.querySelectorAll(".tag-btn");
  const cards = document.querySelectorAll(".demo-card");

  function updateCardVisibility() {
    // Aktive Tags sammeln
    const activeTags = Array.from(tagButtons)
      .filter(btn => btn.classList.contains('active') && btn.dataset.tag !== 'all')
      .map(btn => btn.dataset.tag);

    cards.forEach(card => {
      const tags = (card.getAttribute("data-tags") || '').split(',').map(t => t.trim());
      const matches = activeTags.length === 0 || activeTags.some(tag => tags.includes(tag));
      card.style.display = matches ? "block" : "none";
    });
  }

  tagButtons.forEach(button => {
    button.addEventListener("click", function () {
      const tag = this.dataset.tag;

      if (tag === 'all') {
        // Alle Tags zurücksetzen
        tagButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      } else {
        this.classList.toggle('active');
        document.querySelector('.tag-btn[data-tag="all"]').classList.remove('active');
      }

      updateCardVisibility();
    });
  });

  updateCardVisibility(); // initial
});



document.addEventListener("DOMContentLoaded", function () {
  const tagButtons = document.querySelectorAll(".tag-btn");
  const cards = document.querySelectorAll(".demo-card");
  const searchInput = document.getElementById("demoSearch");

  function updateCardVisibility() {
    const activeTags = Array.from(tagButtons)
      .filter(btn => btn.classList.contains('active') && btn.dataset.tag !== 'all')
      .map(btn => btn.dataset.tag);

    const searchQuery = (searchInput?.value || '').toLowerCase();

    cards.forEach(card => {
      const tags = (card.getAttribute("data-tags") || '').split(',').map(t => t.trim());
      const text = card.innerText.toLowerCase();

      const matchesTag = activeTags.length === 0 || activeTags.some(tag => tags.includes(tag));
      const matchesSearch = !searchQuery || text.includes(searchQuery);

      card.style.display = (matchesTag && matchesSearch) ? "block" : "none";
    });
  }

  tagButtons.forEach(button => {
    button.addEventListener("click", function () {
      const tag = this.dataset.tag;

      if (tag === 'all') {
        tagButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      } else {
        this.classList.toggle('active');
        document.querySelector('.tag-btn[data-tag="all"]').classList.remove('active');
      }

      updateCardVisibility();
    });
  });

  if (searchInput) {
    searchInput.addEventListener("input", updateCardVisibility);
  }

  updateCardVisibility(); // Initialer Aufruf
});



</script>





<?php
require_once __DIR__ . '/../includes/footer.php';
?>
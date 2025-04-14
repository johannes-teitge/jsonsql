<?php
$pageTitle = "📚 JsonSQL Demos";
require_once __DIR__ . '/../includes/header.php';
?>

<style>
  .backContent{
    visibility: hidden;
}
</style>




<div class="row mb-4">
  <div class="col-md-4">
    <input type="text" id="searchInput" class="form-control" placeholder="🔍 Suche nach Titel oder Beschreibung...">
  </div>
  <!--
  <div class="col-md-8">
    <div class="btn-group ms-2" role="group" aria-label="Filtermenü">
      <button class="btn btn-outline-primary btn-sm filter-btn active" data-filter="all">Alle</button>
      <button class="btn btn-outline-secondary btn-sm filter-btn" data-filter="tools">🧰 Tools</button>
      <button class="btn btn-outline-info btn-sm filter-btn" data-filter="api">🌐 API</button>
      <button class="btn btn-outline-warning btn-sm filter-btn" data-filter="autofields">⚙️ AutoFields</button>
      <button class="btn btn-outline-success btn-sm filter-btn" data-filter="security">🔐 Verschlüsselung</button>
      <button class="btn btn-outline-dark btn-sm filter-btn" data-filter="performance">🚀 Performance</button>
    </div>
  </div>
-->
</div>




<div class="d-flex flex-wrap gap-4">
  <!-- Erste Schritte Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🚀 Erste Schritte</h5>
      <p class="card-text">Minimalbeispiel zum schnellen Einstieg in JsonSQL.</p>
      <a href="hello-json-sql.php" class="btn btn-sm btn-outline-secondary mt-auto">Demo öffnen</a>
    </div>
  </div>

<!-- Datenbank Tools Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🧾 Datenbank-Tools</h5>
    <p class="card-text">Testtabellen anlegen, Tabellen löschen und Infos zur Datenbank anzeigen.</p>
    <a href="demo-db-tools.php" class="btn btn-sm btn-outline-primary mt-auto">Demo öffnen</a>
  </div>
</div>


  <!-- Simple Select Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">📋 Simple Select</h5>
      <p class="card-text">Einfaches Einfügen und Lesen von Artikeln mit Sortierung.</p>
      <a href="simple-test-01.php" class="btn btn-sm btn-outline-primary mt-auto">Demo öffnen</a>
    </div>
  </div>

  <!-- Passwort Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🔑 Passwort-Demo</h5>
      <p class="card-text">Feldverschlüsselung mit system.json.</p>
      <a href="demo_encrypt.php" class="btn btn-sm btn-outline-success mt-auto">Demo öffnen</a>
    </div>
  </div>

  <!-- Autoincrement Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🧠 Autoincrement</h5>
      <p class="card-text">Automatisch generierte IDs wie in MySQL.</p>
      <a href="demo_autoincrement.php" class="btn btn-sm btn-outline-warning mt-auto">Demo öffnen</a>
    </div>
  </div>

  <!-- AutoHash Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🌀 AutoHash</h5>
      <p class="card-text">Automatisch generierte Hashwerte beim Einfügen.</p>
      <a href="demo_autohash.php" class="btn btn-sm btn-outline-dark mt-auto">Demo öffnen</a>
    </div>
  </div>

<!-- Benutzer erzeugen Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">👥 Benutzer erzeugen</h5>
    <p class="card-text">Erzeuge 500 Fake-Benutzer mit AutoFields und Verschlüsselung.</p>
    <a href="demo_generate_users.php" class="btn btn-sm btn-outline-secondary mt-auto">Demo öffnen</a>
  </div>
</div>


<!-- Tabelle exportieren Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">📤 Tabelle exportieren</h5>
    <p class="card-text">Exportiere eine JsonSQL-Tabelle inklusive Systemdaten als JSON-Datei.</p>
    <a href="demo_export_table.php" class="btn btn-sm btn-outline-secondary mt-auto">Demo öffnen</a>
  </div>
</div>



  <!-- Kategorien-TreeView Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🌲 Kategorien & Artikel</h5>
      <p class="card-text">Kategoriebasierte Artikelanzeige mit TreeView und Produktzähler.</p>
      <a href="simple-test-02.php" class="btn btn-sm btn-outline-secondary mt-auto">Demo öffnen</a>
    </div>
  </div>

  <!-- API Demo -->
  <div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">🌐 API-Demo</h5>
      <p class="card-text">JsonSQL über eine REST-API per GET, POST, PUT und DELETE ansprechen.</p>
      <a href="api-demo.php" class="btn btn-sm btn-outline-info mt-auto">Demo öffnen</a>
    </div>
  </div>  

<!-- WHERE IN Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🎯 WHERE IN</h5>
    <p class="card-text">Filtert Einträge basierend auf mehreren IDs (z. B. ?ids=1,2,3).</p>
    <a href="demo_where_in.php" class="btn btn-sm btn-outline-info mt-auto">Demo öffnen</a>
  </div>
</div>  

<!-- JsonSQL Join Demo: Kunden und Bestellungen -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🔗 JsonSQL Join Demo: Kunden und Bestellungen</h5>
    <p class="card-text">Veranschaulicht, einfache <strong>Joins</strong> und <strong>Gruppierungen.</strong></p>
    <a href="demo_simple_join.php" class="btn btn-sm btn-outline-info mt-auto">Demo öffnen</a>
  </div>
</div>

<!-- JsonSQL SetTable Demo: Tabelle mit Auto-Feldern -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">⚙️ JsonSQL SetTable Demo</h5>
    <p class="card-text">Zeigt, wie man <strong>Tabellen mit automatisch definierten Feldern</strong> erstellt und bearbeitet.</p>
    <a href="demo_settable.php" class="btn btn-sm btn-outline-info mt-auto">Demo öffnen</a>
  </div>
</div>

<!-- JsonSQL AutoFields CRUD Demo: Tabelle mit Auto-Feldern -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🎨 JsonSQL AutoFields CRUD Demo</h5>
    <p class="card-text">Verwalte Farbverläufe und andere Felder mit CRUD-Operationen und automatischen Feldern wie <strong>Autoincrement</strong>, <strong>AutoCreated</strong>, <strong>AutoUpdated</strong> in einer Tabelle.</p>
    
    <!-- Farbpalette für visuelle Darstellung -->
    <div class="d-flex justify-content-between mb-3">
      <div class="color-box" style="background-color: #FF7E5F;"></div>
      <div class="color-box" style="background-color: #D4A5A5;"></div>
      <div class="color-box" style="background-color: #00B4D8;"></div>
      <div class="color-box" style="background-color: #90E0EF;"></div>
    </div>

    <a href="demo_autofields.php" class="btn btn-sm btn-outline-info mt-auto">Demo öffnen</a>
  </div>
</div>

<!-- JsonSQL Performance Test: Stresstest-Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🚀 JsonSQL Performance Test</h5>
    <p class="card-text">Simuliert viele gleichzeitige Anfragen, um die <strong>Leistung der JsonSQL-Datenbank</strong> zu testen.</p>
    <a href="demo_performance.php" class="btn btn-sm btn-outline-primary mt-auto">Test starten</a>
  </div>
</div>

<!-- Aggregat- und Statistik-Demo -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">📊 Aggregat & Filter</h5>
    <p class="card-text">
      Demo mit dynamischer Datengenerierung, Filtern & umfangreicher Statistik pro Spalte – inklusive Sterne-Rating.
    </p>
    <a href="demo_aggregat.php" class="btn btn-sm btn-outline-success mt-auto">
      Zur Statistik-Demo
    </a>
  </div>
</div>


<!-- Demo Card für dynamische Systemfeld-Verwaltung -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">⚙️ Dynamische Systemfeld-Verwaltung</h5>
    <p class="card-text">Füge, aktualisiere oder entferne Systemfelder für deine Datenbank mit einer benutzerfreundlichen Oberfläche.</p>
    <a href="demo_system.php" class="btn btn-sm btn-outline-success mt-auto">Demo öffnen</a>
  </div>
</div>

<!-- Demo Card für erweiterte Systemfeld-Verwaltung -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🔧 Erweiterte Systemfeld-Verwaltung</h5>
    <p class="card-text">Verwalte Systemfelder mit erweiterten Funktionen wie Validierungen und ENUM-Werten. Ideal für komplexe Datenstrukturen.</p>
    <a href="demo_system_ext.php" class="btn btn-sm btn-outline-warning mt-auto">Demo öffnen</a>
  </div>
</div>


<!-- CarDB Demo : für erweiterte Systemfeld-Verwaltung -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🚗 Car Database Demo</h5>

    <!-- Bild nach dem Titel -->
    <img src="<?= dirname($_SERVER['SCRIPT_NAME']) . '/images/CarDB/banner.webp'; ?>" alt="Bildbeschreibung" class="img-fluid mb-3">

    <p class="card-text">Verwalte Systemfelder mit erweiterten Funktionen wie Validierungen und ENUM-Werten. Ideal für komplexe Datenstrukturen.</p>
    <a href="demo_cars_db.php" class="btn btn-sm btn-outline-danger mt-auto">Demo öffnen</a>
  </div>
</div>

<!-- DateTime Demo : für erweiterte Systemfeld-Verwaltung -->
<div class="demo-card card shadow-sm rounded-4 p-3" style="width: 300px;">
  <div class="card-body d-flex flex-column">
    <h5 class="card-title">🗓️ DateTime Demo.</h5>

    <!-- Bild nach dem Titel -->
    <img src="<?= dirname($_SERVER['SCRIPT_NAME']) . '/images/DateTimeDemo/banner.webp'; ?>" alt="Bildbeschreibung" class="img-fluid mb-3">

    <p class="card-text">Verwalte DateTime, Date, Time und Timestamp Felder. Ideal für Zeitstempel und Datumsoperationen.</p>
    <a href="demo_datetime.php" class="btn btn-sm btn-outline-danger mt-auto">Demo öffnen</a>
  </div>
</div>









<!-- Stil für Farbpalette -->
<style>
  .color-box {
    width: 45px;
    height: 45px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
</style>




</div>





  <!-- Separator -->
  <hr class="soft-shadow-separator">

  <!-- Footer-Link oder Zusatz -->
  <p class="text-center mt-5 text-muted small">
    Neue Demos sind jederzeit willkommen! Diese Seite wird regelmäßig erweitert.
  </p>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const filterButtons = document.querySelectorAll(".filter-btn");
  const cards = document.querySelectorAll(".demo-card");

  let activeFilter = "all";

  // Suche
  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    filterCards(query, activeFilter);
  });

  // Filter
  filterButtons.forEach(button => {
    button.addEventListener("click", function () {
      filterButtons.forEach(btn => btn.classList.remove("active"));
      this.classList.add("active");
      activeFilter = this.getAttribute("data-filter");
      filterCards(searchInput.value.toLowerCase(), activeFilter);
    });
  });

  function filterCards(query, filter) {
    cards.forEach(card => {
      const text = card.innerText.toLowerCase();
      const tags = card.getAttribute("data-tags");

      const matchesSearch = text.includes(query);
      const matchesFilter = filter === "all" || (tags && tags.includes(filter));

      if (matchesSearch && matchesFilter) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }
});
</script>

<style>
.demo-card {
  transition: all 0.2s ease;
}
.filter-btn.active {
  background-color: #0d6efd;
  color: white;
}
</style>




<?php require_once __DIR__ . '/../includes/footer.php'; ?>

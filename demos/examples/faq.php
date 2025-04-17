<?php
$pageTitle = "JsonSQL – FAQ";
require_once __DIR__ . '/../includes/header.php';
?>

<style>




.accordion-body {
  background: linear-gradient(to bottom, #ffffff,rgba(194, 194, 194, 0.23));
  margin-bottom: 8px;
}

.accordion-item {
  border: 0 !important;
}

.accordion-header {
  border: 1px solid rgba(194, 194, 194, 0.53);
  margin-top: 4px;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;  
}

</style>


<div class="container py-4">
  <h1 class="mb-3"><i class="bi bi-question-circle me-2 text-primary"></i>Häufig gestellte Fragen (FAQ)</h1>

  <div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i> Hier findest du Antworten auf häufige Fragen rund um <strong>JsonSQL</strong>. Nutze die Suche oder klappe alle Fragen auf.
  </div>

  <div class="d-flex justify-content-between flex-wrap align-items-center mb-3">
    <input type="text" id="faqSearch" class="form-control me-2 mb-2" placeholder="🔍 FAQ durchsuchen..." style="max-width: 300px;">

    <div class="mb-2">
      <button class="btn btn-outline-secondary btn-sm me-2" onclick="toggleAll(true)">
        <i class="bi bi-arrows-expand me-1"></i> Alle öffnen
      </button>
      <button class="btn btn-outline-secondary btn-sm" onclick="toggleAll(false)">
        <i class="bi bi-arrows-collapse me-1"></i> Alle schließen
      </button>
    </div>

  </div>




<div class="accordion mt-3" id="faqAccordion" data-category="<?= htmlspecialchars($category) ?>">

<?php
$faqs = [
  ["Was ist JsonSQL?", "JsonSQL ist eine PHP-Bibliothek, mit der du SQL-ähnliche Abfragen auf JSON-Dateien durchführen kannst – ganz ohne Datenbank.", "Allgemein"],
  ["Wie funktioniert JsonSQL?", "Die Klasse arbeitet direkt mit JSON-Dateien, die wie Datenbank-Tabellen behandelt werden. SQL-ähnliche Funktionen wie <code>select</code>, <code>where</code>, <code>join</code> oder <code>orderBy</code> stehen zur Verfügung.", "Allgemein"],
  ["Welche Vorteile bietet JsonSQL?", "JsonSQL ist ideal für kleine Projekte, portable Datenbanken, einfache Admin-Tools oder embedded Anwendungen, bei denen keine relationale DB nötig ist.", "Allgemein"],
  ["Wo werden meine Daten gespeichert?", "In einfachen JSON-Dateien im angegebenen Datenbankverzeichnis. Jede Tabelle entspricht einer Datei (z. B. <code>users.json</code>).", "Speicherung"],
  ["Wie sichere ich den Zugriff?", "Da es sich um reine Files handelt, sollte das Verzeichnis durch serverseitige Zugriffsrechte (z. B. .htaccess oder Permission) geschützt sein.", "Sicherheit"],
  ["Wie aktiviere ich Autoincrement für ein Feld?", "Nutze <code>addAutoincrementField('id')</code>. Bei jedem <code>insert()</code> wird automatisch eine eindeutige ID vergeben.", "Auto-Felder"],
  ["Wie erhalte ich die zuletzt vergebene Autoincrement-ID?", "Mit <code>getLastInsertId()</code> erhältst du die letzte vergebene ID direkt nach einem Insert.", "Auto-Felder"],
  ["Wie aktiviere ich Verschlüsselung für ein Feld?", "Mit <code>addEncryptedField('password')</code>. Die Daten werden dann per AES verschlüsselt gespeichert.", "Sicherheit"],
  ["Wie kann ich prüfen, ob ein Feld verschlüsselt ist?", "Nutze <code>isEncryptedField('password')</code>, um das zu ermitteln.", "Sicherheit"],
  ["Wie entferne ich ein verschlüsseltes Feld?", "Nutze <code>removeEncryptedField('feldname')</code> – die system.json wird entsprechend angepasst.", "Sicherheit"],
  ["Welche Felder können automatisch gehasht werden?", "Du kannst ein Feld mit <code>addAutoHashField('hash', 'sha256')</code> konfigurieren, das dann automatisch aus anderen Daten berechnet wird.", "Auto-Felder"],
  ["Was ist der Unterschied zwischen autoincrement und autohash?", "<code>autoincrement</code> erzeugt eine laufende ID, <code>autohash</code> generiert einen Hash basierend auf dem Datensatz.", "Auto-Felder"],
  ["Wie kann ich Tabellen leeren?", "Nutze <code>truncate('tabelle')</code>. Dabei wird die Datei neu geschrieben und ggf. angelegt.", "Tabellenverwaltung"],
  ["Wie kann ich die JSON-Datei direkt ansehen?", "Mit <code>getRawTableData()</code> erhältst du den Original-JSON-Inhalt der Tabelle als String.", "Debugging"],
  ["Wo liegt die system.json?", "Seit Version 2 liegt sie pro Tabelle vor: z. B. <code>users.system.json</code> im gleichen Verzeichnis wie <code>users.json</code>.", "Systemstruktur"],
  ["Kann ich system.json manuell ändern?", "Ja, aber es wird empfohlen, das über die Klassenmethoden wie <code>addEncryptedField</code> oder <code>addAutoincrementField</code> zu tun.", "Systemstruktur"],
  ["Wie funktioniert JOIN in JsonSQL?", "JsonSQL unterstützt <code>INNER</code>, <code>LEFT</code>, <code>RIGHT</code> und <code>FULL OUTER JOIN</code>. Beispiel: <code>->join('kunden', 'id', 'LEFT')</code>.", "Abfragen & JOINs"],
  ["Wie kann ich Einträge mit Bedingungen abfragen?", "Mit <code>where([['field', 'like', '%suchwort%']])</code> kannst du flexible Bedingungen definieren.", "Abfragen & JOINs"],
  ["Wie sieht ein Demo-Setup aus?", "Siehe unsere Bootstrap-basierten Demos: Artikelverwaltung, Passwortdemo, Autoincrement und Hashing.", "Einstieg & Demo"],
  ["Kann JsonSQL auch mit Sessions oder Logins genutzt werden?", "Ja, z. B. für Adminpanels, Userlogins oder konfigurierbare Tools im Backend.", "Einsatzmöglichkeiten"],
  ["Wie lade ich mehrere Tabellen gleichzeitig?", "Immer eine Tabelle pro Abfrage mit <code>from('tabelle')</code>. Für JOINs kannst du weitere Tabellen verbinden.", "Abfragen & JOINs"],
  ["Wie kann ich bestimmte Spalten filtern?", "Nutze <code>select(['id', 'name'])</code> um nur bestimmte Felder zurückzugeben.", "Abfragen & JOINs"],
  ["Ist JsonSQL thread-sicher?", "Ja, dank <code>flock()</code> werden Datei-Zugriffe exklusiv gelockt, um Konflikte bei gleichzeitigen Zugriffen zu verhindern.", "Sicherheit"],
  ["Wie viele Datensätze sind praktikabel?", "Für einfache Anwendungen bis einige Tausend Datensätze pro Tabelle kein Problem. Größere Datenmengen solltest du testen.", "Leistung"],
  ["Kann ich die JSON-Dateien exportieren?", "Ja, sie sind direkt nutzbar (z. B. für Backups, Syncs oder API-Ausgaben). Auch CSV/Excel-Exports lassen sich leicht ableiten.", "Export & Austausch"],
  ["Was passiert bei Update/Delete?", "Änderungen erfolgen im Speicher, dann wird die Datei neu geschrieben. Es gibt keine Transaktionen.", "Dateiverhalten"],
  ["Wie kann ich eigene Felder automatisch generieren lassen?", "Nutze die Felddefinitionen in <code>tabelle.system.json</code> mit <code>autoincrement</code>, <code>autohash</code> oder <code>encrypt</code>.", "Auto-Felder"],
  ["Wie starte ich ein neues Projekt?", "Erstelle ein Verzeichnis, definiere deine Tabellen (z. B. <code>products.json</code>) und binde JsonSQL ein. Demos helfen beim Einstieg.", "Einstieg & Demo"],
  ["Gibt es einen Debug-Modus?", "Du kannst jederzeit mit <code>getRawTableData()</code> oder <code>var_dump()</code> debuggen. Ein erweiterter Debug-Modus ist in Planung.", "Debugging"],
];


foreach ($faqs as $index => [$question, $answer, $category]) {
  $safeId = "faq$index";
  echo <<<HTML
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading$safeId">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse$safeId" aria-expanded="false" aria-controls="collapse$safeId">
          <i class="bi bi-question-circle me-2 text-primary"></i> $question
          <span class="badge bg-info text-dark ms-2">$category</span>
        </button>
      </h2>
      <div id="collapse$safeId" class="accordion-collapse collapse" aria-labelledby="heading$safeId" data-bs-parent="#faqAccordion">
        <div class="accordion-body">$answer</div>
      </div>
    </div>
  HTML;
}
?>

  </div>
</div>

<script>
document.getElementById('faqSearch').addEventListener('input', function () {
  const query = this.value.toLowerCase();
  const items = document.querySelectorAll('#faqAccordion .accordion-item');
  items.forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(query) ? '' : 'none';
  });
});

function toggleAll(open) {
  const buttons = document.querySelectorAll('#faqAccordion .accordion-button');
  buttons.forEach(btn => {
    const target = document.querySelector(btn.dataset.bsTarget);
    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(target);
    open ? bsCollapse.show() : bsCollapse.hide();
  });
}
</script>

<script>
document.getElementById('categoryFilter').addEventListener('change', function () {
  const selectedCategory = this.value;
  const items = document.querySelectorAll('#faqAccordion .accordion-item');
  items.forEach(item => {
    const itemCategory = item.getAttribute('data-category');
    if (selectedCategory === 'all' || itemCategory === selectedCategory) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = "JsonSQL – FAQ";
require_once __DIR__ . '/../includes/header.php';
?>




<div class="container py-4">
  <h1 class="mb-4">❓ Häufig gestellte Fragen (FAQ)</h1>
  <p class="text-muted">Hier findest du Antworten auf häufig gestellte Fragen rund um JsonSQL.</p>





  <div class="accordion mt-4" id="faqAccordion">

<?php
$faqs = [
  ["Was ist JsonSQL?", "JsonSQL ist eine PHP-Bibliothek, mit der du SQL-ähnliche Abfragen auf JSON-Dateien durchführen kannst – ganz ohne Datenbank.",],
  ["Wie funktioniert JsonSQL?", "Die Klasse arbeitet direkt mit JSON-Dateien, die wie Datenbank-Tabellen behandelt werden. SQL-ähnliche Funktionen wie <code>select</code>, <code>where</code>, <code>join</code> oder <code>orderBy</code> stehen zur Verfügung.",],
  ["Welche Vorteile bietet JsonSQL?", "JsonSQL ist ideal für kleine Projekte, portable Datenbanken, einfache Admin-Tools oder embedded Anwendungen, bei denen keine relationale DB nötig ist.",],
  ["Wo werden meine Daten gespeichert?", "In einfachen JSON-Dateien im angegebenen Datenbankverzeichnis. Jede Tabelle entspricht einer Datei (z. B. <code>users.json</code>).",],
  ["Wie sichere ich den Zugriff?", "Da es sich um reine Files handelt, sollte das Verzeichnis durch serverseitige Zugriffsrechte (z. B. .htaccess oder Permission) geschützt sein.",],
  ["Wie aktiviere ich Autoincrement für ein Feld?", "Nutze <code>addAutoincrementField('id')</code>. Bei jedem <code>insert()</code> wird automatisch eine eindeutige ID vergeben.",],
  ["Wie erhalte ich die zuletzt vergebene Autoincrement-ID?", "Mit <code>getLastInsertId()</code> erhältst du die letzte vergebene ID direkt nach einem Insert.",],
  ["Wie aktiviere ich Verschlüsselung für ein Feld?", "Mit <code>addEncryptedField('password')</code>. Die Daten werden dann per AES verschlüsselt gespeichert.",],
  ["Wie kann ich prüfen, ob ein Feld verschlüsselt ist?", "Nutze <code>isEncryptedField('password')</code>, um das zu ermitteln.",],
  ["Wie entferne ich ein verschlüsseltes Feld?", "Nutze <code>removeEncryptedField('feldname')</code> – die system.json wird entsprechend angepasst.",],
  ["Welche Felder können automatisch gehasht werden?", "Du kannst ein Feld mit <code>addAutoHashField('hash', 'sha256')</code> konfigurieren, das dann automatisch aus anderen Daten berechnet wird.",],
  ["Was ist der Unterschied zwischen autoincrement und autohash?", "<code>autoincrement</code> erzeugt eine laufende ID, <code>autohash</code> generiert einen Hash basierend auf dem Datensatz.",],
  ["Wie kann ich Tabellen leeren?", "Nutze <code>truncate('tabelle')</code>. Dabei wird die Datei neu geschrieben und ggf. angelegt.",],
  ["Wie kann ich die JSON-Datei direkt ansehen?", "Mit <code>getRawTableData()</code> erhältst du den Original-JSON-Inhalt der Tabelle als String.",],
  ["Wo liegt die system.json?", "Seit Version 2 liegt sie pro Tabelle vor: z. B. <code>users.system.json</code> im gleichen Verzeichnis wie <code>users.json</code>.",],
  ["Kann ich system.json manuell ändern?", "Ja, aber es wird empfohlen, das über die Klassenmethoden wie <code>addEncryptedField</code> oder <code>addAutoincrementField</code> zu tun.",],
  ["Wie funktioniert JOIN in JsonSQL?", "JsonSQL unterstützt <code>INNER</code>, <code>LEFT</code>, <code>RIGHT</code> und <code>FULL OUTER JOIN</code>. Beispiel: <code>->join('kunden', 'id', 'LEFT')</code>.",],
  ["Wie kann ich Einträge mit Bedingungen abfragen?", "Mit <code>where([['field', 'like', '%suchwort%']])</code> kannst du flexible Bedingungen definieren.",],
  ["Wie sieht ein Demo-Setup aus?", "Siehe unsere Bootstrap-basierten Demos: Artikelverwaltung, Passwortdemo, Autoincrement und Hashing.",],
  ["Kann JsonSQL auch mit Sessions oder Logins genutzt werden?", "Ja, z. B. für Adminpanels, Userlogins oder konfigurierbare Tools im Backend.",],
  ["Wie lade ich mehrere Tabellen gleichzeitig?", "Immer eine Tabelle pro Abfrage mit <code>from('tabelle')</code>. Für JOINs kannst du weitere Tabellen verbinden.",],
  ["Wie kann ich bestimmte Spalten filtern?", "Nutze <code>select(['id', 'name'])</code> um nur bestimmte Felder zurückzugeben.",],
  ["Ist JsonSQL thread-sicher?", "Ja, dank <code>flock()</code> werden Datei-Zugriffe exklusiv gelockt, um Konflikte bei gleichzeitigen Zugriffen zu verhindern.",],
  ["Wie viele Datensätze sind praktikabel?", "Für einfache Anwendungen bis einige Tausend Datensätze pro Tabelle kein Problem. Größere Datenmengen solltest du testen.",],
  ["Kann ich die JSON-Dateien exportieren?", "Ja, sie sind direkt nutzbar (z. B. für Backups, Syncs oder API-Ausgaben). Auch CSV/Excel-Exports lassen sich leicht ableiten.",],
  ["Was passiert bei Update/Delete?", "Änderungen erfolgen im Speicher, dann wird die Datei neu geschrieben. Es gibt keine Transaktionen.",],
  ["Wie kann ich eigene Felder automatisch generieren lassen?", "Nutze die Felddefinitionen in <code>tabelle.system.json</code> mit <code>autoincrement</code>, <code>autohash</code> oder <code>encrypt</code>.",],
  ["Wie starte ich ein neues Projekt?", "Erstelle ein Verzeichnis, definiere deine Tabellen (z. B. <code>products.json</code>) und binde JsonSQL ein. Demos helfen beim Einstieg.",],
  ["Gibt es einen Debug-Modus?", "Du kannst jederzeit mit <code>getRawTableData()</code> oder <code>var_dump()</code> debuggen. Ein erweiterter Debug-Modus ist in Planung.",],
];

foreach ($faqs as $index => [$question, $answer]) {
  echo <<<HTML
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading$index">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse$index" aria-expanded="false" aria-controls="collapse$index">
          $question
        </button>
      </h2>
      <div id="collapse$index" class="accordion-collapse collapse" aria-labelledby="heading$index" data-bs-parent="#faqAccordion">
        <div class="accordion-body">$answer</div>
      </div>
    </div>
  HTML;
}
?>

  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
$pageTitle = "JsonSQL Join Demo: Kunden und Bestellungen";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;


// Datenbank und Tabellen definieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$kundenTabelle = 'kunden';
$bestellungenTabelle = 'bestellungen';

// 1. Tabellen leeren oder anlegen
$db->truncate($kundenTabelle);
$db->truncate($bestellungenTabelle);

// 2. Beispieldaten für Kunden und Bestellungen einfügen
$kunden = [
    ['id' => 1, 'name' => 'Max Mustermann', 'email' => 'max@example.com'],
    ['id' => 2, 'name' => 'Erika Musterfrau', 'email' => 'erika@example.com'],
    ['id' => 3, 'name' => 'John Doe', 'email' => 'john@example.com'],
];

$bestellungen = [
    ['customer_id' => 1, 'product' => 'Laptop', 'price' => 999.99],
    ['customer_id' => 1, 'product' => 'Smartphone', 'price' => 499.99],
    ['customer_id' => 2, 'product' => 'Tablet', 'price' => 299.99],
    ['customer_id' => 3, 'product' => 'Monitor', 'price' => 199.99],
];
?>

<!-- Exclude Begin -->
<div class="alert alert-secondary mt-3">
  <h5 class="mb-2">🧠 Was zeigt diese Demo?</h5>
  <p>
    Diese Demo zeigt, wie du mit der <strong>JsonSQL</strong>-Bibliothek eine einfache, aber leistungsstarke <strong>Join-Operation</strong> zwischen zwei Tabellen (Kunden und Bestellungen) durchführen kannst – ganz ohne eine klassische SQL-Datenbank!
  </p>
  <ul>
    <li>📂 Es wird ein <strong>JOIN</strong> zwischen der <strong>„kunden“</strong>-Tabelle und der <strong>„bestellungen“</strong>-Tabelle durchgeführt, um Kunden mit ihren Bestellungen zu verknüpfen.</li>
    <li>📦 <strong>GROUP BY</strong> ermöglicht es, Bestellungen nach Kunden zu gruppieren, sodass für jeden Kunden alle Bestellungen angezeigt werden.</li>
    <li>✨ <strong>JsonSQL</strong> verwendet JSON-Daten und ermöglicht so SQL-ähnliche Operationen wie JOIN und GROUP BY in reinem JavaScript-Objektformat ohne klassische Datenbank.</li>
  </ul>
  <p class="mb-0">
    Hier werden keine relationalen Datenbanktechniken wie bei MySQL verwendet. Stattdessen wird mit einfachen JSON-Daten gearbeitet, was den Code flexibler und leichter verständlich macht. Dank der JSON-Logik von <code>JsonSQL</code> kannst du jedoch auch mit einfachen Mitteln relativ komplexe Datenabfragen durchführen, die du von traditionellen SQL-Datenbanken gewohnt bist.
  </p>
  
  <h5 class="mt-3">📊 Joins und Gruppierungen in JSON-Datenbanken</h5>
  <p>
    In klassischen relationalen Datenbanken wie MySQL wird der <strong>JOIN</strong> verwendet, um Daten aus mehreren Tabellen zu kombinieren, basierend auf einem gemeinsamen Schlüssel. Dies ist in <strong>JsonSQL</strong> auf ähnliche Weise möglich, obwohl wir keine echten relationalen Datenbanken haben. Stattdessen arbeiten wir mit JSON-Dateien, die als Datenbank fungieren.
  </p>
  
  <h6>So funktioniert der JOIN in JsonSQL:</h6>
  <p>
    - In einer traditionellen SQL-Datenbank wird der <code>JOIN</code> direkt vom Datenbankserver verarbeitet und die verknüpften Daten als Resultat einer einzigen Anfrage zurückgegeben. In JsonSQL hingegen müssen wir die Daten manuell laden und die Verknüpfungen selbst auf Basis der Schlüssel (z.B. `customer_id`) durchführen. Die JOIN-Logik in JsonSQL funktioniert also ähnlich, aber wir müssen uns der Daten als Arrays und Objekte annehmen und diese miteinander kombinieren.
  </p>
  <p>
    - Das bedeutet, dass bei einem <strong>RIGHT JOIN</strong> zum Beispiel alle Bestellungen angezeigt werden, auch wenn ein Kunde nicht existiert (also z.B. Bestellungen ohne zugehörigen Kunden). Dies geschieht durch das Manuelle Abgleichen und Zusammenführen der Daten aus den verschiedenen Tabellen-Arrays.
  </p>
  
  <h6>Und wie funktioniert das Gruppieren?</h6>
  <p>
    - In JsonSQL wird das Gruppieren von Daten mithilfe der <strong>groupBy</strong>-Methode durchgeführt. Diese Methode funktioniert ähnlich wie das klassische SQL <code>GROUP BY</code>, gruppiert jedoch die Daten, nachdem sie in ein Array geladen wurden. Die Daten werden anhand eines bestimmten Schlüssels (z.B. `customer_id`) zusammengefasst, sodass wir für jeden Kunden alle zugehörigen Bestellungen sehen können.
  </p>
  
  <h6>Warum ist das in JSON-Datenbanken möglich?</h6>
  <p>
    - Bei klassischen SQL-Datenbanken handelt es sich um vollwertige Systeme, die Abfragen in einer festen Struktur ausführen, um Relationen und Joins zu ermöglichen. JSON-basierte Datenbanken wie <strong>JsonSQL</strong> hingegen bieten eine flexiblere Handhabung von Daten, da sie keine fest definierte Struktur wie bei relationalen Datenbanken haben.
  </p>
  <p>
    - Der Hauptunterschied liegt also in der Speicherung und der Verarbeitung. Bei JSON-Datenbanken müssen wir die Struktur und Beziehungen manuell aufbauen, was jedoch auch Flexibilität bei der Gestaltung der Datenstruktur erlaubt. Gleichzeitig stellt dies sicher, dass wir mit einfachen Mitteln sehr komplexe Datenmanipulationen wie Joins und Gruppierungen durchführen können.
  </p>

  <p class="mb-0">
    Du kannst dieses Prinzip mit <strong>JsonSQL</strong> in deinen eigenen Projekten verwenden, sei es für einfache Datenbankoperationen oder komplexere Abfragen, bei denen du JSON als Datenquelle nutzt. Die Flexibilität von JSON ermöglicht dir dabei ein einfaches, aber mächtiges Werkzeug, um auch ohne relationales Datenbanksystem effizient mit Daten zu arbeiten.
  </p>
</div>
<!-- Exclude End -->


<h2>🛒 Neue Kunden und Bestellungen eingetragen:</h2>
<ul class='list-group'>

<?php

// Kunden einfügen
foreach ($kunden as $kunde) {
    $db->from($kundenTabelle)->insert($kunde);
    echo "<li class='list-group-item'>✅ Kunde {$kunde['name']} wurde gespeichert.</li>";
}

// Bestellungen einfügen
foreach ($bestellungen as $bestellung) {
    $db->from($bestellungenTabelle)->insert($bestellung);
    echo "<li class='list-group-item'>✅ Bestellung für Kunde {$bestellung['customer_id']} wurde gespeichert: {$bestellung['product']} – {$bestellung['price']} €</li>";
}

echo "</ul>";

// 3. JOIN zwischen 'kunden' und 'bestellungen' durchführen
$joinResult = $db->from($kundenTabelle)
    ->select('*') // Alle Felder der Kunden auswählen
    ->join($bestellungenTabelle, ['local' => 'id', 'foreign' => 'customer_id'], 'RIGHT') // JOIN mit Bestellungen
    ->get();

// 4. Gruppierung der Daten nach 'customer_id' anwenden (mittels groupBy)
$groupedData = $db->groupBy(['customer_id'])->get(); // Gruppierung nach 'customer_id'

// FancyDumpVar Ausgabe der Daten
$debugger->dump($joinResult, $db, $groupedData);

// 5. Ergebnisse der gruppierten Daten anzeigen
echo "<h3 class='mt-5'>📦 Aktuelle Bestellungen und Kunden:</h3>";
echo "<ul class='list-group'>";
foreach ($groupedData as $customerId => $customerData) {
    echo "<li class='list-group-item'>";
    echo "<strong>Kunde: {$customerData[0]['name']} (Email: {$customerData[0]['email']})</strong><br>";
    echo "<ul>";
    foreach ($customerData as $bestellung) {
        echo "<li>Bestellung: {$bestellung['product']} – {$bestellung['price']} €</li>";
    }
    echo "</ul>";
    echo "</li>";
}
echo "</ul>";



// 6. Quellcode anzeigen (ohne den Bereich zwischen den Exclude-Tags)
$scriptName = basename(__FILE__);

// Entferne die Exclude-Tags aus dem Quellcode
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
<!-- Exclude End -->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

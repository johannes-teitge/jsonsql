<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="aggregation"><i class="bi bi-bar-chart-fill"></i> Aggregation & Statistik</h1>

  <p>JsonSQL unterstützt eine Vielzahl an Aggregatfunktionen, mit denen du deine Daten gruppieren, zählen, zusammenfassen oder statistisch analysieren kannst. Diese Funktionen sind ideal für Auswertungen, Dashboards oder Reports.</p>

  <h2 id="groupby">📊 groupBy()</h2>
  <p>Gruppiert die Datensätze nach einem bestimmten Feld und erlaubt dir, Aggregatfunktionen innerhalb dieser Gruppen anzuwenden.</p>
  <pre><code class="language-php">
// Anzahl Nutzer pro Land
$db->from('users')
   ->groupBy('country')
   ->count();
  </code></pre>

  <h2 id="aggregatfunktionen">➕ Aggregatfunktionen</h2>
  <p>Folgende Funktionen stehen dir zur Verfügung:</p>
  <ul>
    <li><code>count()</code> – Anzahl der Einträge</li>
    <li><code>sum('feld')</code> – Summe eines Feldes</li>
    <li><code>avg('feld')</code> – Durchschnitt</li>
    <li><code>min('feld')</code>, <code>max('feld')</code></li>
    <li><code>median('feld')</code></li>
    <li><code>mode('feld')</code> – häufigster Wert</li>
    <li><code>stddev('feld')</code>, <code>variance('feld')</code></li>
    <li><code>range('feld')</code> – Spanne zwischen Min und Max</li>
  </ul>
  <pre><code class="language-php">
// Gesamtsumme aller Bestellungen
$total = $db->from('orders')->sum('amount');

// Durchschnittsalter aller Nutzer
$avg = $db->from('users')->avg('age');
  </code></pre>

  <h2 id="stats">📈 stats() – kombinierte Übersicht</h2>
  <p>Mit <code>stats('feld')</code> bekommst du alle wichtigen Kennzahlen in einem Aufruf:</p>
  <pre><code class="language-php">
$stats = $db->from('users')->stats('age');
/* Gibt zurück:
[
  'count' => 100,
  'sum' => 2300,
  'avg' => 23,
  'min' => 18,
  'max' => 65,
  'median' => 22,
  'mode' => 21,
  'stddev' => 4.2,
  'variance' => 17.64,
  'range' => 47
]
*/
  </code></pre>

  <p>Du kannst <code>stats()</code> auch mit <code>groupBy()</code> kombinieren:</p>
  <pre><code class="language-php">
// Statistik pro Land
$statistik = $db->from('users')->groupBy('country')->stats('age');
  </code></pre>

  <p class="mt-4">Mit diesen Tools kannst du deine Daten schnell analysieren – direkt aus JSON-Dateien heraus, ganz ohne klassische Datenbank.</p>
</section>
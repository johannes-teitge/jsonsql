<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="filter"><i class="bi bi-funnel-fill"></i> Filterlogik mit <code>where()</code></h1>

  <p>Mit <code>where()</code> kannst du gezielt Datensätze filtern. JsonSQL unterstützt eine Vielzahl an Operatoren, auch in Kombination. Du kannst einfache Vergleiche durchführen oder komplexe Bedingungen mit verschachtelten Feldern und Filtergruppen bauen.</p>

  <h2 id="filter-operatoren">🔣 Unterstützte Operatoren</h2>
  <p>Standardmäßig stehen dir diese Operatoren zur Verfügung:</p>
  <ul>
    <li><code>=</code> (gleich)</li>
    <li><code>!=</code> (ungleich)</li>
    <li><code>&gt;</code>, <code>&gt;=</code>, <code>&lt;</code>, <code>&lt;=</code></li>
    <li><code>in</code>, <code>not in</code> (Array-Vergleich)</li>
    <li><code>like</code> (enthält Teilstring, Case-insensitiv)</li>
    <li><code>between</code> (zwischen zwei Werten)</li>
  </ul>
  <pre><code class="language-php">
// Benutzer mit Alter > 30
$db->from('users')->where('age', '>', 30)->get();

// Benutzer mit Namen in Liste
$db->where('name', 'in', ['Alice', 'Bob']);

// Email enthält "gmail"
$db->where('email', 'like', 'gmail');
  </code></pre>

  <h2 id="filter-kombinationen">🔗 Kombinierte Bedingungen (and / or)</h2>
  <p>Mehrere Filter kannst du mit <code>andWhere()</code> oder <code>orWhere()</code> kombinieren:</p>
  <pre><code class="language-php">
$db->from('users')
   ->where('age', '>', 30)
   ->andWhere('status', '=', 'active')
   ->get();

$db->orWhere('name', '=', 'Alice')
   ->orWhere('name', '=', 'Bob');
  </code></pre>

  <h2 id="filter-nested">🪆 Nested-Felder und Pfadfilter</h2>
  <p>Du kannst auch auf verschachtelte Felder zugreifen, z. B. bei Objekten oder Arrays innerhalb eines Datensatzes:</p>
  <pre><code class="language-php">
// Greife auf verschachteltes Feld zu
$db->where('address.city', '=', 'Berlin');

// Oder in Arrays mit Index
$db->where('roles.0', '=', 'admin');
  </code></pre>

  <h2 id="filtergruppen">🧩 Filtergruppen</h2>
  <p>Du kannst auch mehrere Bedingungen gruppieren und so komplexere Logik umsetzen. Dafür nutzt du Arrays als Eingabe:</p>
  <pre><code class="language-php">
$db->where([
  ['status', '=', 'active'],
  ['age', '>', 30]
]);

$db->orWhere([
  ['country', '=', 'DE'],
  ['country', '=', 'AT']
]);
  </code></pre>

  <p class="mt-4">Die Filterlogik ist flexibel, performant und erweiterbar – ideal für komplexe Abfragen auf JSON-Basis. Weitere Filterfunktionen wie <code>groupBy()</code>, <code>orderBy()</code> und <code>limit()</code> werden in separaten Abschnitten behandelt.</p>
</section>

<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="joins"><i class="bi bi-link-45deg"></i> Joins & Beziehungen</h1>

  <p>JsonSQL unterstützt SQL-ähnliche Joins, um Daten aus mehreren Tabellen zu kombinieren. Dabei kannst du klassische <code>inner</code>, <code>left</code>, <code>right</code> oder <code>full</code> Joins nutzen – alles direkt auf JSON-Basis.</p>

  <h2 id="join-arten">🔗 join()-Logik</h2>
  <p>Die Methode <code>join()</code> erlaubt das Verknüpfen zweier Tabellen anhand gemeinsamer Felder:</p>
  <pre><code class="language-php">
$rows = $db->from('orders')
  ->join('users', 'user_id', '=', 'id', 'left')
  ->get();
  </code></pre>

  <p>Parameter:</p>
  <ul>
    <li><strong>Tabelle</strong>: Die zweite Tabelle, z. B. <code>users</code></li>
    <li><strong>Feld 1</strong>: Feld aus der Haupttabelle</li>
    <li><strong>Operator</strong>: Meist <code>=</code></li>
    <li><strong>Feld 2</strong>: Feld aus der zweiten Tabelle</li>
    <li><strong>Typ</strong>: <code>inner</code>, <code>left</code>, <code>right</code>, <code>full</code></li>
  </ul>

  <h2 id="join-nm">🔁 n:m-Beziehungen über Zwischentabellen</h2>
  <p>Für viele-zu-viele-Beziehungen empfiehlt sich eine Zwischentabelle:</p>
  <pre><code class="language-text">
Tabellen:
- products.json
- tags.json
- product_tags.json (Felder: product_id, tag_id)
  </code></pre>
  <p>Beispiel:</p>
  <pre><code class="language-php">
$db->from('product_tags')
   ->join('products', 'product_id', '=', 'id')
   ->join('tags', 'tag_id', '=', 'id')
   ->get();
  </code></pre>
  <p>So erhältst du kombinierte Informationen aus allen drei Tabellen.</p>

  <h2 id="join-best-practices">✅ Best Practices</h2>
  <ul>
    <li>Nutze eindeutige IDs für Relationen</li>
    <li>Halte Relationen schlank (keine riesigen JSON-Objekte einbetten)</li>
    <li>Vermeide Duplikate durch gute Datenmodellierung</li>
    <li>Trenne Stammdaten (z. B. Produkte) und Meta-Relationen (z. B. Kategorien)</li>
    <li>Nutze <code>pluck()</code> oder <code>groupBy()</code> für zusammengefasste Daten</li>
  </ul>

  <h2 id="join-demo">🚀 Live-Demo</h2>
  <ul>
    <li><a href="<?= $baseUrl ?>/../examples/demo_joins.php" target="_blank">Demo: Klassischer Join (orders + users)</a></li>
    <li><a href="<?= $baseUrl ?>/../examples/demo_nm_join.php" target="_blank">Demo: n:m (products + tags über mapping)</a></li>
  </ul>

  <p class="mt-4">Joins sind ein starkes Werkzeug, um deine JSON-Daten strukturiert zu verbinden – fast wie in echten relationalen Datenbanken.</p>
</section>

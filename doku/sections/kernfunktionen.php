<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="kernfunktionen"><i class="bi bi-gear-fill"></i> Kernfunktionen</h1>
  <p>In diesem Abschnitt dokumentieren wir alle Kernfunktionen der JsonSQL-Klasse im Detail. Jede Methode ist modular aufgebaut und kann mit oder ohne Tabellenbindung verwendet werden. Viele Funktionen lassen sich miteinander kombinieren und sind methodenverkettet einsetzbar.</p>

  <h2 id="insert">📥 insert()</h2>
  <p>Fügt einen oder mehrere Datensätze in die aktuell gewählte Tabelle ein. Die Felder werden bei Bedarf automatisch angelegt, sofern kein <code>system.json</code> verwendet wird.</p>
  <pre><code class="language-php">
$db->insert([
  'name' => 'Alice',
  'email' => 'alice@example.com'
]);

// Mehrere Einträge auf einmal
$db->insert([
  ['name' => 'Bob'],
  ['name' => 'Charlie']
]);
  </code></pre>
  <p>Wenn ein system.json existiert, können Felder automatisch ergänzt werden, z. B. <code>created_at</code>, <code>id</code> usw.</p>

  <h2 id="update">🛠️ update()</h2>
  <p>Ändert vorhandene Datensätze. Die Methode akzeptiert ein Array mit Änderungen sowie ein optionales <code>where()</code>-Kriterium.</p>
  <pre><code class="language-php">
$db->from('users')->where('id', '=', 1)->update([
  'email' => 'neu@example.com'
]);
  </code></pre>
  <p>Wenn <code>system.json</code> vorhanden ist, kann automatisch <code>updated_at</code> gesetzt werden.</p>

  <h2 id="delete">🗑️ delete()</h2>
  <p>Löscht Einträge aus der aktiven Tabelle. Standardmäßig direkt, optional mit Trash-Unterstützung (Papierkorb).</p>
  <pre><code class="language-php">
$db->from('users')->where('id', '=', 2)->delete();
  </code></pre>
  <p>Optional kann <code>enableTrashMode(true)</code> aktiviert werden.</p>

  <h2 id="select">🔎 select() / get()</h2>
  <p>Die zentrale Abfragefunktion von JsonSQL. Mit <code>get()</code> werden die ausgewählten Daten geladen. Die Methode <code>select()</code> kann optional verwendet werden, um bestimmte Felder zu holen:</p>
  <pre><code class="language-php">
$rows = $db->from('users')->select('id', 'name')->get();
  </code></pre>
  <p>Zusätzlich nutzbar: <code>where()</code>, <code>orderBy()</code>, <code>limit()</code>, <code>groupBy()</code> usw.</p>

  <h2 id="exists">❓ exists()</h2>
  <p>Prüft, ob ein bestimmter Datensatz existiert (true/false):</p>
  <pre><code class="language-php">
$exists = $db->from('users')->where('email', '=', 'bob@example.com')->exists();
  </code></pre>

  <h2 id="pluck">🎯 pluck()</h2>
  <p>Extrahiert eine einzelne Spalte oder ein einzelnes Feld aus mehreren Einträgen:</p>
  <pre><code class="language-php">
$emails = $db->from('users')->pluck('email');
  </code></pre>

  <h2 id="first">🥇 first()</h2>
  <p>Gibt den ersten passenden Eintrag zurück:</p>
  <pre><code class="language-php">
$user = $db->from('users')->where('name', '=', 'Alice')->first();
  </code></pre>

  <h2 id="clear">♻️ clear()</h2>
  <p>Leert eine Tabelle komplett (alle Einträge werden gelöscht):</p>
  <pre><code class="language-php">
$db->from('users')->clear();
  </code></pre>

  <h2 id="paginate">📄 paginate()</h2>
  <p>Teilt große Ergebnislisten in Seiten auf:</p>
  <pre><code class="language-php">
$page = 1;
$limit = 10;
$result = $db->from('users')->paginate($page, $limit);
  </code></pre>
  <p>Die Rückgabe enthält Einträge, Gesamtanzahl, Seitenanzahl und mehr.</p>
</section>

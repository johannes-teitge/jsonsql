<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="kernfunktionen"><i class="bi bi-gear-fill"></i> Kernfunktionen</h1>
  <p>In diesem Abschnitt dokumentieren wir alle Kernfunktionen der JsonSQL-Klasse im Detail. Jede Methode ist modular aufgebaut und kann mit oder ohne Tabellenbindung verwendet werden. Viele Funktionen lassen sich miteinander kombinieren und sind methodenverkettet einsetzbar.</p>



  <hr class='content-sep'>  
<!-- INSERT -->
<h2 id="insert">📥 insert()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Rückgabewert:</strong> <code>void</code> – kein Rückgabewert</li>
  <li><strong>Parameter:</strong> <code>array $records</code> – Einzelner oder mehrere Datensätze als Array</li>
</ul>
<p><strong>[Trait: <code>JS_CRUD</code>]</strong></p>

<p>
  Mit der Methode <code>insert()</code> lassen sich ein oder mehrere Datensätze in die aktuell gewählte Tabelle einfügen.
</p>

<p>
  Wenn <code>system.json</code> <strong>nicht vorhanden</strong> ist, übernimmt JsonSQL alle Felder automatisch
  – ohne Validierung oder feste Felddefinitionen.
</p>

<p>
  Wenn eine <code>system.json</code> vorhanden ist, richtet sich das Verhalten nach der Option
  <code>"allowAdditionalFields"</code>:
</p>

<ul>
  <li><strong><code>true</code></strong> – zusätzliche Felder werden beim Insert mitgespeichert</li>
  <li><strong><code>false</code></strong> (Standard) – nur Felder, die in <code>system.json</code> definiert sind, werden übernommen</li>
</ul>

<div class="alert alert-warning small mt-3">
  <strong>Wichtig:</strong> Auch wenn neue Felder in den Datensatz geschrieben werden, 
  werden sie <u>nicht automatisch</u> zur <code>system.json</code> hinzugefügt. 
  Die Systemtabelle muss manuell oder programmatisch erweitert werden, 
  wenn die neuen Felder dauerhaft verwendet oder typisiert werden sollen.
</div>

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

<p>
  Wird eine <code>system.json</code> verwendet, folgt der Insert-Vorgang definierten Regeln:
</p>

<ul>
  <li>Felder wie <code>id</code>, <code>uuid</code>, <code>created_at</code> usw. werden automatisch ergänzt.</li>
  <li>Datentypen wie <code>string</code>, <code>integer</code>, <code>float</code>, <code>enum</code> usw. werden validiert.</li>
  <li>Standardwerte, Min/Max-Prüfungen und Auto-Funktionen (z. B. AutoHash oder Autoincrement) werden angewendet.</li>
</ul>

<h5 class="mt-4">⚙️ Option: <code>allowAdditionalFields</code></h5>
<p>
  Die <code>system.json</code> kann steuern, ob beim Einfügen <strong>zusätzliche Felder erlaubt</strong> sind, die <em>nicht</em> in der Felddefinition vorkommen.
  Das wird über den Schalter <code>"allowAdditionalFields": true</code> geregelt.
</p>

<pre><code class="language-json">
{
  "allowAdditionalFields": true,
  "fields": {
    "name": { "dataType": "string" },
    "created_at": { "dataType": "datetime" }
  }
}
</code></pre>

<p>
  Ist diese Option aktiviert, dürfen beim Insert auch neue Felder wie z. B. <code>nickname</code> oder <code>info</code> mitgegeben werden.
  Wenn sie deaktiviert ist, werden nur Felder übernommen, die in <code>fields</code> explizit definiert sind – alle anderen werden ignoriert.
</p>

<h5 class="mt-4">🔍 Intern passiert Folgendes:</h5>
<ul>
  <li><code>applyAutoFields()</code> ergänzt alle Felder laut Systemdefinition.</li>
  <li><code>insertAdditionalFields()</code> prüft, ob weitere Felder erlaubt sind (via <code>allowAdditionalFields</code>).</li>
  <li>Der finale Datensatz wird validiert und am Ende der Tabelle gespeichert.</li>
</ul>

<p class="text-muted small">
  ➕ Tipp: Nutze die <code>system.json</code>, um deine Tabellenstruktur gezielt zu steuern und automatisch sichere, saubere Einträge zu gewährleisten.
</p>


<hr class='content-sep'>
<!-- UPDATE -->
<h2 id="update">🛠️ update()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Trait:</strong> <code>JS_CRUD</code></li>
  <li><strong>Rückgabewert:</strong> <code>int</code> – Anzahl der erfolgreich aktualisierten Datensätze</li>
  <li><strong>Parameter:</strong> <code>array $fieldsToUpdate</code> – Zu ändernde Feld/Wert-Paare</li>
</ul>

<p>
  Aktualisiert gezielt alle Datensätze in der aktuell gewählten Tabelle, die den
  gesetzten <code>where()</code>-Filtern entsprechen. Nur die gefilterten Datensätze
  werden angepasst – alle anderen bleiben unverändert.
</p>

<p><strong>Besonderheiten:</strong></p>
<ul>
  <li>Die Methode ist <strong>nicht destruktiv</strong>: Nur die übergebenen Felder werden verändert.</li>
  <li>Felder mit <code>"auto_modified_timestamp": true</code> in der <code>system.json</code> werden automatisch mit einem aktuellen Zeitstempel aktualisiert.</li>
  <li>Die Zeitformate und Zeitzonen dieser Felder sind individuell definierbar (z. B. <code>format</code>, <code>timezone</code>).</li>
  <li>Verwendet <code>flock()</code> zur sicheren Dateisperre beim Schreiben.</li>
</ul>

<pre><code class="language-php">
$affected = $db->from('produkte')
               ->where('kategorie', '=', 'Haushalt')
               ->update([
                   'status' => 'archiviert',
                   'sichtbar' => false
               ]);

echo "$affected Datensätze aktualisiert.";
</code></pre>

<p>
  Damit Felder automatisch mit dem aktuellen Datum/Zeit aktualisiert werden,
  muss in der <code>system.json</code> ein entsprechender Eintrag vorhanden sein:
</p>

<pre><code class="language-json">
{
  "fields": {
    "updated_at": {
      "dataType": "datetime",
      "auto_modified_timestamp": true,
      "format": "Y-m-d H:i:s",
      "timezone": "Europe/Berlin"
    }
  }
}
</code></pre>

<p>
  Diese Regelung gilt für beliebige Feldnamen. Du kannst mehrere Felder definieren,
  die bei jeder Änderung automatisch aktualisiert werden – mit jeweils eigenem Format und Zeitzone.
</p>

<p>
  Wird <code>where()</code> nicht gesetzt, werden <strong>alle Datensätze</strong> in der Tabelle aktualisiert (mit Vorsicht verwenden).
</p>


<hr class='content-sep'>
<!-- DELETE -->
<h2 id="delete">🗑️ delete()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Trait:</strong> <code>JS_CRUD</code></li>
  <li><strong>Rückgabewert:</strong> <code>int</code> – Anzahl gelöschter Datensätze</li>
</ul>

<p>
  Löscht alle Datensätze aus der aktuell gewählten Tabelle, die den gesetzten
  <code>where()</code>-Filtern entsprechen. Die Datei wird dabei exklusiv
  gesperrt, um gleichzeitige Schreibzugriffe zu verhindern.
</p>

<p><strong>Wichtig:</strong></p>
<ul>
  <li>Die Methode <code>from()</code> muss zuvor aufgerufen worden sein.</li>
  <li>Nur Datensätze, die exakt den Filterbedingungen entsprechen, werden entfernt.</li>
  <li>Die gesetzten Filter bleiben <strong>nach dem Löschen aktiv</strong> – diese müssen ggf. manuell zurückgesetzt werden.</li>
</ul>

<pre><code class="language-php">
// Einzelne Bedingung
$deleted = $db->from('produkte')
              ->where('preis', '<', 10)
              ->delete();

// Mehrere Bedingungen
$deleted = $db->from('kunden')
              ->where([
                  ['land', '=', 'DE'],
                  ['newsletter', '=', false]
              ])
              ->delete();

echo "$deleted Datensätze gelöscht.";
</code></pre>

<p>Die Methode gibt die Anzahl der erfolgreich gelöschten Datensätze zurück. Ein Wert von <code>0</code> bedeutet, dass kein Eintrag den Kriterien entsprach.</p>





  <hr class='content-sep'>  
<!-- SELECT -->  
<h2 id="select">🎯 select()</h2>
<p><strong>[Trait: <code>JS_Query</code>]</strong></p>

<p>Mit <code>select()</code> bestimmst du, welche Felder (Spalten) aus der aktuellen Tabelle bei der Abfrage zurückgegeben werden sollen. Diese Methode ist optional – wenn sie nicht verwendet wird oder mit <code>'*'</code> aufgerufen wird, werden alle Felder zurückgegeben.</p>

<p>Du kannst die Methode mit einem einzelnen String oder einem Array aufrufen. Aliasnamen sind möglich – z. B. <code>"preis AS cost"</code> – und helfen dir, Felder umzubenennen.</p>

<pre><code class="language-php">
// Gibt alle Felder zurück
$rows = $db->from('users')->select('*')->get();

// Nur bestimmte Felder
$rows = $db->from('users')->select('id, name')->get();

// Mit Aliasnamen
$rows = $db->from('products')->select('title AS Produktname, price AS Preis')->get();

// Array-Variante
$rows = $db->from('products')->select(['title AS name', 'price'])->get();
</code></pre>

<h5 class="mt-4">Besonderheiten:</h5>
<ul>
  <li>Mehrfaches Aufrufen von <code>select()</code> ist erlaubt – der letzte Aufruf überschreibt die vorherigen Einstellungen.</li>
  <li>Wird kein <code>select()</code> verwendet, ist das Verhalten wie bei <code>select('*')</code>.</li>
  <li>Aliasnamen (<code>AS</code>) werden automatisch erkannt (case-insensitive).</li>
  <li>Felder, die im Datensatz fehlen, werden mit <code>null</code> gefüllt.</li>
</ul>

<h5 class="mt-4">Internes Verhalten:</h5>
<ul>
  <li>Alle ausgewählten Felder werden in <code>$this-&gt;select</code> gespeichert.</li>
  <li>Die Zuordnung von Aliasnamen erfolgt über <code>$this-&gt;aliasMap</code>.</li>
  <li>Beim späteren Aufruf von <code>get()</code> wird die Auswahl über <code>applySelect()</code> umgesetzt.</li>
</ul>

<h5 class="mt-4">Beispielausgabe:</h5>
<pre><code class="language-json">
[
  {
    "Produktname": "Wasserkocher",
    "Preis": 39.99
  },
  {
    "Produktname": "Toaster",
    "Preis": 29.95
  }
]
</code></pre>

<p class="mt-4">🔁 Diese Auswahl beeinflusst die Ausgabe von <code>get()</code> und ist kombinierbar mit <code>where()</code>, <code>orderBy()</code>, <code>limit()</code>, <code>groupBy()</code> usw.</p>





<hr class='content-sep'>  
<!-- WHERE -->  
<h2 id="where">🔎 where()</h2>
<p><strong>[Trait: <code>JS_Query</code>]</strong></p>

<p>Die Methode <code>where()</code> filtert die Datensätze anhand definierter Bedingungen. Du kannst mehrere Bedingungen gleichzeitig prüfen und sie logisch mit <code>AND</code> oder <code>OR</code> verknüpfen (Standard: <code>OR</code>).</p>

<p>Jede Bedingung besteht aus einem Array mit <strong>drei Elementen</strong>: <code>[Feld, Operator, Wert]</code>. Alternativ kannst du eine Bedingung auch mit <code>'not'</code> negieren.</p>

<pre><code class="language-php">
// Einzelne Bedingung
$db->where([['vendor', '=', 'Aldi']]);

// Mehrere Bedingungen mit AND
$db->where([
  ['vendor', '=', 'Aldi'],
  ['rating', '>=', 4]
], 'AND');

// Negation mit NOT
$db->where([
  ['not', ['rating', '=', 2]],
  ['vendor', '=', 'Lidl']
], 'AND');

// IN-Filter
$db->where([
  ['vendor', 'in', ['Aldi', 'Lidl']],
  ['product', 'not in', 'Toaster, Wasserkocher']
], 'AND');
</code></pre>

<h5 class="mt-4">Unterstützte Operatoren:</h5>
<ul>
  <li><code>=</code>, <code>==</code> – Gleichheit</li>
  <li><code>!=</code> – Ungleichheit</li>
  <li><code>&gt;</code>, <code>&gt;=</code>, <code>&lt;</code>, <code>&lt;=</code> – Vergleichsoperatoren</li>
  <li><code>like</code> – Textsuche mit Platzhalter <code>%</code></li>
  <li><code>in</code>, <code>not in</code> – Vergleich mit einer Liste (Array oder String)</li>
  <li><code>not</code> – Negation einer einzelnen Bedingung</li>
</ul>

<h5 class="mt-4">Verknüpfung:</h5>
<ul>
  <li>Standardmäßig werden Bedingungen mit <strong>ODER</strong> verknüpft (<code>'OR'</code>)</li>
  <li>Mit dem zweiten Parameter kannst du <strong>'AND'</strong> erzwingen</li>
  <li>Mit <code>$append = true</code> kannst du mehrere where()-Aufrufe kombinieren</li>
</ul>

<h5 class="mt-4">Internes Verhalten:</h5>
<ul>
  <li>Alle Bedingungen werden in <code>$this-&gt;filters</code> gespeichert</li>
  <li>Die Verknüpfung (AND/OR) steht in <code>$this-&gt;mergeCondition</code></li>
  <li>Beim <code>get()</code> werden alle Filter mit <code>applyFilters()</code> ausgewertet</li>
</ul>

<h5 class="mt-4">Beispielausgabe:</h5>
<pre><code class="language-json">
[
  {
    "vendor": "Aldi",
    "product": "Wasserkocher",
    "rating": 4
  }
]
</code></pre>

<p class="mt-4">🔁 Diese Filterung kann mit <code>select()</code>, <code>orderBy()</code>, <code>groupBy()</code> usw. kombiniert werden.</p>









<hr class='content-sep'>
<!-- GET -->
<h2 id="get">📦 get()</h2>
<p><strong>[Trait: <code>JS_CRUD</code>]</strong></p>

<p>Die Methode <code>get()</code> ist das Herzstück jeder Abfrage in JsonSQL. Sie führt alle gesetzten Bedingungen aus und gibt die Ergebnisse zurück – ähnlich wie ein klassisches SQL-<code>SELECT</code>.</p>

<h5 class="mt-4">Tabelle wählen</h5>
<p>Bevor <code>get()</code> ausgeführt werden kann, muss eine Tabelle gesetzt sein. Dafür stehen drei Varianten zur Verfügung:</p>

<ul>
  <li><code>$db->from('users')</code> – lädt die Tabelle und setzt sie als aktiv. Optional mit <code>autoload = true</code>, um sie direkt zu lesen.</li>
  <li><code>$db->setTable('users')</code> – setzt nur die Tabelle ohne sofortige Leseoperation.</li>
  <li><code>$db->truncate()</code> – leert die aktuelle Tabelle vollständig (Achtung: destruktiv).</li>
</ul>

<h5 class="mt-4">Optional verwendbar: <code>join()</code></h5>
<p>Falls du Daten aus mehreren Tabellen kombinieren möchtest, kannst du <code>join()</code> vor <code>get()</code> in die Kette einfügen:</p>
<pre><code class="language-php">
$db->from('orders')
   ->join('users', 'orders.user_id', '=', 'users.id')
   ->select('orders.id, users.name')
   ->get();
</code></pre>

<h5 class="mt-4">Typischer Anwendungsfall:</h5>
<pre><code class="language-php">
// Abfrage mit Filter, Auswahl und Sortierung
$rows = $db->from('products')
           ->where('price', '>', 20)
           ->orderBy('price', 'desc')
           ->select('name AS Produktname, price AS Preis')
           ->limit(5)
           ->get();
</code></pre>

<h5 class="mt-4">Verarbeitungsschritte (intern):</h5>
<ol>
  <li>📁 Tabelle wird geladen (nur bei <code>from()</code>)</li>
  <li>🔍 Filter: <code>where()</code></li>
  <li>🔗 Joins (optional)</li>
  <li>📊 Gruppierung: <code>groupBy()</code></li>
  <li>🔃 Sortierung: <code>orderBy()</code></li>
  <li>⏳ Limitierung: <code>limit()</code></li>
  <li>🎯 Feld-Auswahl: <code>select()</code></li>
</ol>

<h5 class="mt-4">Rückgabe:</h5>
<p>Ein Array von assoziativen Arrays – jeder Eintrag entspricht einem Datensatz.</p>

<pre><code class="language-json">
[
  { "Produktname": "Kaffeemaschine", "Preis": 79.90 },
  { "Produktname": "Toaster",        "Preis": 39.99 },
  ...
]
</code></pre>

<h5 class="mt-4">Hinweise:</h5>
<ul>
  <li>Wenn <code>select()</code> nicht aufgerufen wurde, gibt <code>get()</code> alle Felder zurück.</li>
  <li>Die Methode verändert keine Daten – sie ist rein lesend.</li>
  <li>In Kombination mit <code>paginate()</code> enthält die Rückgabe auch Metainformationen zur Seitennavigation.</li>
</ul>

<p class="mt-4 text-muted">📌 Intern werden alle Schritte strikt in Reihenfolge ausgeführt. Die Methode <code>get()</code> schließt die Abfrage ab.</p>


<hr class='content-sep'>
<!-- EXISTS -->
  <h2 id="exists">❓ exists()</h2>
  <p><strong>[Trait: <code>JS_CRUD</code>]</strong></p>

  <p>Prüft, ob ein bestimmter Datensatz existiert (true/false):</p>
  <pre><code class="language-php">
$exists = $db->from('users')->where('email', '=', 'bob@example.com')->exists();
  </code></pre>



<hr class='content-sep'>
<!-- PLUCK -->
<h2 id="pluck">🎯 pluck()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Parameter:</strong></li>
  <li><code>string $column</code> – Der Feldname, der ausgegeben werden soll</li>
  <li><code>bool $all = false</code> – Gibt bei <code>true</code> alle Werte zurück, sonst nur den ersten</li>
</ul>
<p><strong>[Trait: <code>JS_CRUD</code>]</strong></p>

<p>Gibt den Wert eines bestimmten Feldes zurück – entweder vom ersten Datensatz oder von allen.</p>

<pre><code class="language-php">
// Einzelwert (erster Treffer)
$email = $db->from('users')
            ->where('id', '=', 1)
            ->pluck('email');

// Mehrere Werte
$alleEmails = $db->from('users')
                 ->pluck('email', true);
</code></pre>

<ul class="mt-3">
  <li>📥 Gibt standardmäßig nur den <code>ersten</code> Wert zurück</li>
  <li>🔁 Wenn <code>true</code> als zweiter Parameter gesetzt ist, wird ein Array aller Werte geliefert</li>
  <li>💡 Kombinierbar mit <code>where()</code>, <code>orderBy()</code>, <code>limit()</code> etc.</li>
</ul>

<p class="text-muted mt-3">Tipp: Ideal für Dropdowns, Autovervollständigung oder schnelle Lookups!</p>




<hr class='content-sep'>
<!-- FIRST -->
<h2 id="first">🥇 first()</h2>
<p><strong>[Trait: <code>JS_CRUD</code>]</strong></p>
<p>Gibt den <strong>ersten passenden Datensatz</strong> einer Abfrage zurück – ideal für gezielte Einzelergebnisse wie Benutzer- oder Detaildaten.</p>

<pre><code class="language-php">
$user = $db->from('users')
           ->where('email', '=', 'alice@example.com')
           ->first();

if ($user) {
    echo "Willkommen zurück, " . $user['name'];
}
</code></pre>

<ul class="mt-3">
  <li>✅ Gibt ein <code>array</code> mit den Felddaten zurück</li>
  <li>🚫 Gibt <code>null</code> zurück, wenn kein Treffer gefunden wurde</li>
  <li>⚡ Intern wird automatisch <code>limit(1)</code> gesetzt – schnell und effizient</li>
</ul>

<p class="text-muted mt-3">Tipp: Kombiniere <code>select()</code>, <code>where()</code> und <code>first()</code> für gezielte Feldabfragen.</p>



<!-- CLEARTABLE -->
<hr class='content-sep'>
<span id="clearTable"></span>

<h2 id="clearTable">🧹 clearTable()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Rückgabewert:</strong> <code>void</code> – Kein Rückgabewert</li>
  <li><strong>Parameter:</strong> <code>string $tableName</code> – Name der Tabelle, die geleert werden soll</li>
</ul>
<p><strong>[Trait: <code>JS_TABLES</code>]</strong></p>


<div class="alert alert-info">
  Diese Methode leert den Inhalt einer existierenden Tabelle, ohne sie zu löschen oder neu anzulegen.
  Falls die Tabelle <strong>nicht existiert</strong>, wird eine Exception geworfen.
</div>

<p>
  <code>clearTable(string \$tableName)</code> ist ideal für gezielte Löschvorgänge bei vorhandenen Tabellen, ohne das Risiko, versehentlich neue Dateien anzulegen. Anders als <code>truncate()</code> wird keine Tabelle erstellt, wenn sie fehlt.
</p>

<h5 class="mt-4">📌 Methodensignatur</h5>
<pre><code>public function clearTable(string $tableName): void</code></pre>

<h5 class="mt-4">📋 Beispiel</h5>
<pre><code>$json = new JsonSQL('data');
$json->clearTable('produkte'); // Inhalt der Tabelle 'produkte' wird geleert</code></pre>

<h5 class="mt-4">✅ Unterschiede zu truncate()</h5>
<ul>
  <li><strong>clearTable()</strong> → leert nur, wenn Tabelle existiert</li>
  <li><strong>truncate()</strong> → leert oder erstellt leere Datei</li>
</ul>

<h5 class="mt-4">❗ Fehlerbehandlung</h5>
<ul>
  <li>Wenn die Tabelle <code>nicht existiert</code>, wird eine Exception mit einer klaren Fehlermeldung geworfen.</li>
</ul>




<!-- CLEAR -->
<span id="clear"></span>
<hr class='content-sep'>
<h2 id="clear">☠️ clear()</h2>
<ul class="method-signature small text-muted">
  <li><strong>Rückgabewert:</strong> <code>void</code> – Kein Rückgabewert</li>
  <li><strong>Parameter:</strong> <code>bool $requireConfirmation</code> – Muss auf <code>true</code> gesetzt werden, um die Aktion zu bestätigen</li>
</ul>
<p><strong>[Trait: <code>JS_DATBASE</code>]</strong></p>


<div class="alert alert-danger">
  <strong>Warnung:</strong> Diese Methode löscht <strong>alle Tabellen</strong> aus der aktuell gewählten Datenbank unwiderruflich. Sie sollte <u>nur mit Bestätigung</u> aufgerufen werden.
</div>

<p>
  Mit <code>clear(bool \$requireConfirmation)</code> entfernst du sämtliche JSON-Dateien (also Tabellen) aus dem aktuell gesetzten Datenbankverzeichnis.
  Der Parameter <code>\$requireConfirmation</code> muss explizit auf <code>true</code> gesetzt werden, um versehentliches Löschen zu vermeiden.
</p>

<h5 class="mt-4">📌 Methodensignatur</h5>
<pre><code>public function clear(bool $requireConfirmation = false): void</code></pre>

<h5 class="mt-4">📋 Beispiel</h5>
<pre><code>$json = new JsonSQL('data');
$json->clear(true); // Alle Tabellen löschen (nur mit Bestätigung!)</code></pre>

<h5 class="mt-4">🧠 Hinweis</h5>
<ul>
  <li>Ohne <code>true</code> als Parameter wird die Methode mit einer Exception abgebrochen.</li>
  <li>Diese Methode löscht nur die <code>.json</code>-Tabellen, nicht die <code>.system.json</code>-Dateien – sofern du das möchtest, kannst du das intern noch erweitern.</li>
</ul>



<span id="paginate"></span>
<hr class='content-sep'>
  <h2 id="paginate">📄 paginate()</h2>
  <p>Teilt große Ergebnislisten in Seiten auf:</p>
  <pre><code class="language-php">
$page = 1;
$limit = 10;
$result = $db->from('users')->paginate($page, $limit);
  </code></pre>
  <p>Die Rückgabe enthält Einträge, Gesamtanzahl, Seitenanzahl und mehr.</p>

  <hr class='content-sep'>



</section>

<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="system-intro"><i class="bi bi-book"></i> Datenfelder & system.json</h1>

  <h2 id="system-intro">📘 Einführung</h2>  

  <p>
    In JsonSQL kann jede Tabelle durch eine ergänzende <code>.system.json</code>-Datei um eine strukturierte Felddefinition erweitert werden.
    Diese Datei steuert automatisch:
  </p>

  <ul>
    <li>⚙️ die Feldtypen (<code>dataType</code>) und ihre Eigenschaften</li>
    <li>🛡️ Validierungsregeln wie <code>required</code>, <code>min</code>, <code>max</code>, <code>enum</code></li>
    <li>🔐 Verschlüsselung einzelner Felder mit <code>encrypt</code></li>
    <li>⏱️ automatische Timestamps bei Erstellung und Änderung</li>
    <li>🧮 automatisches Hochzählen von Feldern (<code>autoincrement</code>)</li>
    <li>🎲 Zufallswerte oder Hashes</li>
  </ul>

  <p>
    Die <code>system.json</code> ist optional, wird jedoch bei komplexeren Datenstrukturen empfohlen, da sie die Validierung und Automatisierung
    auf Tabellenebene kapselt und die Wartbarkeit erhöht.
  </p>

  <p>
    JsonSQL lädt diese Konfiguration automatisch beim Setzen der Tabelle mittels <code>setTable()</code> und nutzt sie u. a. bei:
  </p>

  <ul>
    <li><code>insert()</code> – automatische Ergänzung & Prüfung von Feldern</li>
    <li><code>update()</code> – Validierung & Timestamp-Aktualisierung</li>
    <li><code>analyzeTable()</code> – strukturelle Analyse der Tabellendaten</li>
  </ul>

  <p>Die Konfiguration befindet sich standardmäßig in <code>[TABELLE].system.json</code> im selben Verzeichnis wie die Tabelle selbst.</p>
</section>







<hr class='content-sep'>  
<h2 id="system-options">🌐 Globale Optionen</h2>  

<p>
  Neben den Feldern selbst kannst du in der <code>system.json</code>-Datei auch globale Optionen für das Verhalten der Tabelle definieren. Diese betreffen z. B. den Umgang mit unbekannten Feldern, die Verschlüsselung oder die Zeitverarbeitung.
</p>

<table class="table table-sm table-bordered mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Typ</th>
      <th>Beschreibung</th>
      <th>Standardwert</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>allowAdditionalFields</code></td>
      <td><code>boolean</code></td>
      <td>Erlaubt zusätzliche Felder, die nicht in <code>fields</code> definiert sind</td>
      <td><code>true</code></td>
    </tr>
    <tr>
      <td><code>encryption_key</code></td>
      <td><code>string</code></td>
      <td>Optionaler Schlüssel für die Feld-Verschlüsselung (nur für <code>encrypt</code>-Felder notwendig)</td>
      <td><em>intern generierter Fallback</em></td>
    </tr>
    <tr>
      <td><code>timezone</code></td>
      <td><code>string</code></td>
      <td>Zeitzone für automatische Timestamps (z. B. <code>UTC</code>, <code>Europe/Berlin</code>)</td>
      <td><code>UTC</code></td>
    </tr>
  </tbody>
</table>

<p>
  Du kannst diese Optionen jederzeit mit <code>setSystemOption()</code> ändern oder mit <code>getSystemOption()</code> auslesen.
</p>

<pre><code class="language-php">
// Beispiel: Option setzen
$db->setSystemOption('allowAdditionalFields', false);

// Beispiel: Option lesen
$tz = $db->getSystemOption('timezone');
</code></pre>






<hr class='content-sep'>  
<h2 id="field-properties">🧩 Feldoptionen</h2>

<p>
  Die Feldoptionen in <code>system.json</code> definieren, wie sich jedes einzelne Feld einer Tabelle verhalten soll – 
  sowohl bei der Speicherung (<code>insert()</code>) als auch bei der Aktualisierung (<code>update()</code>).
  Diese Metadaten erlauben es, deine Datenstruktur formal zu beschreiben und Prüfungen, Automatisierungen und Schutzmechanismen 
  zentral zu hinterlegen – ohne diese manuell im Code implementieren zu müssen.
</p>

<p>
  JsonSQL nutzt diese Informationen u. a. für:
</p>

<ul>
  <li>automatische Generierung von Werten (z. B. bei <code>autoincrement</code>, <code>random</code>, <code>timestamp</code>)</li>
  <li>Validierung von Pflichtfeldern, erlaubten Werten oder Längen</li>
  <li>Datenverschlüsselung bei sensiblen Informationen wie <code>Passwörtern</code></li>
</ul>

<p>
  Die folgende Tabelle zeigt dir alle unterstützten Feldoptionen mit Beschreibung und Beispielen:
</p>

<table class="table table-sm table-bordered mt-3">
  <thead class="table-light">
    <tr>
      <th>Property</th>
      <th>Typ</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>dataType</code></td>
      <td><code>string</code></td>
      <td>
        Legt den Datentyp des Feldes fest.<br>
        Unterstützte Werte sind z. B. <code>string</code>, <code>integer</code>, <code>float</code>, <code>datetime</code>, <code>boolean</code>, <code>enum</code>.
      </td>
      <td><code>"integer"</code></td>
    </tr>
    <tr>
      <td><code>length</code></td>
      <td><code>int</code></td>
      <td>Begrenzt die maximale Länge des Feldes (nur für <code>string</code>)</td>
      <td><code>255</code></td>
    </tr>
    <tr>
      <td><code>precision</code></td>
      <td><code>int</code></td>
      <td>Gibt an, auf wie viele Nachkommastellen gerundet wird (nur für <code>float</code>)</td>
      <td><code>2</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td><em>beliebig</em></td>
      <td>Wert, der verwendet wird, wenn beim Insert/Update kein Wert übergeben wurde</td>
      <td><code>"unbekannt"</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td><code>boolean</code></td>
      <td>Gibt an, ob das Feld zwingend ausgefüllt werden muss</td>
      <td><code>true</code></td>
    </tr>
    <tr>
      <td><code>unique</code></td>
      <td><code>boolean</code></td>
      <td>Stellt sicher, dass dieser Feldwert nur einmal in der gesamten Tabelle vorkommt</td>
      <td><code>true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td><code>boolean</code></td>
      <td>Legt fest, ob der Wert explizit <code>null</code> sein darf</td>
      <td><code>false</code></td>
    </tr>
    <tr>
      <td><code>unit</code></td>
      <td><code>string</code></td>
      <td>Optionaler Einheitshinweis – z. B. für Anzeigezwecke oder Export</td>
      <td><code>"kg"</code>, <code>"€"</code></td>
    </tr>
    <tr>
      <td><code>enumValues</code></td>
      <td><code>array</code></td>
      <td>Definiert erlaubte Werte für Felder mit Typ <code>enum</code></td>
      <td><code>["rot", "grün", "blau"]</code></td>
    </tr>
    <tr>
      <td><code>min</code> / <code>max</code></td>
      <td><code>int</code>/<code>float</code></td>
      <td>Grenzwerte für Zahlenfelder (auch bei Zufallswerten oder Validation)</td>
      <td><code>min: 0</code>, <code>max: 100</code></td>
    </tr>
    <tr>
      <td><code>random</code></td>
      <td><code>boolean</code></td>
      <td>Wenn <code>true</code>, wird der Wert automatisch per Zufall (innerhalb <code>min</code>/<code>max</code>) erzeugt</td>
      <td><code>true</code></td>
    </tr>
    <tr>
      <td><code>comment</code></td>
      <td><code>string</code></td>
      <td>Dokumentation oder Beschreibung des Feldes – rein informativ</td>
      <td><code>"Interne Referenznummer"</code></td>
    </tr>
  </tbody>
</table>

<p>
  Die Feldoptionen können beliebig kombiniert werden – etwa ein Feld mit Typ <code>integer</code>, <code>required</code>, <code>random</code> und
  einem gültigen Bereich von <code>1</code> bis <code>99</code>. JsonSQL prüft diese Eigenschaften automatisch bei jedem Speichern.
</p>

<p>
  Weitere spezielle Eigenschaften findest du im Abschnitt <a href="#auto-fields">⚙️ Auto-Felder</a>, z. B. <code>autoincrement</code>, <code>autohash</code>, <code>auto_create_timestamp</code> oder <code>encrypt</code>.
</p>





<hr class='content-sep'> 
<h2 id="auto-fields">⚙️ Auto-Felder</h2>

<p>
  JsonSQL unterstützt sogenannte „Auto-Felder“, bei denen bestimmte Werte nicht manuell gesetzt werden müssen,
  sondern automatisch bei <code>insert()</code> oder <code>update()</code> generiert werden.
</p>

<p>
  Diese Felder sparen Zeit, sorgen für Konsistenz und reduzieren Fehlerquellen – vor allem bei IDs, Timestamps und Sicherheitsthemen.
</p>

<table class="table table-sm table-bordered mt-3">
  <thead class="table-light">
    <tr>
      <th>Feldtyp</th>
      <th>Beschreibung</th>
      <th>Beispiel-Konfiguration</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong><code>autoincrement</code></strong></td>
      <td>
        Zählt bei jedem neuen Datensatz automatisch hoch (z. B. für IDs). Unterstützt <code>autoincrement_value</code> (Startwert) und <code>autoincrement_step</code>.
      </td>
      <td>
<pre><code>{
  "id": {
    "dataType": "integer",
    "autoincrement": true,
    "autoincrement_value": 1,
    "autoincrement_step": 1
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>autouuid</code></strong></td>
      <td>
        Erstellt beim Speichern automatisch eine <a href="https://de.wikipedia.org/wiki/UUID" target="_blank">UUIDv4</a>.
        Ideal für externe Referenzen oder sichere Primärschlüssel.
      </td>
      <td>
<pre><code>{
  "uuid": {
    "dataType": "string",
    "autouuid": true
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>autohash</code></strong></td>
      <td>
        Generiert automatisch einen Hash aus einem oder mehreren Feldwerten. Unterstützte Algorithmen: <code>md5</code>, <code>sha1</code>, <code>sha256</code>.
      </td>
      <td>
<pre><code>{
  "checksum": {
    "dataType": "string",
    "autohash": true,
    "algorithm": "sha256",
    "length": 64
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>auto_create_timestamp</code></strong></td>
      <td>
        Setzt den aktuellen Zeitstempel bei Erstellung eines Datensatzes. Kann mit <code>format</code> und <code>timezone</code> angepasst werden.
      </td>
      <td>
<pre><code>{
  "created_at": {
    "dataType": "datetime",
    "auto_create_timestamp": true,
    "format": "Y-m-d H:i:s",
    "timezone": "UTC"
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>auto_modified_timestamp</code></strong></td>
      <td>
        Aktualisiert sich bei jeder Änderung automatisch. Gleiche Format-Optionen wie bei <code>auto_create_timestamp</code>.
      </td>
      <td>
<pre><code>{
  "updated_at": {
    "dataType": "datetime",
    "auto_modified_timestamp": true,
    "format": "Y-m-d H:i:s"
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>encrypt</code></strong></td>
      <td>
        Verschlüsselt das Feld automatisch beim Speichern.<br>
        Nur für Felder vom Typ <code>string</code> erlaubt. Entschlüsselung erfolgt intern beim Lesen automatisch.
      </td>
      <td>
<pre><code>{
  "password": {
    "dataType": "string",
    "encrypt": true
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><strong><code>random</code></strong></td>
      <td>
        Generiert bei fehlendem Wert eine Zufallszahl im Bereich <code>min</code>–<code>max</code>. Nur für <code>integer</code> oder <code>float</code>.
      </td>
      <td>
<pre><code>{
  "code": {
    "dataType": "integer",
    "random": true,
    "min": 100000,
    "max": 999999
  }
}</code></pre>
      </td>
    </tr>
  </tbody>
</table>

<p>
  Auto-Felder können mit anderen Eigenschaften wie <code>required</code>, <code>defaultValue</code> oder <code>comment</code> kombiniert werden.
</p>

<p>
  Du kannst Auto-Felder bequem über den internen API-Call <code>addFieldDefinition()</code> oder interaktive Tools hinzufügen.
</p>





<hr class='content-sep'> 
<h2 id="validation">🧪 Validierung</h2>

<p>
  JsonSQL prüft beim <code>insert()</code> und <code>update()</code> automatisch die Felder gegen die in <code>system.json</code> definierten Regeln.
  Dabei wird sichergestellt, dass Datensätze vollständig, konsistent und im erwarteten Format gespeichert werden.
</p>

<h3>📌 Unterstützte Validierungsregeln</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Feld</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>required</code></td>
      <td>Feld darf nicht fehlen oder leer sein</td>
      <td>
<pre><code>{
  "email": {
    "dataType": "string",
    "required": true
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><code>min</code> / <code>max</code></td>
      <td>Numerische Wertebegrenzung oder Stringlängenprüfung (bei <code>string</code>)</td>
      <td>
<pre><code>{
  "age": {
    "dataType": "integer",
    "min": 18,
    "max": 99
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><code>length</code></td>
      <td>Erwartete Länge von Strings oder numerischen Feldern</td>
      <td>
<pre><code>{
  "zip": {
    "dataType": "string",
    "length": 5
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><code>enumValues</code></td>
      <td>Zulässige Werte für ein <code>enum</code>-Feld (als Liste)</td>
      <td>
<pre><code>{
  "status": {
    "dataType": "enum",
    "enumValues": ["open", "closed", "pending"]
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt explizit <code>null</code> als gültigen Wert</td>
      <td>
<pre><code>{
  "comment": {
    "dataType": "string",
    "allowNULL": true
  }
}</code></pre>
      </td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Wird verwendet, wenn das Feld leer oder nicht gesetzt ist</td>
      <td>
<pre><code>{
  "country": {
    "dataType": "string",
    "defaultValue": "Germany"
  }
}</code></pre>
      </td>
    </tr>
  </tbody>
</table>

<h3>⚠️ Fehlerbehandlung</h3>
<p>
  Wenn ein Feld nicht den Validierungsregeln entspricht, wird der Vorgang mit einer <code>Exception</code> abgebrochen.
  Dies verhindert inkonsistente oder unvollständige Daten.
</p>

<p>Die Prüfung erfolgt intern in <code>validateFieldProperties()</code> und <code>validateSystemFieldProperties()</code> und gilt sowohl für neue als auch für geänderte Felder.</p>

<h3>🔍 Tipps</h3>
<ul>
  <li>Wenn <code>required</code> gesetzt ist, muss entweder ein Wert übergeben oder ein <code>defaultValue</code> angegeben werden.</li>
  <li><code>enum</code> ist besonders nützlich für Status-Felder oder feste Auswahlwerte.</li>
  <li>Für strukturierte Prüfung von Tabellen kannst du <code>analyzeTable()</code> oder <code>analyzeSystemTable()</code> verwenden.</li>
</ul>






<hr class='content-sep'> 
<h2 id="analyze">🔎 Analyse</h2>

<p>
  JsonSQL bietet eingebaute Analysefunktionen, um deine Tabellenstruktur und die zugehörige <code>system.json</code>-Konfiguration auf Inkonsistenzen zu überprüfen.
  Dies ist besonders hilfreich zur Qualitätssicherung und Fehlersuche.
</p>

<hr>

<h3>📊 <code>analyzeTable()</code> – Daten prüfen</h3>
<p>
  Diese Methode analysiert die vorhandenen Datensätze einer Tabelle und prüft sie gegen die in der <code>system.json</code> definierten Felder.
  Sie erkennt:
</p>

<ul>
  <li>❌ <strong>fehlende Pflichtfelder</strong> (wenn <code>required: true</code>)</li>
  <li>⚠️ <strong>zusätzliche Felder</strong>, die nicht in <code>system.json</code> definiert sind</li>
</ul>

<p>Optional kann auch geprüft werden, ob zusätzliche Felder erlaubt sind oder nicht (abhängig von <code>allowAdditionalFields</code>).</p>

<h4>Beispiel:</h4>
<pre><code class="language-php">
$errors = $db->analyzeTable();
foreach ($errors as $issue) {
  echo "Zeile {$issue['row']} hat Probleme:\n";
  echo "- Fehlend: " . implode(', ', $issue['missing']) . "\n";
  echo "- Überflüssig: " . implode(', ', $issue['extra']) . "\n";
}
</code></pre>

<h4>Beispielausgabe:</h4>
<pre><code>
Zeile 3 hat Probleme:
- Fehlend: email
- Überflüssig: debug_note
</code></pre>

<hr>

<h3>🧠 <code>analyzeSystemTable()</code> – system.json prüfen</h3>
<p>
  Diese Methode prüft die <code>system.json</code> einer Tabelle auf fehlerhafte Felddefinitionen. Dabei wird analysiert:
</p>

<ul>
  <li>🧨 Ungültige <code>dataType</code>-Werte (z. B. Tippfehler wie "datim")</li>
  <li>🚫 Nicht erlaubte Feldoptionen (z. B. <code>foobar</code> statt <code>defaultValue</code>)</li>
</ul>

<h4>Beispiel:</h4>
<pre><code class="language-php">
$report = $db->analyzeSystemTable();
print_r($report);
</code></pre>

<h4>Beispielausgabe:</h4>
<pre><code>
[
  "invalidTypes" => [
    ["field" => "birthday", "dataType" => "datim"]
  ],
  "invalidProperties" => [
    ["field" => "email", "property" => "foobar"]
  ]
]
</code></pre>

<p>So erkennst du auf einen Blick, ob Konfigurationsfehler in der <code>system.json</code> vorliegen.</p>

<hr>

<h3>🔁 Einsatzmöglichkeiten</h3>
<ul>
  <li>🧪 Vor dem Import größerer Datenmengen</li>
  <li>🧼 Nach dem Hinzufügen neuer Felder zur Validierung</li>
  <li>📋 Zur strukturellen Kontrolle bei dynamischen Datenquellen</li>
</ul>

<p>
  Tipp: Kombiniere <code>getTableInfo()</code> mit <code>analyzeTable()</code>, um vollständige Einblicke in Struktur, Datenmenge, Felder und Inkonsistenzen zu erhalten.
</p>




<hr class='content-sep'> 
<h2 id="type-string">🔤 Datentyp: <code>string</code></h2>

<p>
  Der Datentyp <code>string</code> ist der meistverwendete Typ für Textinhalte wie Namen, E-Mails, Adressen, Notizen oder einfache Texteingaben.
  Felder mit diesem Typ akzeptieren beliebige Zeichenfolgen, können aber zusätzlich eingeschränkt oder geschützt werden.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>length</code></td>
      <td>Erwartete exakte Zeichenanzahl</td>
      <td><code>"length": 5</code></td>
    </tr>
    <tr>
      <td><code>min</code></td>
      <td>Minimale Länge des Textes</td>
      <td><code>"min": 3</code></td>
    </tr>
    <tr>
      <td><code>max</code></td>
      <td>Maximale Länge des Textes</td>
      <td><code>"max": 50</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld (darf nicht leer sein)</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Wird verwendet, wenn kein Wert gesetzt wurde</td>
      <td><code>"defaultValue": "N/A"</code></td>
    </tr>
    <tr>
      <td><code>encrypt</code></td>
      <td>Speichert den Wert verschlüsselt (AES-256)</td>
      <td><code>"encrypt": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt explizit den Wert <code>null</code></td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🧪 Validierung</h3>
<ul>
  <li>Wird auf Mindest- und Maximallänge geprüft (wenn gesetzt)</li>
  <li>Leere Strings <code>""</code> gelten als "nicht gesetzt", wenn <code>required: true</code></li>
  <li>Ein <code>defaultValue</code> wird eingesetzt, wenn der Wert leer oder nicht vorhanden ist</li>
  <li>Bei <code>encrypt: true</code> erfolgt die Verschlüsselung automatisch vor dem Speichern</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"email": {
  "dataType": "string",
  "required": true,
  "min": 5,
  "max": 100,
  "defaultValue": "unbekannt@example.com",
  "encrypt": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Feld wird intern immer als <code>string</code> behandelt – auch wenn numerisch (z. B. Postleitzahl)</li>
  <li>In Kombination mit <code>encrypt</code> besonders geeignet für sensible Daten wie Passwörter, IBAN, API-Keys</li>
</ul>




<hr class='content-sep'> 
<h2 id="type-integer">🔢 Datentyp: <code>integer</code></h2>

<p>
  Der <code>integer</code>-Typ ist ideal für ganzzahlige Werte – z. B. IDs, Mengen, Altersangaben oder Zähler.
  JsonSQL erkennt, ob eine gültige Ganzzahl eingegeben wurde, und kann diese bei Bedarf automatisch generieren oder prüfen.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>min</code></td>
      <td>Minimal erlaubter Wert</td>
      <td><code>"min": 0</code></td>
    </tr>
    <tr>
      <td><code>max</code></td>
      <td>Maximal erlaubter Wert</td>
      <td><code>"max": 1000</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Feld muss gesetzt sein</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standardwert, falls keiner angegeben</td>
      <td><code>"defaultValue": 1</code></td>
    </tr>
    <tr>
      <td><code>autoincrement</code></td>
      <td>Wert wird automatisch hochgezählt</td>
      <td><code>"autoincrement": true</code></td>
    </tr>
    <tr>
      <td><code>autoincrement_value</code></td>
      <td>Startwert des Zählers</td>
      <td><code>"autoincrement_value": 100</code></td>
    </tr>
    <tr>
      <td><code>autoincrement_step</code></td>
      <td>Schrittweite beim Hochzählen</td>
      <td><code>"autoincrement_step": 5</code></td>
    </tr>
    <tr>
      <td><code>random</code></td>
      <td>Zufallswert generieren (zwischen min und max)</td>
      <td><code>"random": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt den Wert <code>null</code></td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🧪 Validierung</h3>
<ul>
  <li>Wird auf Ganzzahligkeit geprüft (keine Kommawerte oder Strings erlaubt)</li>
  <li>Bei <code>min</code>/<code>max</code> wird der Zahlenbereich kontrolliert</li>
  <li>Bei <code>random: true</code> wird der Wert zufällig zwischen <code>min</code> und <code>max</code> gesetzt</li>
  <li>Wenn <code>autoincrement</code> aktiv ist, wird der Zählerwert automatisch verwendet</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"counter": {
  "dataType": "integer",
  "autoincrement": true,
  "autoincrement_value": 1000,
  "autoincrement_step": 10
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Sehr gut geeignet für IDs, Artikelnummern, Positionen oder sortierbare Reihenfolgen</li>
  <li><code>autoincrement</code> kann für jede Tabelle separat gesteuert werden</li>
  <li>Du kannst <code>setAutoincrementValue()</code> verwenden, um den Zähler manuell zu setzen</li>
</ul>




<hr class='content-sep'> 
<h2 id="type-float">🌊 Datentyp: <code>float</code></h2>

<p>
  Der <code>float</code>-Typ dient zur Speicherung von Gleitkommazahlen mit Dezimalstellen, z. B. Gewichte, Preise, Temperaturwerte oder Prozentangaben.
  Im Gegensatz zu <code>integer</code> erlaubt dieser Typ Nachkommastellen und kann sehr große oder sehr kleine Werte speichern.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>min</code></td>
      <td>Minimaler Wert</td>
      <td><code>"min": 0.0</code></td>
    </tr>
    <tr>
      <td><code>max</code></td>
      <td>Maximaler Wert</td>
      <td><code>"max": 100.5</code></td>
    </tr>
    <tr>
      <td><code>precision</code></td>
      <td>Anzahl der Dezimalstellen</td>
      <td><code>"precision": 2</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standardwert bei leerem Feld</td>
      <td><code>"defaultValue": 0.0</code></td>
    </tr>
    <tr>
      <td><code>random</code></td>
      <td>Zufallswert (zwischen <code>min</code> und <code>max</code>)</td>
      <td><code>"random": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt <code>null</code> als gültigen Wert</td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🧪 Validierung</h3>
<ul>
  <li>Wert muss numerisch und als Fließkommazahl interpretierbar sein</li>
  <li><code>min</code> und <code>max</code> definieren den erlaubten Wertebereich</li>
  <li>Bei aktivierter <code>random</code>-Option wird ein zufälliger Float generiert</li>
  <li><code>precision</code> rundet den Wert automatisch auf die gewünschte Nachkommastelle</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"price": {
  "dataType": "float",
  "min": 0.0,
  "max": 9999.99,
  "precision": 2,
  "defaultValue": 0.0,
  "required": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Intern wird der Wert in PHP als <code>float</code> gecastet – z. B. <code>(float) $value</code></li>
  <li>Bei <code>precision</code> = 2 wird <code>4.5678</code> zu <code>4.57</code></li>
  <li>Optimal für Geldbeträge, Berechnungsfelder oder wissenschaftliche Messwerte</li>
</ul>






<hr class='content-sep'> 
<h2 id="type-boolean">☑️ Datentyp: <code>boolean</code></h2>

<p>
  Der <code>boolean</code>-Typ wird verwendet, um logische Zustände zu speichern: <code>true</code> (wahr) oder <code>false</code> (falsch).
  Typisch z. B. für Schalter, Aktiv-Flags, Berechtigungen, Bestätigungen oder Statusfelder.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standardwert (<code>true</code> oder <code>false</code>)</td>
      <td><code>"defaultValue": false</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt explizit den Wert <code>null</code></td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🎯 Zulässige Werte</h3>
<ul>
  <li><code>true</code> oder <code>false</code> (als Boolean-Wert)</li>
  <li><code>1</code> / <code>0</code> (werden zu <code>true</code>/<code>false</code> konvertiert)</li>
  <li><code>"true"</code> / <code>"false"</code> (werden erkannt und entsprechend umgewandelt)</li>
</ul>

<h3>🧪 Validierung</h3>
<ul>
  <li>Nur die oben genannten Werte sind gültig</li>
  <li>Ungültige Werte (z. B. <code>"yes"</code>, <code>"no"</code>, <code>"vielleicht"</code>) führen zu einer Fehlermeldung</li>
  <li>Standardwert wird gesetzt, wenn kein gültiger Wert angegeben und <code>defaultValue</code> definiert ist</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"isActive": {
  "dataType": "boolean",
  "defaultValue": true,
  "required": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Ideal für Checkboxen, Statusfelder, Aktivierungsflags oder Ja/Nein-Entscheidungen</li>
  <li>Intern wird der Wert automatisch zu <code>true</code>/<code>false</code> gecastet</li>
</ul>





<hr class='content-sep'> 
<h2 id="type-datetime">📅🕒 Datentyp: <code>datetime</code></h2>

<p>
  Der <code>datetime</code>-Typ speichert Datum und Uhrzeit in einem einheitlichen Format.
  Er eignet sich für Felder wie <code>created_at</code>, <code>updated_at</code>, Terminangaben, Zeitpunkte von Events oder Logeinträge.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>auto_create_timestamp</code></td>
      <td>setzt automatisch bei Erstellung</td>
      <td><code>"auto_create_timestamp": true</code></td>
    </tr>
    <tr>
      <td><code>auto_modified_timestamp</code></td>
      <td>aktualisiert automatisch bei Änderung</td>
      <td><code>"auto_modified_timestamp": true</code></td>
    </tr>
    <tr>
      <td><code>format</code></td>
      <td>Datumsformat (PHP-kompatibel)</td>
      <td><code>"format": "Y-m-d H:i:s"</code></td>
    </tr>
    <tr>
      <td><code>timezone</code></td>
      <td>Zeitzone (z. B. UTC, Europe/Berlin)</td>
      <td><code>"timezone": "UTC"</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standardwert (z. B. "now")</td>
      <td><code>"defaultValue": "now"</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld</td>
      <td><code>"required": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🧪 Validierung</h3>
<ul>
  <li>Wert muss ein gültiges Datum/Uhrzeit im angegebenen <code>format</code> sein</li>
  <li>Bei <code>defaultValue: "now"</code> wird der aktuelle Zeitstempel eingefügt</li>
  <li>Automatik-Felder wie <code>auto_create_timestamp</code> oder <code>auto_modified_timestamp</code> ignorieren benutzerdefinierte Eingaben</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"created_at": {
  "dataType": "datetime",
  "auto_create_timestamp": true,
  "format": "Y-m-d H:i:s",
  "timezone": "UTC",
  "comment": "Setzt sich beim Insert automatisch"
},
"updated_at": {
  "dataType": "datetime",
  "auto_modified_timestamp": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Ideal für Logging, automatische Zeitstempel, zeitgesteuerte Prozesse</li>
  <li>Format kann an lokale Systeme angepasst werden (z. B. <code>d.m.Y H:i</code> für deutsche Darstellung)</li>
  <li><code>timezone</code> wirkt nur bei automatischer Timestamp-Erstellung</li>
</ul>




<hr class='content-sep'> 
<h2 id="type-date">📅 Datentyp: <code>date</code></h2>

<p>
  Der <code>date</code>-Typ speichert ausschließlich ein Datum – ohne Uhrzeit.
  Er eignet sich für Geburtsdaten, Fälligkeiten, Gültigkeiten oder historische Zeitpunkte.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>format</code></td>
      <td>Datumsformat nach PHP-Syntax</td>
      <td><code>"format": "Y-m-d"</code> (Standard)</td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standarddatum (z. B. <code>"now"</code>, <code>"2025-01-01"</code>)</td>
      <td><code>"defaultValue": "now"</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt <code>null</code> als gültigen Wert</td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🎯 Zulässige Formate</h3>
<ul>
  <li><code>Y-m-d</code> (Standard: 2025-04-18)</li>
  <li>Beliebige PHP-Formate wie <code>d.m.Y</code>, <code>m/d/Y</code>, etc.</li>
</ul>

<h3>🧪 Validierung</h3>
<ul>
  <li>Das Datum muss dem definierten <code>format</code> entsprechen</li>
  <li>Bei <code>"defaultValue": "now"</code> wird das heutige Datum automatisch eingesetzt</li>
  <li>Fehlerhafte Eingaben wie <code>"2025-99-99"</code> werden erkannt und abgelehnt</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"birthdate": {
  "dataType": "date",
  "format": "Y-m-d",
  "required": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Ohne Uhrzeit – ideal für klare Kalendertage</li>
  <li>Automatische Verarbeitung bei <code>insert()</code> möglich (mit <code>"now"</code>)</li>
  <li>Im Gegensatz zu <code>datetime</code> kein Zeitanteil enthalten</li>
</ul>





<hr class='content-sep'> 
<h2 id="type-time">🕒 Datentyp: <code>time</code></h2>

<p>
  Der <code>time</code>-Typ speichert ausschließlich Uhrzeiten ohne Datum.
  Ideal für Öffnungszeiten, Startzeiten, Erinnerungen oder Zeitpunkte innerhalb eines Tages.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>format</code></td>
      <td>Uhrzeit-Format nach PHP-Notation</td>
      <td><code>"format": "H:i:s"</code> (Standard)</td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Standardzeit (z. B. <code>"now"</code> oder <code>"08:00:00"</code>)</td>
      <td><code>"defaultValue": "now"</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td><code>null</code> ist erlaubt</td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🎯 Gültige Uhrzeitformate</h3>
<ul>
  <li><code>H:i:s</code> (24h – z. B. 15:45:00)</li>
  <li><code>H:i</code> (ohne Sekunden – z. B. 09:30)</li>
  <li><code>g:i A</code> (12h – z. B. 5:00 PM)</li>
</ul>

<h3>🧪 Validierung</h3>
<ul>
  <li>Uhrzeit muss dem angegebenen <code>format</code> entsprechen</li>
  <li><code>"defaultValue": "now"</code> setzt aktuelle Uhrzeit (Serverzeit)</li>
  <li>Ungültige Zeiten wie <code>"25:99"</code> oder <code>"ab:cd"</code> werden abgewiesen</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"open_at": {
  "dataType": "time",
  "format": "H:i",
  "defaultValue": "08:00"
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Kann mit <code>date</code> oder <code>datetime</code> kombiniert werden</li>
  <li>Perfekt für Zeitfenster (z. B. von/bis)</li>
  <li>Unterstützt sowohl 24h- als auch 12h-Formate (abhängig vom Formatstring)</li>
</ul>

<p class="mt-4">
  Weiter mit: 📚 <a href="#type-enum">Datentyp: enum</a>
</p>



<hr class='content-sep'> 
<h2 id="type-enum">📚 Datentyp: <code>enum</code></h2>

<p>
  Der <code>enum</code>-Typ erlaubt ausschließlich vordefinierte Werte, ähnlich wie Auswahllisten oder Drop-Downs. 
  Er ist ideal für feste Zustände wie Rollen, Kategorien, Farben oder Ja/Nein-Logiken mit Klartext.
</p>

<h3>🔧 Unterstützte Optionen</h3>

<table class="table table-bordered table-sm mt-3">
  <thead class="table-light">
    <tr>
      <th>Option</th>
      <th>Beschreibung</th>
      <th>Beispiel</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>enumValues</code></td>
      <td>Liste erlaubter Werte (Pflichtfeld)</td>
      <td><code>"enumValues": ["active", "inactive", "pending"]</code></td>
    </tr>
    <tr>
      <td><code>defaultValue</code></td>
      <td>Voreinstellung, wenn kein Wert übergeben wurde</td>
      <td><code>"defaultValue": "pending"</code></td>
    </tr>
    <tr>
      <td><code>required</code></td>
      <td>Pflichtfeld – darf nicht leer sein</td>
      <td><code>"required": true</code></td>
    </tr>
    <tr>
      <td><code>allowNULL</code></td>
      <td>Erlaubt <code>null</code> als gültigen Wert</td>
      <td><code>"allowNULL": true</code></td>
    </tr>
  </tbody>
</table>

<h3>🧪 Validierung</h3>
<ul>
  <li>Der eingegebene Wert muss exakt einem der <code>enumValues</code> entsprechen</li>
  <li>Groß-/Kleinschreibung wird beachtet (<code>case-sensitive</code>)</li>
  <li>Ungültige Eingaben werden automatisch abgelehnt</li>
</ul>

<h3>📦 Beispieldefinition</h3>
<pre><code class="language-json">
"status": {
  "dataType": "enum",
  "enumValues": ["open", "closed", "in_progress"],
  "defaultValue": "open",
  "required": true
}
</code></pre>

<h3>📌 Besonderheiten</h3>
<ul>
  <li>Pflicht zur Angabe der gültigen <code>enumValues</code></li>
  <li>Optimal für Drop-Downs, Filter und Statusanzeige</li>
  <li>Standardwerte wie <code>defaultValue</code> vereinfachen <code>insert()</code>-Aufrufe</li>
  <li>Kann mit <code>required</code> kombiniert werden</li>
</ul>

<p class="mt-4">
  🎉 Das war die Übersicht aller unterstützten Datentypen in JsonSQL!
</p>

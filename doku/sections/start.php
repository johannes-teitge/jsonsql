<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<!-- Einstieg in JsonSQL: Erste Demo -->
<section class="container mt-5 mb-5">
  <h1><i class="bi bi-lightning-fill"></i> Einstieg: Direkt loslegen statt Theorie pauken</h1>

  <p>Wir wollen dich nicht stundenlang mit trockener Theorie langweilen – stattdessen steigen wir direkt mit einer funktionierenden Demo ein. Du kannst sie hier live ausprobieren:</p>

  <div class="text-left my-4">
    <a href="<?= $baseUrl ?>/../demos/examples/hello-json-sql.php" target="_blank">
      <img src="assets/images/hello-json-sql.webp" alt="Erste Demo starten" class="img-fluid rounded shadow" style="max-width: 50%; height: auto;"> 
    </a>
  </div>

  <h3 class="mt-5"><i class="bi bi-terminal"></i> Was passiert in dieser Demo?</h3>

  <pre><code class="language-php">
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$table = 'hello';
$db->truncate($table);
$db->from($table)->insert([ 'name' => 'Alice', 'email' => 'alice@example.com' ]);
$db->insert([ 'name' => 'Bob', 'email' => 'bob@example.com' ]);
$rows = $db->from($table)->get();
  </code></pre>

  <h4 class="mt-4">🧩 Schritt für Schritt erklärt</h4>

  <h5>1. JsonSQL einbinden und Instanz erzeugen</h5>
  <p>Die Klasse wird per <code>require_once</code> eingebunden und dann mit einer Datenbank-Liste initialisiert:</p>
  <pre><code class="language-php">
require_once __DIR__ . '/../vendor/JsonSQL/src/JsonSQL.php';
use Src\JsonSQL;

$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
  </code></pre>

  <h5>2. Datenbank und Tabelle festlegen</h5>
  <p>Mit <code>$db->use('demo')</code> wählst du deine Datenbank, anschließend setzt du die Tabelle mit <code>setTable()</code> oder <code>from()</code>.</p>

  <p><strong>Unterschied:</strong></p>
  <ul>
    <li><code>setTable()</code>: speichert die Tabelle dauerhaft in der Instanz</li>
    <li><code>from()</code>: verwendet die Tabelle nur temporär</li>
    <li><code>truncate()</code>: leert oder erstellt die Tabelle neu – nützlich für Demos!</li>
  </ul>

  <h5>3. Daten einfügen mit <code>insert()</code></h5>
  <pre><code class="language-php">
$db->from($table)->insert([
  'name' => 'Alice',
  'email' => 'alice@example.com'
]);
  </code></pre>
  <p>Die Daten werden als Array mit Schlüssel/Wert-Paaren übergeben. JsonSQL legt die Felder bei Bedarf automatisch an. Auch eine zweite Zeile ist möglich:</p>
  <pre><code class="language-php">
$db->insert([ 'name' => 'Bob', 'email' => 'bob@example.com' ]);
  </code></pre>

  <h5>4. Daten abfragen mit <code>get()</code></h5>
  <p>Mit <code>get()</code> holst du alle Einträge. Weitere Details zu <code>select()</code>, <code>where()</code> und mehr folgen später.</p>

  <pre><code class="language-php">
$rows = $db->from($table)->get();
  </code></pre>

  <h5>5. Bonus: FancyVarDump aktiviert</h5>
  <p>Mit <strong>FancyDumpVar</strong> kannst du das <code>$db</code>-Objekt sowie die Rückgaben direkt inspizieren. Einfach über den Footer aktivieren oder im Code:</p>
  <pre><code class="language-php">
$debugger->addInfoText('JsonSQL Object');
$debugger->dump($db);
$debugger->addInfoText('Die fertigen Daten');
$debugger->dump($rows);
  </code></pre>

  <h4 class="mt-4">🖥️ Bootstrap-Ausgabe mit PHP-Loop</h4>
  <p>Die fertige Liste wird mit Bootstrap-HTML ausgegeben, das ward dann auch schon.</p>

  <h4 class="mt-5">✅ Fazit</h4>
  <p>Diese erste Demo zeigt dir den typischen JsonSQL-Workflow:</p>
  <ol>
    <li>Datenbank wählen: <code>use()</code></li>
    <li>Tabelle setzen: <code>from()</code> oder <code>setTable()</code></li>
    <li>Daten einfügen: <code>insert()</code></li>
    <li>Daten holen: <code>get()</code></li>
    <li>Daten anzeigen mit HTML oder Debugger</li>
  </ol>

  <p>Ohne echte Datenbank, ohne Setup, aber mit voller SQL-Logik – willkommen bei JsonSQL! 🚀</p>
</section>

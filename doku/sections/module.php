<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="module"><i class="bi bi-puzzle-fill"></i> Erweiterung & eigene Module</h1>

  <p>JsonSQL ist modular aufgebaut. Du kannst eigene Module erstellen, um die Funktionalität zu erweitern oder Speziallogik für dein Projekt einzubinden – ganz ohne die Hauptklasse zu verändern.</p>

  <h2 id="eigene-module">🧩 Neue Module erstellen & einbinden</h2>
  <p>Lege einfach eine neue Datei im <code>src/</code>-Verzeichnis an, z. B. <code>JS_CustomDemo.php</code>. Die Datei muss eine Trait-Definition enthalten, die dann in <code>JsonSQL.php</code> eingebunden wird:</p>

  <pre><code class="language-php">// Datei: src/JS_CustomDemo.php
trait JS_CustomDemo {
  public function sayHello($name) {
    return "Hallo, $name!";
  }
}</code></pre>

  <p>Danach fügst du die Datei in <code>JsonSQL.php</code> ein:</p>
  <pre><code class="language-php">require_once __DIR__ . '/JS_CustomDemo.php';
class JsonSQL {
  use JS_CustomDemo;
  // ...weitere Module...
}</code></pre>

  <h2 id="struktur-best-practice">🧱 Struktur & Best Practices</h2>
  <ul>
    <li>Jedes Modul als <code>JS_*.php</code> benennen</li>
    <li>Immer einen <code>trait</code> verwenden</li>
    <li>Methoden sprechend und eindeutig benennen</li>
    <li>Keine direkten <code>echo</code>-Ausgaben in Modulen – lieber Rückgabewerte verwenden</li>
    <li>Verwende interne Methoden wie <code>$this->load()</code> oder <code>$this->save()</code>, falls du mit Tabellen arbeitest</li>
  </ul>

  <h2 id="beispielmodule">📦 Beispiele für eigene Module</h2>
  <p>Einige Beispielmodule findest du in den Demos:</p>
  <ul>
    <li><code>JS_Export.php</code> – Export/Import von JSON-Daten</li>
    <li><code>JS_SQLParser.php</code> – SQL-kompatibler Query-Parser</li>
    <li><code>JS_System.php</code> – Automatische Felder und system.json-Regeln</li>
    <li><code>JS_CustomUUID.php</code> – eigenes Modul zur UUID-Erzeugung</li>
  </ul>

  <h2 id="live-demo">🚀 Demo: Custom Modul</h2>
  <ul>
    <li><a href="<?= $baseUrl ?>/../examples/demo_custom_module.php" target="_blank">Demo: Eigene Methode sayHello()</a></li>
  </ul>

  <p class="mt-4">Mit dieser modularen Struktur kannst du JsonSQL beliebig erweitern – ideal für eigene Projekte, Frameworks oder spezialisierte Tools.</p>
</section>

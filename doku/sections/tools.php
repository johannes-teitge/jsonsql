<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="tools"><i class="bi bi-tools"></i> Extras & Tools</h1>

  <p>JsonSQL bietet eine Reihe nützlicher Zusatzfunktionen für Entwickler, Power-User und Admins. Hier findest du praktische Tools wie Import/Export, Backupfunktionen, Locking-Mechanismen und eine einfache SQL-Parser-Schnittstelle.</p>

  <h2 id="import-export">📤 import() & 📥 export()</h2>
  <p>Du kannst Tabellen oder ganze Datenbanken als JSON-Dateien importieren oder exportieren – z. B. für Backups oder Migrationszwecke:</p>
  <pre><code class="language-php">
// Ganze Tabelle exportieren
$data = $db->from('users')->export();

// Importieren
$db->from('users')->import($data);
  </code></pre>

  <h2 id="locking">🔒 Locking & paralleler Zugriff</h2>
  <p>JsonSQL arbeitet mit Dateisperren, um gleichzeitige Schreibzugriffe abzusichern. Beim Öffnen einer Tabelle wird automatisch ein exklusiver <code>flock()</code> verwendet. Damit ist auch Multiuser-Zugriff auf dem gleichen Server möglich.</p>

  <p>Tipps:</p>
  <ul>
    <li>Immer <code>get()</code> oder <code>save()</code> korrekt abschließen</li>
    <li>Keine Dauerprozesse mit blockierenden Operationen!</li>
  </ul>

  <h2 id="backups">🗂️ Backupstrategien</h2>
  <p>Backups können automatisch oder manuell erfolgen. Aktiviere die Option <code>enableBackups(true)</code>, um bei jedem Speichern eine Kopie in <code>/backups</code> abzulegen:</p>
  <pre><code class="language-php">
$db->enableBackups(true);
  </code></pre>

  <p>Backups werden automatisch mit Zeitstempel versehen und in einem separaten Unterordner abgelegt.</p>

  <h2 id="query">📝 SQL-Parser mit query()</h2>
  <p>Mit <code>query()</code> kannst du einfache SQL-Befehle ausführen, die intern auf JsonSQL gemappt werden. Unterstützt werden aktuell:</p>
  <ul>
    <li><code>SELECT ... FROM ... WHERE ...</code></li>
    <li><code>INSERT INTO ...</code></li>
    <li><code>UPDATE ... SET ...</code></li>
    <li><code>DELETE FROM ...</code></li>
  </ul>
  <pre><code class="language-php">
$db->query("SELECT * FROM users WHERE age > 18");
  </code></pre>
  <p>Ideal für einfache API-Schnittstellen oder SQL-ähnliche Skripte.</p>

  <h2 id="debugging">🐞 Logging & Debugging (FancyDumpVar)</h2>
  <p>Für tieferen Einblick in die Funktionsweise kannst du das Tool <strong>FancyDumpVar</strong> nutzen. Es visualisiert interne Objekte, Status und Rückgaben direkt im Browser:</p>
  <pre><code class="language-php">
$debugger->addInfoText('Datenbankobjekt');
$debugger->dump($db);

$debugger->addInfoText('Letzte Einträge');
$debugger->dump($rows);
  </code></pre>

  <p>FancyDumpVar ist ideal bei der Plugin-Entwicklung, für Analysezwecke oder zum Nachvollziehen von komplexeren Abläufen.</p>
</section>

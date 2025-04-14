<section class="container mt-5 mb-5">
  <h1><i class="bi bi-diagram-3-fill"></i> Architektur & Grundlagen</h1>

  <p>Nachdem du in der Demo einen ersten Eindruck von JsonSQL bekommen hast, steigen wir nun etwas tiefer ein und schauen uns an, wie das System unter der Haube funktioniert. JsonSQL ist modular aufgebaut und besteht aus einer Hauptklasse sowie verschiedenen Spezialmodulen, die bestimmte Aufgaben übernehmen.</p>

  <h2 class="mt-4"><i class="bi bi-box"></i> Was ist JsonSQL?</h2>
  <p>JsonSQL ist eine leichtgewichtige PHP-Klasse, die SQL-ähnliche Operationen auf JSON-Dateien ermöglicht – ohne echte Datenbank im Hintergrund. Sie ist ideal für kleine Projekte, Admin-Tools, Demo-Anwendungen oder embedded Systeme, bei denen keine komplexe DB nötig oder möglich ist.</p>

  <p>Die Daten werden als einzelne Dateien im JSON-Format in einem Verzeichnis gespeichert und durch die Klasse mit Operationen wie <code>insert()</code>, <code>select()</code>, <code>update()</code> oder <code>delete()</code> verwaltet.</p>

  <h2 class="mt-4"><i class="bi bi-folder"></i> Projektstruktur und Modulaufbau</h2>
  <p>JsonSQL ist modular aufgebaut, um besser wartbar und erweiterbar zu bleiben. Die Kernstruktur liegt im Verzeichnis <code>src/</code>:</p>

  <pre><code class="language-bash">
src/
├── JsonSQL.php        # Hauptklasse (bindet alle Module ein)
├── JS_Base.php        # Basisklasse mit allgemeinen Hilfsfunktionen
├── JS_Filters.php     # Filterlogik (where, or, and, etc.)
├── JS_Select.php      # Select-Funktion inkl. Limit, OrderBy, GroupBy
├── JS_Insert.php      # Insert-Funktionen inkl. Auto-Felder
├── JS_Update.php      # Update-Logik
├── JS_Delete.php      # Delete-Funktion
├── JS_System.php      # system.json: AutoFelder, Validierung, Defaults, usw.
├── JS_Export.php      # Export- und Import-Tools
└── JS_SQLParser.php   # Parser für einfache SQL-Strings (optional)
  </code></pre>

  <p>Alle Module werden automatisch in der <code>JsonSQL.php</code> gebündelt. Du arbeitest immer nur mit einer Instanz – die interne Logik wird dynamisch über die eingebundenen Module abgewickelt.</p>

  <h2 class="mt-4"><i class="bi bi-play-circle-fill"></i> Eine Instanz erstellen</h2>
  <p>Um JsonSQL zu verwenden, erzeugst du eine Instanz und übergibst ein Array mit Datenbanknamen und Pfaden:</p>

  <pre><code class="language-php">
require_once __DIR__ . '/../src/JsonSQL.php';
use Src\JsonSQL;

$db = new JsonSQL([
  'demo' => __DIR__ . '/../testdb',
  'kunden' => __DIR__ . '/../kunden-db'
]);
$db->use('demo');
  </code></pre>

  <p>Du kannst mehrere Datenbanken definieren und bei Bedarf mit <code>use('name')</code> zwischen ihnen wechseln. Intern sind das jeweils nur Verzeichnisse mit JSON-Dateien.</p>

  <h2 class="mt-4"><i class="bi bi-database-fill-gear"></i> Datenstruktur & Verzeichnis-Layout</h2>
<p>Jede „Datenbank“ ist technisch ein Verzeichnis mit folgenden Inhalten:</p>

<ul>
  <li><strong><code>users.json</code></strong>: Die Tabelle <code>users</code> – pro Tabelle eine JSON-Datei</li>
  <li><strong><code>users.system.json</code></strong> (optional): Strukturdefinition, Regeln und Meta-Felder für <code>users.json</code></li>
  <li><strong><code>products.json</code></strong>: Weitere Tabelle</li>
  <li><strong><code>products.system.json</code></strong> (optional): Strukturdefinition für die Produkttabelle</li>
  <li><strong><code>backups/</code></strong> (optional): Backup-Dateien deiner Tabellen (wenn aktiviert)</li>
</ul>

<p>Beispielhafte Struktur:</p>
<pre><code class="language-bash">
testdb/
├── users.json
├── users.system.json
├── products.json
├── products.system.json
├── backups/
│   └── users_2024-12-01.json
└─
</code></pre>

<p>Jede Tabelle <code>[name].json</code> kann durch eine optionale <code>[name].system.json</code> ergänzt werden. Diese Datei enthält tabellenspezifische Regeln wie z. B. automatische Felder, erlaubte Typen, Pflichtfelder, Verschlüsselung oder Validierungen.</p>

<div class="alert alert-info mt-4">
  <strong>Tipp:</strong> Du kannst das Datenbankverzeichnis direkt im Explorer oder Editor öffnen und die JSON-Dateien bearbeiten – JsonSQL bleibt vollständig transparent.
</div>

<p class="mt-4">Jetzt, wo du die Grundstruktur kennst, gehen wir im nächsten Kapitel ins Detail: <strong><code>insert()</code>, <code>select()</code>, <code>update()</code> und <code>delete()</code></strong> im praktischen Einsatz.</p>

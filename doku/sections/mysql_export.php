<div class="doc-section">
  <h1 id="mysql-export"><i class="bi bi-box-arrow-up-right"></i> MySQL-Export</h1>

  <p>
    JsonSQL bietet die Möglichkeit, deine JSON-Tabellen samt <code>system.json</code>-Definitionen in gültige <strong>MySQL CREATE TABLE</strong>-Anweisungen zu exportieren. Das ist besonders praktisch, wenn du bestehende Daten in eine klassische SQL-Datenbank migrieren oder externe Tools anbinden möchtest.
  </p>

  <h5>🚀 Funktionen im Überblick</h5>
  <ul>
    <li><code>ExportMySQLCreate($table)</code>: Gibt ein <strong>CREATE TABLE</strong>-Statement für eine bestimmte Tabelle zurück.</li>
    <li><code>ExportMySQLData($table)</code>: Gibt alle <strong>INSERT INTO</strong>-Statements der Datensätze einer Tabelle zurück.</li>
    <li><code>ExportMySQLFull($table)</code>: Kombiniert <code>CREATE</code> und <code>INSERT</code> für eine Tabelle.</li>
    <li><code>ExportMySQLCreateAll()</code>: Gibt CREATE-Statements für alle vorhandenen Tabellen zurück.</li>
    <li><code>ExportMySQLDataAll()</code>: Gibt INSERTs für alle Tabellen zurück.</li>
    <li><code>ExportMySQLFullAll()</code>: Gibt einen vollständigen Dump (Struktur + Daten) für alle Tabellen zurück.</li>
  </ul>

  <p>
    Du kannst die Funktionen direkt im Code verwenden – oder bequem per URL-Parameter auslösen:
  </p>

  <h5>🧭 Beispiele für URL-Aufrufe</h5>
  <ul>
    <li><code>?table=students</code> → Nur <strong>Struktur</strong> (CREATE)</li>
    <li><code>?table=students&data=1</code> → Nur <strong>Daten</strong> (INSERTs)</li>
    <li><code>?table=students&full=1</code> → <strong>Komplette Tabelle</strong> (CREATE + INSERT)</li>
    <li><code>?all=1&create=1</code> → <strong>Alle CREATE-Statements</strong></li>
    <li><code>?all=1&data=1</code> → <strong>Alle INSERTs</strong></li>
    <li><code>?all=1&full=1</code> → <strong>Komplettes SQL-Dump</strong> (für alle Tabellen)</li>
  </ul>

  <h5>🧪 Beispielausgabe</h5>
  <pre><code class="language-sql">
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255),
  `email` VARCHAR(255),
  `created_at` DATETIME
);

INSERT INTO `students` (`id`, `name`, `email`, `created_at`) VALUES
(1, 'Anna Beispiel', 'anna@example.com', '2024-10-03 14:00:00'),
(2, 'Bert Nutzer', 'bert@example.com', '2024-10-03 14:10:00');
  </code></pre>

  <h5>📦 Export in der Demo nutzen</h5>
  <p>
    In der n:m-Demo <code><a href="../demos/nm_students/" target=_blank>/demos/nm_students/</a></code> kannst du dir die <strong>MySQL-Definition jeder Tabelle</strong> direkt per Button anzeigen lassen – inkl. Struktur- und Datendump.
  </p>

  <p class="alert alert-info mt-3">
    🔒 Hinweis: Der Export funktioniert nur für Tabellen mit einer vorhandenen <code>.system.json</code>-Datei, da hier die Felddefinitionen und Datentypen festgelegt sind.
  </p>
</div>

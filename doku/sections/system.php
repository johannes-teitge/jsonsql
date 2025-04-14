<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="system"><i class="bi bi-sliders"></i> Auto-Felder & Systemkonfiguration</h1>

  <p>Mit Hilfe einer <code>system.json</code>-Datei pro Tabelle kannst du die Verarbeitung von Feldern automatisieren, validieren, verschlüsseln und Typen festlegen. JsonSQL nutzt diese Datei, um intelligente Feldlogik bei <code>insert()</code> und <code>update()</code> auszuführen.</p>

  <h2 id="system-struktur">📁 system.json je Tabelle</h2>
  <p>Für jede Tabelle kannst du eine eigene <code>system.json</code> Datei anlegen, z. B. <code>users.system.json</code>. Diese beschreibt die Felder, ihre Typen, Standardwerte und Regeln:</p>
  <pre><code class="language-json">
{
  "id": { "type": "autoincrement" },
  "uuid": { "type": "autouuid" },
  "created_at": { "type": "timestamp:create" },
  "updated_at": { "type": "timestamp:update" },
  "email": { "required": true, "type": "string" },
  "password": { "type": "encrypt" },
  "role": { "type": "enum", "values": ["user", "admin"] }
}
  </code></pre>

  <h2 id="system-typen">🔑 Unterstützte Typen & Funktionen</h2>
  <ul>
    <li><strong>autoincrement</strong> – Zähler, der automatisch hochzählt</li>
    <li><strong>autohash</strong> – generiert Hash aus Inhalten (z. B. für Checksummen)</li>
    <li><strong>autouuid</strong> – erzeugt eindeutige UUIDv4</li>
    <li><strong>timestamp:create</strong> – setzt Zeitstempel bei Erstellung</li>
    <li><strong>timestamp:update</strong> – aktualisiert Zeitstempel bei Änderung</li>
    <li><strong>encrypt</strong> – verschlüsselt das Feld bei Speicherung</li>
    <li><strong>decrypt</strong> – entschlüsselt das Feld automatisch beim Lesen</li>
    <li><strong>default</strong> – Standardwert bei leerem Feld</li>
    <li><strong>required</strong> – Pflichtfeld (Validierung)</li>
    <li><strong>enum</strong> – Wert muss aus festgelegter Liste stammen</li>
  </ul>

  <h2 id="system-anwendung">⚙️ Anwendung & Verhalten</h2>
  <p>Wenn du Daten mit <code>insert()</code> oder <code>update()</code> speicherst, prüft JsonSQL automatisch auf alle system.json-Regeln und ergänzt oder validiert Felder entsprechend. Du musst dich nicht um IDs, Timestamps oder Pflichtfelder kümmern – das geschieht automatisch.</p>

  <h2 id="system-beispiele">🚀 Live-Beispiele</h2>
  <ul>
    <li><a href="<?= $baseUrl ?>/../examples/demo_autoincrement.php" target="_blank">Demo: autoincrement</a></li>
    <li><a href="<?= $baseUrl ?>/../examples/demo_system_fields.php" target="_blank">Demo: timestamp &amp; default</a></li>
    <li><a href="<?= $baseUrl ?>/../examples/demo_encryption.php" target="_blank">Demo: encryption &amp; decryption</a></li>
  </ul>

  <p class="mt-4">Weitere Regeln wie <code>regex</code>, <code>minlength</code>, <code>maxlength</code> und benutzerdefinierte Validierung sind geplant.</p>
</section>

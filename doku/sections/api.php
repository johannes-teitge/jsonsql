<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<section class="container mt-5 mb-5">
  <h1 id="api"><i class="bi bi-journal-code"></i> API-Referenz</h1>

  <p>Die folgende API-Referenz listet alle öffentlichen Methoden der JsonSQL-Klasse mit Signatur, Beschreibung, Parametern und Rückgabewerten auf. Sie eignet sich als Nachschlagewerk für Entwickler.</p>

  <h2 id="api-insert">📥 insert()</h2>
  <p><strong>Beschreibung:</strong> Fügt einen oder mehrere Datensätze in die aktive Tabelle ein.</p>
  <pre><code class="language-php">insert(array|array[] $data): array</code></pre>
  <ul>
    <li><strong>$data</strong>: Einzelnes Array oder Array von Arrays mit Feldern und Werten</li>
  </ul>
  <p><strong>Rückgabe:</strong> Array mit eingefügten Einträgen (inkl. Auto-Felder)</p>

  <h2 id="api-update">🛠️ update()</h2>
  <p><strong>Beschreibung:</strong> Aktualisiert Einträge gemäß Filterkriterien.</p>
  <pre><code class="language-php">update(array $values): int</code></pre>
  <ul>
    <li><strong>$values</strong>: Felder und neue Werte</li>
  </ul>
  <p><strong>Rückgabe:</strong> Anzahl der aktualisierten Datensätze</p>

  <h2 id="api-delete">🗑️ delete()</h2>
  <p><strong>Beschreibung:</strong> Löscht Einträge gemäß aktueller Filterung.</p>
  <pre><code class="language-php">delete(): int</code></pre>
  <p><strong>Rückgabe:</strong> Anzahl der gelöschten Einträge</p>

  <h2 id="api-get">🔎 get()</h2>
  <p><strong>Beschreibung:</strong> Führt die aktuelle Abfrage aus und gibt das Ergebnis zurück.</p>
  <pre><code class="language-php">get(): array</code></pre>
  <p><strong>Rückgabe:</strong> Liste der Datensätze (ggf. gefiltert, sortiert etc.)</p>

  <h2 id="api-where">🔍 where()</h2>
  <p><strong>Beschreibung:</strong> Fügt eine Filterbedingung hinzu.</p>
  <pre><code class="language-php">where(string $field, string $operator, mixed $value): self</code></pre>

  <h2 id="api-join">🔗 join()</h2>
  <p><strong>Beschreibung:</strong> Verknüpft eine weitere Tabelle mit der aktuellen via Join.</p>
  <pre><code class="language-php">join(string $table, string $field1, string $operator, string $field2, string $type = 'inner'): self</code></pre>

  <h2 id="api-pluck">🎯 pluck()</h2>
  <p><strong>Beschreibung:</strong> Gibt eine Spalte als flaches Array zurück.</p>
  <pre><code class="language-php">pluck(string $field): array</code></pre>

  <h2 id="api-first">🥇 first()</h2>
  <p><strong>Beschreibung:</strong> Gibt das erste Ergebnis der Abfrage zurück.</p>
  <pre><code class="language-php">first(): ?array</code></pre>

  <h2 id="api-clear">♻️ clear()</h2>
  <p><strong>Beschreibung:</strong> Löscht alle Datensätze in der aktuellen Tabelle.</p>
  <pre><code class="language-php">clear(): bool</code></pre>

  <h2 id="api-paginate">📄 paginate()</h2>
  <p><strong>Beschreibung:</strong> Gibt eine paginierte Liste mit Meta-Daten zurück.</p>
  <pre><code class="language-php">paginate(int $page, int $limit): array</code></pre>

  <h2 id="api-stats">📊 stats()</h2>
  <p><strong>Beschreibung:</strong> Gibt Statistikwerte zu einem Feld zurück.</p>
  <pre><code class="language-php">stats(string $field): array</code></pre>

  <h2 id="api-query">📝 query()</h2>
  <p><strong>Beschreibung:</strong> Führt einen einfachen SQL-Befehl aus.</p>
  <pre><code class="language-php">query(string $sql): mixed</code></pre>

  <p class="mt-4">Weitere Hilfsfunktionen wie <code>use()</code>, <code>setTable()</code>, <code>enableBackups()</code>, <code>enableTrashMode()</code> usw. findest du in den entsprechenden Abschnitten der Dokumentation.</p>
</section>

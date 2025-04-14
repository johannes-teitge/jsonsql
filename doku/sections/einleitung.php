<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<div class="container">
        <h1><i class="bi bi-chat-text"></i> Einführung</h1>  
        <p>Die leistungsstarke, flexible und leichtgewichtige Lösung für SQL-ähnliche Abfragen auf JSON-Daten.</p>
</div>

<section class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Was ist JsonSQL?</h2>
            <p>JsonSQL ist eine <strong>SQL-ähnliche Abfragesprache</strong> speziell für die Arbeit mit JSON-Daten. Es erlaubt dir, Daten zu filtern, zu sortieren, zu gruppieren, zu verbinden und zu aggregieren – alles in der Art und Weise, wie du es von SQL-Datenbanken kennst. Aber statt auf komplexe relationale Datenbanken angewiesen zu sein, arbeitet JsonSQL direkt mit deinen <strong>JSON-Dateien</strong> und bietet dir eine einfache Möglichkeit, deine Daten abzufragen und zu bearbeiten.</p>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container">
    <div class="row feature-item-outer">
        <!-- 1. Feature -->
        <div class="col-12 col-md-4 feature-item">
            <i class="bi bi-lightbulb"></i>
            <h3>Einfachheit</h3>
            <p>Keine komplexen Datenbankverbindungen – JsonSQL arbeitet direkt mit deinen JSON-Daten.</p>
        </div>
        <!-- 2. Feature -->
        <div class="col-12 col-md-4 feature-item">
            <i class="bi bi-speedometer"></i>
            <h3>Leistung</h3>
            <p>JsonSQL ermöglicht schnelle SQL-ähnliche Abfragen auf JSON-Dateien, ohne eine Datenbank zu benötigen.</p>
        </div>
        <!-- 3. Feature -->
        <div class="col-12 col-md-4 feature-item">
            <i class="bi bi-arrows-move"></i>
            <h3>Flexibilität</h3>
            <p>Ideal für kleine bis mittlere Datenmengen. Leicht in Webanwendungen und APIs zu integrieren.</p>
        </div>
    </div>
</section>


<!-- Why Choose JsonSQL Section -->
<section class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Warum JsonSQL?</h2>
            <ul>
                <li><strong>Einfachheit</strong>: Du musst keine komplexen Datenbankverbindungen oder Konfigurationen einrichten. JsonSQL arbeitet direkt mit deinen bestehenden JSON-Daten.</li>
                <li><strong>Leistung</strong>: JsonSQL bietet eine schnelle, in-memory Verarbeitung von Daten und ermöglicht schnelle SQL-ähnliche Abfragen auf JSON-Dateien.</li>
                <li><strong>Flexibilität</strong>: JsonSQL ist einfach zu integrieren und funktioniert hervorragend mit bestehenden Webanwendungen und APIs.</li>
                <li><strong>Vielseitigkeit</strong>: Du kannst JsonSQL in einer Vielzahl von Szenarien verwenden – sei es zum Erstellen von Prototypen, für Tests, bei kleinen Datenmengen oder als Alternative zu klassischen Datenbanken.</li>
            </ul>
        </div>
    </div>
</section>


<div class="container">
  <h2 class="mb-4">🔍 Vergleich: <strong>Klassische Datenbank</strong> vs. <strong>JsonSQL</strong></h2>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>Merkmal</th>
          <th>Klassische Datenbank<br><small class="text-muted">(z. B. MySQL, PostgreSQL)</small></th>
          <th><span class="text-warning fw-bold">JsonSQL</span></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Installation</th>
          <td>Muss installiert &amp; konfiguriert werden</td>
          <td>Keine Installation nötig<br><small>(nur PHP + JSON)</small></td>
        </tr>
        <tr>
          <th>Verbindung</th>
          <td>Benötigt Datenbank-Server &amp; Verbindung</td>
          <td>Arbeitet direkt mit lokalen JSON-Dateien</td>
        </tr>
        <tr>
          <th>Komplexität</th>
          <td>Hoch – mit Benutzer, Rechten, Tabellen etc.</td>
          <td>Einfach – JSON-Dateien als Datenbasis</td>
        </tr>
        <tr>
          <th>Abfrage-Sprache</th>
          <td>SQL (Structured Query Language)</td>
          <td>SQL-ähnlich, speziell für JSON</td>
        </tr>
        <tr>
          <th>Leistung</th>
          <td>Sehr performant bei großen Datenmengen</td>
          <td>Schnell bei kleinen/mittleren Daten</td>
        </tr>
        <tr>
          <th>Speicherung</th>
          <td>Relationale Tabellen</td>
          <td>Strukturierte JSON-Dateien</td>
        </tr>
        <tr>
          <th>Einsatzgebiet</th>
          <td>Enterprise, große Webanwendungen</td>
          <td>Prototypen, kleine Projekte, APIs</td>
        </tr>
        <tr>
          <th>Abhängigkeiten</th>
          <td>DB-Server, Treiber, evtl. ORM</td>
          <td>Keine externen Abhängigkeiten</td>
        </tr>
        <tr>
          <th>Portabilität</th>
          <td>Weniger flexibel (Dump/Import nötig)</td>
          <td>Leicht kopierbar als einfache Dateien</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>



<!-- Call to Action Section -->
<section class="container mt-5 mb-5">
    <h3><i class="bi bi-arrow-right-square-fill"></i> Bereit, loszulegen?</h3>
    <p>Diese Dokumentation führt dich Schritt für Schritt durch alle grundlegenden Funktionen von JsonSQL. Du wirst lernen, wie du die API effizient nutzt und dabei die Kraft von SQL-ähnlichen Abfragen für deine JSON-Daten entfesseln kannst.</p>
    <p>Aber am schnellsten kommst du rein durch <strong>Learning by Doing</strong> – unsere Demos helfen dir dabei, direkt loszulegen und JsonSQL im echten Einsatz zu erleben!</p>

    <!-- Verlinktes Bannerbild -->
    <div class="text-center mt-4">
        <a href="<?= $baseUrl ?>/../demos/examples/" target="_blank">
            <img src="assets/images/demos-banner.webp" alt="JsonSQL Demos" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
        </a>
    </div>

    <!-- Optionaler Button darunter -->
    <div class="text-center mt-3">
        <a href="<?= $baseUrl ?>/../demos/examples/" target="_blank" class="btn btn-success btn-lg">
            <i class="bi bi-lightning-charge-fill"></i> Jetzt JsonSQL-Demos entdecken
        </a>
    </div>
</section>

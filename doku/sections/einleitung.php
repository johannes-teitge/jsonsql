<?php
$baseUrl = dirname($_SERVER['PHP_SELF']);
?>
<div class="container" style="margin-bottom: 45px;">
        <h1><i class="bi bi-chat-text"></i> Einführung</h1>  
        <p>Die leistungsstarke, flexible und leichtgewichtige Lösung für SQL-ähnliche Abfragen auf JSON-Daten.</p>

        <div class="mt-4 d-flex flex-wrap gap-3">

          <!-- GitHub Button -->
          <a href="https://github.com/johannes-teitge/JsonSQL" target="_blank" class="btn-download-github pulsating-download d-inline-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 8px;" width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
              <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 
              7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49
              -2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13
              -.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 
              1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07
              -1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15
              -.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82a7.54 
              7.54 0 0 1 2-.27c.68 0 1.36.09 2 .27 1.53-1.04 
              2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 
              1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 
              1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 
              8.01 0 0 0 16 8c0-4.42-3.58-8-8-8Z"/>
            </svg>
            Download über GitHub
          </a>

          <!-- Demo Button -->
           <!--
          <a href="<?= $baseUrl ?>/../demos/examples/" target="_blank" class="btn btn-primary d-inline-flex align-items-center demo-nav-link">
            <span class="me-2 d-inline-flex align-items-center" style="height: 20px;">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 117 148" width="20" height="20" style="display: block;" fill="currentColor">
                <path d="M73.205,5.462l35.75,39l-2.75,79l-99.25,3.75l0,-121.75l66.25,0Z" style="fill:#fff;stroke:#000;stroke-width:0.75px;"/>
                <path d="M116.25,40.445l0,95.68c0,6.416 -5.209,11.625 -11.625,11.625l-93,0c-6.416,0 -11.625,-5.209 -11.625,-11.625l0,-124.5c0,-6.416 5.209,-11.625 11.625,-11.625l64.18,0l40.445,40.445Zm-43.845,17.911l-11.25,0l-16.406,57.706l11.25,0l16.406,-57.706Zm8.913,2.816l-7.35,7.35l17.212,17.212l-16.744,16.744l7.35,7.35l24.094,-24.094l-24.562,-24.562Zm22.853,-15.696l-30.953,-30.952l0,26.195c0,2.625 2.132,4.757 4.758,4.757l26.195,0Zm-68.96,64.352l7.35,-7.35l-17.211,-17.212l16.743,-16.744l-7.35,-7.35l-24.093,24.094l24.561,24.562Z" style="fill:#0076cd;"/>
              </svg>
            </span>
            Entdecke JsonSQL live ➤ einfach ausprobieren!
          </a>
          -_>

          <!-- Demo Button mit weißem Icon -->
          <a href="<?= $baseUrl ?>/../demos/examples/" target="_blank" class="btn btn-primary d-inline-flex align-items-center demo-nav-link">
            <span class="me-2 d-inline-flex align-items-center" style="height: 20px;">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 117 148" width="20" height="20" style="display: block;">
                <g>
                  <path d="M73.205,5.462l35.75,39l-2.75,79l-99.25,3.75l0,-121.75l66.25,0Z" style="fill:#0076cd;stroke:#000;stroke-width:0.75px;"/>
                  <path d="M116.25,40.445l0,95.68c0,6.416 -5.209,11.625 -11.625,11.625l-93,0c-6.416,0 -11.625,-5.209 -11.625,-11.625l0,-124.5c0,-6.416 5.209,-11.625 11.625,-11.625l64.18,0l40.445,40.445Zm-43.845,17.911l-11.25,0l-16.406,57.706l11.25,0l16.406,-57.706Zm8.913,2.816l-7.35,7.35l17.212,17.212l-16.744,16.744l7.35,7.35l24.094,-24.094l-24.562,-24.562Zm22.853,-15.696l-30.953,-30.952l0,26.195c0,2.625 2.132,4.757 4.758,4.757l26.195,0Zm-68.96,64.352l7.35,-7.35l-17.211,-17.212l16.743,-16.744l-7.35,-7.35l-24.093,24.094l24.561,24.562Z" style="fill:#fff;"/>
                </g>
              </svg>
            </span>
            Entdecke JsonSQL live ➤ einfach ausprobieren!
          </a>          

        </div>

</div>

<div class="animated-divider"></div>



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

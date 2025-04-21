<?php

// Tabellen zählen
$counts = [
  '📖 Kurse'       => $db->setTable($table_courses, true)->count(),
  '👩‍🏫 Dozenten'    => $db->setTable($table_teachers, true)->count(),
  '🧑‍🎓 Studenten'   => $db->setTable($table_students, true)->count(),
  '🏫 Klassen'     => $db->setTable($table_classes, true)->count(),
  '📌 Belegungen'  => $db->setTable($table_enrollments, true)->count()
];
?>



<div class="p-4 bg-light rounded shadow-sm mb-4">
  <h2 class="mb-3">📚 Kursverwaltung – Übersicht</h2>
  <p>Willkommen zur <code>JsonSQL</code>-Demo für Kurs-, Dozenten- und Teilnehmerverwaltung.</p>
  <ul>
    <li>📖 <strong>Kurse</strong>: Welche Kurse gibt es? Wer unterrichtet wann?</li>
    <li>👩‍🏫 <strong>Dozenten</strong>: Wer sind die Lehrkräfte?</li>
    <li>🧑‍🎓 <strong>Studenten</strong>: Welche Schüler sind in welchen Klassen?</li>
    <li>📌 <strong>Belegungen</strong>: Wer ist in welchem Kurs angemeldet?</li>
  </ul>

  <p class="mt-4">
    🔄 Falls du neue Zufallsdaten brauchst: 
    <a href="create_data.php" class="btn btn-sm btn-danger ms-2">Demodaten erzeugen</a>
  </p>
</div>

<style>
  .tick-card-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
  }

  .tick-card {
    flex: 0 1 calc(20% - 1rem); /* 5 Boxen mit etwas Abstand */
    min-width: 200px !important;          /* Optional: Minimalbreite */
  }

  @media (max-width: 768px) {
    .tick-card {
      flex: 0 1 calc(50% - 1rem); /* 2 pro Zeile auf kleinen Geräten */
    }
  }

  @media (max-width: 480px) {
    .tick-card {
      flex: 0 1 100%; /* 1 pro Zeile auf ganz kleinen Geräten */
    }
  }

  .card-title {
    margin-bottom: 20px;
  }

  .tick-card {
    background: linear-gradient(to bottom, #ffffff, #c2c2c29b);
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(82, 82, 82, 0.58) !important;   
  }

</style>

<div class="tick-card-row">
  <?php foreach ($counts as $label => $num): 
    $jsLabel = str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9]/', '', $label));
    $digits = strlen((string)$num);
    $pad = str_repeat(' ', max(0, $digits - 1));
  ?>
    <div class="tick-card card shadow-sm">
      <div class="card-body text-center">
        <h5 class="card-title"><?= $label ?></h5>
        <div class="tick" data-value="0" data-did-init="handleTickInit_<?= $jsLabel ?>">
          <div data-layout="horizontal center" data-repeat="true"
               data-transform="arrive(9, .001) -> round -> <?= $pad ? "pad('$pad') -> " : "" ?>split -> delay(rtl, 100, 150)">
            <span data-view="flip"></span>
          </div>
        </div>
        <script>
          function handleTickInit_<?= $jsLabel ?>(tick) {
            tick._credits = null;
            setTimeout(() => { tick.value = <?= $num ?>; }, 150);
          }
        </script>
      </div>
    </div>
  <?php endforeach; ?>
</div>


<script>
document.querySelectorAll('.tick').forEach(el => {
  const tick = new Flip({
    node: el,
    from: 0,
    to: el.dataset.value,
    duration: 1200
  });
});
</script>


<div class="p-4 bg-light rounded shadow-sm mb-4">
  <h2 class="mb-3">🔁 JsonSQL Demo: Kurs- und Teilnehmerverwaltung</h2>
  <p>
    Diese Demo zeigt dir ein typisches Schul- oder Kursverwaltungssystem mit mehreren Datenbeziehungen, unter anderem:
  </p>
  <ul>
    <li><strong>Dozenten</strong> unterrichten <strong>Kurse</strong></li>
    <li><strong>Schüler</strong> gehören <strong>Klassen</strong> an</li>
    <li><strong>Schüler</strong> können sich für mehrere <strong>Kurse</strong> einschreiben (n:m-Beziehung)</li>
  </ul>

  <p>
    Das Besondere an dieser Demo: Sie verwendet ausschließlich <code>JsonSQL</code> – ein leichtgewichtiges, dateibasiertes Datenbanksystem, das SQL-ähnliche Abfragen auf JSON-Dateien erlaubt. Perfekt für kleine Projekte, lokale Tools und Demos ohne große Datenbank-Infrastruktur.
  </p>

  <h5 class="mt-4">🧠 Was du beim Durchstöbern lernen kannst:</h5>
  <ul>
    <li>Wie man n:m-Beziehungen modelliert (z. B. <code>enrollments</code>-Tabelle)</li>
    <li>Wie Daten verknüpft, zusammengeführt und gruppiert werden</li>
    <li>Wie man strukturierte Tabellen, Filter und verlinkte Detailansichten mit PHP & Bootstrap aufbaut</li>
    <li>Wie man Quellcode sinnvoll strukturiert, um wartbaren und nachvollziehbaren Code zu erhalten</li>
  </ul>

  <h5 class="mt-4">📤 Optionaler Export nach MySQL</h5>
  <p>
    Ein besonderes Highlight dieser Demo ist die Möglichkeit, alle Datenstrukturen automatisch in
    <strong>MySQL-kompatiblen SQL-Code</strong> umzuwandeln:
  </p>
  <ul>
    <li>Generiert vollständige <code>CREATE TABLE</code>-Anweisungen basierend auf den <code>.system.json</code>-Dateien</li>
    <li>Optional auch <code>INSERT</code>-Statements für bestehende Daten</li>
    <li>Ideal für Migrationen oder produktive Umgebungen mit MySQL-Backends</li>
  </ul>
  <p>
    Du findest diese Funktion im Abschnitt <strong>„📤 MySQL Export“</strong> direkt in der Demo-Oberfläche.
  </p>

  <p>
    Der Quellcode ist offen und auf GitHub verfügbar – du kannst alles nachvollziehen und für deine eigenen Projekte anpassen.
  </p>
  <p>
    📎 Die <strong>automatische Anzeige des Quellcodes</strong> ist bei dieser Demo bewusst deaktiviert, da die Dateien zahlreich und umfangreich sind. Du findest den Code aber zentral im <code>/demos/nm_students/</code>-Verzeichnis deines Projekts.
  </p>

  <p class="mt-4 mb-0">
    Viel Spaß beim Erkunden dieser umfangreichen und praxisnahen Demo!
  </p>
</div>

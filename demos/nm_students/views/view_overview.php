<?php

// Tabellen zählen
$counts = [
  'Kurse'       => $db->setTable($table_courses, true)->count(),
  'Dozenten'    => $db->setTable($table_teachers, true)->count(),
  'Studenten'   => $db->setTable($table_students, true)->count(),
  'Klassen'     => $db->setTable($table_classes, true)->count(),
  'Belegungen'  => $db->setTable($table_enrollments, true)->count()
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

<div class="row text-center">
  <?php foreach ($counts as $label => $num): ?>
    <div class="col-md-2 mb-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= $label ?></h5>
          <p class='numbers'><?= $num ?></p>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>




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
      
        <p>
          Der Quellcode ist offen und auf GitHub verfügbar – du kannst alles nachvollziehen und für deine eigenen Projekte anpassen.
        </p>
        <p>
          📎 Die **automatische Anzeige des Quellcodes** ist bei dieser Demo bewusst deaktiviert, da die Dateien zahlreich und umfangreich sind. Du findest den Code aber zentral im <code>/demos/nm_students/</code>-Verzeichnis deines Projekts.
        </p>
      
        <p class="mt-4 mb-0">
          Viel Spaß beim Erkunden dieser umfangreichen und praxisnahen Demo!
        </p>
      </div>
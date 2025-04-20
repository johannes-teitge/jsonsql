<?php

// Wenn ein Schüler ausgewählt wurde, Detailansicht anzeigen
if (isset($_GET['id'])) {
  $studentId = (int) $_GET['id'];
  $student = $db->setTable($table_students, true)->where([['id', '=', $studentId]])->first();

  if (!$student) {
    echo "<div class='alert alert-danger'>❌ Schüler nicht gefunden.</div>";
    return;
  }

  $class = $db->setTable($table_classes)->where([['id', '=', $student['class_id']]])->first();
  $className = $class['name'] ?? '❓ unbekannt';

  echo "<h2>🧑‍🎓 Schüler: {$student['firstname']} {$student['lastname']}</h2>";
  echo "<p><strong>E-Mail:</strong> <a href='mailto:{$student['email']}'>{$student['email']}</a></p>";
  echo "<p><strong>Klasse:</strong> {$className}</p>";

  // Belegte Kurse
  $enrollments = $db->setTable($table_enrollments)->where([['student_id', '=', $studentId]])->get();
  $courseIds = array_column($enrollments, 'course_id');

  if (empty($courseIds)) {
    echo "<p><em>Keine belegten Kurse</em></p>";
  } else {
    $courses = $db->setTable($table_courses)->where([['id', 'IN', $courseIds]])->get();

    // Dozenten zuordnen
    $teacherMap = [];
    foreach ($db->setTable($table_teachers)->select('*')->get() as $t) {
      $teacherMap[$t['id']] = "{$t['firstname']} {$t['lastname']}";
    }

    echo "<h4 class='mt-4'>📚 Belegte Kurse:</h4>";
    echo "<table class='table table-sm table-bordered'>";
    echo "<thead class='table-light'><tr><th>Fach</th><th>Stufe</th><th>Tag</th><th>Zeit</th><th>Dozent</th></tr></thead><tbody>";

    foreach ($courses as $course) {
      $teacher = $teacherMap[$course['teacher_id']] ?? '❓';
      echo "<tr>
              <td>{$course['subject']}</td>
              <td>{$course['level']}</td>
              <td>{$course['weekday']}</td>
              <td>{$course['time_from']} – {$course['time_to']}</td>
              <td>{$teacher}</td>
            </tr>";
    }

    echo "</tbody></table>";
  }

  echo "<a href='?view=students' class='btn btn-sm btn-secondary mt-3'>Zurück zur Übersicht</a>";
  return;
}

?>
<!-- Falls keine ID gesetzt ist: normale Schülerliste -->

<?php

// Schüler laden
$students = $db->setTable($table_students, true)->select('*')->get();

// Klassenmap aufbauen
$classMap = [];
foreach ($db->setTable($table_classes)->select('*')->get() as $c) {
  $classMap[$c['id']] = $c['name'] ?? '❓';
}

?>

<h2 class="mb-4">🧑‍🎓 Schülerliste</h2>

<table class="table table-striped table-bordered align-middle table-sortable">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>E-Mail</th>
      <th>Klasse</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($students as $s): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td>
          <a href="?view=students&id=<?= $s['id'] ?>">
            <?= htmlspecialchars($s['firstname'] . ' ' . $s['lastname']) ?>
          </a>
        </td>
        <td>
          <a href="mailto:<?= htmlspecialchars($s['email']) ?>">
            <?= htmlspecialchars($s['email']) ?>
          </a>
        </td>
        <td>
          <?= htmlspecialchars($classMap[$s['class_id']] ?? '❓ unbekannt') ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

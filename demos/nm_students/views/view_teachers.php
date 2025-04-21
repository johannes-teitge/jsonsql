<?php

// Optional: Einzelansicht per ID
if (isset($_GET['id'])) {
  $teacherId = (int) $_GET['id'];
  $teacher = $db->setTable($table_teachers, true)->where([['id', '=', $teacherId]])->First();

  if (!$teacher) {
    echo "<div class='alert alert-danger'>❌ Dozent nicht gefunden.</div>";
    return;
  }

  echo "<h2>👩‍🏫 Dozent: {$teacher['firstname']} {$teacher['lastname']}</h2>";
  echo "<p><strong>E-Mail:</strong> {$teacher['email']}</p>";

  if (!empty($teacher['description'])) {
    echo "<h4 class='mt-4'>📝 Beschreibung</h4>";
    echo (str_contains($teacher['description'], '<p>') || str_contains($teacher['description'], '<br'))
        ? $teacher['description']
        : "<p>" . nl2br(htmlspecialchars($teacher['description'])) . "</p>";
}


  // Optional: Kurse dieses Dozenten
  $courses = $db->setTable($table_courses)->where([['teacher_id', '=', $teacherId]])->get();

  if ($courses) {
    echo "<h4 class='mt-4'>📚 Kurse:</h4>";
    echo "<ul>";
    foreach ($courses as $course) {
      echo "<li>{$course['subject']} ({$course['weekday']} {$course['time_from']})</li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Keine Kurse gefunden.</p>";
  }

  echo "<a href='?view=teachers' class='btn btn-sm btn-secondary mt-3'>Zurück zur Übersicht</a>";
  return;
}

// Übersicht aller Dozenten
$teachers = $db->setTable($table_teachers, true)->select('*')->get();
?>

<h2 class="mb-4">👩‍🏫 Dozenten</h2>

<table class="table table-striped table-bordered align-middle table-sortable">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Vorname</th>
      <th>Nachname</th>
      <th>E-Mail</th>
      <th>Details</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($teachers as $t): ?>
      <tr>
        <td><?= $t['id'] ?></td>
        <td><?= htmlspecialchars($t['firstname']) ?></td>
        <td><?= htmlspecialchars($t['lastname']) ?></td>
        <td><?= htmlspecialchars($t['email']) ?></td>
        <td>
          <a href="?view=teachers&id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary">Anzeigen</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

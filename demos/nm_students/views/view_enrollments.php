<?php

$filterCourseId = isset($_GET['course_id']) ? (int) $_GET['course_id'] : null;

// Schüler & Kurse
$students = $db->setTable($table_students, true)->select('*')->get();
$studentMap = [];
foreach ($students as $s) {
  $studentMap[$s['id']] = "{$s['firstname']} {$s['lastname']}";
}

// Kurse & Dozenten
$courses = $db->setTable($table_courses)->select('*')->get();
$courseMap = [];
$teacherMap = [];

foreach ($db->setTable($table_teachers)->select('*')->get() as $t) {
  $teacherMap[$t['id']] = "{$t['firstname']} {$t['lastname']}";
}

foreach ($courses as $c) {
  $courseMap[$c['id']] = [
    'subject' => $c['subject'],
    'level' => $c['level'],
    'weekday' => $c['weekday'],
    'time' => $c['time_from'] . ' – ' . $c['time_to'],
    'teacher' => $teacherMap[$c['teacher_id']] ?? '❓'
  ];
}

// Belegungen laden (ggf. gefiltert)
$enrollments = $db->setTable($table_enrollments)->select('*')->get();
if ($filterCourseId !== null) {
  $enrollments = array_filter($enrollments, fn($e) => $e['course_id'] == $filterCourseId);
}

?>

<h2 class="mb-4">📌 Kursbelegungen</h2>

<?php if ($filterCourseId): ?>
  <div class="alert alert-info">
    <strong>Gefiltert nach Kurs ID <?= $filterCourseId ?></strong> –
    <a href="?view=enrollments" class="btn btn-sm btn-outline-secondary ms-2">Filter entfernen</a>
  </div>
<?php endif; ?>

<table class="table table-striped table-bordered align-middle table-sortable">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Schüler</th>
      <th>Kurs</th>
      <th>Stufe</th>
      <th>Tag</th>
      <th>Zeit</th>
      <th>Dozent</th>
      <th>Belegt am</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($enrollments as $e): ?>
      <?php
        $course = $courseMap[$e['course_id']] ?? null;
        if (!$course) continue;
      ?>
      <tr>
        <td><?= $e['id'] ?></td>
        <td>
          <a href="?view=students&id=<?= $e['student_id'] ?>">
            <?= htmlspecialchars($studentMap[$e['student_id']] ?? '❓ Unbekannt') ?>
          </a>
        </td>
        <td>
          <a href="?view=courses&highlight=<?= $e['course_id'] ?>">
            <?= htmlspecialchars($course['subject']) ?>
          </a>
        </td>
        <td><?= $course['level'] ?></td>
        <td><?= $course['weekday'] ?></td>
        <td><?= $course['time'] ?></td>
        <td><?= $course['teacher'] ?></td>
        <td><?= $e['created_at'] ?? '–' ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php

// Kurse laden
$courses = $db->setTable($table_courses, true)->select('*')->get();

// Dozenten zuordnen
$teacherMap = [];
foreach ($db->setTable($table_teachers)->select('*')->get() as $t) {
  $teacherMap[$t['id']] = "{$t['firstname']} {$t['lastname']}";
}

// Schüler & Belegungen vorbereiten
$students = $db->setTable($table_students)->select('*')->get();
$studentMap = [];
foreach ($students as $s) {
  $studentMap[$s['id']] = [
    'name'  => "{$s['firstname']} {$s['lastname']}",
    'email' => $s['email']
  ];
}

$enrollments = $db->setTable($table_enrollments)->select('*')->get();
$participantCounts = [];
$participantsByCourse = [];

foreach ($enrollments as $e) {
  $cid = $e['course_id'];
  $sid = $e['student_id'];
  $participantCounts[$cid] = ($participantCounts[$cid] ?? 0) + 1;
  $participantsByCourse[$cid][] = $studentMap[$sid] ?? ['name' => '❓ Unbekannt', 'email' => ''];
}

?>

<h2 class="mb-4">📖 Kursübersicht</h2>

<table class="table table-striped table-bordered align-middle table-sortable">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Fach</th>
      <th>Stufe</th>
      <th>Tag</th>
      <th>Zeit</th>
      <th>Dozent</th>
      <th>Teilnehmer</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($courses as $course): ?>
      <?php $cid = $course['id']; ?>
      <tr>
        <td><?= $cid ?></td>
        <td><?= htmlspecialchars($course['subject']) ?></td>
        <td><?= $course['level'] ?></td>
        <td><?= $course['weekday'] ?></td>
        <td><?= $course['time_from'] ?> – <?= $course['time_to'] ?></td>
        <td>
          <?php if (!empty($teacherMap[$course['teacher_id']])): ?>
            <a href="?view=teachers&id=<?= $course['teacher_id'] ?>">
              <?= htmlspecialchars($teacherMap[$course['teacher_id']]) ?>
            </a>
          <?php else: ?>
            ❓ unbekannt
          <?php endif; ?>
        </td>
        <td>
          <button class="btn btn-sm btn-outline-primary toggle-participants" data-target="course-<?= $cid ?>">
            <?= $participantCounts[$cid] ?? 0 ?> anzeigen
          </button>
        </td>
      </tr>
      <tr class="participant-row" id="course-<?= $cid ?>" style="display:none;">
        <td colspan="7">
          <?php if (!empty($participantsByCourse[$cid])): ?>
            <table class="table table-sm table-bordered mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>E-Mail</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($participantsByCourse[$cid] as $i => $p): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td>
                      <?php if ($p['email']): ?>
                        <a href="mailto:<?= htmlspecialchars($p['email']) ?>"><?= htmlspecialchars($p['email']) ?></a>
                      <?php else: ?>
                        <em>keine E-Mail</em>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <em>Keine Teilnehmer</em>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-participants').forEach(btn => {
      btn.addEventListener('click', () => {
        const targetId = btn.dataset.target;
        const row = document.getElementById(targetId);
        if (row.style.display === 'none') {
          row.style.display = '';
          btn.textContent = btn.textContent.replace('anzeigen', 'verbergen');
        } else {
          row.style.display = 'none';
          btn.textContent = btn.textContent.replace('verbergen', 'anzeigen');
        }
      });
    });
  });
</script>

<?php

// Klassen laden
$classes = $db->setTable($table_classes, true)->select('*')->get();

// Lehrer-Map
$teachers = $db->setTable($table_teachers)->select('*')->get();
$teacherMap = [];
foreach ($teachers as $t) {
  $teacherMap[$t['id']] = "{$t['firstname']} {$t['lastname']}";
}

// Schüler laden und nach Klasse gruppieren
$students = $db->setTable($table_students)->select('*')->get();
$studentsByClass = [];
foreach ($students as $s) {
  $studentsByClass[$s['class_id']][] = $s;
}

?>

<h2 class="mb-4">🏫 Klassenübersicht</h2>

<table class="table table-striped table-bordered align-middle table-sortable">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Raum</th>
      <th>Lehrer</th>
      <th>Schüler</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($classes as $class): ?>
      <?php $cid = $class['id']; ?>
      <tr>
        <td><?= $cid ?></td>
        <td><?= htmlspecialchars($class['name']) ?></td>
        <td><?= htmlspecialchars($class['room']) ?></td>
        <td><?= $teacherMap[$class['teacher_id']] ?? '❓' ?></td>
        <td>
          <button class="btn btn-sm btn-outline-primary toggle-students" data-target="class-<?= $cid ?>">
            <?= count($studentsByClass[$cid] ?? []) ?> anzeigen
          </button>
        </td>
      </tr>
      <tr class="student-row" id="class-<?= $cid ?>" style="display:none;">
        <td colspan="5">
          <?php if (!empty($studentsByClass[$cid])): ?>
            <table class="table table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>E-Mail</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($studentsByClass[$cid] as $s): ?>
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
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <em>Keine Schüler zugewiesen</em>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-students').forEach(btn => {
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

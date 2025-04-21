<?php

require_once 'include/theme.php';

$additionalCss = [
  'assets/scripts/flip/flip.min.css'
];

$additionalFooterJs = [
  'assets/scripts/flip/flip.min.js'
];

$baseUrl = '../examples/'; // Wenn du direkt im Root von /JsonSQL bist
include __DIR__ . '/../includes/header.php';
use Src\JsonSQL;

$table_courses = 'courses';
$table_teachers = 'teachers';
$table_classes = 'classes';
$table_students = 'students';
$table_enrollments = 'enrollments';

$dataDir = __DIR__ . '/data';

require_once __DIR__ . '/../../src/JsonSQL.php';
$db = new JsonSQL(['main' => $dataDir]);
$db->use('main');

$page = $_GET['view'] ?? 'overview';
?>



<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link <?= $page == 'overview' ? 'active' : '' ?>" href="?view=overview">🏠 Übersicht</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $page == 'courses' ? 'active' : '' ?>" href="?view=courses">📖 Kurse</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $page == 'teachers' ? 'active' : '' ?>" href="?view=teachers">👩‍🏫 Dozenten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $page == 'classes' ? 'active' : '' ?>" href="?view=classes">🏫 Klassen</a>
  </li>  
  <li class="nav-item">
    <a class="nav-link <?= $page == 'students' ? 'active' : '' ?>" href="?view=students">🧑‍🎓 Studenten</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $page == 'enrollments' ? 'active' : '' ?>" href="?view=enrollments">📌 Belegungen</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= $page == 'mysql' ? 'active' : '' ?>" href="?view=mysql">🛠️ MySQL Export</a>
  </li>  
  <li class="nav-item ms-auto">
  <!--  <a class="nav-link text-danger" href="create_data.php">🚀 Demodaten erzeugen</a> -->
  </li>
</ul>


<?php
switch ($page) {
  case 'courses':
    include 'views/view_courses.php';
    break;

  case 'teachers':
    include 'views/view_teachers.php';
    break;

  case 'classes':
      include 'views/view_classes.php';
      break;
    
  case 'students':
    include 'views/view_students.php';
    break;

  case 'enrollments':
    include 'views/view_enrollments.php';
    break;

    case 'mysql':
      include 'views/view_mysql.php';
      break;    

  case 'overview':
  default:
    include 'views/view_overview.php';
    break;
}
?>





<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.table-sortable th').forEach(header => {
      header.style.cursor = 'pointer';
      header.addEventListener('click', () => {
        const table = header.closest('table');
        const index = [...header.parentNode.children].indexOf(header);
        const ascending = !header.classList.contains('asc');

        [...table.querySelectorAll('tbody tr')]
          .sort((a, b) => {
            const cellA = a.children[index].innerText.trim();
            const cellB = b.children[index].innerText.trim();

            return ascending
              ? cellA.localeCompare(cellB, 'de', { numeric: true })
              : cellB.localeCompare(cellA, 'de', { numeric: true });
          })
          .forEach(row => table.querySelector('tbody').appendChild(row));

        // Sortierklasse setzen
        table.querySelectorAll('th').forEach(th => th.classList.remove('asc', 'desc'));
        header.classList.toggle('asc', ascending);
        header.classList.toggle('desc', !ascending);
      });
    });
  });
</script>

<style>
  th.asc::after {
    content: " ▲";
    font-size: 0.8em;
  }
  th.desc::after {
    content: " ▼";
    font-size: 0.8em;
  }
</style>


<?php include __DIR__ . '/../includes/footer.php'; ?>


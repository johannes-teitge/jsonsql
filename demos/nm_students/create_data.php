<?php
require_once 'include/theme.php';
require_once __DIR__ . '/../includes/header.php';
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


function showAlert($type, $message) {
  $icons = [
      'success' => 'check-circle-fill',
      'danger'  => 'exclamation-triangle-fill',
      'info'    => 'info-circle-fill',
      'warning' => 'exclamation-circle-fill'
  ];
  $icon = $icons[$type] ?? 'info-circle-fill';
  echo "<div class='alert alert-$type d-flex align-items-center mt-4' role='alert'>
      <i class='bi bi-$icon me-2'></i>
      <div>$message</div>
  </div>";
}

function create_teachers($db, $numTeachers = 4) {
  global $table_teachers;
  
  $table = $table_teachers;
  $db->Truncate($table); // Bestehende Tabelle löschen

  $db->addAutoincrementField('id')
      ->addField('gender', ['dataType' => 'enum', 'enumValues' => 'm,f', 'required' => true])  
      ->addField('firstname', ['dataType' => 'string', 'required' => true, 'length' => 50])
      ->addField('lastname', ['dataType' => 'string', 'required' => true, 'length' => 50])
      ->addField('email', ['dataType' => 'string', 'length' => 100])
      ->addCreatedAtField('created_at')
      ->addUpdatedAtField('updated_at');

      $firstnames = [
        ['name' => 'Anna', 'gender' => 'f'],
        ['name' => 'Ben', 'gender' => 'm'],
        ['name' => 'Clara', 'gender' => 'f'],
        ['name' => 'David', 'gender' => 'm'],
        ['name' => 'Eva', 'gender' => 'f'],
        ['name' => 'Felix', 'gender' => 'm'],
        ['name' => 'Greta', 'gender' => 'f'],
        ['name' => 'Hannes', 'gender' => 'm'],
        ['name' => 'Isabel', 'gender' => 'f'],
        ['name' => 'Jonas', 'gender' => 'm']
      ];
      



  $lastnames  = ["Müller", "Schmidt", "Meier", "Schulz", "Fischer", "Becker", "Hoffmann", "Wagner", "Weber", "Koch"];

  for ($i = 1; $i <= $numTeachers; $i++) {
    $f = $firstnames[array_rand($firstnames)];
    $firstname = $f['name'];
    $gender    = $f['gender'];
    $lastname  = $lastnames[array_rand($lastnames)];
    $email     = strtolower($firstname . '.' . $lastname . "@schule.de");

    $db->setTable($table)->insert([
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'email'     => $email,
        'gender'    => $gender
    ]);
   }

}


function create_classes($db, $numClasses = 2, array $teacherIds = []) {
  global $table_classes;

  $table = $table_classes;
  $db->Truncate($table);

  $db->addAutoincrementField('id')
      ->addField('name', ['dataType' => 'string', 'required' => true, 'unique' => true])
      ->addField('room', ['dataType' => 'string', 'length' => 10])
      ->addField('teacher_id', ['dataType' => 'integer'])
      ->addCreatedAtField('created_at')
      ->addUpdatedAtField('updated_at');

  $classnames = ["10A", "10B", "11A", "11B", "12A", "12B", "Q1", "Q2"];
  $rooms = ["A101", "B202", "C303", "D404", "E505"];

  $allRecords = [];

  for ($i = 1; $i <= $numClasses; $i++) {
      $name = $classnames[array_rand($classnames)];
      $room = $rooms[array_rand($rooms)];
      $teacher_id = $teacherIds ? $teacherIds[array_rand($teacherIds)] : 0;

      $allRecords[] = [
          'name' => $name,
          'room' => $room,
          'teacher_id' => $teacher_id
      ];
  }

  // 🔁 Jetzt nur EINMAL insert aufrufen
  $db->insert($allRecords);

  // 💡 Optionale Info: Anzahl übersprungener Duplikate
  if ($db->getSkippedInsertsCount() > 0) {
      echo "⛔ {$db->getSkippedInsertsCount()} Duplikate mit <b>Name</b> wurden beim Erstellen der Klassen übersprungen.<br>";
  }
}


function create_courses($db, $numCourses = 10, array $teacherIds = []) {
    global $table_courses;

    $table = $table_courses;
    $db->Truncate($table); // Leert Tabelle (falls vorhanden)

    $db->addAutoincrementField('id')
        ->addField('subject', ['dataType' => 'string', 'required' => true])
        ->addField('level', ['dataType' => 'enum', 'enumValues' => 'Grundlagen,I,II'])
        ->addField('weekday', ['dataType' => 'enum', 'enumValues' => 'Montag,Dienstag,Mittwoch,Donnerstag,Freitag'])
        ->addField('time_from', ['dataType' => 'time'])
        ->addField('time_to', ['dataType' => 'time'])
        ->addField('teacher_id', ['dataType' => 'integer'])
        ->addCreatedAtField('created_at')
        ->addUpdatedAtField('updated_at');

    $subjects = ["Mathematik", "Informatik", "Deutsch", "Biologie"];
    $levels = ["Grundlagen", "I", "II"];
    $weekdays = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag"];
    $times = [["08:00", "09:30"], ["10:00", "11:30"], ["12:00", "13:30"], ["14:00", "15:30"]];

    for ($i = 1; $i <= $numCourses; $i++) {
        $subject = $subjects[array_rand($subjects)];
        $level = $levels[array_rand($levels)];
        $weekday = $weekdays[array_rand($weekdays)];
        [$from, $to] = $times[array_rand($times)];

        // 🧠 Lehrer-ID aus vorhandenem Pool wählen, sonst 0
        $teacher_id = $teacherIds ? $teacherIds[array_rand($teacherIds)] : 0;        

        $db->insert([
          'subject' => $subject,
            'level' => $level,
            'weekday' => $weekday,
            'time_from' => $from,
            'time_to' => $to,
            'teacher_id' => $teacher_id
        ]);
    }
}


function create_students($db, $numStudents = 20, array $classIds = []) {
  global $table_students;

  $table = $table_students;
  $db->Truncate($table);

  $db->addAutoincrementField('id')
      ->addField('firstname', ['dataType' => 'string', 'required' => true, 'length' => 50])
      ->addField('lastname', ['dataType' => 'string', 'required' => true, 'length' => 50])
      ->addField('email', ['dataType' => 'string', 'unique' => true])
      ->addField('class_id', ['dataType' => 'integer']) // FK zu classes
      ->addCreatedAtField('created_at')
      ->addUpdatedAtField('updated_at');

  $firstnames = ["Luca", "Mia", "Paul", "Emma", "Noah", "Lea", "Tim", "Lina", "Max", "Sophie"];
  $lastnames  = ["Schneider", "Keller", "Baumann", "Richter", "Lorenz", "Hartmann", "Schuster", "Berger", "Jung", "Kuhn"];

  for ($i = 0; $i < $numStudents; $i++) {
      $firstname = $firstnames[array_rand($firstnames)];
      $lastname  = $lastnames[array_rand($lastnames)];
      $email     = strtolower($firstname . '.' . $lastname . rand(100, 999) . "@schule.de");

      $class_id  = $classIds ? $classIds[array_rand($classIds)] : 0;

      $db->insert([
          'firstname' => $firstname,
          'lastname'  => $lastname,
          'email'     => $email,
          'class_id'  => $class_id
      ]);
  }
}


function create_enrollments($db, int $numEnrollments = 50, array $studentIds = [], array $courseIds = []) {
  global $table_enrollments;

  $db->Truncate($table_enrollments);

  $db->addAutoincrementField('id')
      ->addField('student_id', ['dataType' => 'integer', 'required' => true])
      ->addField('course_id', ['dataType' => 'integer', 'required' => true])
      ->addCreatedAtField('created_at')
      ->addUpdatedAtField('updated_at');

  $insertedPairs = [];

  for ($i = 0; $i < $numEnrollments; $i++) {
      $student_id = $studentIds[array_rand($studentIds)] ?? 0;
      $course_id = $courseIds[array_rand($courseIds)] ?? 0;

      $pairKey = $student_id . '-' . $course_id;

      // ➕ Nur hinzufügen, wenn diese Kombination noch nicht existiert
      if (isset($insertedPairs[$pairKey])) {
          continue; // doppelt → überspringen
      }

      $insertedPairs[$pairKey] = true;

      $db->insert([
          'student_id' => $student_id,
          'course_id' => $course_id
      ]);
  }
}








// Formular abgeschickt?
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['courses'])) {
    $numCourses = (int)($_POST['courses'] ?? 12);
    $numTeachers = (int)($_POST['teachers'] ?? 4);    
    $numClasses = (int)($_POST['classes'] ?? 4); 
    $numStudents = (int)($_POST['students'] ?? 20);     
    $numEnrollments = (int)($_POST['enrollments'] ?? 20);             

    create_teachers($db, $numTeachers);    
    $db->setTable($table_teachers,true);     
    $teacher_ids = $db->pluck('id', true);
    
    create_courses($db, $numCourses, $teacher_ids);
    $course_ids = $db->pluck('id', true);      
    
    create_classes($db, $numClasses, $teacher_ids);
    $class_ids = $db->pluck('id', true);    
    
    create_students($db, $numStudents, $class_ids);  
    $student_ids = $db->pluck('id', true);         

    create_enrollments($db, $numEnrollments, $student_ids, $course_ids);    



    showAlert('success', "<b>$numCourses Kurse</b> und <b>$numTeachers Lehrer</b> erfolgreich erstellt.");

} else {

}

    // Formular
    ?>

<div class="mt-5 text-center">
  <a href="index.php" class="btn btn-outline-secondary btn-lg">
    ⬅️ Zurück zur Übersicht
  </a>
</div>    

    <h2>📊 Demodaten erstellen</h2>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="mb-4">
        <div class="row g-3">
            <div class="col-md-2"><label class="form-label">Kurse</label><input class="form-control" name="courses" value="12" /></div>
            <div class="col-md-2"><label class="form-label">Dozenten</label><input class="form-control" name="teachers" value="4" /></div>
            <div class="col-md-2"><label class="form-label">Studenten</label><input class="form-control" name="students" value="20" /></div>
            <div class="col-md-2"><label class="form-label">Belegungen</label><input class="form-control" name="enrollments" value="50" /></div>
            <div class="col-md-2"><label class="form-label">Klassen</label><input class="form-control" name="classes" value="5" /></div>
        </div>
        <button type="submit" class="btn btn-success mt-3">🚀 Generieren</button>        
    </form>
    <hr>

    <?php


  // ausgabe der Daten

  echo "<h2>Aktueller Datenbestand</h2>";

  $db->setTable($table_teachers,true);
  $numTeachers = $db->count();  

  $teachers = $db->select('*')->from('teachers')->get();
  echo "<h3>👩‍🏫 Dozenten ( {$numTeachers})</h3><table class='table table-bordered table-sm'><thead><tr>
  <th>ID</th><th>Anrede</th><th>Name</th><th>Email</th></tr></thead><tbody>";
  foreach ($teachers as $t) {
    $anrede = $t['gender'] === 'f' ? 'Frau' : 'Herr';
      echo "<tr><td>{$t['id']}</td><td>{$anrede}</td></td><td>{$t['firstname']} {$t['lastname']}</td><td>{$t['email']}</td></tr>";
  }
  echo "</tbody></table>";

  $db->setTable($table_courses,true);
  $numCourses = $db->count();    

  $courses = $db->select('*')->from($table_courses)->get();
  echo "<h3>📖 Kurse ({$numCourses})</h3><table class='table table-bordered table-sm'><thead><tr>
      <th>ID</th><th>Fach</th><th>Stufe</th><th>Tag</th><th>Zeit</th><th>Lehrer-ID</th>
  </tr></thead><tbody>";
  foreach ($courses as $c) {
      echo "<tr>
          <td>{$c['id']}</td><td>{$c['subject']}</td><td>{$c['level']}</td><td>{$c['weekday']}</td><td>{$c['time_from']} – {$c['time_to']}</td><td>{$c['teacher_id']}</td>
      </tr>";
  }
  echo "</tbody></table>";



  $db->setTable($table_classes, true);
  $numClasses = $db->count();
  $class = $db->select('*')->from($table_classes)->get();
  
  echo "<h3>🏫 Klassen ({$numClasses})</h3><table class='table table-bordered table-sm'><thead><tr>
  <th>ID</th><th>Name</th><th>Raum</th><th>Lehrer-ID</th></tr></thead><tbody>";
  
  foreach ($class as $c) {
      echo "<tr>
          <td>{$c['id']}</td>
          <td>{$c['name']}</td>
          <td>{$c['room']}</td>
          <td>{$c['teacher_id']}</td>
      </tr>";
  }
  echo "</tbody></table>";  


  $db->setTable($table_students, true);
  $numStudents = $db->count();
  $students = $db->select('*')->from($table_students)->get();
  
  echo "<h3>🧑‍🎓 Studenten ({$numStudents})</h3>
  <table class='table table-bordered table-sm'>
  <thead>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Klasse</th>
  </tr>
  </thead>
  <tbody>";
  
  foreach ($students as $s) {
      echo "<tr>
          <td>{$s['id']}</td>
          <td>{$s['firstname']} {$s['lastname']}</td>
          <td>{$s['email']}</td>
          <td>{$s['class_id']}</td>
      </tr>";
  }
  
  echo "</tbody></table>";
  


  $db->setTable($table_enrollments, true);
  $numEnrollments = $db->count();
  
  $enrollments = $db->select('*')->from($table_enrollments)->get();
  
  echo "<h3>📌 Belegungen ({$numEnrollments})</h3>";
  echo "<table class='table table-bordered table-sm'><thead><tr>
  <th>ID</th><th>Schüler</th><th>Kurs</th><th>Datum</th></tr></thead><tbody>";
  
  // Schüler, Kurse und Lehrer holen
  $students = $db->setTable($table_students, true)->select('*')->get();
  $courses  = $db->setTable($table_courses, true)->select('*')->get();
  $teachers = $db->setTable($table_teachers, true)->select('*')->get();
  
  // 🧠 Maps aufbauen
  $studentMap = [];
  foreach ($students as $s) {
      $studentMap[$s['id']] = "{$s['firstname']} {$s['lastname']}";
  }
  
  $teacherMap = [];
  foreach ($teachers as $t) {
      $teacherMap[$t['id']] = "{$t['firstname']} {$t['lastname']}";
  }
  
  $courseMap = [];
  foreach ($courses as $c) {
      $teacher = $teacherMap[$c['teacher_id']] ?? '❓';
      $courseMap[$c['id']] = "{$c['subject']} ({$c['weekday']} {$c['time_from']}) – Dozent: {$teacher}";
  }
  
  // 👇 Ausgabe
  $db->setTable($table_enrollments, true);
  foreach ($enrollments as $e) {
      $student = $studentMap[$e['student_id']] ?? '❓ Unbekannt';
      $course  = $courseMap[$e['course_id']] ?? '❓ Unbekannt';
  
      echo "<tr>
          <td>{$e['id']}</td>
          <td>{$student}</td>
          <td>{$course}</td>
          <td>{$e['created_at']}</td>
      </tr>";
  }
  echo "</tbody></table>";
  






require_once __DIR__ . '/../includes/footer.php';
?>

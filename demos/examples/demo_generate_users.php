<?php
$pageTitle = "JsonSQL User erstellen Demo";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

use Src\JsonSQL;



// Liste der zusätzlichen CSS-Dateien
$additionalCss = [
    'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', // z.B. DataTables CSS
];

// Liste der zusätzlichen JavaScript-Dateien
$additionalJs = ['https://code.jquery.com/jquery-3.6.0.min.js',
    'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', // DataTables JS
];


require_once __DIR__ . '/../includes/header.php';


function fakeUser(): array {
    $maleNames = ['Lukas', 'Mehmet', 'Jens', 'Tobias', 'Daniel', 'Tom'];
    $femaleNames = ['Anna', 'Chloe', 'Fatima', 'Lena', 'Laura', 'Mia'];
    $lastnames = ['Müller', 'Schmidt', 'Nguyen', 'Kowalski', 'Rossi'];

    $gender = rand(0, 1) ? 'm' : 'f';
    $firstname = $gender === 'm'
        ? $maleNames[array_rand($maleNames)]
        : $femaleNames[array_rand($femaleNames)];

    $lastname = $lastnames[array_rand($lastnames)];
    $email = strtolower($firstname . '.' . $lastname) . rand(100, 999) . '@example.com';

    return [
        'name'     => $firstname,
        'lastname' => $lastname,
        'email'    => $email,
        'username' => strtolower(substr($firstname, 0, 1)) . strtolower($lastname) . rand(1, 99),
        'password' => bin2hex(random_bytes(4)),
        'birth'    => date('Y-m-d', rand(strtotime('1950-01-01'), strtotime('2005-12-31'))),
        'gender'   => $gender,
    ];
}

// Init
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb/demo']);
$table = 'demo_users';
$db->use('demo');



// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete_all'])) {
      // Löschen aller Benutzer und Zurücksetzen der Tabelle
      $db->Truncate($table);
      echo "✅ Alle Benutzer wurden gelöscht und die Tabelle zurückgesetzt.<br>";
  }

  if (isset($_POST['create_users'])) {
      // Anzahl der zu erstellenden Benutzer festlegen
      $userCount = isset($_POST['user_count']) ? (int)$_POST['user_count'] : 0;

      // Tabelle überprüfen und ggf. anlegen, zurücksetzen
      $db->setTable($table);

      echo "✅ Tabelle <code>{$table}</code> mit Truncate zurückgesetzt.<br>";

      $db->addAutoincrementField('id')
         ->addCreatedAtField('created')
         ->addUpdatedAtField('timestamp')
         ->addEncryptedField('password');

      // Benutzer erstellen
      for ($i = 0; $i < $userCount; $i++) {
          $db->insert(fakeUser());
      }

      echo "✅ $userCount Benutzer wurden erstellt.<br>";
  }
}

$debugger->dump($table,$db);

// Abfrage der Benutzer aus der Tabelle
$users = $db->from($table)->select(['id', 'name', 'lastname', 'email', 'username', 'password', 'birth', 'gender', 'created', 'timestamp'])->orderBy('id')->get();
?>

<!-- Formular zum Erstellen und Löschen von Benutzern -->
<div class="container mt-5">
    <h2>👥 Benutzerverwaltung</h2>

    <form method="POST" action="">
        <div class="d-flex align-items-end mb-4" style="gap: 1rem;">
            <div>
                <label for="user_count" class="form-label">Anzahl der zu erstellenden Benutzer:</label>
                <input type="number" class="form-control" id="user_count" name="user_count" value="10" min="1" max="1000">
            </div>
            
            <div>
                <button type="submit" name="create_users" class="btn btn-primary">Benutzer hinzufügen</button>
            </div>
        </div>
    </form>

    <form method="POST" action="" class="mt-3">
        <button type="submit" name="delete_all" class="btn btn-danger">Alle Benutzer löschen</button>
    </form>
</div>
<?php

// Ausgabe der Benutzer in einer HTML-Tabelle mit Simple Datatables
echo "<h3 style='margin-top:60px;'>📦 Benutzerübersicht:</h3>";
echo "<table id='userTable' class='table table-bordered table-striped'>";
echo "<thead><tr>
        <th>ID</th>
        <th>Name</th>
        <th>Nachname</th>
        <th>Email</th>
        <th>Username</th>
        <th>Passwort</th>        
        <th>Geburtsdatum</th>
        <th>Geschlecht</th>
        <th>Erstellt am</th>
        <th>Aktualisiert am</th>
      </tr></thead>";
echo "<tbody>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['name']}</td>";
    echo "<td>{$user['lastname']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td>{$user['username']}</td>";
    echo "<td>{$user['password']}</td>";    
    echo "<td>{$user['birth']}</td>";
    echo "<td>" . ($user['gender'] === 'm' ? 'Männlich' : 'Weiblich') . "</td>";
    echo "<td>{$user['created']}</td>";
    echo "<td>{$user['timestamp']}</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";







?>


<div class="alert alert-info mt-5">
  <h5>ℹ️ Was zeigt dieser Abschnitt?</h5>
  <p>In diesem Bereich werden die beiden zentralen JSON-Dateien angezeigt, die von <code>JsonSQL</code> verwendet werden:</p>

  <ul>
    <li>
      <strong><code>demo_users.json</code></strong><br>
      Diese Datei enthält die <em>Rohdaten</em> aller Benutzer, also die gespeicherten Inhalte der Tabelle. Einige Felder wie <code>password</code> werden hier <strong>verschlüsselt</strong> gespeichert.
    </li>
    <li class="mt-3">
      <strong><code>demo_users.system.json</code></strong><br>
      Diese Datei beschreibt die <em>Systemdefinition</em> der Tabelle – also welche Felder existieren, welchen Typ sie haben, ob sie automatisch gesetzt oder verschlüsselt werden usw.
    </li>
  </ul>

  <hr>

  <h5>🧩 Mischung aus definierten und freien Feldern</h5>
  <p>In dieser Demo sind nur einige Felder im <code>system.json</code> definiert – z. B. <code>id</code>, <code>created</code>, <code>timestamp</code>, <code>password</code>. Alle anderen Felder wie <code>name</code> oder <code>birth</code> werden einfach "frei" hinzugefügt.</p>

  <p>Damit das funktioniert, muss in der Systemdatei explizit <code>"allowAdditionalFields": true</code> gesetzt sein.</p>

  <p><strong>Vorteil:</strong> JsonSQL erlaubt dadurch maximale Flexibilität – du kannst dynamisch neue Felder hinzufügen, ohne das System anzupassen.<br>
  <strong>Nachteil:</strong> Wenn du z. B. einen Tippfehler im Feldnamen machst, gibt es keine Warnung – das Feld landet einfach im Datensatz.</p>

  <hr>

  <h5>⚠️ Empfohlen: Prüfung auf Existenz von Feldern</h5>
  <p>Beim Auslesen von Daten solltest du deshalb immer prüfen, ob ein Feld überhaupt existiert, bevor du es verwendest:</p>

  <pre class="code-block"><code>&lt;?php
if (isset($user['birth'])) {
    echo "Geburtsdatum: " . $user['birth'];
} else {
    echo "Kein Geburtsdatum vorhanden.";
}
?&gt;</code></pre>

  <p>Alternativ kannst du auch mit dem Null-Coalescing-Operator arbeiten:</p>

  <pre class="code-block"><code>&lt;?php
echo "Geburtsdatum: " . ($user['birth'] ?? 'nicht angegeben');
?&gt;</code></pre>

  <p>So vermeidest du Fehler bei fehlenden oder falsch benannten Feldern – und nutzt gleichzeitig die volle Power von JsonSQL mit freien und strukturierten Daten.</p>
</div>



<!-- Neuer Tab für JSON-Dateien -->
<div class="container mt-5 mb-3">
  <div class="accordion" id="jsonAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingJson">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJson" aria-expanded="false" aria-controls="collapseJson">
          📄 JSON-Dateien anzeigen
        </button>
      </h2>
      <div id="collapseJson" class="accordion-collapse collapse" aria-labelledby="headingJson" data-bs-parent="#jsonAccordion">
        <div class="accordion-body">
          <h4>JsonSQL Datei: testdb/demo/demo_users.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/demo/demo_users.json'));
          ?></code></pre>
          
          <h4>JsonSQL System Datei: testdb/demo/demo_users.system.json</h4>
          <pre class="code-block"><code><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/../testdb/demo/demo_users.system.json'));
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>
<?php





// Quellcode anzeigen
$scriptName = basename(__FILE__);

// Entferne die Exclude-Tags aus dem Quellcode
$scriptContent = file_get_contents(__FILE__);
$scriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $scriptContent);
?>

<!-- Exclude Begin -->
<div class="container mt-5 mb-3">
  <hr class="shadow-lg rounded">
  <div class="accordion" id="codeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingCode">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
          📄 Quellcodeauszug dieser Demo anzeigen (<?= htmlspecialchars($scriptName) ?>)
        </button>
      </h2>
      <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
        <div class="accordion-body">
          <pre class="code-block"><code><?php echo htmlspecialchars($scriptContent); ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Initialisiere die DataTable und setze die Sprache auf Deutsch
    $('#userTable').DataTable({
        language: {
            search: "Suchen:",
            lengthMenu: "Zeige _MENU_ Einträge",
            info: "Zeige _START_ bis _END_ von _TOTAL_ Einträgen",
            infoEmpty: "Zeige 0 bis 0 von 0 Einträgen",
            infoFiltered: "(gefiltert von _MAX_ insgesamt Einträgen)",
            paginate: {
                first: "Erste",
                previous: "Vorherige",
                next: "Nächste",
                last: "Letzte"
            }
        }
    });
});
</script>

<?php

require_once __DIR__ . '/../includes/footer.php';
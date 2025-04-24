<?php
$pageTitle = "JsonSQL Demo: Auto-Datenbank Demo - Systemfelder";
$baseUrl = dirname($_SERVER['PHP_SELF']);
$basedir =  dirname($_SERVER['SCRIPT_NAME']) . '/../assets';
$pageImage = 'https://www.teitge.de/JsonSQL/demos/examples/images/CarDB/BannerFacebook.webp';

$themeOptions = [
  'css' => [
    'header' => [
      'background' => "url('../examples/images/CarDB/header.webp') no-repeat center center",
      'background-size' => 'cover',
      'height' => '440px',
      'color' => 'white'
    ],
    '.head-wrapper' => [
      'background-color' => 'rgba(0, 0, 0, 0.54)',
      'padding' => '10px 0'
    ],
    'h1' => [
      'color' => 'var(--white-color)'
    ],
    '.backContent' => [
      'color' => ' rgba(255, 255, 255, 0.81)'
    ],   
    '.logo-style' => [
      'background-image' => 'url("../assets/images/JsonSQL-Logo-FullWhite.svg")',
    ],      
    '.headlogo img' => [
      'filter' => 'drop-shadow(0 0 3px rgb(255, 255, 255))',
      'animation' => 'glow 2.5s ease-in-out infinite alternate',
      'animation-delay' => '1.8s'
    ],
    '@keyframes glow' => [
      'from' => ['filter' => 'drop-shadow(0 0 3px rgb(255, 255, 255))'],
      'to'   => ['filter' => 'drop-shadow(0 0 20px rgb(255, 255, 255))']
    ]
  ],
  'logo_src' => ($basedir . '/images/JsonSQL-Logo-FullWhite.svg')  
];




$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';


use Src\JsonSQL;

try {

// Datenbank initialisieren
$db = new JsonSQL(['main' => __DIR__ . '/CarDB']);
$db->use('main');
$table = 'cars';
$db->setBackupMode(true);





// Wir nehmen an, dass die Datenbankverbindung und -initialisierung hier bereits vorhanden sind

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Wenn der Action-Parameter auf "delete" gesetzt ist
    if ($action == 'delete') {
        // Löschaktion
        // 1. Tabelle überprüfen und ggf. anlegen, zurücksetzen
        $db->Truncate($table); // Tabelle wird gelöscht und neu geladen
        $db->truncateSystem($table); // Systemtabelle leeren
        echo "<div class='alert alert-success'>Alle Daten wurden erfolgreich gelöscht.</div>";
        // JavaScript-Redirect nach dem Löschen
        echo "<script>window.location.href = window.location.pathname;</script>";
        exit;    
    }

    // Wenn der Action-Parameter auf "create" gesetzt ist
    if ($action == 'create') {





// 1. Tabelle überprüfen und ggf. anlegen, zurücksetzen
$db->Truncate($table); // Tabelle wird gelöscht und neu geladen
$db->truncateSystem($table); // Systemtabelle leeren


$db->addAutoincrementField('id')
->addField('id', ['comment' => 'Automatisch generierte ID'])
->addAutoHashField('hash', 'md5')
->addField('brand', ['dataType' => 'string', 'length' => 40, 'required' => true, 'defaultValue' => '', 'comment' => 'Marke des Autos'])
->addField('model', ['dataType' => 'string', 'length' => 100, 'comment' => 'Modelltyp des Autos'])
->addField('year_built', ['dataType' => 'integer', 'comment' => 'Baujahr des Fahrzeugs'])
->addField('displacement', ['dataType' => 'integer', 'comment' => 'Hubraum'])
->addField('power', ['dataType' => 'integer', 'comment' => 'Leistung', 'unit' => 'kwh'])
->addField('fuel', [
    'dataType' => 'enum',
    'enumValues' => 'Benzin,Diesel,Elektro,Hybrid',
    'defaultValue' => 'Benzin'
])
->addField('transmission_type', [
  'dataType' => 'enum',
  'enumValues' => 'Schaltgetriebe,Automatik,Doppelkupplung,Tiptronic'
])
->addField('gear_count', [
  'dataType' => 'integer',
  'min' => 3,
  'max' => 10
])
->addField('doors', ['dataType' => 'integer','min' => '4','max' => '6'])
->addField('price', ['dataType' => 'float', 'unit' => '€'])
->addField('datum', ['dataType' => 'datetime'])
->addField('description', ['dataType' => 'string'])
->addField('logo', ['dataType' => 'string', 'defaultValue' => '']) // z. B. URL zum Markenlogo
->addField('images', ['dataType' => 'text', 'defaultValue' => '']) // Bilder 
->addCreatedAtField('created_at')
->addUpdatedAtField('updated_at');


$images = json_encode([
  [
    'filename' => 'images/CarDB/larifari/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/larifari/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/larifari/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/larifari/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],  
  [
    'filename' => 'images/CarDB/larifari/image05.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]   
  ]);

  $db->insert([
    'brand' => 'Larifari',
    'model' => 'Speedy 3000',
    'year_built' => 2030,
    'displacement' => 2998,
    'power' => 420,
    'fuel' => 'Benzin',
    'transmission_type' => 'Doppelkupplung',
    'gear_count' => 7,
    'doors' => 3,
    'price' => 129990.99,
    'description' => 'Der <strong>Speedy 3000</strong> von Larifari ist ein kompromissloser Sportwagen mit modernem Design.<br>
    <ul>
      <li>3.0L Turbo-V6 mit 420 PS</li>
      <li>Beschleunigung 0–100 km/h in 3,9 Sekunden</li>
      <li>Sportauspuff & Carbon-Innenverkleidung</li>
    </ul>
    <strong>Hinweis:</strong> Für Adrenalinjunkies mit Stil.',
    'logo' => 'images/CarDB/larifari/logo.webp',
    'images' => $images
  ]);
  




$images = json_encode([
  [
    'filename' => 'images/CarDB/elantrix/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/elantrix/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/elantrix/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/elantrix/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],  
  [
    'filename' => 'images/CarDB/elantrix/image05.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]   
  ]);

  $db->insert([
    'brand' => 'Eleantrix',
    'model' => 'Pico',
    'year_built' => 2028,
    'displacement' => 999,
    'power' => 95,
    'fuel' => 'Benzin',
    'transmission_type' => 'Schaltgetriebe',
    'gear_count' => 6,
    'doors' => 3,
    'price' => 14990.00,
    'description' => 'Der <strong>Pico</strong> ist ein agiler Cityflitzer für die urbane Mobilität.<br>
    <ul>
      <li>1.0L Dreizylinder mit 95 PS</li>
      <li>Kompakt, wendig und sparsam</li>
      <li>Perfekt für Einsteiger und Großstadtverkehr</li>
    </ul>
    <strong>Tipp:</strong> Maximale Freiheit auf kleinem Raum.',
    'logo' => 'images/CarDB/elantrix/logo.webp',
    'images' => $images
  ]);
  



$images = json_encode([
  [
    'filename' => 'images/CarDB/zentoro/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/zentoro/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/zentoro/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/zentoro/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]
  ]);

  $db->insert([
    'brand' => 'Zentoro',
    'model' => 'Veloce',
    'year_built' => 2029,
    'displacement' => 1598,
    'power' => 204,
    'fuel' => 'Hybrid',
    'transmission_type' => 'Automatik',
    'gear_count' => 6,
    'doors' => 5,
    'price' => 28900.00,
    'description' => 'Der <strong>Zentoro Veloce</strong> verbindet Dynamik mit Effizienz.<br>
    <ul>
      <li>1.6L Turbo-Hybrid mit 204 PS</li>
      <li>Intelligentes Allrad-System</li>
      <li>LED-Lichtpaket & digitale Cockpitanzeige</li>
    </ul>
    <strong>Für alle:</strong> Die sportlich UND grün fahren wollen.',
    'logo' => 'images/CarDB/zentoro/logo.webp',
    'images' => $images
  ]);
  




$images = json_encode([
  [
    'filename' => 'images/CarDB/worsche/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/worsche/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/worsche/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/worsche/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/worsche/image05.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]  
  ]);

  $db->insert([
    'brand' => 'Worsche',
    'model' => 'WX 700',
    'year_built' => 2029,
    'displacement' => 3996,
    'power' => 580,
    'fuel' => 'Benzin',
    'transmission_type' => 'Tiptronic',
    'gear_count' => 8,    
    'doors' => 2,
    'price' => 179900.00,
    'description' => 'Der <strong>WX 700</strong> steht für High-End-Performance made in Germany.<br>
    <ul>
      <li>4.0L V8 Biturbo mit 580 PS</li>
      <li>Launch Control & Keramikbremse</li>
      <li>Exklusives Lederinterieur & Infotainment-System</li>
    </ul>
    <strong>Hinweis:</strong> Supercar für die Straße.',
    'logo' => 'images/CarDB/worsche/logo.webp',
    'images' => $images
  ]);
  




$images = json_encode([
  [
    'filename' => 'images/CarDB/solarix/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/solarix/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/solarix/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/solarix/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]
  ]);

  $db->insert([
    'brand' => 'Solarix',
    'model' => 'Free Energy',
    'year_built' => 2028,
    'displacement' => 0,
    'power' => 250,
    'fuel' => 'Elektro',
    'transmission_type' => 'Automatik',
    'gear_count' => 1,
    'doors' => 5,
    'price' => 65900.00,
    'description' => 'Der <strong>Free Energy</strong> ist das Aushängeschild für nachhaltige Mobilität.<br>
    <ul>
      <li>250 PS starker E-Motor mit Allradantrieb</li>
      <li>Reichweite: 520 km (WLTP)</li>
      <li>Panoramadach & Solarunterstützung</li>
    </ul>
    <strong>Empfohlen:</strong> Für Familien mit Umweltbewusstsein.',
    'logo' => 'images/CarDB/solarix/logo.webp',
    'images' => $images
  ]);
  





$images = json_encode([
  [
    'filename' => 'images/CarDB/nordex/image01.webp',
    'title' => 'Frontansicht',
    'alt' => 'Larifari Speedy 3000 von vorne'
  ],
  [
    'filename' => 'images/CarDB/nordex/image02.webp',
    'title' => 'Heckansicht',
    'alt' => 'Larifari Speedy 3000 von hinten'
  ],
  [
    'filename' => 'images/CarDB/nordex/image03.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ],
  [
    'filename' => 'images/CarDB/nordex/image04.webp',
    'title' => 'Innenraum',
    'alt' => 'Innenraum mit pinkem Leder'
  ]
  ]);

  $db->insert([
    'brand' => 'Nordex',
    'model' => 'Primo',
    'year_built' => 2028,
    'displacement' => 0,
    'power' => 150,
    'fuel' => 'Elektro',
    'transmission_type' => 'Automatik',
    'gear_count' => 1,
    'doors' => 5,
    'price' => 31990.00,
    'description' => 'Der <strong>Nordex Primo</strong> ist ein fortschrittliches Elektrofahrzeug für den Alltag.<br>
    <ul>
      <li>150 PS starker E-Motor</li>
      <li>Bis zu 400 km Reichweite (WLTP)</li>
      <li>Smart Navigation, Rückfahrkamera und Keyless Go serienmäßig</li>
    </ul>
    <strong>Ideal für:</strong> Familien, Pendler und E-Mobilitätsfans.',
    'logo' => 'images/CarDB/nordex/logo.webp',
    'images' => $images
  ]);
  
  













         // JavaScript-Redirect nach dem Löschen
         echo "<script>window.location.href = window.location.pathname;</script>";
         exit;


}
}



// echo "✅ Tabelle '{$table}' wurde neu initialisiert und mit Systemfeldern konfiguriert.";

?>

<!--
<div class="demo-info-container">
  <div class="demo-info-text">
    <h2>Diese Demo ist noch in Entwicklung…</h2>
    <p>
      Aber gute Dinge brauchen eben ihre Zeit.<br>
      Unser Handwerker ist schon fleißig am Werk – du darfst gespannt sein!
    </p>
  </div>
  <div class="demo-info-image">
    <img src="<?php echo $baseUrl ?>/../assets/images/handwerker.webp" alt="Fleißiger Handwerker">
  </div>
</div>
-->

<?php


$db->setTable($table);
$db->select('*')->get();



// Eintrag laden
$entries = $db->select('*')->get(); // oder get()[0], falls kein first() vorhanden

?>



<div class="container">
  <h1 class="my-4" style="color: var(--main-color);">🎯 Willkommen in der **Auto-Datenbank Demo**!</h1>

  <p class="text-muted">
    🚗 **Diese Demo zeigt, wie man mit JsonSQL eine leistungsfähige Fahrzeugdatenbank aufbaut und pflegt.** Du wirst lernen, wie man Fahrzeugdaten speichert, abruft und validiert. Besondere Highlights:
  </p>

  <ul class="list-unstyled text-muted">
    <li><i class="bi bi-check-circle-fill" style="color: var(--main-color);"></i> **Fahrzeugdaten wie Kraftstoffart, Getriebe, Leistung und Preis sind speicherbar und durchsuchbar.** </li>
    <li><i class="bi bi-pencil-square" style="color: var(--main-color);"></i> **Daten lassen sich durch einen interaktiven Frontend-Editor bearbeiten – direkt im Browser!**</li>
    <li><i class="bi bi-server" style="color: var(--main-color);"></i> **Lerne, wie du Systemdaten für eine saubere Datenstruktur aufbaust und in deine Datenbank integrierst.**</li>
    <li><i class="bi bi-arrow-repeat" style="color: var(--main-color);"></i> **Erfahre, wie man Daten effizient mit AJAX aktualisiert und die Benutzererfahrung optimiert.**</li>
  </ul>

  <p class="text-muted">
    🔧 **Viel Spaß beim Erkunden der Fahrzeuge und dem Ausprobieren der interaktiven Funktionen!** <br>
    Diese Demo ist eine ideale Gelegenheit, um sowohl die Grundlagen der Datenbankverwaltung als auch fortgeschrittene Techniken wie Datenvalidierung und Frontend-Interaktivität zu lernen.
  </p>
</div>



<div class="container my-4">
  <!-- Buttons für Datenaktionen -->
  <div class="d-flex justify-content-start">
    <!-- Button zum Löschen von Daten -->
    <a href="?action=delete" class="btn btn-danger me-3" onclick="return confirm('Bist du sicher, dass du alle Daten löschen möchtest?')">
      <i class="bi bi-trash"></i> Daten löschen
    </a>

    <!-- Button zum Erstellen von Daten -->
    <a href="?action=create" class="btn btn-success">
      <i class="bi bi-plus-circle"></i> Daten erstellen
    </a>
  </div>
</div>





<?php
// Überprüfen, ob Einträge vorhanden sind
if (count($entries) == 0) {
  echo "<div class='alert alert-warning'>Keine Daten vorhanden.</div>";
}  
?>




<div class="row">
  <?php  
  foreach ($entries as $entry) {
  // Bild-Galerie aus JSON
  $images = json_decode($entry['images'], true);
  ?>
<div class="col-md-6 mb-4">
  <div class="card h-100 shadow-sm">


    <!-- Slider -->
    <div class="swiper auto-swiper">
      <div class="swiper-wrapper">
        <?php foreach ($images as $img): ?>
          <div class="swiper-slide">
          <img src="<?= htmlspecialchars($img['filename']) ?>" 
     class="img-fluid w-100 gallery-thumb" 
     alt="<?= htmlspecialchars($img['alt']) ?>" 
     title="<?= htmlspecialchars($img['title'] ?? '') ?>"
     data-bs-toggle="modal"
     data-bs-target="#imageModal"
     data-image="<?= htmlspecialchars($img['filename']) ?>"
     data-title="<?= htmlspecialchars($img['title'] ?? '') ?>"
     data-alt="<?= htmlspecialchars($img['alt'] ?? '') ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Navigationspfeile -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>

      <div class="swiper-pagination"></div>
    </div>

    <!-- Fahrzeugdetails -->
    <div class="card-body text-left">

    <!-- Verstecktes ID-Feld für späteres Speichern -->
    <input type="hidden" class="vehicle-id" value="<?= $entry['id'] ?>">    

      <!-- Logo in der Card -->
      <div class="mb-3 brand-logo">
        <img src="<?= htmlspecialchars($entry['logo']) ?>" alt="Logo von <?= htmlspecialchars($entry['brand']) ?>">
      </div>


  <!-- brand editierbar -->
  <?php  
    // Angenommen, $fieldname ist der Name des aktuellen Feldes
    $dataID = $entry['id'];
    $fieldname = 'brand';
    $datatype = $db->getFieldDataType($fieldname);
  ?>
  <h4 class="card-title editable"
      data-id="<?= $dataID ?>"
    data-field="<?= $fieldname ?>"
      data-type="<?= $datatype ?>"      
      data-value="<?= htmlspecialchars($entry[$fieldname]) ?>"
      title="Klicken zum Bearbeiten">
    <?= htmlspecialchars($entry[$fieldname]) ?>
  </h4>


  <!-- Modellname editierbar -->
  <?php  
    $fieldname = 'model';
    $datatype = $db->getFieldDataType($fieldname); 
  ?>
  <h5 class="card-title editable"
      data-id="<?= $dataID ?>"
      data-field="<?= $fieldname ?>"
      data-type="<?= $datatype ?>"      
      data-value="<?= htmlspecialchars($entry[$fieldname]) ?>"
      title="Klicken zum Bearbeiten">
      <?= htmlspecialchars($entry[$fieldname]) ?>
  </h5>

  <!-- Beschreibung editierbar -->
  <?php  
    $fieldname = 'description';
    $datatype = $db->getFieldDataType($fieldname);
    $htmlText = strip_tags($entry[$fieldname], '<br><strong><em><ul><li><b>'); // für Anzeige
    $attrValue = htmlspecialchars($entry[$fieldname], ENT_QUOTES | ENT_HTML5); // roh als Text für data-Attribute
  ?>  

  <div class="card-text editable"
    data-id="<?= $dataID ?>"
    data-field="<?= $fieldname ?>" 
    data-type="<?= $datatype ?>"       
    data-value="<?= $attrValue ?>"
    title="Klicken zum Bearbeiten">
    <?= $htmlText ?> <!-- HTML sichtbar -->
  </div>




  <div class="vehicle-details">
  <div class="row" style="margin-bottom:0;padding-bottom:0">
    <?php 
    $fields = [
      ['label' => 'Baujahr',       'field' => 'year_built',   'suffix' => ''],
      ['label' => 'Hubraum',       'field' => 'displacement', 'suffix' => 'cm³'],
      ['label' => 'Leistung',      'field' => 'power',        'suffix' => 'kWh'],
      ['label' => 'Kraftstoffart', 'field' => 'fuel',         'suffix' => ''],
      ['label' => 'Getriebe',      'field' => 'transmission_type', 'suffix' => ''],
      ['label' => 'Gänge',      'field' => 'gear_count', 'suffix' => ''],      
      ['label' => 'Türanzahl',     'field' => 'doors',        'suffix' => '']
    ];

    foreach ($fields as $f):
      $fieldname = $f['field'];
      $label = $f['label'];
      $value = htmlspecialchars($entry[$fieldname]);
      $datatype = $db->getFieldDataType($fieldname);
      $suffix = $f['suffix'];
      $enumValues = ($datatype === 'enum') ? $db->getEnumValues($fieldname) : null;
      $enumJson = $enumValues ? htmlspecialchars(json_encode($enumValues)) : '';      
      ?>
        <div class="col-md-6 detail-element">
          <strong><?= $label ?>:</strong>
          <span 
            class="editable" 
            data-id="<?= $entry['id'] ?>" 
            data-field="<?= $fieldname ?>" 
            data-type="<?= $datatype ?>" 
            data-value="<?= $value ?>" 
            <?= $enumJson ? "data-options='$enumJson'" : '' ?>>
              <?= $value ?> 
          </span> <?= $suffix ?>
        </div>
      <?php endforeach; ?>
  </div>
</div>






  <!-- Preis editierbar -->
  <?php  
    $fieldname = 'price';
    $datatype = $db->getFieldDataType($fieldname);
    $value = htmlspecialchars($entry[$fieldname]);    
  ?>  
<div class="price-label"><span class="editable on-dark" 
   data-id="<?= $dataID ?>"
   data-field="<?= $fieldname ?>"
   data-type="<?= $datatype ?>"     
   data-value="<?= $value ?>"
   title="Klicken zum Bearbeiten">
  <?= number_format($entry[$fieldname], 2, ',', '.') ?></span> €
</div>


<!-- Speichern Button -->
<button class="btn btn-primary save-button" data-id="<?= $entry['id'] ?>" style="display:none;">Speichern</button>

<!-- Neue Zeile im blauen Design mit zwei Link-Buttons -->
<div class="row save-row" id="save-row-<?= $entry['id'] ?>">
  <div class="col-6 text-center">
    <a href="#" class="btn btn-outline-light" id="save-button-<?= $entry['id'] ?>">
      <i class="bi bi-check-circle"></i> Speichern
    </a>
  </div>
  <div class="col-6 text-center">
    <a href="#" class="btn btn-outline-light" id="cancel-button-<?= $entry['id'] ?>">
      <i class="bi bi-x-circle"></i> Abbrechen
    </a>
  </div>
</div>

    </div>

</div> </div>

<?php
}
?>

<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;"></div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="imageModalLabel"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded" alt="">
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');

    modal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const imgSrc = trigger.getAttribute('data-image');
      const imgTitle = trigger.getAttribute('data-title');
      const imgAlt = trigger.getAttribute('data-alt');

      modalImg.src = imgSrc;
      modalImg.alt = imgAlt;
      modalTitle.textContent = imgTitle;
    });
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
  let currentEditableElement = null;

  document.querySelectorAll('.editable').forEach(el => {
    el.addEventListener('click', (event) => {
      const el = event.currentTarget;
      if (currentEditableElement && currentEditableElement !== el) return;
      if (el.querySelector('textarea') || el.querySelector('input') || el.querySelector('select')) return;

      const field = el.dataset.field;
      const id = el.dataset.id;
      const oldValue = el.dataset.value;
      const dataType = el.dataset.type || 'string';
      const unit = el.dataset.unit || '';

      if (currentEditableElement) {
        const previousSaveRow = currentEditableElement.closest('.card-body').querySelector('.save-row');
        if (previousSaveRow) previousSaveRow.style.display = 'none';
      }

      currentEditableElement = el;

      const saveRow = document.querySelector(`#save-row-${id}`);
      const saveButton = document.querySelector(`#save-button-${id}`);
      const cancelButton = document.querySelector(`#cancel-button-${id}`);

      cancelButton.onclick = (e) => {
        e.preventDefault();
        el.innerHTML = oldValue;
        saveRow.style.display = 'none';
        currentEditableElement = null;
      };

      // Beschreibung (Textarea)
      if (field === 'description') {
        const textarea = document.createElement('textarea');
        textarea.value = oldValue;
        textarea.style.width = '100%';
        textarea.style.height = '120px';
        textarea.className = 'frontend-edit';
        el.innerHTML = '';
        el.appendChild(textarea);
        textarea.focus();

        saveRow.style.display = 'flex';

        saveButton.onclick = (e) => {
          e.preventDefault();
          const newValue = textarea.value;
          updateVehicleData(id, field, newValue, dataType, function(success) {
            if (success) {
              el.dataset.value = newValue;
              el.innerHTML = newValue;
            } else {
              el.innerHTML = oldValue;
            }
            saveRow.style.display = 'none';
            currentEditableElement = null;
          });
        };

      }

      // ENUM-Auswahl (Select)
      else if (dataType === 'enum') {
        const options = JSON.parse(el.dataset.options || '[]');
        const select = document.createElement('select');
        select.className = 'frontend-edit';

        options.forEach(opt => {
          const option = document.createElement('option');
          option.value = opt;
          option.textContent = opt;
          if (opt === oldValue) option.selected = true;
          select.appendChild(option);
        });

        el.innerHTML = '';
        el.appendChild(select);
        select.focus();

        saveRow.style.display = 'flex';

        saveButton.onclick = (e) => {
          e.preventDefault();
          const newValue = select.value;
          updateVehicleData(id, field, newValue, dataType, function(success) {
            if (success) {
              el.dataset.value = newValue;
              el.innerHTML = newValue;
            } else {
              el.innerHTML = oldValue;
            }
            saveRow.style.display = 'none';
            currentEditableElement = null;
          });
        };
      }

      // Normale Textfelder (Input)
      else {
        const input = document.createElement('input');
        input.type = 'text';
        input.value = oldValue;
        input.className = 'frontend-edit';
        el.innerHTML = '';
        el.appendChild(input);
        input.focus();

        saveRow.style.display = 'flex';

        saveButton.onclick = (e) => {
          e.preventDefault();
          const newValue = input.value;
          updateVehicleData(id, field, newValue, dataType, function(success) {
            if (success) {
              el.dataset.value = newValue;

              let displayValue = newValue;
              if (dataType === 'float') {
                displayValue = parseFloat(newValue).toLocaleString('de-DE', {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2
                });
              }

              el.innerHTML = displayValue;
            } else {
              el.innerHTML = oldValue;
            }
            saveRow.style.display = 'none';
            currentEditableElement = null;
          });
        };
      }
    });
  });
});



// ✅ AJAX mit reiner Validierungsprüfung – keine Vorverarbeitung!
function updateVehicleData(id, field, newValue, dataType = 'string', callback = null) {
  const vehicleId = id;

  console.group(`📤 Update-Fahrzeugdaten ID: ${vehicleId}`);
  console.log("   Datentyp:       ", dataType);
  console.log("   Feld:           ", field);
  console.log("   Ursprungswert:  ", newValue);

  const url = `update_vehicle.php?id=${encodeURIComponent(vehicleId)}&field=${encodeURIComponent(field)}&value=${encodeURIComponent(newValue)}`;

  const xhr = new XMLHttpRequest();
  xhr.open("GET", url, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        console.log("📥 Antwort vom Server:", response);

        if (response.status === 'success') {
          if (response.updated === 0) {
            showSnackbar("Kein Speichern notwendig – keine Änderung erkannt.", "info");
          } else {
            showSnackbar("Änderung gespeichert!", "success");
          }
          if (callback) callback(true);
        } else {
          showSnackbar(response.message, "error");
          if (callback) callback(false);
        }
      } catch (e) {
        showSnackbar("Ungültige Serverantwort", "error");
        if (callback) callback(false);
      }
    } else {
      showSnackbar("Serverfehler", "error");
      if (callback) callback(false);
    }
  };

  xhr.onerror = () => {
    showSnackbar("Netzwerkfehler", "error");
    if (callback) callback(false);
  };

  xhr.send();
}
</script>




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>


.brand-logo {
  display: flex;
  justify-content: center; /* Horizontale Zentrierung */
  align-items: center;     /* Vertikale Zentrierung (optional, falls das Logo kleiner als der Container ist) */
  text-align: center; /* Zentriert das Bild horizontal */  
  height: 150px;
  margin-top: -35px;
}

.brand-logo img {
  max-height: 100px;
} 

.frontend-edit {
  border-color: var(--main-color);
  background: linear-gradient(135deg,var(--editBG-start),var(--editBG-end));
  border-width: 2px;
  border-style: solid;
  padding: 8px;
  border-radius: 4px;
  font-size: 16px;
  width: 100%;
}

/* Stil für das Eingabefeld, wenn es fokussiert wird */
.frontend-edit:focus {
  border-color: var(--main-color);
  outline: none;
  box-shadow: 0 0 5px var(--main-color);
}




.save-row {
  margin-top: 15px;
  background: linear-gradient(135deg,var(--main-color),var(--main-color-darken));
  padding: 10px 0; 
  display: none;
  border-radius: 8px;
}

.card-body {
  padding-bottom: 0 !important;
  margin-bottom: 0 !important;  
}


  .swiper {
  width: 100%;
  height: 380px; /* oder auto, aber mit Fallback-Höhe */
}
.swiper-slide img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}

.swiper-button-prev,
.swiper-button-next {
  background-color: rgba(0, 0, 0, 0.4); /* schwarzer Hintergrund, 40% transparent */
  color: white; /* Pfeilfarbe */
  padding: 1rem;
  border-radius: 20%;
  width: 34px;
  height: 54px;
}

.swiper-button-prev::after,
.swiper-button-next::after {
  color: white; /* falls Swiper die Pfeile über ::after generiert */
  font-size: 20px;
}

.swiper-button-prev:hover,
.swiper-button-next:hover {
  background-color: rgba(0, 0, 0, 0.6);
  transform: scale(1.1);
}

.swiper-button-prev:hover::after,
.swiper-button-next:hover::after {
  transform: scale(1.2);
}

.swiper-pagination-bullet {
  background: white;
  opacity: 0.5; /* leicht transparent, kannst du auch auf 1 setzen */
}

.swiper-pagination-bullet-active {
  opacity: 1; /* der aktive Punkt ist voll sichtbar */
}

.price-label {
  display: inline-block;
  position: relative;
  background: linear-gradient(135deg,var(--main-color),var(--main-color-darken));
  color: white;
  font-size: 1.4rem;
  font-weight: bold;
  padding: 10px 20px;
  border-radius: 30px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  text-align: center;
  transition: transform 0.2s ease;
  cursor: pointer;
  margin-top: 35px;
}

.price-label:hover {
  transform: scale(1.05);
}

.price-label::after {
  content: 'NEU';
  position: absolute;
  top: -10px;
  right: -10px;
  background:rgb(162, 10, 10);
  color: white;
  font-size: 0.75rem;
  padding: 4px 10px;
  border-radius: 4px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}




.vehicle-details {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
  margin-top: 20px;
}

.vehicle-details .row {
  display: flex;
  flex-wrap: wrap;
}

.vehicle-details .col-md-6 {
  margin-bottom: 0;
  /* padding: 10px; */
}

.detail-element {
  background-color:rgba(240, 240, 240, 0) !important;
}

.vehicle-details strong {
  color: #2d3436;
  font-size: 1.1rem;
}

.vehicle-details .col-md-6 {
  background-color: #ffffff;
  border-radius: 6px;
  /* box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); */
}

.vehicle-details .col-md-6:hover {
  background-color: #f0f0f0;
}








/* Snackbar-Wrapper zentriert auf der Seite */
#custom-snackbar-wrapper {
  width: 380px;
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 9999;
  transform: translate(-50%, -50%);
}

/* Basis-Toast-Stil */
#custom-snackbar-wrapper .toast {
  width: 100%;
  height: auto;
  padding: 10px 0 0 18px;
  background-color: #ffffff;
  border-radius: 7px;
  display: grid;
  grid-template-columns: 1.3fr 6fr 0.5fr;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
  align-items: center;
  transition: all 0.3s ease;
  margin-bottom: 0px;
}

/* Farben nach Typ */
#custom-snackbar-wrapper .toast.success {
  border-left: 5px solid var(--success) !important;
}
#custom-snackbar-wrapper .toast.error {
  border-left: 5px solid var(--error);
}
#custom-snackbar-wrapper .toast.info {
  border-left: 5px solid var(--info);
}
#custom-snackbar-wrapper .toast.warning {
  border-left: 5px solid var(--warning);
}

/* Icon-Farbe */
#custom-snackbar-wrapper .toast.success i {
  color: var(--success) !important;
}
#custom-snackbar-wrapper .toast.error i {
  color: var(--error);
}
#custom-snackbar-wrapper .toast.info i {
  color: var(--info);
}
#custom-snackbar-wrapper .toast.warning i {
  color: var(--warning);
}

/* Icon-Größe */
#custom-snackbar-wrapper .toast .outer-container i {
  font-size: 35px;
  margin-top: -15px;
}

/* Inhalt */
#custom-snackbar-wrapper .toast .inner-container p:first-child {
  font-weight: 600;
  font-size: 16px;
  color: #101020;
  margin-bottom: 5px;
}
#custom-snackbar-wrapper .toast .inner-container p:last-child {
  font-size: 15px;
  font-weight: 400;
  color: #656565;
}

/* Close-Button */
#custom-snackbar-wrapper .toast button {
  all: unset;
  font-size: 22px;
  color: #656565;
  cursor: pointer;
  height: 20px;
  width: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: -20px;
  align-self: flex-start;
}

/* Optionales Ausblenden */
#custom-snackbar-wrapper .toast.hide {
  opacity: 0;
  transform: translateY(-10px);
}


.editable {
  position: relative;
  /* display: inline-block;  */
  cursor: pointer;
  transition: border 0.2s, background-color 0.2s;
}

.editable:hover {
  border-bottom: 1px dashed #999;
  background-color:rgba(248, 249, 250, 0);
  padding-right: 16px; /* ➕ Nur beim Hover Platz lassen */  
}

/* Stift-Icon anzeigen */
.editable::after {
  content: "✎\2003"; /* EM-SPACE – typografisch hübscher */
  font-size: 16px;
  color:var(--main-color);
  position: absolute;
  right: ;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0;
  transition: opacity 0.2s;
}

/* Beim Hover Stift anzeigen */
.editable:hover::after {
  opacity: 1;
}

.editable.on-dark::after {
  color: white; /* Auf dunklem Hintergrund – weißer Stift */
}




</style>

<script>
// Swiper Initialisierung mit Fullscreen und Zoom
const swiper = new Swiper('.auto-swiper', {
  spaceBetween: 10,      // Abstand zwischen den Bildern
  slidesPerView: 1,     // Zeige nur ein Bild gleichzeitig
  loop: true,           // Bilder können in einer Schleife durchlaufen werden
  pagination: {
    el: '.swiper-pagination', // Pagination (Punkte unten)
    clickable: true,
  },
  navigation: {
    nextEl: '.swiper-button-next',  // Nächster Pfeil
    prevEl: '.swiper-button-prev',  // Vorheriger Pfeil
  },
  zoom: {
    maxRatio: 3,           // Maximale Zoomstufe
    toggle: true,          // Zoom durch Klick aktivieren
  },
  effect: 'fade',         // Fade-Effekt, optional für Lightbox-Feeling
  keyboard: {
    enabled: true,        // Tastatursteuerung aktivieren
    onlyInViewport: true,
  },
});


function showSnackbar(message, type = 'info', title = null) {
  const wrapperId = 'custom-snackbar-wrapper';
  let wrapper = document.getElementById(wrapperId);

  // 🧹 Wrapper erzeugen oder leeren
  if (!wrapper) {
    wrapper = document.createElement('div');
    wrapper.id = wrapperId;
    wrapper.className = 'wrapper';
    document.body.appendChild(wrapper);
  } else {
    wrapper.innerHTML = ''; // Vorherige Toasts entfernen
  }

  // 🔧 Defaults
  const titles = {
    success: 'Erfolg',
    error: 'Fehler',
    info: 'Hinweis',
    warning: 'Achtung'
  };
  const icons = {
    success: 'fa-check-circle',
    error: 'fa-times-circle',
    info: 'fa-info-circle',
    warning: 'fa-exclamation-circle'
  };

  const usedTitle = title || titles[type] || 'Nachricht';
  const usedIcon = icons[type] || 'fa-bell';

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <div class="outer-container"><i class="fas ${usedIcon}"></i></div>
    <div class="inner-container">
      <p>${usedTitle}</p>
      <p>${message}</p>
    </div>
    <button>&times;</button>
  `;

  wrapper.appendChild(toast);

  // ⏱️ Auto-Remove nach Timeout
  setTimeout(() => {
    toast.classList.add('hide');
    setTimeout(() => toast.remove(), 300);
  }, 4000);

  // ✖️ Manuelles Entfernen
  toast.querySelector('button').onclick = () => toast.remove();
}


function showSnackbar_old(message, type = 'info') {
  const container = document.getElementById('toast-container');

  // 🧹 Alle bestehenden Toasts schließen und Timer abbrechen
  const activeToasts = container.querySelectorAll('.toast');
  activeToasts.forEach(toastEl => {
    // Falls es einen aktiven Bootstrap Toast gibt
    const toastInstance = bootstrap.Toast.getInstance(toastEl);
    if (toastInstance) {
      toastInstance.hide(); // Sanft ausblenden
    }

    // 🔁 Timer manuell stoppen, falls einer gesetzt ist
    clearTimeout(toastEl.dataset.toastTimeout);

    // Sofort entfernen (optional – oder oben .hide() + nach .hidden.bs.toast)
    toastEl.remove();
  });

  // 🎨 Hintergrund-Klassen nach Typ
  const bgClass = {
    success: 'bg-success text-white',
    error: 'bg-danger text-white',
    info: 'bg-primary text-white',
    warning: 'bg-warning text-dark'
  }[type] || 'bg-secondary text-white';

  const toastId = `toast-${Date.now()}`;
  const toastHtml = `
    <div id="${toastId}" class="toast align-items-center ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>`;

  container.insertAdjacentHTML('beforeend', toastHtml);

  const toastElement = document.getElementById(toastId);
  const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
  toast.show();

  // 🕒 Falls du einen Fallback willst, Timer in Dataset speichern (optional)
  toastElement.dataset.toastTimeout = setTimeout(() => {
    toastElement.remove();
  }, 5000);

  toastElement.addEventListener('hidden.bs.toast', () => {
    toastElement.remove();
  });
}



</script>




<?php

/*
// Aktuelles Datum und Uhrzeit erstellen
$date = new DateTime();

// Formatieren auf MySQL-kompatibles Format 'Y-m-d H:i:s'
$formattedDate = $date->format('Y-m-d H:i:s');

// In die Datenbank einfügen
$db->insert(['datum' => $formattedDate, 'price' => 120000.55, 'brand' => 'HuiHui']);

echo "✅ Testdaten eingefügt";
*/


?>




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
          <h4>JsonSQL System Datei: /CarDB/cars.system.json</h4>
          <pre class="code-block"><code class="language-json"><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/CarDB/cars.system.json'));          
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Neuer Tab für JSON-Dateien -->
<div class="container mt-1 mb-3">
  <div class="accordion" id="jsonAccordion2">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingJson">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJson2" aria-expanded="false" aria-controls="collapseJson">
          📄 JSON-Dateien anzeigen
        </button>
      </h2>
      <div id="collapseJson2" class="accordion-collapse collapse" aria-labelledby="headingJson" data-bs-parent="#jsonAccordion2">
        <div class="accordion-body">        

        Hier sieht man gut, wie alle fehlenden Felder automatisch durch die Systemdefinition ergänzt werden.
Für kleinere Experimente oder einfache Tabellen kann man darauf verzichten.
Wir empfehlen jedoch, die Datenbank stets sauber zu definieren – vor allem im Hinblick auf die Datenkonsistenz.
So wird auch beim Einfügen neuer Datensätze eine zuverlässige Validierung sichergestellt.
        <pre class="code-block"><code class="language-sql">
$db->insert(['doors' => 100,'year_built' => 2024, 'brand' => 'dsf dsf das fds fds af dsg dsfg fds gsf gf dsg fsdgfdsgdsgfdsgfdgfd gfdsgfdsgfdsg fsdgfsdg' ]);

// Aktuelles Datum und Uhrzeit erstellen
$date = new DateTime();

// Formatieren auf MySQL-kompatibles Format 'Y-m-d H:i:s'
$formattedDate = $date->format('Y-m-d H:i:s');

// In die Datenbank einfügen
$db->insert(['datum' => $formattedDate, 'price' => 120000.55]);
        </code></pre>

          <h4>JsonSQL System Datei: /CarDB/cars.json</h4>
          <pre class="code-block"><code class="language-json"><?php
            echo htmlspecialchars(file_get_contents(__DIR__ . '/CarDB/cars.json'));          
          ?></code></pre>
        </div>
      </div>
    </div>
  </div>
</div>


<?php 

} catch (\Exception $e) {
    // Fange die Exception und gebe die Fehlermeldung aus
    echo "<div class='alert alert-danger'>
    <i class='bi bi-exclamation-circle'></i> Fehler: <strong>" . $e->getMessage() . "</strong>
  </div>";
    // Ausgabe des gesamten Stack-Trace für detaillierte Fehlersuche
    echo "<pre><code>" . $e->__toString() . "</code></pre>";  // Ausgabe des vollständigen Stack-Trace    
} finally {
    // Dieser Block wird immer ausgeführt, auch wenn eine Exception geworfen wurde
    // Hier kannst du den Footer immer laden
    require_once __DIR__ . '/../includes/footer.php';
}

 

?>

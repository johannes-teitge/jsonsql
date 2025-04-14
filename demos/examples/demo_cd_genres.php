<?php

// Seitentitel und Includes
$pageTitle = "JsonSQL Demo: CD-Verwaltung mit Genres (n:m)";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../vendor\JsonSQL/src/JsonSQL.php';

// Dynamischer Pfad zum FDV-Hauptverzeichnis
$fdvPath = realpath(__DIR__ . '/../fdv-plugin/fdv/FancyDumpVar.php');
if (!$fdvPath || !file_exists($fdvPath)) {
    die("Fehler: FancyDumpVar.php nicht gefunden unter $fdvPath");
}
require_once $fdvPath;

use FancyDumpVar\FancyDumpVar;  // FancyDumpVar importieren
use Src\JsonSQL;

// FancyDumpVar Instanz und Beispiel-Dump
$debugger = new \FancyDumpVar\FancyDumpVar();

// Datenbank-Verbindung
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');

$cdTable = 'cds';
$genreTable = 'genres';
$cdGenresTable = 'cd_genres';

// Demo-Daten vorbereiten
if (!file_exists(__DIR__ . '/../testdb/' . $cdTable . '.json')) {
    $db->truncate($cdTable);
    $cds = [
        ['cd_id' => 1, 'cd_title' => 'Greatest Hits'],
        ['cd_id' => 2, 'cd_title' => 'Rock Classics'],
        ['cd_id' => 3, 'cd_title' => 'Jazz Vibes'],
        ['cd_id' => 4, 'cd_title' => 'Electronic Dreams'],
        ['cd_id' => 5, 'cd_title' => 'Acoustic Moods'],
    ];
    foreach ($cds as $cd) {
        $db->from($cdTable)->insert($cd);
    }
}

if (!file_exists(__DIR__ . '/../testdb/' . $genreTable . '.json')) {
    $db->truncate($genreTable);
    $genres = ['Pop', 'Rock', 'Jazz', 'Electronic', 'Acoustic', 'Indie'];
    foreach ($genres as $i => $genre) {
        $db->from($genreTable)->insert([
            'genre_id' => $i + 1,
            'genre_title' => $genre
        ]);
    }
}

if (!file_exists(__DIR__ . '/../testdb/' . $cdGenresTable . '.json')) {
    $db->truncate($cdGenresTable);
    $relations = [
        [1, 1], [1, 2],
        [2, 2], [2, 6],
        [3, 3], [3, 6],
        [4, 4],
        [5, 5], [5, 1]
    ];
    foreach ($relations as [$cd_id, $genre_id]) {
        $db->from($cdGenresTable)->insert([
            'rel_cd_id' => $cd_id,
            'rel_genre_id' => $genre_id
        ]);
    }
}



$cds = $db->from($cdTable)
          ->select('*')  // Holen wir uns die gesamten Spalten der CD-Tabelle
          ->get();

// Debugging der CDs
$debugger->dump($cds);

$cdGenres = $db->from($cdTable)
               ->select('*')  // Auswahl der CD-Daten
               ->join($cdGenresTable, ['local' => 'cd_id', 'foreign' => 'rel_cd_id'], 'RIGHT')  // RIGHT JOIN mit cd_genres
               ->get();

// Debugging der Joins
$debugger->dump($cdGenres);


$cdsWithGenres = $db->from($cdTable)
    ->select('*')  // Alle Felder der CD auswählen
    ->join($cdGenresTable, ['local' => 'cd_id', 'foreign' => 'rel_cd_id'], 'LEFT') // LEFT JOIN mit cd_genres
    ->join($genreTable, ['local' => 'rel_genre_id', 'foreign' => 'genre_id'], 'LEFT') // LEFT JOIN mit genres
    ->select('genre_title') // Genre-Titel zusätzlich auswählen
    ->get();  // Hole die Daten

// Debugging der finalen Joins
$debugger->dump($cdsWithGenres);




// Zuordnungen laden (CDs mit den Genre-IDs und Titel)
$cdsWithGenres2 = $db->from($cdTable)
    ->select('*')  // CD-ID und Titel auswählen
    ->join($cdGenresTable, ['local' => 'cd_id', 'foreign' => 'rel_cd_id'], 'RIGHT') // JOIN mit cd_genres
    ->join($genreTable, ['local' => 'rel_genre_id', 'foreign' => 'genre_id'], 'LEFT') // JOIN mit genres
    ->select('genre_title') // Genre-Titel zusätzlich auswählen
    ->get();  // Hole die Daten

// Überprüfung, ob 'cd_id' und 'cd_title' vorhanden sind
$groupedCds = [];
foreach ($cdsWithGenres as $cd) {
    if (isset($cd['cd_id']) && isset($cd['cd_title'])) {  // Sicherstellen, dass die Felder existieren
        $groupedCds[$cd['cd_id']]['cd_title'] = $cd['cd_title'];
        if (!isset($groupedCds[$cd['cd_id']]['genres'])) {
            $groupedCds[$cd['cd_id']]['genres'] = [];
        }
        if (isset($cd['genre_title'])) {
            $groupedCds[$cd['cd_id']]['genres'][] = $cd['genre_title'];
        }
    }
}

// Debugging der gruppierten Daten
$debugger->dump($groupedCds);
$debugger->dumpOut();
?>

<div class="container">
  <h1 class="my-4">🎵 CD-Verwaltung mit Genres (n:m)</h1>
  <p class="text-muted">
    CDs können mehreren Genres zugeordnet werden. Die Zuordnung erfolgt über eine Zwischentabelle.<br>
    Beim Klick auf das <code>+</code> neben dem CD-Titel wird die Liste der Genres eingeblendet.
  </p>

  <ul class="list-group">
    <?php foreach ($groupedCds as $cdId => $cdData): ?>
      <li class="list-group-item">
        <button class="btn btn-sm btn-outline-primary toggle-genres me-2" data-cd-id="<?= $cdId ?>">+</button>
        <strong><?= htmlspecialchars($cdData['cd_title'] ?? 'Unbekannt') ?></strong>  <!-- Nullwert absichern -->
        <ul class="genre-list mt-2 ms-4 d-none" id="genres-<?= $cdId ?>">
          <?php foreach ($cdData['genres'] as $genre): ?>
            <li><?= htmlspecialchars($genre ?? 'Unbekannt') ?></li> <!-- Nullwert absichern -->
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<script>
  document.querySelectorAll('.toggle-genres').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.cdId;
      const list = document.getElementById('genres-' + id);
      list.classList.toggle('d-none');
      btn.textContent = list.classList.contains('d-none') ? '+' : '−';
    });
  });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

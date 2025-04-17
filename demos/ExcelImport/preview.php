<?php
require_once __DIR__ . '/../../src/JS_Import/ExcelImport.php';

use JS_Import\ExcelImport;

if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== 0) {
    die("Fehler beim Datei-Upload");
}

$uploadPath = __DIR__ . '/uploaded.csv';
move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadPath);

$importer = new ExcelImport();
if (!$importer->loadFile($uploadPath)) {
    die("Datei konnte nicht geladen werden");
}

$headers = $importer->getHeaders();
$data = $importer->getPreview();
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Vorschau der Excel-Datei</title>
</head>
<body>
  <h2>📊 Vorschau der geladenen Datei</h2>
  <table border="1" cellpadding="5">
    <thead><tr>
      <?php foreach ($headers as $head): ?><th><?= htmlspecialchars($head) ?></th><?php endforeach; ?>
    </tr></thead>
    <tbody>
      <?php foreach ($data as $row): ?>
        <tr><?php foreach ($row as $cell): ?>
          <td><?= htmlspecialchars($cell) ?></td>
        <?php endforeach; ?></tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

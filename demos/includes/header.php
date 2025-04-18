<?php
// Überprüfen, ob der URL-Parameter gesetzt ist
if (isset($_GET['debugger'])) {
    // URL-Parameter lesen und den gewünschten Status setzen
    $debuggerStatus = $_GET['debugger'] === 'true' ? 'true' : 'false';

    // Cookie immer setzen (true oder false)
    setcookie('show_debugger', $debuggerStatus, time() + (86400 * 30), "/");  // 30 Tage gültig
    $_COOKIE['show_debugger'] = $debuggerStatus;  // Setzen des Cookies direkt für sofortige Verwendung
}

// Prüfen, ob der Debugger angezeigt werden soll (Cookie auslesen)
$showDebugger = isset($_COOKIE['show_debugger']) && $_COOKIE['show_debugger'] === 'true';
$headerBG = isset($headerBG) ? $headerBG : ''; // Wenn $headerBG gesetzt ist, behält es den Wert, sonst wird es auf einen leeren String gesetzt.




// FancyDumpVar für Debugging einbinden
require_once __DIR__ . '/../includes/tools/fdv/FancyDumpVar.php';
use FancyDumpVar\FancyDumpVar;
$debugger = new FancyDumpVar();

$title = $pageTitle ?? 'JsonSQL Demo';
$removeOverview = $removeOverview ?? false;

$ogDescription = $pageDescription ?? 'Eine interaktive Demo aus dem JsonSQL-Projekt.';
$ogImage = $pageImage ?? 'https://www.teitge.de/JsonSQL/demos/assets/images/FacebookDefault.webp';
// URL dynamisch erzeugen
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$ogUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$__start = microtime(true);

define('APP_ROOT', dirname(__DIR__)); // z. B. /var/www/html/projekte/JsonSQL
define('APP_ASSETS', APP_ROOT . '/assets'); // Pfad für Server-Zugriff
define('APP_ASSETS_URL', dirname($_SERVER['SCRIPT_NAME']) . '/../assets'); // URL relativ zur aktuellen Datei

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ Open Graph für Facebook -->
  <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($ogDescription) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($ogUrl) ?>">
  <meta property="og:type" content="website">

  <link rel="icon" href="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo.svg" type="image/webp">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

 <!-- JsonSQL CSS -->  
 <link href="../includes/css/styles.css?v=<?= date('YmdHis') ?>" rel="stylesheet">


   <!-- JsonSQL Icon  -->  
   <link href="../assets/Icons/JsonSQL/style.css" rel="stylesheet">  


  <!-- Highlight.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/github.min.css">
  <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      hljs.highlightAll();
      hljs.initLineNumbersOnLoad(); // <- Zeilennummern aktivieren
    });
    </script>

  <?php if (!empty($additionalCss)): ?>
    <?php foreach ($additionalCss as $css): ?>
      <link rel="stylesheet" href="<?= $css ?>">
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($additionalJs)): ?>
    <?php foreach ($additionalJs as $js): ?>
      <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>  


  <style>




<?php if ($headerBG != '') { ?>


    header {
            background: url('<?= $headerBG ?>') no-repeat center center;
            background-size: cover;
            height: <?= $headerHeight ?>;
            color: white;
        }

    .head-wrapper {
      background-color:rgba(0, 0, 0, 0.54);  
      padding: 10px 0
    }

    h1 {
          color: var(--white-color);
        } 

.headlogo img {
  filter: drop-shadow(0 0 3px rgb(255, 255, 255));
  animation: glow 2.5s ease-in-out infinite alternate;
  animation-delay: 1.8s; /* startet nach dem Landen */
}


@keyframes glow {
  from {
    filter: drop-shadow(0 0 3px rgb(255, 255, 255));
  }
  to {
    filter: drop-shadow(0 0 20px rgb(255, 255, 255));
  }
}





<?php } else { ?>

    h1 {
          color: var(--main-color);
        }


.headlogo img {
  filter: drop-shadow(0 0 10px #0076cfaa);
  animation: glow 2.5s ease-in-out infinite alternate;
  animation-delay: 1.8s; /* startet nach dem Landen */
}

  @keyframes glow {
  from {
    filter: drop-shadow(0 0 5px #0076cf55);
  }
  to {
    filter: drop-shadow(0 0 20px #0076cfff);
  }
}


<?php } ?>    








  </style>
</head>



<body class="bg-light d-flex flex-column min-vh-100">

   
  <header class="bg-primary text-white py-3 shadow-sm">
  <div class="head-wrapper">  
  <div class="container align-items-center justify-content-between flex-wrap">    

  <div class="text-center mb-4 headlogo">
      <?php if ($headerBG != '') { ?>
        <img src="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo-FullWhite.svg" alt="JsonSQL Logo" style="max-height: 80px;">
      <?php } else { ?>
          <img src="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo.svg" alt="JsonSQL Logo" style="max-height: 80px;">          
      <?php } ?>          
  </div>
    
  <h1 class="mb-4 text-center demoshead"><?= htmlspecialchars($title) ?></h1>
    
  <div class="text-center d-flex justify-content-center gap-3">
    <?php if (!$removeOverview): ?>
      <a href="index.php" class="backContent">
        <i class="bi bi-arrow-left"></i> Zur Übersicht
      </a>
    <?php endif; ?>
    <a href="../../doku/" class="backContent">
      <i class="bi bi-journal-code"></i> Dokumentation
    </a>
    <a href="../examples/faq.php" class="backContent">
      <i class="bi bi-question-circle"></i> FAQ
    </a>    
  </div>

</div>   
  
</div>
  </header> 
  

<div class="container py-3">  
    


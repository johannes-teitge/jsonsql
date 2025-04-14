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
  <link rel="icon" href="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo.svg" type="image/webp">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


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

:root {
      --main-color: #0176D0;
      --main-color-darken:rgb(0, 83, 147);      
      --accent-color: #FFA800;
      --accent-hover: #e69500;
      --text-color: #444;
      --background-color: #f8f9fa;
      --background-color2:rgb(212, 212, 212); 
      --white-color:rgb(255, 255, 255);           
    }

    header {
  background: linear-gradient(to bottom, var(--background-color), var(--background-color2));     
}



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






    body {
     /* padding-bottom: 5rem; */
      color: var(--text-color);
      background: linear-gradient(to bottom, #ffffff,rgba(194, 194, 194, 0.26)); 
      min-height: 100vh;      
    }

    main {
  flex: 1 0 auto;
}    



    pre.code-block {
      background-color: #1e1e1e;
      color: #f8f8f2;
      padding: 1rem;
      border-radius: 0.5rem;
      overflow-x: auto;
    }

    .footer {
      background: linear-gradient(to bottom, var(--main-color), var(--main-color-darken));      
      flex-shrink: 0;
    }

    .headlogo {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
  opacity: 0;
  transform: translateY(-160px);
  animation: moonland 1.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}



@keyframes moonland {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}






    .soft-shadow-separator {
    margin: 1rem auto;
    width: 100%;
    height: 20px;
  }

  .card {
    margin-bottom: 20px;
  }

  .backContent {
    text-decoration: none;
    color:rgb(168, 168, 168);
    padding: 5px;
    border-radius: 4px;
  }

  .backContent:hover {
    text-decoration: none;
    background-color:rgb(168, 168, 168);
    color: white;
  }  

  .demoshead {
    margin-bottom: 4px !important;
  }


  pre.code-block {
    background-color:rgba(255, 255, 255, 0);
    color:rgb(226, 226, 223);
    padding: 0;
    border-radius: 0;
    _overflow-x: auto;
}

.hljs-ln-code_, header {
  background-color:rgba(119, 187, 255, 0.1);  
}

pre code.hljs {
    padding: 0;
}




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
    
  <div class="text-center">
    <a href="index.php" class="backContent">
      ← Zur Übersicht
    </a>
  </div>
</div>   
  
</div>
  </header> 
  

<div class="container py-3">  
    


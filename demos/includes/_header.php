<?php
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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css_" rel="stylesheet">


  <!-- Highlight.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/github.min.css_">
  <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js_"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js_"></script>
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
    }

    body {
      padding-bottom: 5rem;
      color: var(--text-color);
      background: linear-gradient(to bottom, #ffffff,rgba(194, 194, 194, 0.26));      
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

    }

    .headlogo {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
  opacity: 0;
  transform: translateY(-160px);
  animation: moonland 1.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.headlogo img {
  filter: drop-shadow(0 0 10px #0076cfaa);
  animation: glow 2.5s ease-in-out infinite alternate;
  animation-delay: 1.8s; /* startet nach dem Landen */
}

@keyframes moonland {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes glow {
  from {
    filter: drop-shadow(0 0 5px #0076cf55);
  }
  to {
    filter: drop-shadow(0 0 20px #0076cfff);
  }
}






    .soft-shadow-separator {
    margin: 4rem auto;
    width: 80%;
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

.hljs-ln-code_ {
  background-color:rgba(119, 187, 255, 0.1);  
}

pre code.hljs {
    padding: 0;
}




  </style>
</head>





<body class="bg-light">
  <div class="container py-5">
    
  <div class="text-center mb-4 headlogo">
      <img src="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo.svg" alt="JsonSQL Logo" style="max-height: 80px;">
  </div>
    
  <h1 class="mb-4 text-center demoshead"><?= htmlspecialchars($title) ?></h1>
    
  <div class="text-center mb-4">
    <a href="index.php" class="backContent">
      ← Zur Übersicht
    </a>
  </div>    
    
  <hr class="soft-shadow-separator">


<div class="accordion" id="accordionExample">

    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
          Was ist JsonSQL?
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
           data-bs-parent="#accordionExample">
        <div class="accordion-body">
          JsonSQL ist eine PHP-Bibliothek für SQL-ähnliche Abfragen auf JSON-Dateien – ganz ohne Datenbank.
        </div>
      </div>
    </div>

    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
          Wie funktioniert JsonSQL?
        </button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
           data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Es verarbeitet JSON-Dateien wie Datenbank-Tabellen. SQL-ähnliche Methoden wie <code>select</code>, <code>where</code>, <code>join</code> sind verfügbar.
        </div>
      </div>
    </div>

  </div>
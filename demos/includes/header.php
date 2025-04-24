<?php

require_once __DIR__ . '/tools/renderThemeCSS.php';

$baseUrl = !empty($baseUrl) ? $baseUrl : dirname($_SERVER['PHP_SELF']);
$basedir = !empty($basedir) ? $basedir : dirname($_SERVER['SCRIPT_NAME']) . '/../assets';

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

// FancyDumpVar für Debugging einbinden
require_once __DIR__ . '/../includes/tools/fdv/FancyDumpVar.php';
use FancyDumpVar\FancyDumpVar;
$debugger = new FancyDumpVar();

$title = $pageTitle ?? 'JsonSQL Demo';
$removeOverview = $removeOverview ?? false;

$baseUrl = $baseUrl ?? '';  // Standard: eine Ebene zurück (für /demos/*)
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

  <!-- Favicon -->
  <link rel="icon" href="<?= APP_ASSETS_URL ?>/images/favicon.ico" sizes="any"> <!-- Fallback für ältere Browser -->
  <link rel="icon" href="<?= APP_ASSETS_URL ?>/images/favicon.svg" type="image/svg+xml"> <!-- Modernes SVG -->
  <link rel="apple-touch-icon" href="<?= APP_ASSETS_URL ?>/images/apple-touch-icon.png"> <!-- iOS -->
  <meta name="theme-color" content="#2f2f2f"> <!-- Browser-Tab-Farbe z.B. auf Android -->  

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

 <!-- JsonSQL CSS -->  
 <link href="../includes/css/styles.css?v=<?= date('YmdHis') ?>" rel="stylesheet">


   <!-- JsonSQL Icon  -->  
   <link href="../assets/Icons/JsonSQL/style.css" rel="stylesheet">  



<!-- Prism Theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-coy.css">

<!-- Zeilennummern CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/plugins/line-numbers/prism-line-numbers.min.css">

<!-- Prism Core -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js"></script>

<!-- ✅ Wichtige Abhängigkeit zuerst -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-markup-templating.min.js"></script>

<!-- ✅ Dann erst php -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>

<!-- Weitere Sprachen (z. B. JSON, SQL) -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-json.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-sql.min.js"></script>

<!-- ✅ Toolbar-Plugin zuerst laden -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>

<!-- Copy-Button Plugin -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
<!-- ✅ Toolbar CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/plugins/toolbar/prism-toolbar.min.css">


<!-- Zeilennummern Plugin -->
<script src="https://cdn.jsdelivr.net/npm/prismjs/plugins/line-numbers/prism-line-numbers.min.js"></script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>




<?php if (!empty($additionalCss)): ?>
<!-- Additional CSS -->  
  <?php foreach ($additionalCss as $css): ?>
<link rel="stylesheet" href="<?= $css ?>">
  <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($additionalJs)): ?>
<!-- Additional JS -->   
  <?php foreach ($additionalJs as $js): ?>
<script src="<?= $js ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>  

<?php if (!empty($themeOptions['css'])): ?>
  <style>
    <?= renderCssFromArray($themeOptions['css']) ?>
  </style>
<?php endif; ?>


</head>



<body class="bg-light d-flex flex-column min-vh-100">

   


  <header class="py-3 shadow-sm">
  <div class="head-wrapper">  
  <div class="container align-items-center justify-content-between flex-wrap">    


  <div class="text-center mb-4 headlogo">
    <img src="<?= $themeOptions['logo_src'] ?? (APP_ASSETS_URL . '/images/JsonSQL-Logo.svg') ?>"
        alt="JsonSQL Logo"
        class="_logo-style">
  </div>  
    
  <h1 class="mb-4 text-center demoshead"><?= htmlspecialchars($title) ?></h1>
    
  <div class="text-center d-flex justify-content-center gap-3">
    <?php if (!$removeOverview): ?>
      <a href="<?= $baseUrl ?>/index.php" class="backContent">
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
    


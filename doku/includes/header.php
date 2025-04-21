<?php

$__start = microtime(true);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/images/JsonSQL-Logo.svg" type="image/svg">    
    <title><?= $pageTitle ?></title>
    <meta name="description" content="JsonSQL ist eine schlanke PHP-Bibliothek für SQL-ähnliche Abfragen auf JSON-Dateien. Perfekt für Web-Apps, Admin-Tools und datenbankfreie Prototypen.">
    <meta name="keywords" content="JsonSQL, SQL für JSON, PHP JSON Datenbank, JSON Abfragen, Datenbank ohne SQL, PHP JSON Framework">
    <meta name="author" content="Johannes Teitge">

    <!-- Canonical URL für Suchmaschinen -->
    <link rel="canonical" href="https://teitge.de/jsonsql/doku/">    

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://teitge.de/jsonsql/doku/"> 
    <meta property="og:title" content="JsonSQL – SQL für JSON-Dateien">
    <meta property="og:description" content="JsonSQL ist eine schlanke PHP-Library für SQL-ähnliche Abfragen direkt auf JSON-Dateien – ideal für kleine Web-Apps & Tools.">
    <meta property="og:image" content="https://teitge.de/JsonSQL/doku/assets/images/demos-banner.webp"> 

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="JsonSQL – SQL für JSON-Dateien in PHP">
    <meta name="twitter:description" content="JsonSQL: Einfach. Flexibel. Schnell. Nutze JSON-Dateien wie Datenbanken – mit SQL-ähnlicher Syntax.">
    <meta name="twitter:image" content="https://teitge.de/jsonsql/doku/assets/images/demos-banner.webp">
   <!-- <meta name="twitter:site" content="@dein_twittername"> --> <!-- Optional -->    

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon.ico" sizes="any"> <!-- Fallback für ältere Browser -->
  <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml"> <!-- Modernes SVG -->
  <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png"> <!-- iOS -->
  <meta name="theme-color" content="#2f2f2f"> <!-- Browser-Tab-Farbe z.B. auf Android -->





   <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>"> <!-- Style für Dokumentation -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Highlight.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/github.min.css">
  <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>


<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "JsonSQL",
  "operatingSystem": "Web, PHP",
  "applicationCategory": "DeveloperApplication",
  "description": "JsonSQL ist eine PHP-Bibliothek zur Arbeit mit JSON-Dateien im SQL-Stil. Ideal für kleine bis mittelgroße Webprojekte ohne klassische Datenbank.",
  "url": "https://teitge.de/jsonsql/doku/",
  "image": "https://teitge.de/jsonsql/doku/assets/images/demos-banner.webp",
  "author": {
    "@type": "Person",
    "name": "Johannes Teitge",
    "url": "https://teitge.de"
  }
}
</script>




  <script>
    document.addEventListener('DOMContentLoaded', function () {
      hljs.highlightAll();
      hljs.initLineNumbersOnLoad(); // <- Zeilennummern aktivieren
    });
    </script>


</head>
<body>
  <!--
    <header class="doku-head text-white text-center p-3 ">
        <div class="headlogo" id="home">
            <img src="assets/images/JsonSQL-Logo.svg" alt="JsonSQL Logo" style="max-height: 80px;">
        </div>            
    </header>
  -->

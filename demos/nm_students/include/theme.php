<?php

$baseUrl = dirname($_SERVER['PHP_SELF']);
$basedir =  dirname($_SERVER['SCRIPT_NAME']) . '/../assets';
$title = "Klassen ↔ Schüler (n:m mit Update)";
$pageDescription = "Diese JsonSQL-Demo zeigt, wie man eine n:m-Beziehung zwischen Klassen und Schülern verwaltet – inklusive Zuordnung, Bearbeitung und Anzeige aller Belegungen. Ideal für Schul- oder Kursverwaltungen.";
$pageImage = 'https://www.teitge.de/JsonSQL/demos/nm_students/assets/images/BannerFacebook.jpg';
$extra_css = []; // falls du z. B. Tagify oder Select2 später brauchst
$extra_js  = [];

$themeOptions = [
    'css' => [
      'header' => [
        'background' => "url('../nm_students/assets/images/header.webp') no-repeat center center",
        'background-size' => 'cover',
        'height' => '340px',
        'color' => 'white'
      ],
      '.head-wrapper' => [
        'background-color' => 'rgba(0, 0, 0, 0.54)',
        'padding' => '10px 0'
      ],
      'h1' => [
        'color' => 'var(--white-color)'
      ],
      '.numbers' => [
        'font-size' => '2.8rem',
        'font-weight' => 'bold'
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



  
?>
<style>
.tick {
  font-size:1rem; white-space:nowrap; font-family:arial,sans-serif;
}

.tick-flip,.tick-text-inline {
  font-size:3.0em;
}

.tick-label {
  margin-top:1em;font-size:1em;
}

.tick-char {
  width:1.5em;
}

.tick-text-inline {
  display:inline-block;text-align:center;min-width:1em;
}

.tick-text-inline+.tick-text-inline {
  margin-left:-.325em;
}

.tick-group {
  margin:0 .5em;text-align:center;
}

body {
   background-color: rgb(255, 255, 255) !important; 
}

.tick-text-inline {
   color: rgb(90, 93, 99) !important; 
}

.tick-label {
   color: rgb(90, 93, 99) !important; 
}

.tick-flip-panel {
   color: rgb(255, 255, 255) !important; 
}

.tick-flip {
   font-family: !important; 
}

.tick-flip-panel-text-wrapper {
   line-height: 1.45 !important; 
}

.tick-flip-panel {
   background-color: rgb(59, 61, 59) !important; 
}

.tick-flip {
   border-radius:0.12em !important; 
}
</style>

<?php

$baseUrl = dirname($_SERVER['PHP_SELF']);
$basedir =  dirname($_SERVER['SCRIPT_NAME']) . '/../assets';
$title = "Klassen ↔ Schüler (n:m mit Update)";
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

<?php
define('APP_ASSETS', __DIR__ . '/../assets');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>JsonSQL Logo Animation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      background-color: #f8f9fa;
      text-align: center;
      padding: 4rem;
      font-family: sans-serif;
    }

    .headlogo {
      display: inline-block;
      max-width: 400px;
    }

    .headlogo svg {
      width: 100%;
      height: auto;
      transform: rotate(-90deg) scale(0.8);
      opacity: 0;
      animation: rotatein 1.2s ease-out forwards;
    }

    .headlogo path {
      stroke: #0076cd;
      stroke-width: 2;
      fill: none;
      stroke-dasharray: 1000;
      stroke-dashoffset: 1000;
      animation: draw 2s ease forwards 1.2s; /* startet nach Drehung */
    }

    @keyframes rotatein {
      to {
        transform: rotate(0deg) scale(1);
        opacity: 1;
      }
    }

    @keyframes draw {
      to {
        stroke-dashoffset: 0;
      }
    }

    h1 {
      margin-top: 2rem;
      color: #444;
    }
  </style>
</head>
<body>

  <div class="headlogo">
    <?php include(APP_ASSETS . '/images/JsonSQL-Logo.svg'); ?>
  </div>

  <h1>JsonSQL Logo Animation</h1>

</body>
</html>

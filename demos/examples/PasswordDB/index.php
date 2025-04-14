<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>🔐 Passwortdatenbank</title>
  <link rel="icon" type="image/webp" href="assets/images/logo.webp" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <style>
    :root {
      --main-color: #04304F;
      --accent-color: #FFA800;
      --accent-hover: #e69500;
      --text-color: #444;
      --background-color: #f8f9fa;
    }

    body {
      padding-bottom: 5rem;
      background-color: var(--background-color);
      color: var(--text-color);
    }

    .navbar.bg-primary {
      background-color: var(--main-color) !important;
    }

    .navbar-brand {
      font-weight: bold;
    }

    .entry-card {
      transition: all 0.2s;
    }

    .entry-card:hover {
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }

    .btn-success {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
    }

    .btn-success:hover {
      background-color: var(--accent-hover);
      border-color: var(--accent-hover);
    }

    .navbar {
      background: linear-gradient(to bottom, rgb(224, 231, 238), rgb(134, 154, 183));
    }

    .navbar, .navbar a, .navbar span {
      color: var(--main-color) !important;
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="assets/images/logo.webp" alt="Logo" height="80" class="me-2" />
        <i class="bi bi-shield-lock-fill me-2"></i> PasswortDB
      </a>
      <span class="small">Demo powered by JsonSQL</span>
    </div>
  </nav>

  <!-- Hauptinhalt -->
  <div class="container">
    <div class="row mb-3">
      <div class="col-md-8">
        <input id="search" class="form-control" placeholder="🔎 Suche nach Bezeichnung, Benutzer, Meta…" />
      </div>
      <div class="col-md-4 text-end">
        <button class="btn btn-success">➕ Neuer Eintrag</button>
      </div>
    </div>

    <div id="passwordList" class="row gy-3">
      <!-- Einträge werden hier per JS gerendert -->
    </div>

    <div class="text-center text-muted mt-5">
      <hr />
      <small>&copy; 2025 – JsonSQL Passwort-Demo</small>
    </div>
  </div>

  <!-- WICHTIG: JS am Ende -->
  <script src="assets/js/app.js"></script>
</body>
</html>

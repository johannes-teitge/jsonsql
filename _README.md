# 🗂️ JsonSQL

**JsonSQL** ist eine moderne PHP-Bibliothek für SQL-ähnliche Datenbankabfragen auf **JSON-Dateien**.  
Sie bietet viele Funktionen klassischer relationaler Datenbanken – jedoch **komplett dateibasiert**, ohne MySQL, SQLite oder externe Server.

Ideal für:
- 🧩 kleine bis mittelgroße Webanwendungen
- ⚒️ Tools, Prototypen, Backend-Mockups
- 💻 Desktop- und Offline-Apps
- 📱 portable, serverlose Projekte

---

## 🚀 Features

- 🔍 **SQL-ähnliche Abfragen:** `select`, `where`, `orderBy`, `limit`, `groupBy`, `join`, ...
- 📁 **JSON als Datenquelle:** arbeitet direkt mit `.json`-Dateien
- 🔄 **Transaktionen:** `transact()` + `commit()` für verzögertes Schreiben
- 🏷️ **Systemfelder:** automatische Felder wie `autoincrement`, `autohash`, `autouuid`, Timestamps
- 🔐 **Verschlüsselung:** bidirektionale Verschlüsselung definierter Felder
- 🧮 **Statistiken & Aggregatfunktionen:** `sum`, `avg`, `count`, `median`, `mode`, `stddev`, `range`, `variance`, ...
- 🔧 **Systemkonfiguration:** zentrale `system.json` pro Tabelle zur Steuerung von Feldtypen, Regeln & Verhalten
- 🧪 **Demo-Projekte:** inkl. Beispielanwendungen, Passwortmanager, Produktlisten, Statistiktools
- 🐞 **Debugging:** Unterstützung für [FancyDumpVar](https://github.com/johannes-teitge/fancydumpvar)

---

## 📦 Installation

Per Composer installieren:
```bash
composer require johannes-teitge/jsonsql
```

Oder manuell einbinden:
```php
require_once 'src/JsonSQL/JsonSQL.php';
```

---

## ⚡ Schnellstart

```php
use JsonSQL\JsonSQL;

$db = new JsonSQL([
    'path' => __DIR__ . '/testdb', // JSON-Dateipfad
    'table' => 'users',
]);

$results = $db->select(['id', 'name', 'age'])
              ->where('age', '>', 30)
              ->orderBy('name')
              ->get();
```

---

## 📁 Projektstruktur

```text
JsonSQL/
│
├─ composer.json            ← Composer-Definition
├─ README.md                ← Diese Beschreibung
├─ LICENSE                  ← Lizenz (z. B. MIT)
├─ .gitignore               ← Ignorierte Dateien (z. B. Backups, Demos)
├─ .gitattributes           ← Für GitHub/Packagist-Handling
│
├─ src/                     ← Hauptcode der JsonSQL-Bibliothek
│   └─ JsonSQL/
│       ├─ JsonSQL.php
│       └─ ...              ← modularisierte Klassen & Traits
│
├─ api/
│   └─ JsonSQL-API.php      ← REST-API Wrapper (optional)
│
├─ testdb/                  ← Beispiel-Datenbanken (JSON-Dateien)
│
├─ examples/                ← Anwendungsbeispiele & Demos
│   ├─ demos/               ← Voll funktionsfähige GUI-Demos
│   │   ├─ assets/
│   │   │   ├─ fonts/
│   │   │   └─ images/
│   │   ├─ includes/
│   │   │   ├─ tools/
│   │   │   │   └─ fdv/     ← FancyDumpVar Tools
│   │   │   ├─ footer.php
│   │   │   └─ header.php
│   │   └─ ...
│   └─ PasswordDB/          ← Beispiel: Passwortverwaltung
│
├─ doku/                    ← Eigene Dokumentationsseite
│   ├─ index.php            ← Startseite der lokalen Doku
│   ├─ assets/
│   │   ├─ css/
│   │   └─ images/
│   ├─ includes/
│   │   ├─ footer.php
│   │   └─ header.php
│   └─ sections/            ← Einzelne Doku-Abschnitte als PHP-Dateien
│
├─ tools/                   ← Entwicklungs- & Hilfswerkzeuge
│
└─ admin-panel/             ← (Optional) Adminoberfläche zur Verwaltung
```

---

## 🔐 Automatische Systemfelder

| Typ               | Beschreibung                              |
|------------------|-------------------------------------------|
| `autoincrement`  | Zählt bei jedem `insert()` hoch           |
| `autouuid`       | Generiert eine UUID                       |
| `autohash`       | Erzeugt automatisch einen Hash (z. B. md5)|
| `timestamp:create` | Erstellt Zeitstempel beim Erstellen     |
| `timestamp:update` | Aktualisiert Zeitstempel bei Updates    |
| `encrypt/decrypt` | Felder werden verschlüsselt gespeichert  |

---

## 🧪 Demo-Anwendungen

Beispiele unter `examples/demos/`:

- 🔐 **Passwortmanager** mit Verschlüsselung und Nutzerverwaltung
- 🚗 **Autoliste** mit Filtern, Hash, UUID & verschachtelten Feldern
- 📊 **Statistikdemo** mit Aggregatfunktionen und Chart.js
- 🧾 **Produktverwaltung** (CSV-ähnlich) mit Bildern, Templates & Mini-GUI
- 🎵 **CD-Datenbank** mit Genres, n:m-Verknüpfung und Tagify

---

## 📌 Roadmap / ToDo

- [x] `autoincrement`, `autouuid`, `autohash`, `timestamp`
- [x] `encrypt` / `decrypt`
- [x] `groupBy`, `join`, `having`, `limit`, `offset`
- [x] `transact()` und `commit()`
- [ ] Admin-UI zum Bearbeiten von Tabellen & Feldern
- [ ] Backup & Restore Tools
- [ ] Mehrsprachige Demo-GUIs (DE/EN)
- [ ] Export als SQL / CSV / XML
- [ ] Visuelles Statistik-Panel

---

## 🧠 Lizenz

MIT License – frei für private & kommerzielle Nutzung.  
Verwendung auf eigene Verantwortung.

---

## 🤝 Mitmachen

Du möchtest JsonSQL in dein Projekt integrieren, Ideen einbringen oder Bugs melden?  
👉 Pull Requests, Issues und neue Demos sind jederzeit willkommen!

---

**© 2024–2025 Johannes Teitge**  
🔗 [https://teitge.de/jsonsql](https://teitge.de/JsonSQL)  
🔧 [https://github.com/johannes-teitge](https://github.com/johannes-teitge)

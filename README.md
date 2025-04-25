# 🗂️ JsonSQL

**JsonSQL** ist eine moderne PHP-Bibliothek für SQL-ähnliche Abfragen auf **JSON-Dateien**.  
Sie funktioniert **komplett dateibasiert** – ohne MySQL, SQLite oder Datenbankserver.

[![Latest Stable Version](https://img.shields.io/packagist/v/jsonsql/jsonsql.svg)](https://packagist.org/packages/jsonsql/jsonsql)
[![License](https://img.shields.io/packagist/l/jsonsql/jsonsql.svg)](https://packagist.org/packages/jsonsql/jsonsql)

---

## ✅ Vorteile

- Kein Datenbankserver notwendig
- Läuft überall, auch in Shared-Hosting & Offline-Apps
- Vollständig in PHP geschrieben
- SQL-ähnliche Syntax (`select`, `where`, `groupBy`, `join`, …)
- Transaktionen, Verschlüsselung, Statistikfunktionen
- Erweiterbar & verständlich – ideal für Prototypen, Tools & Adminpanels

---

## 🚀 Installation

Mit Composer installieren:

```bash
composer require teitge/jsonsql
```

Oder manuell einbinden:

```php
require_once 'src/JsonSQL/JsonSQL.php';
```

---

## ⚡ Beispiel

```php
use JsonSQL\JsonSQL;

$db = new JsonSQL([
    'path' => __DIR__ . '/data',
    'table' => 'users',
]);

$users = $db->select(['id', 'name'])
            ->where('age', '>=', 18)
            ->orderBy('name')
            ->get();
```

---

## 🧰 Features

| Kategorie             | Details                                                                 |
|-----------------------|-------------------------------------------------------------------------|
| **Datenquelle**        | JSON-Dateien je Tabelle                                                |
| **Abfragen**           | `select`, `where`, `orderBy`, `groupBy`, `join`, `limit`, `offset`     |
| **Systemlogik**        | `autoincrement`, `autouuid`, `autohash`, `timestamps`, Validierung     |
| **Verschlüsselung**    | Felder können automatisch ver- und entschlüsselt werden (`encrypt`)    |
| **Statistik**          | `sum`, `avg`, `count`, `median`, `mode`, `stddev`, `variance`, …       |
| **Transaktionen**      | `transact()`, `commit()` – sicher & verzögert schreiben                |
| **Import/Export**      | CSV & MySQL (CREATE/INSERT) aus `.system.json` generieren              |
| **Modularer Code**     | PSR-4, eigene Traits & Klassen je Bereich                              |

---

## 📁 Struktur

```
src/
├── JsonSQL.php          // Hauptklasse
├── JS_Base.php          // Gemeinsame Methoden
├── JS_Select.php        // SELECT-Logik
├── JS_Insert.php        // INSERT-Logik
├── JS_System.php        // Automatische Felder, Validierung, Timestamps, ...
└── ...
```

---

## 🔎 Systemfelder (system.json)

| Typ                | Bedeutung                                 |
|--------------------|-------------------------------------------|
| `autoincrement`    | Zählt IDs automatisch hoch                |
| `autouuid`         | Generiert UUIDs bei jedem Insert         |
| `autohash`         | Erzeugt Hash (z. B. md5, sha256)          |
| `timestamp:create` | Zeitstempel bei Erstellung                |
| `timestamp:update` | Zeitstempel bei Änderung                  |
| `encrypt` / `decrypt` | Feldinhalt verschlüsseln / entschlüsseln |

---

## 🧪 Demos

👉 Vollständige Demos findest du unter `/examples/demos`:

- 🔐 Passwortmanager
- 🚗 Fahrzeugdatenbank mit n:m-Kategorien
- 📦 Produktverwaltung mit Bildern & CSV-Export
- 📊 Statistiken & Charts
- 🧾 MiniShop mit JSON-Daten und Bestellung

---

## 📌 Roadmap

- [x] Systemfelder & Validierung
- [x] MySQL- & CSV-Export aus JSON
- [x] Transaktionen
- [x] Aggregatfunktionen
- [ ] Admin-UI zur Datenbearbeitung
- [ ] JsonSQL Plugin-API
- [ ] Dokumentationsgenerator aus system.json
- [ ] Visual Query Builder

---

## 🔐 Lizenz

MIT – kostenlos & offen für private oder kommerzielle Nutzung.

---

## 🤝 Mitwirken

Du hast Ideen, willst mithelfen oder Fehler melden?  
→ Issues & Pull Requests sind willkommen!

---

**© 2024–2025 JsonSQL Team**  
🔗 Projektseite: [https://teitge.de](https://www.teitge.de/JsonSQL/doku/)  
🔧 GitHub: [https://github.com/johannes-teitge/JsonSQL](https://github.com/johannes-teitge/JsonSQL)

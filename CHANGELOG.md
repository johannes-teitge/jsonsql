# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden in diesem Dokument festgehalten.

Dieses Changelog folgt den Richtlinien von [Keep a Changelog](https://keepachangelog.com/de/1.0.0/)
und verwendet [Semantische Versionierung](https://semver.org/lang/de/).

---

## [1.0.3] – 2025-04-18
### Hinzugefügt
- Neue Demo: `demo_required.php` zur Validierung von Pflichtfeldern
  - Zeigt Fehlermeldung bei fehlendem Pflichtfeld
  - Erfolgreicher Insert nach Fehlerbehandlung
- Sicherheits-Checkliste vorbereitet für sicheres Setup mit Login & Zugriffskontrolle
- Neue Demo „MiniShop“ gestartet
  - Kategorien, Produkte, n:m-Verknüpfung
  - Warenkorb-Logik (Basisversion)
- Neue Funktion `analyzeTable()` in Modul `JS_Tables` integriert
- Vorbereitung für `tableRepair()` zur automatischen Reparatur basierend auf Systemdefinition
- Einführung `JS_META`-Trait zur Analyse von `@change`-Blöcken für Changelog-Generierung
- Neue Übersicht `overview.php` für strukturierte Demosammlung erstellt

### Geändert
- Demos in `index.php` modularisiert – Auslagerung in `overview.php`
- Demoübersicht überarbeitet: Cards, Suchfunktion, Themenfilter (vorerst deaktiviert)
- Demo-Startseiten nach Themen (AutoFields, Security, Performance etc.) gruppiert

---

## [1.0.2] – 2025-04-17
### Hinzugefügt
- Erste Dokumentation der `insert()`-Funktion mit Beschreibung interner Mechanismen:
  - `applyAutoFields`, automatische Felder
  - Validierung, Verschlüsselung, Hashing
- Einheitliches Format für Methodensignaturen in der Doku:
  - `<ul>`-Blöcke mit `Trait`, Rückgabetyp und Parametern
- Neuer Menüpunkt „Datenfelder“ in der Dokumentation mit Beschreibung aller verfügbaren Feldoptionen

### Geändert
- Version auf `1.0.2` aktualisiert
- Dokumentation strukturell überarbeitet

---

## [1.0.1] – 2025-04-10
### Hinzugefügt
- Admin-Oberfläche mit Bootstrap-Interface und Navigation
- Erste Demos zu:
  - AutoFields
  - CD-Verwaltung mit Genres (n:m-Beziehung)
  - Passwortverwaltung mit verschlüsselten Feldern
  - Statistikmodul inkl. Aggregaten (AVG, STDDEV, etc.)

### Geändert
- `system.json` wird nun tabellenweise verarbeitet
- Verschlüsselung und `autoHash` integriert in `insert()` und `update()`

---

## [1.0.0] – 2025-04-01
### Initial
- Projektstart mit `JsonSQL`-Kernklasse
- Unterstützte SQL-ähnliche Funktionen:
  - `select()`, `insert()`, `update()`, `delete()`, `where()`, `join()`, `groupBy()`, `orderBy()`, `limit()`, `pluck()`, `exists()`, `paginate()`
- Filelocking und Multiuser-Handling
- Erste Testdatenbanken und strukturierte Projektmappe

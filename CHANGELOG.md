# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden in diesem Dokument festgehalten.

Dieses Changelog folgt den Richtlinien von [Keep a Changelog](https://keepachangelog.com/de/1.0.0/)
und verwendet [Semantische Versionierung](https://semver.org/lang/de/).

---

## [1.0.6] – 2025-04-21
### Hinzugefügt
- **MySQL-Exportfunktionen erweitert:**
  - Neue Methode `ExportMySQLCreate()` zur Generierung von SQL-Tabellen aus `.system.json`
  - In `view_mysql.php` (N:M-Demo) werden nun alle Tabellen dynamisch angezeigt inkl.:
    - Dateipfad
    - Verlinkter Systemdefinition
    - Exportbutton für einzelne Tabellen (nur mit Systemdefinition)
    - Gesamtexport aller `.system.json`-basierten Tabellen möglich
- **Export-Sicherheit verbessert:**
  - Nur Tabellen mit gültiger `.system.json` können als SQL (INSERT) exportiert werden  
    → Sicherstellung korrekter Typen, Validierungen und Autowerte

### Geändert
- `listTables()`-Methode erhält neue Option zur Ausblendung von `.system.json`-Dateien
- `view_mysql.php` modernisiert:
  - Übersichtliche Darstellung mit Tabellennamen, Verlinkungen und Aktionen
  - Neue Exportbuttons eingebaut (je Tabelle & global)

### Sonstiges
- Dokumentation um Abschnitt *MySQL-Export* ergänzt (inkl. Anwendungsbeispiele und Einschränkungen)

---

## [1.0.5] – 2025-04-20
### Hinzugefügt
- Demo `nm_students` fertiggestellt:
  - Kurs-, Dozenten-, Klassen-, Studenten- und Belegungsverwaltung
  - Ansicht `view_overview.php` mit animierten Flip-Zählern (via @pqina/flip)
  - Kursansicht `view_courses.php` mit Dozenten-Link und Teilnehmerzählung
- Umfangreiche Dokumentation zur `where()`-Methode ergänzt:
  - Unterstützte Operatoren (`=`, `!=`, `like`, `in`, `not`, etc.)
  - Negierte Bedingungen mit `['not', [...]]`
  - Kombinierte Bedingungen mit `AND` oder `OR`
  - Hinweise zur Erweiterbarkeit und Nutzung mit `append = true`

### Geändert
- `JsonSQL::setTable()` setzt `autoload = true` nun standardmäßig  
  - reduziert Fehlerquellen durch vergessene `true`-Angabe beim Setzen der Tabelle
- Flip-Integration vereinheitlicht, nicht mehr mit `FlipCounter`, sondern mit `@pqina/flip` über `data-did-init`
- Datenanzeige und Navigation über Tabs modernisiert

### Sonstiges
- Dokumentation und Changelog erweitert


---


## [1.0.4] – 2025-04-19
### Hinzugefügt
- Unique-Validierung für `insert()` mit neuer Methode `recordExistsByUniqueFields()`
- Neuer Mechanismus zur **Duplikatprüfung bei Insert**
  - `$this->skippedInserts` speichert übersprungene Datensätze
  - Methoden: `getSkippedInserts()`, `getSkippedInsertCount()`, `clearSkippedInserts()`
- Unterstützung für Masseninserts mit nur einmaligem Ladevorgang der Tabelle
- Helper-Funktion `dump()` für strukturierte Debug-Ausgabe
- Erweiterung `create_classes()` zur Verwendung von Masseninsert und Reporting
- Fallback-Logik in `setTable()` für komplett leere JSON-Dateien (0 Byte)

### Geändert
- `insert()` aktualisiert: lädt Daten nur einmal, prüft Duplikate gegen geplante Inserts
- Unique-Feldprüfung erfolgt nun speichereffizient und korrekt ohne erneutes Laden
- Dokumentation der Methode `recordExistsByUniqueFields()` mit vollständigem DocBlock

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

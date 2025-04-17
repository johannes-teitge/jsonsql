# Changelog

Alle nennenswerten Änderungen an diesem Projekt werden in diesem Dokument festgehalten.

Dieses Changelog folgt den Richtlinien von [Keep a Changelog](https://keepachangelog.com/de/1.0.0/)
und verwendet [Semantische Versionierung](https://semver.org/lang/de/).

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


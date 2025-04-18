<?php
return [

    [
        'icon' => '🚀',
        'title' => 'Erste Schritte',
        'keywords' => 'insert, select',    
        'file' => 'hello-json-sql.php',
        'description' => 'Minimalbeispiel zum schnellen Einstieg in JsonSQL.',
        'alt' => 'Systemdefinition prüfen mit JsonSQL',
        'buttonClass' => 'btn-outline-secondary',
        'tags' => 'system, validation'
      ],

      [
        'icon' => '🧾',
        'title' => 'Datenbank-Tools',
        'file' => 'demo-db-tools.php',
        'description' => 'Testtabellen anlegen, Tabellen löschen und Infos zur Datenbank anzeigen.',
        'alt' => 'Datenbank-Tools mit JsonSQL',
        'buttonClass' => 'btn-outline-primary',
        'tags' => 'tools, datenbank',
        'keywords' => 'db-tools, datenbank löschen, tabellen, info, hilfe'
      ],

      [
        'icon' => '📋',
        'title' => 'Simple Select',
        'file' => 'simple-test-01.php',
        'description' => 'Einfaches Einfügen und Lesen von Artikeln mit Sortierung.',
        'alt' => 'Einfacher Select-Test mit JsonSQL',
        'buttonClass' => 'btn-outline-primary',
        'tags' => 'select, einfügen, lesen',
        'keywords' => 'insert, lesen, sortieren, einfache demo, simple, artikel'
      ],

      [
        'icon' => '🔑',
        'title' => 'Passwort-Demo',
        'file' => 'demo_encrypt.php',
        'description' => 'Feldverschlüsselung mit system.json.',
        'alt' => 'Passwort-Verschlüsselung mit JsonSQL',
        'buttonClass' => 'btn-outline-success',
        'tags' => 'security, verschlüsselung, password',
        'keywords' => 'encrypt, verschlüsseln, passwort, sicherheit, daten schützen'
      ],

      [
        'icon' => '🧠',
        'title' => 'Autoincrement',
        'file' => 'demo_autoincrement.php',
        'description' => 'Automatisch generierte IDs wie in MySQL.',
        'alt' => 'Autoincrement-Funktion mit JsonSQL',
        'buttonClass' => 'btn-outline-warning',
        'tags' => 'autofields, id, increment',
        'keywords' => 'autoincrement, id generieren, auto id, datenbank ids, auto fields'
      ],
      
      [
        'icon' => '🌀',
        'title' => 'AutoHash',
        'file' => 'demo_autohash.php',
        'description' => 'Automatisch generierte Hashwerte beim Einfügen.',
        'alt' => 'AutoHash Funktion in JsonSQL',
        'buttonClass' => 'btn-outline-dark',
        'tags' => 'autofields, hash, sicherheit',
        'keywords' => 'autohash, md5, sha256, hashwert erzeugen, auto, sicherheit'
      ],
                  
      [
        'icon' => '👥',
        'title' => 'Benutzer erzeugen',
        'file' => 'demo_generate_users.php',
        'description' => 'Erzeuge 500 Fake-Benutzer mit AutoFields und Verschlüsselung.',
        'alt' => 'Benutzer generieren mit JsonSQL',
        'buttonClass' => 'btn-outline-secondary',
        'tags' => 'faker, autofields, verschlüsselung',
        'keywords' => 'benutzer erstellen, user generator, testdaten, fake daten, verschlüsselt, autofelder'
      ],
      
      [
        'icon' => '📤',
        'title' => 'Tabelle exportieren',
        'file' => 'demo_export_table.php',
        'description' => 'Exportiere eine JsonSQL-Tabelle inklusive Systemdaten als JSON-Datei.',
        'alt' => 'JsonSQL-Tabelle exportieren',
        'buttonClass' => 'btn-outline-secondary',
        'tags' => 'tools, export, json',
        'keywords' => 'tabelle exportieren, json datei, export tool, datenbank sichern, systemdaten'
      ],
      
      [
        'icon' => '🌲',
        'title' => 'Kategorien & Artikel',
        'file' => 'simple-test-02.php',
        'description' => 'Kategoriebasierte Artikelanzeige mit TreeView und Produktzähler.',
        'alt' => 'Kategorien und Artikel mit TreeView in JsonSQL',
        'buttonClass' => 'btn-outline-secondary',
        'tags' => 'kategorien, treeview, artikel',
        'keywords' => 'kategoriebaum, unterkategorien, artikelstruktur, produktkatalog, strukturierte anzeige'
      ],
      
      [
        'icon' => '🌐',
        'title' => 'API-Demo',
        'file' => 'api-demo.php',
        'description' => 'JsonSQL über eine REST-API per GET, POST, PUT und DELETE ansprechen.',
        'alt' => 'REST-API Nutzung mit JsonSQL',
        'buttonClass' => 'btn-outline-info',
        'tags' => 'api, rest, datenzugriff',
        'keywords' => 'rest api, jsonsql schnittstelle, post, put, get, delete, daten über api bearbeiten'
      ],
      
      [
        'icon' => '🎯',
        'title' => 'WHERE IN',
        'file' => 'demo_where_in.php',
        'description' => 'Filtert Einträge basierend auf mehreren IDs (z. B. ?ids=1,2,3).',
        'alt' => 'WHERE IN Filter in JsonSQL',
        'buttonClass' => 'btn-outline-info',
        'tags' => 'filter, query, where',
        'keywords' => 'where in, filter mehrere ids, datensatz filtern, id liste, abfrage'
      ],
      
      [
        'icon' => '🔗',
        'title' => 'JsonSQL Join Demo: Kunden und Bestellungen',
        'file' => 'demo_simple_join.php',
        'description' => 'Veranschaulicht, einfache <strong>Joins</strong> und <strong>Gruppierungen.</strong>',
        'alt' => 'Join-Demo mit Kunden und Bestellungen in JsonSQL',
        'buttonClass' => 'btn-outline-info',
        'tags' => 'joins, gruppierung, kunden, bestellungen',
        'keywords' => 'join, group by, kunden, bestellungen, relationen, datenzusammenführung'
      ],
      
      [
        'icon' => '⚙️',
        'title' => 'JsonSQL SetTable Demo',
        'file' => 'demo_settable.php',
        'description' => 'Zeigt, wie man <strong>Tabellen mit automatisch definierten Feldern</strong> erstellt und bearbeitet.',
        'alt' => 'SetTable mit AutoFields in JsonSQL',
        'buttonClass' => 'btn-outline-info',
        'tags' => 'autofields, tabellen, setup',
        'keywords' => 'tabelle erstellen, autofelder, settable, struktur anlegen, jsonsql tabellen, system.json'
      ],
      
      [
        'icon' => '🌈',
        'title' => 'JsonSQL AutoFields CRUD Demo',
        'file' => 'demo_autofields.php',
        'description' => 'Verwalte Farbverläufe und andere Felder mit CRUD-Operationen und automatischen Feldern wie <strong>Autoincrement</strong>, <strong>AutoCreated</strong>, <strong>AutoUpdated</strong> in einer Tabelle.',
        'image' => 'images/banner_gradients.webp',
        'alt' => 'AutoFields CRUD mit Farbverläufen in JsonSQL',
        'buttonClass' => 'btn-outline-info',
        'tags' => 'autofields, farben, crud',
        'keywords' => 'autofields, farbverlauf, create update delete, autofelder, verlaufsverwaltung, dynamische felder'
      ],
      
      [
        'icon' => '🚀',
        'title' => 'JsonSQL Performance Test',
        'file' => 'demo_performance.php',
        'description' => 'Simuliert viele gleichzeitige Anfragen, um die <strong>Leistung der JsonSQL-Datenbank</strong> zu testen.',
        'alt' => 'Stresstest der JsonSQL Datenbank',
        'buttonClass' => 'btn-outline-primary',
        'buttonText' => 'Test starten',
        'tags' => 'performance, stress, benchmark',
        'keywords' => 'jsonsql, performance, test, benchmark, speed, stresstest'
      ],
      
      [
        'icon' => '📊',
        'title' => 'Aggregat & Filter',
        'file' => 'demo_aggregat.php',
        'description' => 'Demo mit dynamischer Datengenerierung, Filtern & umfangreicher Statistik pro Spalte – inklusive Sterne-Rating.',
        'image' => 'images/banner_default.webp',
        'alt' => 'Statistik- und Filter-Demo mit JsonSQL',
        'buttonClass' => 'btn-outline-success',
        'buttonText' => 'Zur Statistik-Demo',
        'tags' => 'statistics, filters, aggregat',
        'keywords' => 'jsonsql, statistik, aggregat, filter, rating, datenanalyse'
      ],
      
      [
        'icon' => '⚙️',
        'title' => 'Dynamische Systemfeld-Verwaltung',
        'file' => 'demo_system.php',
        'description' => 'Füge, aktualisiere oder entferne Systemfelder für deine Datenbank mit einer benutzerfreundlichen Oberfläche.',
        'image' => 'images/banner_default.webp',
        'alt' => 'Systemfeldverwaltung in JsonSQL',
        'buttonClass' => 'btn-outline-success',
        'buttonText' => 'Demo öffnen',
        'tags' => 'system, fields, ui',
        'keywords' => 'systemfelder, jsonsql, feldverwaltung, editieren, ui, system.json'
      ],

      [
        'icon' => '🔧',
        'title' => 'Erweiterte Systemfeld-Verwaltung',
        'file' => 'demo_system_ext.php',
        'description' => 'Verwalte Systemfelder mit erweiterten Funktionen wie Validierungen und ENUM-Werten. Ideal für komplexe Datenstrukturen.',
        'image' => 'images/banner_default.webp',
        'alt' => 'Erweiterte Verwaltung von Systemfeldern mit JsonSQL',
        'buttonClass' => 'btn-outline-warning',
        'buttonText' => 'Demo öffnen',
        'tags' => 'system, enum, validation',
        'keywords' => 'system.json, validierung, enum, felder, jsonsql, systemverwaltung, datentypen'
      ],

      [
        'icon' => '🚗',
        'title' => 'Car Database Demo',
        'file' => 'demo_cars_db.php',
        'description' => 'Verwalte Systemfelder mit erweiterten Funktionen wie Validierungen und ENUM-Werten. Ideal für komplexe Datenstrukturen.',
        'image' => 'images/CarDB/banner.webp',
        'alt' => 'CarDB Banner – Verwaltung von Fahrzeugdaten',
        'buttonClass' => 'btn-outline-danger',
        'buttonText' => 'Demo öffnen',
        'tags' => 'cars, enum, autofields, system',
        'keywords' => 'auto, fahrzeugdatenbank, jsonsql, enum, validierung, datentyp, systemfelder'
      ],

      [
        'icon' => '🗓️',
        'title' => 'DateTime Demo',
        'file' => 'demo_datetime.php',
        'description' => 'Verwalte DateTime, Date, Time und Timestamp Felder. Ideal für Zeitstempel und Datumsoperationen.',
        'image' => 'images/DateTimeDemo/banner.webp',
        'alt' => 'DateTime-Felder mit JsonSQL verwalten',
        'buttonClass' => 'btn-outline-danger',
        'buttonText' => 'Demo öffnen',
        'tags' => 'datetime, timestamps, system',
        'keywords' => 'zeitstempel, datum, uhrzeit, datetime, jsonsql, timestamps'
      ],

      [
        'icon' => '🧪',
        'title' => 'Required Demo',
        'file' => 'demo_required.php',
        'description' => 'Zeigt, wie <code>required</code> Felder in der <code>system.json</code> geprüft werden. Inklusive Fehlerbehandlung beim Einfügen unvollständiger Datensätze – ideal zum Testen von Pflichtfeld-Validierung.',
        'image' => 'images/RequiredDemo/banner.webp',
        'alt' => 'Fehler beim Einfügen – Pflichtfelder in JsonSQL',
        'buttonClass' => 'btn-outline-danger',
        'buttonText' => 'Demo öffnen',
        'tags' => 'validation, required, fields',
        'keywords' => 'pflichtfeld, validierung, required, jsonsql, validieren, fehlerbehandlung'
      ],

      [
        'icon' => '📊',
        'title' => 'AnalyzeTable Demo',
        'file' => 'demo_analyzeTable.php',
        'description' => 'Prüft alle Datensätze einer Tabelle auf <strong>fehlende Pflichtfelder</strong> und <strong>unerlaubte Zusatzfelder</strong> gemäß der <code>system.json</code>.<br>Ideal zur Qualitätssicherung und Vorbereitung von Reparaturfunktionen wie <code>tableRepair()</code>.',
        'image' => 'images/AnalyzeTableDemo/banner.webp',
        'alt' => 'Analyse von JSON-Tabellen mit JsonSQL',
        'buttonClass' => 'btn-outline-primary',
        'buttonText' => 'Demo öffnen',
        'tags' => 'validation, table, analysis',
        'keywords' => 'analyse, system.json, fehlende felder, zusätzliche felder, validierung, tableRepair'
      ],
            
      [
        'icon' => '🧪',
        'title' => 'AnalyzeSystemTable Demo',
        'file' => 'demo_analyzeSystemTable.php',
        'description' => 'Diese Demo prüft die <code>system.json</code> einer Tabelle auf <strong>ungültige Datentypen</strong> und <strong>nicht erlaubte Feldoptionen</strong>.<br>Hilfreich zur <strong>Fehlersuche</strong> in Systemtabellen oder beim Import externer Definitionen.',
        'image' => 'images/AnalyzeTableDemo/banner_red.webp',
        'alt' => 'Systemdefinition prüfen mit JsonSQL',
        'buttonClass' => 'btn-outline-danger',
        'buttonText' => 'Demo öffnen',
        'tags' => 'system, validation, config',
        'keywords' => 'system.json, felddefinition, validierung, datatype, properties, analyse, debug'
      ],

  // Weitere Demos hier ergänzen...
];

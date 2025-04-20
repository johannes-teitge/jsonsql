<?php
namespace Src\JsonSQL;

/**
 * Trait JS_Tables
 *
 * Methoden zur Verwaltung von Tabellen in JsonSQL:
 * - Anlegen, Prüfen, Löschen und Leeren von Tabellen
 * - Tabelleninformationen abrufen (Größe, Datensätze, system.json)
 * - Rohdaten auslesen für Debugzwecke
 *
 * Wird innerhalb der JsonSQL-Hauptklasse über `use` eingebunden.
 *
 * 🔧 Genutzt von:
 * - setTable()
 * - getTableInfo()
 * - dropTable(), truncate()
 * - listTables(), tableExists()
 *
 * @package Src\JsonSQL
 * @since 1.0.0
 */

trait JS_Tables
{


    public function listTables(): array {
        if (!$this->currentDbPath) {
            throw new \Exception("Keine Datenbank ausgewählt.");
        }

        $files = glob($this->currentDbPath . DIRECTORY_SEPARATOR . '*.json');
        $tables = array_map(function ($file) {
            return basename($file, '.json');
        }, $files);

        return $tables;
    }    

    protected function loadTableData(): void {
        if (!$this->currentTableFile) return;

        $fp = fopen($this->currentTableFile, 'r');
        if (flock($fp, LOCK_SH)) {
            $content = stream_get_contents($fp);
            $this->currentData = $content ? json_decode($content, true) : [];
            flock($fp, LOCK_UN);
            fclose($fp);
            $this->tableLoaded = true;
        } else {
            throw new \Exception("Datei konnte nicht gelesen werden (Lock fehlgeschlagen).");
        }
    }



        // Tabelldetails (Filegröße, Änderungsdatum, Anzahl der Felder und Datensätze) sammeln
//        $this->collectTableInfo($tableFile, $systemFile);
/**
 * Setzt den aktuellen Tabellennamen und lädt optional die Tabelle direkt in den Speicher.
 *
 * @method JsonSQL setTable(string $tableName, bool $autoLoad = true)
 *
 * @param string $tableName   Der Name der Tabelle (entspricht dem Dateinamen ohne .json)
 * @param bool   $autoLoad    Gibt an, ob die Tabelle direkt nach dem Setzen geladen werden soll.
 *                            Standardwert ist seit April 2025 auf `true` gesetzt, um typische
 *                            Anwendungsfehler durch vergessenes Nachladen zu vermeiden.
 *
 * @return self               Gibt die Instanz zurück zur Methodenkettung.
 */
public function setTable(string $tableName, bool $autoLoad = true): self {
    $this->currentTableName = $tableName;
    $this->tableLoaded = false;    

    // system.json prüfen/erstellen
    $systemFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.system.json';
    if (!file_exists($systemFile)) {
        $this->initializeSystemConfig($tableName);
    }

    // Tabelle prüfen/erstellen
    $tableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';
    if (!file_exists($tableFile)) {
        file_put_contents($tableFile, json_encode([], JSON_PRETTY_PRINT));
    } elseif (filesize($tableFile) === 0) {
        // 🧯 Falls leer (0 Byte), sicher initialisieren
        file_put_contents($tableFile, json_encode([], JSON_PRETTY_PRINT));
    }

    $this->currentTableFile = $tableFile;

    if ($autoLoad) {
        $this->loadTableData();
    }

    return $this;
}


    


    public function getTableInfo(): array {

    
        // Wenn noch nicht geladen, berechnen
        if (!$this->currentTableFile) {
            throw new \Exception("Keine Tabelle ausgewählt.");
        } 

        $tableName = $this->currentTableName;  
        
        // Überprüfen, ob die system.json-Datei existiert
        $systemFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.system.json';
        if (!file_exists($systemFile)) {
            // system.json existiert nicht, also erstellen wir die grundlegenden Felder
            $this->initializeSystemConfig($tableName);
        }

        // Überprüfen, ob die Tabelle .json existiert
        $tableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';
        if (!file_exists($tableFile)) {
            // Tabelle existiert nicht, also eine leere Tabelle erstellen
            file_put_contents($tableFile, json_encode([], JSON_PRETTY_PRINT));
        }        

        $this->loadTableData();  // Lade die Tabellendaten, um sie ab sofort bearbeiten zu können
    
        // Tabelldetails sammeln
        $this->collectTableInfo($tableFile,$systemFile);
    
        return $this->tableInfo;
    }    



    // Überprüft, ob eine Tabelle existiert
    public function tableExists(string $tableName): bool {
        $tableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';
        return file_exists($tableFile);
    }

    // Gibt die Anzahl der Datensätze in einer Tabelle zurück
    public function getRecordCount(string $tableName): int {
        $tableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';

        // Sicherstellen, dass die Tabelle existiert
        if (!$this->tableExists($tableName)) {
            return 0; // Falls die Tabelle nicht existiert, gibt es keine Datensätze
        }

        $data = json_decode(file_get_contents($tableFile), true);
        return is_array($data) ? count($data) : 0;
    }


    protected function collectTableInfo(string $tableFile, string $systemFile): void {
        if (file_exists($tableFile)) {
            // Tabelle Infos
            $fileSize = filesize($tableFile);
            $lastModified = date("Y-m-d H:i:s", filemtime($tableFile));
        
            // Sicherstellen, dass die Tabelle korrekt geladen wird
            $tableData = json_decode(file_get_contents($tableFile), true);
            if ($tableData === null) {
                throw new \Exception("Fehler beim Laden der Tabellendaten aus der Datei: $tableFile");
            }
        
            $recordCount = count($tableData);
        
            // system.json Infos
            if (file_exists($systemFile)) {
                $systemData = json_decode(file_get_contents($systemFile), true);
                $fieldsCount = isset($systemData['fields']) ? count($systemData['fields']) : 0;
                $autocreated = isset($systemData['autocreated']) ? $systemData['autocreated'] : 'Nicht definiert';
                $autoupdated = isset($systemData['autoupdated']) ? $systemData['autoupdated'] : 'Nicht definiert';
                $autoincrement = isset($systemData['fields']['id']) && !empty($systemData['fields']['id']['autoincrement']) ? 'id (autoincrement)' : 'Nicht definiert';
                $autohash = isset($systemData['autohash']) ? $systemData['autohash'] : 'Nicht definiert';
                $autouuid = isset($systemData['autouuid']) ? $systemData['autouuid'] : 'Nicht definiert';
                
                // Wenn in system.json die entsprechenden Felder für Verschlüsselung vorhanden sind
                $encryptionKey = isset($systemData['encryption_key']) ? $systemData['encryption_key'] : 'Nicht definiert';
            } else {
                $fieldsCount = 0; // Falls keine system.json existiert
                $autocreated = $autoupdated = $autoincrement = $autohash = $autouuid = 'Nicht definiert';
                $encryptionKey = 'Nicht definiert';
            }
        
            // Speichern der Tabelle Informationen für Debugging/Verwaltung
            $this->tableInfo = [
                'table_name' => basename($tableFile, '.json'), // Tabellennamen extrahieren
                'table_path' => $tableFile,                    // Pfad der Tabelle                
                'file_size' => $fileSize,
                'last_modified' => $lastModified,
                'record_count' => $recordCount,
                'fields_count' => $fieldsCount,
                'real_fields_count' => !empty($tableData) ? count(array_keys($tableData[0])) : 0, // Echte Felder zählen
                'autocreated' => $autocreated,
                'autoupdated' => $autoupdated,
                'autoincrement' => $autoincrement,
                'autohash' => $autohash,
                'autouuid' => $autouuid,
                'system_fields_count' => count([$autocreated, $autoupdated, $autoincrement, $autohash, $autouuid]), // Systemfelder zählen
                'encryption_key' => $encryptionKey,
            ];
        } else {
            throw new \Exception("Tabelle '$tableFile' existiert nicht.");
        }
    }
    

    /**
     * Gibt den rohen JSON-Inhalt der aktuellen Tabelle zurück.
     * Nützlich zur Debugging-Anzeige von verschlüsselten Daten.
     */
    public function getRawTableData(): ?string {
        if (!$this->currentTableFile) {
            return null;
        }

        if (!file_exists($this->currentTableFile)) {
            return null;
        }

        return file_get_contents($this->currentTableFile);
    }
    
   
    
    public function clearTable(string $tableName): void {
        if (!$this->currentDbPath) {
            throw new \Exception("Keine Datenbank ausgewählt.");
        }
    
        $this->currentTableName = $tableName;
        $this->currentTableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';
    
        // Nur leeren, wenn die Tabelle existiert
        if (file_exists($this->currentTableFile)) {
            file_put_contents($this->currentTableFile, json_encode([], JSON_PRETTY_PRINT));
            $this->loadSystemConfig(); // Falls Autoincrement/UUID zurückgesetzt werden sollen
        } else {
            throw new \Exception("Tabelle '$tableName' existiert nicht und kann daher nicht geleert werden.");
        }
    }
    



/**
 * Löscht die system.json-Datei der angegebenen Tabelle.
 *
 * @param string $tableName Der Tabellenname (ohne .json)
 */
public function truncateSystem(string $tableName): void {
    if (!$this->currentDbPath) {
        throw new \Exception("Keine Datenbank ausgewählt.");
    }

    $systemFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.system.json';
    if (file_exists($systemFile)) {
        unlink($systemFile);
    }

    // Falls die aktuelle Tabelle betroffen ist, interne Konfiguration zurücksetzen
    if ($this->currentTableName === $tableName) {
        $this->systemConfig = null;
    }
}



/**
 * Leert eine Tabelle und optional auch die zugehörige system.json.
 *
 * @param string $tableName   Der Tabellenname (ohne .json)
 * @param bool   $resetSystem Wenn true, wird zusätzlich die system.json gelöscht
 *
 * @throws \Exception Wenn keine Datenbank ausgewählt wurde.
 */
public function truncate(string $tableName, bool $resetSystem = false): void {
    if (!$this->currentDbPath) {
        throw new \Exception("Keine Datenbank ausgewählt.");
    }

    $this->currentTableName = $tableName;
    $this->currentTableFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';

    // Tabelle leeren oder neu erstellen
    file_put_contents($this->currentTableFile, json_encode([], JSON_PRETTY_PRINT));

    // Optional: system.json löschen
    if ($resetSystem) {
        $this->truncateSystem($tableName);
    }

    // System (neu) laden
    $this->loadSystemConfig();
}

    
    /**
     * Löscht eine Tabelle und ggf. ihre zugehörige system.json-Konfiguration.
     *
     * @param string $tableName Name der Tabelle (ohne .json)
     * @return array Rückgabeinfos: ['tableDeleted' => bool, 'systemDeleted' => bool]
     */
    public function dropTable(string $tableName): array {
        if (!$this->currentDbPath) {
            throw new \Exception("Keine Datenbank ausgewählt.");
        }

        $tableFile  = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.json';
        $systemFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.system.json';

        $tableDeleted  = file_exists($tableFile)  ? unlink($tableFile)  : false;
        $systemDeleted = file_exists($systemFile) ? unlink($systemFile) : false;

        return [
            'tableDeleted'  => $tableDeleted,
            'systemDeleted' => $systemDeleted,
        ];
    }


    public function renameTable(string $oldName, string $newName): bool {
        $oldJson = $this->currentDbPath . DIRECTORY_SEPARATOR . $oldName . '.json';
        $newJson = $this->currentDbPath . DIRECTORY_SEPARATOR . $newName . '.json';
        $oldSys  = $this->currentDbPath . DIRECTORY_SEPARATOR . $oldName . '.system.json';
        $newSys  = $this->currentDbPath . DIRECTORY_SEPARATOR . $newName . '.system.json';
    
        $renamed = false;
    
        if (file_exists($oldJson)) {
            $renamed = rename($oldJson, $newJson);
        }
    
        if ($renamed && file_exists($oldSys)) {
            rename($oldSys, $newSys);
        }
    
        return $renamed;
    }


// In deiner JsonSQL Klasse (z. B. in JS_TABLES)
public function getCurrentTableFile(): string
{
    if (empty($this->currentTableFile)) {
        throw new \Exception("❌ Keine Tabelle gesetzt.");
    }
    return $this->currentTableFile;
}    
    
// In JsonSQL Klasse
public function saveTable(array $data): bool
{
    if (empty($this->currentTableFile)) {
        throw new \Exception("❌ Keine Tabelle gesetzt.");
    }
    return file_put_contents($this->currentTableFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}


    /**
     * Lädt die aktuellen Daten der gesetzten Tabelle im Rohformat.
     *
     * @trait JS_TABLES
     * @return array Array mit Datensätzen aus der JSON-Datei
     * @throws Exception Wenn keine Tabelle gesetzt ist oder Datei nicht lesbar
     */
    public function loadTable(): array
    {
        if (empty($this->currentTableFile)) {
            throw new \Exception("❌ Keine Tabelle gesetzt. Bitte zuerst setTable() aufrufen.");
        }

        if (!file_exists($this->currentTableFile)) {
            return []; // Leere Tabelle bei nicht vorhandener Datei
        }

        $json = file_get_contents($this->currentTableFile);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \Exception("❌ Fehler beim Parsen der Tabelle: JSON ist ungültig.");
        }

        return $data;
    }




     /**
     * Analyse der aktiven Tabelle auf Abweichungen zur Systemdefinition.
     *
     * Diese Methode überprüft alle Datensätze gegen die aktuell geladene
     * Systemdefinition. Sie erkennt:
     * - fehlende Pflichtfelder (required: true)
     * - zusätzliche Felder, die nicht in der Definition enthalten sind
     *   (abhängig von allowAdditionalFields oder manuell aktivierbar)
     *
     * Rückgabeformat:
     * [
     *   [
     *     'row' => 2,
     *     'missing' => ['email'],
     *     'extra' => ['foo'],
     *     'excerpt' => '{\"firstname\":\"Max\",\"foo\":\"...\"...}'
     *   ],
     *   ...
     * ]
     *
     * @trait JS_TABLES
     * @param bool $checkExtras Zusätzliche Felder anzeigen, auch wenn erlaubt (Standard: false)
     * @return array Liste aller fehlerhaften Datensätze mit Details
     * @throws Exception Wenn keine Tabelle gesetzt ist
     */
    public function analyzeTable(bool $checkExtras = false): array
    {
        if (empty($this->currentTableFile)) {
            throw new \Exception("❌ Keine aktive Tabelle gesetzt. Bitte setTable() zuerst aufrufen.");
        }

        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        $definedFields = $this->systemConfig['fields'] ?? [];
        $allowAdditional = $this->systemConfig['allowAdditionalFields'] ?? false;

        $requiredFields = [];
        foreach ($definedFields as $key => $meta) {
            if (!empty($meta['required'])) {
                $requiredFields[] = $key;
            }
        }

        $data = $this->loadTable(); // Achtung: Diese Methode muss currentTable nutzen!

        $result = [];
        foreach ($data as $index => $row) {
            $missing = [];
            $extra = [];

            // Fehlende Pflichtfelder
            foreach ($requiredFields as $field) {
                if (!array_key_exists($field, $row)) {
                    $missing[] = $field;
                }
            }

            // Zusätzliche Felder prüfen
            if (!$allowAdditional || $checkExtras) {
                foreach (array_keys($row) as $key) {
                    if (!array_key_exists($key, $definedFields)) {
                        $extra[] = $key;
                    }
                }
            }

            if (!empty($missing) || !empty($extra)) {
                $result[] = [
                    'row' => $index + 1,
                    'missing' => $missing,
                    'extra' => $extra,
                    'excerpt' => substr(json_encode($row, JSON_UNESCAPED_UNICODE), 0, 120) . '...'
                ];
            }
        }

        return $result;
    }

    
    /**
     * Analysiert die Systemdefinition der aktuellen Tabelle (system.json).
     *
     * Diese Methode prüft:
     * - ob alle dataType-Werte gültig sind (optional)
     * - ob alle Property-Namen innerhalb der Felddefinitionen erlaubt sind (optional)
     *
     * Rückgabeformat:
     * [
     *   'invalidTypes' => [
     *     ['field' => 'birthdate', 'dataType' => 'datim']
     *   ],
     *   'invalidProperties' => [
     *     ['field' => 'email', 'property' => 'foobar']
     *   ]
     * ]
     *
     * @param bool $checkDataTypes Ob dataType-Werte geprüft werden sollen
     * @param bool $checkFieldProperties Ob Feldoptionen geprüft werden sollen
     * @return array Fehlerliste
     * @throws \Exception Wenn keine Tabelle geladen ist
     */
    public function analyzeSystemTable(bool $checkDataTypes = true, bool $checkFieldProperties = true): array
    {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        $definedFields = $this->systemConfig['fields'] ?? [];
        $invalidTypes = [];
        $invalidProperties = [];

        foreach ($definedFields as $field => $meta) {
            // 1️⃣ Ungültige Datentypen prüfen
            if ($checkDataTypes && isset($meta['dataType']) && !in_array($meta['dataType'], self::$allowedDataTypes)) {
                $invalidTypes[] = [
                    'field' => $field,
                    'dataType' => $meta['dataType']
                ];
            }

            // 2️⃣ Ungültige Properties prüfen
            if ($checkFieldProperties) {
                foreach (array_keys($meta) as $propertyName) {
                    if (!in_array($propertyName, self::$allowedFieldProperties)) {
                        $invalidProperties[] = [
                            'field' => $field,
                            'property' => $propertyName
                        ];
                    }
                }
            }
        }

        return [
            'invalidTypes' => $invalidTypes,
            'invalidProperties' => $invalidProperties
        ];
    }




}

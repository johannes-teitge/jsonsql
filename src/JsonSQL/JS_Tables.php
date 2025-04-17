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


    public function setTable(string $tableName, bool $autoLoad = false): self {
        $this->currentTableName = $tableName;
    
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
    
        // Tabelldetails (Filegröße, Änderungsdatum, Anzahl der Felder und Datensätze) sammeln
//        $this->collectTableInfo($tableFile, $systemFile);
    
        // Tabelle für die Arbeit setzen
        $this->currentTableFile = $tableFile;

        // Keine Daten laden, nur Struktur setzen
        $this->tableLoaded = false;  // Setzt ein Flag, dass die Daten noch nicht geladen sind

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
    



}

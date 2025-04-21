<?php
namespace Src\JsonSQL;


trait JS_System
{




    /**
     * Initialisiert die erlaubten Datentypen und Feld-Properties für system.json.
     * Wird beim Start automatisch aufgerufen.
     * 
     * added: 2025-04-18 by Dscho
     */
    protected function initSystemDefaults(): void {
        self::$allowedDataTypes = [
            'string', 
            'text',            
            'integer', 
            'boolean', 
            'float', 
            'datetime', 
            'enum', 
            'datetime', 
            'date', 
            'time',
            'timestamp'
        ];

        self::$allowedFieldProperties = [
            'dataType',
            'length',
            'precision',
            'allowNULL',
            'defaultValue',
            'comment',
            'enumValues',
            'encrypt',
            'autoincrement',
            'autoincrement_value',
            'autohash',
            'required',
            'unique',
            'min',
            'max',
            'random',
            'unit',
            'isNULL',
            'format',
            'auto_modified_timestamp',
            'auto_create_timestamp',
        ];
    }



    /**
     * Gibt den aktuellen Datenbankpfad (Ordner) zurück – ohne Dateinamen.
     *
     * @return string|null Pfad zur aktuellen Datenbank oder null, wenn keine gesetzt ist.
     */
    public function getDatabasePath(): ?string {
        return $this->currentDbPath ?? null;
    }


    /**
     * Gibt die Liste der erlaubten Datentypen zurück.
     *
     * @return array
     */
    public static function getAllowedDataTypes(): array {
        return self::$allowedDataTypes;
    }

    /**
     * Gibt die Liste der erlaubten Feld-Properties zurück.
     *
     * @return array
     */
    public static function getAllowedFieldProperties(): array {
        return self::$allowedFieldProperties;
    }    





    /**
     * Validiert die Eigenschaften eines Feldes basierend auf den übergebenen Parametern.
     *
     * Diese Methode prüft, ob alle übergebenen Eigenschaften des Feldes den gültigen Definitionen entsprechen.
     * Sie stellt sicher, dass:
     * - Nur erlaubte Eigenschaften übergeben werden.
     * - Die `min` und `max` Werte für zufällig generierte Werte (`random` = true) korrekt gesetzt sind.
     * - Ein `validated_at` Zeitstempel hinzugefügt wird, um den Zeitpunkt der Validierung zu dokumentieren.
     * 
     * Zudem sorgt die Methode für die korrekte Handhabung von Autoincrement-Werten und überprüft, ob diese innerhalb der gültigen Grenzen liegen.
     * 
     * Falls ungültige Werte übergeben werden oder zwingend erforderliche Werte fehlen, wird eine Ausnahme (`Exception`) geworfen.
     *
     * @param array $fieldProperties Die Eigenschaften des Feldes, die validiert werden sollen. 
     *                               Diese können `datentyp`, `length`, `min`, `max`, `random` und weitere Optionen umfassen.
     * 
     * @throws \Exception Wenn eine ungültige Eigenschaft übergeben wird oder wenn `min` größer als `max` ist.
     *                    Oder wenn erforderliche Eigenschaften wie `min` und `max` fehlen, wenn `random` gesetzt ist.
     * 
     * @return array Die validierten Feld-Eigenschaften, einschließlich des hinzugefügten `validated_at` Zeitstempels.
     * 
     * @example
     * $fieldProperties = [
     *     'dataType' => 'integer',
     *     'random' => true,
     *     'min' => 10,
     *     'max' => 100,
     *     'fieldName' => 'age'
     * ];
     * 
     * $validatedProperties = $this->validateFieldProperties($fieldProperties);
     * // Gibt die validierten Feld-Eigenschaften zurück, einschließlich eines "validated_at" Zeitstempels.
     */
    public function validateFieldProperties(array $fieldProperties): array {
        // Liste der gültigen Property-Bezeichner
        $validProperties = self::$allowedFieldProperties;
    
        // Überprüfen, ob die übergebenen Properties gültig sind
        foreach ($fieldProperties as $property => $value) {
            if (!in_array($property, $validProperties)) {
                throw new \Exception("Ungültige Property '$property' für das Feld '{$fieldProperties['fieldName']}'.");
            }
    
            // Wenn Property 'min' oder 'max' gesetzt ist, aber keine definierten Werte vorhanden sind, Fallback-Werte zuweisen
            if (isset($fieldProperties['random']) && $fieldProperties['random'] === true) {
                if (!isset($fieldProperties['min']) || !is_numeric($fieldProperties['min'])) {
                    if (isset($fieldProperties['dataType']) && $fieldProperties['dataType'] === 'integer') {
                        $fieldProperties['min'] = 1;  // Standardwert für Integer
                    } elseif (isset($fieldProperties['dataType']) && $fieldProperties['dataType'] === 'float') {
                        $fieldProperties['min'] = 0.0;  // Standardwert für Float
                    }
                }
    
                if (!isset($fieldProperties['max']) || !is_numeric($fieldProperties['max'])) {
                    if (isset($fieldProperties['dataType']) && $fieldProperties['dataType'] === 'integer') {
                        $fieldProperties['max'] = 1000;  // Standardwert für Integer
                    } elseif (isset($fieldProperties['dataType']) && $fieldProperties['dataType'] === 'float') {
                        $fieldProperties['max'] = 1000.0;  // Standardwert für Float
                    }
                }
    
                // Überprüfen, ob der 'min' Wert kleiner oder gleich dem 'max' Wert ist
                if ($fieldProperties['min'] > $fieldProperties['max']) {
                    throw new \Exception("Der 'min' Wert für das Feld '{$fieldProperties['fieldName']}' darf nicht größer sein als der 'max' Wert.");
                }
            }
    
            // ⏱️ Validierungszeitpunkt eintragen
     //       $fieldProperties['⏱️ validated_at'] = date('Y-m-d H:i:s');                           
        }
    
        return $fieldProperties;
    }
    
    

    public function validateSystemFieldProperties(array $fieldProperties, string $fieldName = '', bool $isUpdate = false): array
    {
        $validTypes = self::$allowedDataTypes;
    
        // Nur prüfen, wenn Feld 'dataType' übergeben wird
        if (isset($fieldProperties['dataType']) && !in_array($fieldProperties['dataType'], $validTypes)) {
            throw new \Exception("Ungültiger Datentyp '{$fieldProperties['dataType']}' für das Feld '$fieldName'.");
        }
    
        // Enum prüfen – aber nur wenn Typ enum ist und enumValues übergeben werden
        if (
            isset($fieldProperties['dataType']) && $fieldProperties['dataType'] === 'enum' &&
            isset($fieldProperties['enumValues']) && empty(explode(',', $fieldProperties['enumValues']))
        ) {
            throw new \Exception("Für das ENUM-Feld '$fieldName' müssen gültige Werte angegeben werden.");
        }
    
        // Autoincrement prüfen
        /*
        if (isset($fieldProperties['autoincrement']) && $fieldProperties['autoincrement'] &&
            !isset($fieldProperties['autoincrement_value']) || !is_numeric($fieldProperties['autoincrement_value'])) {
            throw new \Exception("Der Wert für 'autoincrement_value' für das Feld '$fieldName' muss eine Zahl sein.");
        }
            */

        // Autoincrement prüfen & setzen
        if (isset($fieldProperties['autoincrement']) && $fieldProperties['autoincrement']) {
            // Wenn kein Startwert vorhanden oder ungültig → Standardwert 1 setzen
            if (!isset($fieldProperties['autoincrement_value']) || !is_numeric($fieldProperties['autoincrement_value'])) {
                $fieldProperties['autoincrement_value'] = 1;
            }
        }            
    
        // Hash-Algorithmus
        if (isset($fieldProperties['autohash']) && !in_array($fieldProperties['autohash'], ['md5', 'sha1', 'sha256'])) {
            throw new \Exception("Ungültiger Hash-Algorithmus für das Feld '$fieldName'. Gültige Optionen: md5, sha1, sha256.");
        }
    
        // Encryption prüfen – aber nur wenn Typ + Flag gesetzt
        if (isset($fieldProperties['encrypt']) && $fieldProperties['encrypt'] &&
            (isset($fieldProperties['dataType']) && $fieldProperties['dataType'] !== 'string')) {
            throw new \Exception("Verschlüsselung ist nur für String-Felder erlaubt. Feld '$fieldName' hat den Datentyp '{$fieldProperties['dataType']}'.");
        }
    
        // required + defaultValue nur bei Neuanlage prüfen
        /* // Deprecated since 2025-04-17: Pflichtwertprüfung wurde auf insert() verschoben
        if (
            !$isUpdate &&
            !empty($fieldProperties['required']) &&
            (!isset($fieldProperties['defaultValue']) || $fieldProperties['defaultValue'] === null)
        ) {
            throw new \Exception("Das Feld '$fieldName' ist als erforderlich markiert, aber es wurde kein gültiger Standardwert 'defaultValue' angegeben.");
        }
        */

        // ⏱️ Validierungszeitpunkt eintragen
  //      $fieldProperties['⏱️ system_validated_at'] = date('Y-m-d H:i:s');     

        return $fieldProperties;
    }
    
    


    // 🔧 Systemkonfiguration laden/speichern:

    protected function loadSystemConfig(): void {
        // Überprüfen, ob die Datenbank- und Tabellennamen gesetzt sind
        if (!$this->currentDbPath || !$this->currentTableName) {
            $this->systemConfig = []; // Leere Konfiguration, wenn keine Tabelle gesetzt ist
            return;
        }
    
        // Pfad zur system.json-Datei der aktuellen Tabelle
        $file = $this->currentDbPath . DIRECTORY_SEPARATOR . $this->currentTableName . '.system.json';
    
        // Überprüfen, ob die system.json existiert
        if (file_exists($file)) {
            $json = json_decode(file_get_contents($file), true);
            $this->systemConfig = $json;
    
            // Verschlüsselungsschlüssel setzen, wenn vorhanden
            if (!empty(trim($json['encryption_key'] ?? ''))) {
                $this->encryptionKey = trim($json['encryption_key']);
            }
        } else {
            $this->systemConfig = []; // Leere Konfiguration, wenn Datei nicht existiert
        }
    
        // Standardwert für Verschlüsselungsschlüssel setzen, falls nicht vorhanden
        if (empty($this->encryptionKey)) {
            $this->encryptionKey = 'JsonSQL-Default-Key@04-2025!?#';
        }
    
        // Sicherstellen, dass 'fields' vorhanden ist
        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }
    
        // Setze allowAdditionalFields auf true, wenn es nicht definiert ist
        if (!isset($this->systemConfig['allowAdditionalFields'])) {
            $this->systemConfig['allowAdditionalFields'] = true;
        }
    
        // Autoincrement-Werte setzen, falls erforderlich
        foreach ($this->systemConfig['fields'] as $fieldName => &$fieldConfig) {
            if (!empty($fieldConfig['autoincrement']) && !isset($fieldConfig['autoincrement_value'])) {
                $fieldConfig['autoincrement_value'] = 1; // Setzt den Startwert für Autoincrement
            }
        }
    
        // Speichern der systemConfig, wenn eine gültige Tabelle geladen wurde
        if ($this->currentTableName && file_exists($file)) {
            $this->saveSystemConfig(); // Nur speichern, wenn eine gültige system.json existiert
        }
    }
    

    /**
     * Initialisiert die Systemkonfiguration für eine neue Tabelle
     */
    protected function initializeSystemConfig(string $tableName): void {
        $this->systemConfig = [
            'fields' => [],
        ];
    
        // Speichern der initialen system.json
        $systemFile = $this->currentDbPath . DIRECTORY_SEPARATOR . $tableName . '.system.json';
        file_put_contents($systemFile, json_encode($this->systemConfig, JSON_PRETTY_PRINT));
        $this->saveSystemConfig();  // Sicherstellen, dass die Konfiguration gespeichert wird
    }
    
    
    
    /**
     * Speichert die aktuelle systemConfig als JSON-Datei.
     *
     * @return bool true bei Erfolg, false bei Fehler
     */
    protected function saveSystemConfig(): bool
    {
        if (!$this->currentDbPath || !$this->currentTableName) return false;

        $file = $this->currentDbPath . DIRECTORY_SEPARATOR . $this->currentTableName . '.system.json';
        return file_put_contents($file, json_encode($this->systemConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    

    public function addField(string $fieldName, array $definition): self {
        // Ruf die ursprüngliche addFieldDefinition auf und übergebe die Parameter weiter
        return $this->addFieldDefinition($fieldName, $definition);
    }    
    

    public function addFieldDefinition(string $fieldName, array $definition): self {
    

        // Lädt die aktuelle Systemkonfiguration
        $this->loadSystemConfig(); 
        $this->clearLastMessage();  // Setzt die vorherige Nachricht zurück        

        // Überprüfung für das 'created_at' Feld: Erlaubt nur ein Feld
        if (isset($definition['create_at']) && $definition['create_at'] === true) {
            // Wenn bereits ein 'created_at' existiert, verhindern wir die Hinzufügung
            if (isset($this->systemConfig['fields']['created_at'])) {
                $this->setLastError('addFieldDefinition', "⚠️ Ein 'created_at' Feld existiert bereits in der Tabelle.");
                return $this;
            }
        }

        // Überprüfung für das 'auto_modified_timestamp' Feld: Erlaubt nur ein Feld
        if (isset($definition['auto_modified_timestamp']) && $definition['auto_modified_timestamp'] === true) {
            // Wenn bereits ein 'updated_at' existiert, verhindern wir die Hinzufügung
            if (isset($this->systemConfig['fields']['auto_modified_timestamp'])) {
                $this->setLastError('addFieldDefinition', "⚠️ Ein 'auto_modified_timestamp' Feld existiert bereits in der Tabelle.");
                return $this;
            }
        }
    


        // 🔍 Strukturelle Prüfung aller Feldnamen (ob gültig)
        $definition = $this->validateFieldProperties($definition);       
        
        // Standardwerte für Präzision festlegen, wenn nicht angegeben
        if (isset($definition['dataType'])) {
            switch ($definition['dataType']) {
                case 'float':
                    if (!isset($definition['precision'])) {
                        $definition['precision'] = 24;  // Standardpräzision für Fließkommazahlen auf 24 Dezimalstellen
                    }
                    break;
            }
        }        

   
        // Wenn das Feld bereits existiert, aktualisiere nur die relevanten Eigenschaften
        if (isset($this->systemConfig['fields'][$fieldName])) {
            // 🟡 Validierung im Update-Modus
            $definition = $this->validateSystemFieldProperties($definition, $fieldName, true);


            $this->systemConfig['fields'][$fieldName] = array_merge(
                $this->systemConfig['fields'][$fieldName], 
                $definition
            );
        
            // Setze die Erfolgsmeldung für ein Update
            $this->setLastMessage('update', "Das Feld '$fieldName' wurde erfolgreich aktualisiert.", [
                'fieldName' => $fieldName,
                'updatedValues' => $definition
            ]);
        } else {
            // 🔵 Validierung bei Neuanlage
            $definition = $this->validateSystemFieldProperties($definition, $fieldName);


            // Wenn das Feld nicht existiert, füge es hinzu
            $this->systemConfig['fields'][$fieldName] = $definition;
        
            // Setze die Erfolgsmeldung für die Erstellung
            $this->setLastMessage('create', "Das Feld '$fieldName' wurde erfolgreich hinzugefügt.", [
                'fieldName' => $fieldName,
                'initialValues' => $definition
            ]);
        }
    
        // 📝 Speichern der Konfiguration
        $this->saveSystemConfig();
    
        return $this;
    }
    
    public function removeFieldDefinition(string $fieldName): self {
        $this->loadSystemConfig();  // Lädt die aktuelle Systemkonfiguration
        $this->clearLastError();    // Setzt vorherige Fehler zurück, um saubere Fehlerbehandlung zu ermöglichen
    
        // Überprüfen, ob das Feld existiert
        if (isset($this->systemConfig['fields'][$fieldName])) {
            // Feld aus der Konfiguration entfernen
            unset($this->systemConfig['fields'][$fieldName]);
            $this->saveSystemConfig();  // Speichern der Änderungen
        } else {
            // Fehlerbehandlung: Speichern des Fehlers im lastError Array
            $this->setLastError('removeFieldDefinition', "Feld '$fieldName' existiert nicht und kann nicht entfernt werden.");
        }
    
        return $this;
    }
    
        

    

    public function removeEncryptedField(string $field): void {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        if (isset($this->systemConfig['fields'][$field])) {
            unset($this->systemConfig['fields'][$field]);
            $this->saveSystemConfig();
        }
    }   

    public function removeAutoincrementField(string $field): void {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (isset($this->systemConfig['fields'][$field]['autoincrement'])) {
            unset($this->systemConfig['fields'][$field]);
            $this->saveSystemConfig();
        }
    }
    
    public function hasField(string $fieldName): bool {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
            
        // Überprüft, ob das Feld in der systemConfig existiert
        return isset($this->systemConfig['fields'][$fieldName]);
    }    
    
    public function isEncryptedField(string $field): bool {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        return isset($this->systemConfig['fields'][$field]['encrypt']) && $this->systemConfig['fields'][$field]['encrypt'] === true;
    }

    public function isAutoincrementField(string $field): bool {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        return isset($this->systemConfig['fields'][$field]['autoincrement']) &&
               $this->systemConfig['fields'][$field]['autoincrement'] === true;
    }
    
       
    

    // Fügt das "created_at"-Feld hinzu (wird nur beim Erstellen gesetzt)
    public function addCreatedAtField_ollld(string $field = 'created_at'): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    

        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }

        // Setzen des Feldes als "autocreated"
        $this->systemConfig['auto_create_timestamp'] = $field;
        $this->saveSystemConfig();
        return $this;
    }


    // Fügt das "created_at"-Feld als Timestamp-Feld hinzu, das nur beim Erstellen gesetzt wird
    public function addCreatedAtField(string $field = 'created_at'): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }

        // Nur hinzufügen, wenn das Feld noch nicht existiert
        if (!isset($this->systemConfig['fields'][$field])) {
            $this->systemConfig['fields'][$field] = [
                'dataType' => 'datetime',
                'auto_create_timestamp' => true,
                'format' => 'Y-m-d H:i:s',
                'timezone' => 'UTC',
                'comment' => 'automatisch beim Erstellen gesetzt'
            ];
        }

        $this->saveSystemConfig();
        return $this;
    }


    // Fügt das "updated_at"-Feld als Timestamp-Feld hinzu, das bei jeder Änderung aktualisiert wird
    public function addUpdatedAtField(string $field = 'updated_at'): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }

        // Nur hinzufügen, wenn das Feld noch nicht existiert
        if (!isset($this->systemConfig['fields'][$field])) {
            $this->systemConfig['fields'][$field] = [
                'dataType' => 'datetime',
                'auto_modified_timestamp' => true,
                'format' => 'Y-m-d H:i:s',
                'timezone' => 'UTC',
                'comment' => 'automatisch bei Änderungen aktualisiert'
            ];
        }

        $this->saveSystemConfig();
        return $this;
    }


    // Wendet die Timestamps an: "created_at" beim Erstellen und "updated_at" beim Erstellen und Aktualisieren
    public function _____applyTimestamps(array &$data, bool $isUpdate = false): void {
        $currentTimestamp = date('Y-m-d H:i:s');
    
        // "created_at" nur setzen, wenn der Datensatz neu erstellt wird
        if (!isset($data['created_at']) && isset($this->systemConfig['autocreated'])) {
            $data['created_at'] = $currentTimestamp;
        }
    
        // "updated_at" immer setzen, wenn der Datensatz erstellt oder aktualisiert wird
        if (isset($this->systemConfig['autoupdated']) || $isUpdate) {
            $data['updated_at'] = $currentTimestamp;
        }
    }


    // Entfernt das "created_at"-Feld
    public function removeCreatedAtField(string $field = 'created_at'): void {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    

        if (isset($this->systemConfig['fields'][$field])) {
            unset($this->systemConfig['fields'][$field]);
            $this->saveSystemConfig();
        }
    }

    // Entfernt das "updated_at"-Feld
    public function removeUpdatedAtField(string $field = 'updated_at'): void {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    

        if (isset($this->systemConfig['fields'][$field])) {
            unset($this->systemConfig['fields'][$field]);
            $this->saveSystemConfig();
        }
    }

    public function isCreatedAtField(string $field): bool {
        // Wenn die Systemkonfiguration noch nicht geladen wurde, laden wir sie
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Prüft, ob das Feld als "created_at" markiert wurde
        return isset($this->systemConfig['autocreated']) && $this->systemConfig['autocreated'] === $field;
    }
    
    public function isUpdatedAtField(string $field): bool {
        // Wenn die Systemkonfiguration noch nicht geladen wurde, laden wir sie
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Prüft, ob das Feld als "updated_at" markiert wurde
        return isset($this->systemConfig['autoupdated']) && $this->systemConfig['autoupdated'] === $field;
    }    


    public function addEncryptedField(string $field): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }
    
        // Definiere das Feld mit dem Datentyp string und die Verschlüsselung
        $this->systemConfig['fields'][$field] = [
            'dataType' => 'string', // Setze den Datentyp auf String
            'encrypt' => true       // Markiere das Feld als verschlüsselt
        ];
    
        // Speichern der geänderten Systemkonfiguration
        $this->saveSystemConfig();
    
        return $this;
    }
    



    /**
     * Fügt ein automatisch generiertes Hash-Feld zur Systemkonfiguration hinzu.
     * 
     * Diese Methode fügt ein Hash-Feld zu den definierten Feldern hinzu. Sie ermöglicht es,
     * für das angegebene Feld den gewünschten Hash-Algorithmus sowie eine Länge für den Hash-Wert
     * zu definieren. Das `autohash`-Flag wird auf `true` gesetzt, um das Feld als zu hashendes Feld
     * zu kennzeichnen. Der Hash-Wert wird dann automatisch bei der Datenverarbeitung generiert.
     *
     * Es können verschiedene Hash-Algorithmen wie `md5`, `sha1`, `sha256` und andere verwendet werden.
     * Die Länge des Hash-Werts wird optional festgelegt. Standardmäßig wird der Wert auf 64 gesetzt.
     *
     * @param string $field Der Name des Feldes, das als Hash-Feld definiert werden soll.
     * @param string $algorithm Der Hash-Algorithmus, der für die Generierung des Hashes verwendet werden soll. 
     *                          Mögliche Werte: `md5`, `sha1`, `sha256`, `sha512`. Standardwert ist `md5`.
     * @param int $length Die Länge des Hash-Werts, der generiert werden soll. Standardwert ist 64.
     *
     * @return self Die Instanz der aktuellen Klasse (für Methodenketten).
     * 
     * @throws \Exception Wenn das Feld bereits existiert oder wenn ungültige Parameter übergeben werden.
     *
     * @example
     * // Fügt ein Feld namens 'some_field' mit dem SHA-256-Algorithmus und einer Länge von 64 Zeichen hinzu
     * $db->addAutoHashField('some_field', 'sha256', 64);
     * 
     * // Fügt ein Feld namens 'some_field' mit dem MD5-Algorithmus und einer Länge von 32 Zeichen hinzu (Standardlänge)
     * $db->addAutoHashField('some_field', 'md5', 32);
     */
    public function addAutoHashField(string $field, string $algorithm = 'md5', int $length = 64): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        
        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }

        // Datentyp auf 'integer' setzen
        $this->systemConfig['fields'][$field]['dataType'] = 'string';

        // Setze den Hash-Algorithmus, die Länge und das autohash-Flag
        $this->systemConfig['fields'][$field]['autohash'] = true; // Autohash auf true setzen
        $this->systemConfig['fields'][$field]['algorithm'] = $algorithm; // Algorithmus für den Hash
        $this->systemConfig['fields'][$field]['length'] = $length; // Länge des Hashes

        $this->saveSystemConfig();
        return $this;
    }

    
    
    public function removeAutoHashField(string $field): void {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (isset($this->systemConfig['fields'][$field]['autohash'])) {
            unset($this->systemConfig['fields'][$field]['autohash']);
            $this->saveSystemConfig();
        }
    }
    
    /**
     * Überprüft, ob ein Feld ein Auto-Hash-Feld ist.
     *
     * @param string $field Der Name des Feldes.
     * @return bool `true`, wenn das Feld ein Auto-Hash-Feld ist, andernfalls `false`.
     */
    public function isAutoHashField(string $field): bool {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        
        // Prüft, ob der Eintrag für 'autohash' existiert und auf 'true' gesetzt ist
        return isset($this->systemConfig['fields'][$field]['autohash']) && $this->systemConfig['fields'][$field]['autohash'] === true;
    }

    /**
     * Gibt den Hash-Algorithmus für ein Auto-Hash-Feld zurück, wenn definiert.
     *
     * @param string $field Der Name des Feldes.
     * @return string|null Der Algorithmus des Hashes, oder `null` wenn nicht gesetzt.
     */
    public function getAutoHashAlgorithm(string $field): ?string {
        if ($this->isAutoHashField($field)) {
            // Wenn der Hash-Algorithmus definiert ist, geben wir diesen zurück, andernfalls 'sha256' als Standard
            return $this->systemConfig['fields'][$field]['algorithm'] ?? 'sha256'; // Default: sha256
        }
        
        return null;
    }

    /**
     * Gibt die Länge des Hashes für ein Auto-Hash-Feld zurück, wenn definiert.
     *
     * @param string $field Der Name des Feldes.
     * @return int|null Die Länge des Hashes, oder `null` wenn nicht gesetzt.
     */
    public function getAutoHashLength(string $field): ?int {
        if ($this->isAutoHashField($field)) {
            // Wenn die Länge definiert ist, geben wir diese zurück, andernfalls 64 als Standard
            return $this->systemConfig['fields'][$field]['length'] ?? 64; // Default length: 64
        }

        return null;
    }

    public function isUuidField(string $field): bool {
        // Wenn die Systemkonfiguration noch nicht geladen wurde, laden wir sie
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Prüfen, ob das Feld in der Konfiguration als UUID markiert ist
        return isset($this->systemConfig['fields'][$field]['autouuid']) && $this->systemConfig['fields'][$field]['autouuid'] === true;
    }
    




    public function addAutoincrementField(string $field, int $start = 1, int $step = 1): self {
        global $debugger;
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    

        $debugger->dump($this->systemConfig);
    
        if (!isset($this->systemConfig['fields'])) {
            $this->systemConfig['fields'] = [];
        }
    
        // Falls Feld noch nicht definiert wurde – neu anlegen
        if (!isset($this->systemConfig['fields'][$field])) {
            $this->systemConfig['fields'][$field] = [];
        }
    
        // Datentyp auf 'integer' setzen
        $this->systemConfig['fields'][$field]['dataType'] = 'integer';

        $this->systemConfig['fields'][$field]['autoincrement'] = true;
    
        // Schrittweite setzen (default = 1)
        $this->systemConfig['fields'][$field]['autoincrement_step'] = $step;
    
        // Wenn kein gültiger Zähler vorhanden oder kleiner als Startwert → setzen
        if (!isset($this->systemConfig['fields'][$field]['autoincrement_value']) ||
            $this->systemConfig['fields'][$field]['autoincrement_value'] < $start) {
            $this->systemConfig['fields'][$field]['autoincrement_value'] = $start;
        }
    
        $this->saveSystemConfig();
        return $this;
    }
   

    public function getAutoincrementInfo(string $field): ?array {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (!isset($this->systemConfig['fields'][$field]['autoincrement'])) {
            return null; // Kein Autoincrement-Feld
        }
    
        return [
            'value' => $this->systemConfig['fields'][$field]['autoincrement_value'] ?? null,
            'step'  => $this->systemConfig['fields'][$field]['autoincrement_step'] ?? 1,
        ];
    }

    public function setAutoincrementValue(string $field, int $newValue): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (!isset($this->systemConfig['fields'][$field]['autoincrement'])) {
            throw new \Exception("Feld '$field' ist kein Autoincrement-Feld.");
        }
    
        $this->systemConfig['fields'][$field]['autoincrement_value'] = $newValue;
        $this->saveSystemConfig();
    
        return $this;
    }

    public function setAutoincrementStep(string $field, int $step): self {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
    
        if (!isset($this->systemConfig['fields'][$field]['autoincrement'])) {
            throw new \Exception("Feld '$field' ist kein Autoincrement-Feld.");
        }
    
        $this->systemConfig['fields'][$field]['autoincrement_step'] = $step;
        $this->saveSystemConfig();
    
        return $this;
    }
    
    
    
    
    
    

    
    
    /**
     * Extrahiert automatisch definierte Felder aus system.json
     */
    protected function getAutoFields(array $systemData): array {
        $autoFields = [];
    
        if (isset($systemData['fields'])) {
            foreach ($systemData['fields'] as $field => $config) {
                if (isset($config['autoincrement'])) {
                    $autoFields[] = "$field (autoincrement)";
                }
                if (isset($config['autohash'])) {
                    $autoFields[] = "$field (autohash)";
                }
                if (isset($config['autouuid'])) {
                    $autoFields[] = "$field (autouuid)";
                }
                if (isset($config['created_at'])) {
                    $autoFields[] = "$field (created_at)";
                }
                if (isset($config['updated_at'])) {
                    $autoFields[] = "$field (updated_at)";
                }
            }
        }
    
        return $autoFields;
    }
    





    public function setEncryptionKey(string $key): void {
        // Stelle sicher, dass die Systemkonfiguration geladen ist
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Setze den Verschlüsselungsschlüssel
        $this->systemConfig['encryption_key'] = $key;
    
        // Speichere die Konfiguration
        $this->saveSystemConfig();
    
        // Setze den internen Schlüssel
        $this->encryptionKey = $key;
    }


    public function hasEncryptionKey(): bool {
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        return !empty(trim($this->systemConfig['encryption_key'] ?? ''));
    }



    
    public function getSystemFilePath(): ?string {
        if (!$this->currentDbPath || !$this->currentTableName) return null;
        return $this->currentDbPath . DIRECTORY_SEPARATOR . $this->currentTableName . '.system.json';
    }

    /**
     * Gibt das aktuelle Systemverzeichnis zurück, also den Pfad zur Datenbank (ohne Datei).
     *
     * @return string|null Der aktuelle Datenbankpfad oder null, wenn keine Datenbank gesetzt ist.
     */
    public function getSystemDir(bool $trailingSlash = false): ?string {
        if (!$this->currentDbPath) return null;
        return $trailingSlash
            ? rtrim($this->currentDbPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            : $this->currentDbPath;
    }
    


    public function hasSystemConfig(): bool {
        $path = $this->getSystemFilePath();
        return $path && file_exists($path);
    }



    public function getRawSystemData(): array {
        // Überprüfen, ob die systemConfig geladen ist, andernfalls laden
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Gibt die systemConfig-Daten zurück (dies sind die rohen Daten aus system.json)
        return $this->systemConfig;
    }  



    // Funktion zur Überprüfung und zum Hinzufügen von Systemfeldern
    public function checkAutofield($type, $fieldname, $value = null) {
        $result = [];

        // Überprüfen, ob der Tabellenname gesetzt ist
        if (is_null($this->currentTableName)) {
            throw new \InvalidArgumentException(
                "❌ Kein Tabellenname angegeben. Bitte setze den Tabellenname mit 'setTable()' oder 'from()' bevor du diese Funktion aufrufst. Beispiel: \$db->setTable('deine_tabelle');"
            );
        }

        switch ($type) {
            case 'AutoIncrement':
                // Überprüfen, ob das Feld ein Auto-Increment ist
                if (!$this->isAutoincrementField($fieldname)) {
                    $this->addAutoincrementField($fieldname, $value ?? 1); // Standardwert 1
                    $result[] = [
                        'status' => 'info',
                        'message' => "⚙️ Autoincrement für '$fieldname' in Tabelle '{$this->currentTableName}' wurde gesetzt (Startwert " . ($value ?? 1) . ")."
                    ];
                } else {
                    $result[] = [
                        'status' => 'success',
                        'message' => "✅ Autoincrement für '$fieldname' in Tabelle '{$this->currentTableName}' ist bereits gesetzt."
                    ];
                }
                break;

            case 'Create':
                // Überprüfen, ob das Feld 'created_at' existiert
                if (!$this->isCreatedAtField($fieldname)) {
                    $this->addCreatedAtField($fieldname);
                    $result[] = [
                        'status' => 'info',
                        'message' => "⚙️ '$fieldname' Feld in Tabelle '{$this->currentTableName}' wurde gesetzt."
                    ];
                } else {
                    $result[] = [
                        'status' => 'success',
                        'message' => "✅ '$fieldname' Feld in Tabelle '{$this->currentTableName}' ist bereits gesetzt."
                    ];
                }
                break;

            case 'Update':
                // Überprüfen, ob das Feld 'updated_at' existiert
                if (!$this->isUpdatedAtField($fieldname)) {
                    $this->addUpdatedAtField($fieldname);
                    $result[] = [
                        'status' => 'info',
                        'message' => "⚙️ '$fieldname' Feld in Tabelle '{$this->currentTableName}' wurde gesetzt."
                    ];
                } else {
                    $result[] = [
                        'status' => 'success',
                        'message' => "✅ '$fieldname' Feld in Tabelle '{$this->currentTableName}' ist bereits gesetzt."
                    ];
                }
                break;

            case 'Hash':
                // Überprüfen, ob das Feld einen Hash benötigt
                if (!$this->isHashField($fieldname)) {
                    $this->addHashField($fieldname);
                    $result[] = [
                        'status' => 'info',
                        'message' => "⚙️ '$fieldname' Feld in Tabelle '{$this->currentTableName}' wurde als Hash-Feld gesetzt."
                    ];
                } else {
                    $result[] = [
                        'status' => 'success',
                        'message' => "✅ '$fieldname' Feld in Tabelle '{$this->currentTableName}' ist bereits als Hash-Feld gesetzt."
                    ];
                }
                break;

            case 'Uuid':
                // Überprüfen, ob das Feld ein UUID ist
                if (!$this->isUuidField($fieldname)) {
                    $this->addUuidField($fieldname);
                    $result[] = [
                        'status' => 'info',
                        'message' => "⚙️ '$fieldname' Feld in Tabelle '{$this->currentTableName}' wurde als UUID gesetzt."
                    ];
                } else {
                    $result[] = [
                        'status' => 'success',
                        'message' => "✅ '$fieldname' Feld in Tabelle '{$this->currentTableName}' ist bereits als UUID gesetzt."
                    ];
                }
                break;

            default:
                $result[] = [
                    'status' => 'danger',
                    'message' => "❌ Ungültiger Feldtyp '$type' angegeben."
                ];
                break;
        }

        // Rückgabe des Ergebnisses als Array
        return $result;
    }



    /**
     * Setzt eine globale Option in der system.json der aktiven Tabelle.
     *
     * @trait JS_SYSTEM
     * @param string $optionKey Name der Option (z. B. 'allowAdditionalFields')
     * @param mixed $value Wert der Option
     * @return bool true bei Erfolg
     * @throws Exception Wenn keine aktive Tabelle gesetzt ist
     */
    public function setSystemOption(string $optionKey, $value): bool
    {
        if (empty($this->currentTableFile)) {
            throw new \Exception("❌ Keine aktive Tabelle gesetzt. Bitte setTable() zuerst aufrufen.");
        }

        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        $this->systemConfig[$optionKey] = $value;

        // Rückgabe: true bei erfolgreichem Speichern
        return $this->saveSystemConfig();
    }



    
    /**
     * Liest eine globale Option aus der system.json der aktiven Tabelle.
     *
     * @trait JS_SYSTEM
     * @param string $optionKey Name der Option
     * @param mixed $default Fallback-Wert, falls Option nicht gesetzt ist
     * @return mixed
     * @throws Exception Wenn keine aktive Tabelle gesetzt ist
     */
    public function getSystemOption(string $optionKey, $default = null)
    {
        if (empty($this->currentTableFile)) {
            throw new \Exception("❌ Keine aktive Tabelle gesetzt. Bitte setTable() aufrufen.");
        }

        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        $system = $this->getSystemDefinition($this->activeTable);
        return $system[$optionKey] ?? $default;
    }



    /**
     * Schreibt eine neue system.json-Konfiguration für die aktuelle Tabelle.
     *
     * @param array $config Neue Konfiguration (z. B. 'fields' und Optionen)
     * @throws \Exception Wenn keine Tabelle gesetzt ist
     */
    public function writeSystemConfig(array $config): void
    {
        if (empty($this->currentTableName)) {
            throw new \Exception("❌ Es wurde keine Tabelle gesetzt. writeSystemConfig() nicht möglich.");
        }

        $file = $this->getSystemFilePath();
        file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->systemConfig = $config;
    }








}    

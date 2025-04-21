<?php
namespace Src\JsonSQL;

trait JS_Crud
{


    public function transact(): void {
        $this->isTransaction = true;
        $this->transactionBuffer = [];
    }

    public function commit(): void {
        if (!$this->isTransaction) {
            return;
        }
    
        $fp = fopen($this->currentTableFile, 'c+');
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $data = $content ? json_decode($content, true) : [];
            $data = array_merge($data, $this->transactionBuffer);
    
            rewind($fp);
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
    
            $this->transactionBuffer = [];
            $this->isTransaction = false;
    
            $this->saveSystemConfig();
        } else {
            throw new \Exception("Datei konnte nicht gesperrt werden (commit).");
        }
    }   
    
    public function rollback(): void {
        $this->transactionBuffer = [];
        $this->inTransaction = false;
    }    



    private function applyAutoIncrement(array &$record, string $field, array &$config): void {
        if (!isset($record[$field]) && !empty($config['autoincrement'])) {
            $step = isset($config['autoincrement_step']) ? (int)$config['autoincrement_step'] : 1;
            $config['autoincrement_value'] ??= 1;
    
            $record[$field] = $config['autoincrement_value'];
            $this->lastInsertId = $config['autoincrement_value'];
            $config['autoincrement_value'] += $step;
            $this->systemConfig['fields'][$field] = $config;          
        }
    }
    
    private function applyAutoTimestamps(array &$record): void {
        if (!isset($record['created_at']) && isset($this->systemConfig['autocreated'])) {
            $record[$this->systemConfig['autocreated']] = date('Y-m-d H:i:s');
        }
    
        if (!isset($record['updated_at']) && isset($this->systemConfig['autoupdated'])) {
            $record[$this->systemConfig['autoupdated']] = date('Y-m-d H:i:s');
        }
    }
    

    private function applyAutoHash(array &$record, string $field, array &$config): void {
        if (!isset($record[$field]) && !empty($config['autohash'])) {
            $valueToHash = json_encode($record);
            $record[$field] = match (strtolower($config['autohash'])) {
                'md5'    => md5($valueToHash),
                'sha1'   => sha1($valueToHash),
                'sha256' => hash('sha256', $valueToHash),
                default  => md5($valueToHash),
            };
        }
    }
    

    private function applyAutoUuid(array &$record, string $field, array &$config): void {
        if (!isset($record[$field]) && !empty($config['autouuid'])) {
            $record[$field] = $this->generateUuid();
        }
    }
    

    private function applyEncryption(array &$record, string $field, array &$config): void {
        if (isset($config['encrypt'])) {
            $record[$field] = $this->encryptValue((string)$record[$field]);
        }
    }





/**
 * Validiert, ob der Wert ein Integer ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param int $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist.
 *
 * @return int Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateInteger($value, $default = 0) {
    if (is_int($value)) {
        return $value;
    } elseif (is_numeric($value)) {
        return (int) $value;
    }
    return $default;
}    

/**
 * Validiert, ob der Wert ein Float ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param float $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist.
 *
 * @return float Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateFloat($value, $default = 0.0) {
    if (is_float($value)) {
        return $value;
    } elseif (is_numeric($value)) {
        return (float) $value;
    }
    return $default;
}

/**
 * Validiert, ob der Wert ein String ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param string $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist.
 *
 * @return string Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateString($value, $default = '') {
    if (is_string($value)) {
        return $value;
    }
    return $default;
}


/**
 * Validiert, ob der Wert ein gültiges Datum im MySQL-Format ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param string $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist (im Format 'Y-m-d H:i:s').
 *
 * @return string Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateDateTime($value, $default = '1970-01-01 00:00:00') {
    $format = 'Y-m-d H:i:s';
    
    // Überprüfen, ob der Wert ein gültiges Datum im richtigen Format ist
    $d = \DateTime::createFromFormat($format, $value);
    
    // Wenn das Datum ungültig ist, Standardwert zurückgeben
    if (!$d || $d->format($format) !== $value) {
        return $default;
    }

    return $value;
}


/**
 * Validiert, ob der Wert ein gültiges Datum im MySQL-Format ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param string $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist (im Format 'Y-m-d').
 *
 * @return string Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateDate($value, $default = '1970-01-01') {
    $format = 'Y-m-d';
    
    // Überprüfen, ob der Wert ein gültiges Datum im richtigen Format ist
    $d = \DateTime::createFromFormat($format, $value);
    
    // Wenn das Datum ungültig ist, Standardwert zurückgeben
    if (!$d || $d->format($format) !== $value) {
        return $default;
    }

    return $value;
}


/**
 * Validiert, ob der Wert eine gültige Zeit im MySQL-Format ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param string $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist (im Format 'H:i:s').
 *
 * @return string Der validierte Wert (entweder der eingegebene Wert oder der Standardwert).
 */
function validateTime($value, $default = '00:00:00') {
    $format = 'H:i:s';
    
    // Überprüfen, ob der Wert eine gültige Zeit im richtigen Format ist
    $t = \DateTime::createFromFormat($format, $value);
    
    // Wenn die Zeit ungültig ist, Standardwert zurückgeben
    if (!$t || $t->format($format) !== $value) {
        return $default;
    }

    return $value;
}



/**
 * Validiert, ob der Wert ein gültiger Unix-Timestamp ist, andernfalls wird der Standardwert verwendet.
 *
 * @param mixed $value Der zu überprüfende Wert.
 * @param int $default Der Standardwert, der verwendet wird, falls der Wert ungültig ist.
 *
 * @return int Der validierte Unix-Timestamp (entweder der eingegebene Wert oder der Standardwert).
 */
public function validateTimestamp($value): int {
    if (strtoupper($value) === 'NOW()') {
        return time(); // Aktuellen Unix-Timestamp zurückgeben
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return time(); // Falls ungültig, aktuellen Unix-Timestamp zurückgeben
    }

    return $timestamp;
}


    


private function getMaxMinINTValue(array $field, int $value): int {
    // 1. Prüfen, ob das Feld ein Array und definiert ist
    if (!is_array($field) || empty($field)) {
        throw new \Exception("Das Feld ist nicht definiert oder kein Array.");
    }


    // 2. Den Min- und Max-Wert aus den Systemkonfigurationen oder einer anderen Quelle holen
    $min = isset($field['min']) ? $field['min'] : null;
    $max = isset($field['max']) ? $field['max'] : null;

    // Wenn keine Min-Max-Werte vorhanden sind, geben wir den übergebenen Wert zurück
    if ($min === null && $max === null) {
        return $value;
    }

    // 3. Falls ein Min-Wert vorhanden ist und der Wert unter dem Min-Wert liegt, setzen wir den Wert auf den Min-Wert
    if ($min !== null && $value < $min) {
        $value = $min;
    }

    // 4. Falls ein Max-Wert vorhanden ist und der Wert über dem Max-Wert liegt, setzen wir den Wert auf den Max-Wert
    if ($max !== null && $value > $max) {
        $value = $max;
    }

    // 5. Den angepassten Wert zurückgeben
    return $value;
}

private function getMaxMinFloatValue(array $field, float $value): float {
    // 1. Prüfen, ob das Feld ein Array und definiert ist
    if (!is_array($field) || empty($field)) {
        throw new \Exception("Das Feld ist nicht definiert oder kein Array.");
    }


    // 2. Den Min- und Max-Wert aus den Systemkonfigurationen oder einer anderen Quelle holen
    $min = isset($field['min']) ? (float)$field['min'] : null;
    $max = isset($field['max']) ? (float)$field['max'] : null;

    // Wenn keine Min-Max-Werte vorhanden sind, geben wir den übergebenen Wert zurück
    if ($min === null && $max === null) {
        return $value;
    }

    // 3. Falls ein Min-Wert vorhanden ist und der Wert unter dem Min-Wert liegt, setzen wir den Wert auf den Min-Wert
    if ($min !== null && $value < $min) {
        $value = $min;
    }

    // 4. Falls ein Max-Wert vorhanden ist und der Wert über dem Max-Wert liegt, setzen wir den Wert auf den Max-Wert
    if ($max !== null && $value > $max) {
        $value = $max;
    }

    // 5. Den angepassten Wert zurückgeben
    return $value;
}


/**
 * Validiert die Länge eines Strings.
 * 
 * Falls die Länge des Strings die maximale Länge überschreitet, wird der String abgeschnitten.
 * Wenn keine maximale Länge definiert ist, wird der String nicht verändert.
 * 
 * @param string $value Der zu prüfende String.
 * @param array $config Die Konfiguration des Feldes, die `length` enthalten kann.
 * @return string Der validierte String.
 */
private function validateStringLength(string $value, array $config): string {
    // Standardwert für `length` setzen, falls sie nicht definiert ist
    $maxLength = $config['length'] ?? null;

    // Wenn eine maximale Länge definiert ist und die Länge überschritten wird, den String kürzen
    if ($maxLength !== null && strlen($value) > $maxLength) {
        $value = substr($value, 0, $maxLength);
    }

    return $value;
}



/**
 * Evaluates a date expression and returns the resulting date value.
 * 
 * This function interprets a string-based date expression, such as 'NOW()', 
 * 'NOW() + INTERVAL 1 DAY' or 'NOW() + INTERVAL 3 DAYS', and returns the computed date.
 * The function supports both singular and plural forms of time intervals, such as:
 *   - 'SECOND' or 'SECONDS'
 *   - 'MINUTE' or 'MINUTES'
 *   - 'HOUR' or 'HOURS'
 *   - 'DAY' or 'DAYS'
 *   - 'MONTH' or 'MONTHS'
 *   - 'YEAR' or 'YEARS'
 *
 * The result is a string representing the computed date in the format 'Y-m-d H:i:s'.
 * 
 * Supported expressions:
 * - 'NOW()' - current date and time.
 * - 'NOW() + INTERVAL 1 SECOND' or 'NOW() + INTERVAL 1 SECOND'.
 * - 'NOW() + INTERVAL 1 DAY' or 'NOW() + INTERVAL 1 DAY' or 'NOW() + INTERVAL 3 DAYS'.
 * - Other similar expressions for time intervals.
 *
 * Example usage:
 *   - evaluateDateExpression('NOW()');
 *   - evaluateDateExpression('NOW() + INTERVAL 1 DAY');
 *   - evaluateDateExpression('NOW() + INTERVAL 3 DAYS');
 *   - evaluateDateExpression('NOW() + INTERVAL 1 DAY 2 HOURS 4 MINUTES 30 SECONDS');
 * 
 * @param string $expression The date expression to be evaluated.
 * 
 * @return string The resulting date in 'Y-m-d H:i:s' format.
 * 
 * @throws \Exception If the date expression is invalid or cannot be processed.
 */
private function evaluateDateExpression(string $expression): string {
    // Remove extra spaces and normalize the interval expressions
    $expression = preg_replace('/\s+/', ' ', trim($expression));

    // Check for NOW() in the expression and normalize
    if (preg_match('/NOW\(\)/i', $expression)) {
        $expression = str_replace('NOW()', 'CURRENT_TIMESTAMP', $expression);
    }

    // Handle complex intervals like "NOW() + INTERVAL 1 DAY 2 HOURS 30 MINUTES"
    if (preg_match('/NOW\(\)\s*\+\s*INTERVAL\s*(.*?)\s*/i', $expression, $matches)) {
        $intervalExpression = $matches[1];  // Extract the part like "1 DAY 2 HOURS 30 MINUTES"

        // Split the expression into intervals like "1 DAY", "2 HOURS"
        preg_match_all('/(\d+)\s*(SECOND|MINUTE|HOUR|DAY|MONTH|YEAR|SECOND|MINUTE|HOUR|DAY|MONTH|YEAR)/i', $intervalExpression, $intervals, PREG_SET_ORDER);

        // Initialize the date with CURRENT_TIMESTAMP
        $currentDate = new \DateTime();

        // Apply each interval
        foreach ($intervals as $interval) {
            $value = (int)$interval[1];
            $unit = strtoupper($interval[2]);

            switch ($unit) {
                case 'SECOND':
                    $currentDate->modify("+$value second");
                    break;
                case 'MINUTE':
                    $currentDate->modify("+$value minute");
                    break;
                case 'HOUR':
                    $currentDate->modify("+$value hour");
                    break;
                case 'DAY':
                    $currentDate->modify("+$value day");
                    break;
                case 'MONTH':
                    $currentDate->modify("+$value month");
                    break;
                case 'YEAR':
                    $currentDate->modify("+$value year");
                    break;
                default:
                    throw new \Exception("Unsupported time unit in expression: $unit");
            }
        }

        // Return the final date as a string
        return $currentDate->format('Y-m-d H:i:s');
    }

    // If no matching pattern is found, throw an exception
    throw new \Exception("Invalid date expression: $expression");
}


private function applyAutoModified(array $config, $currentValue = '', bool $isUpdate = false): string {
    // Automatischer Zeitstempel?
    $useCreate = !$isUpdate && !empty($config['auto_create_timestamp']);
    $useUpdate = $isUpdate && !empty($config['auto_modified_timestamp']);

    if ($useCreate || $useUpdate) {

        $format = $config['format'] ?? 'Y-m-d H:i:s';
        $timezone = $config['timezone'] ?? 'UTC';
        $dt = new \DateTime('now', new \DateTimeZone($timezone));
        $currentValue = $dt->format($format);
    }

    // Kein Auto-Timestamp → alten Wert zurück
    return $currentValue;
}




private function applyAutoFields(array $insertrecord): array {
    global $debugger;

    if ($this->systemConfig === null) {
        $this->loadSystemConfig();
    }

    $record = $insertrecord;

    if (!isset($this->systemConfig['fields'])) {
        return $record;
    }

    foreach ($this->systemConfig['fields'] as $field => &$config) {

        if (!isset($record[$field])) { // Prüfen ob das Feld noch nicht existiert, dann anlegen


            switch ($config['dataType']) {


                /*********  INTEGER  ***********/
                case 'integer':
                    // Wenn der Wert für das Feld übergeben wurde, validiere ihn
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? 0);
                    $value = $this->validateInteger($value, 0); // Validierung für Integer

                    // Anwenden der min/max-Grenzen für Integer
                    $value = $this->getMaxMinINTValue($config, $value);

                    // Optional: Hier könnten wir den Wert auch auf einen bestimmten Bereich begrenzen (aber da wir min/max haben, ist das nicht zwingend nötig)
                    $record[$field] = $value;  // Wert setzen
                    break;


                /*********  FLOAT  ***********/
                case 'float':
                    // Wenn der Wert für das Feld übergeben wurde, validiere ihn
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? 0.0);

                    // Prüfen, ob precision in der Konfiguration angegeben ist, ansonsten Standardwert setzen
                    $precision = isset($config['precision']) ? $config['precision'] : 24; // Standard auf 24 Dezimalstellen

                    // Sicherstellen, dass der Wert ein Float ist
                    $value = $this->validateFloat($value, 0.0);                     

                    // Anwenden der min/max-Grenzen (falls definiert)
                    $value = $this->getMaxMinFloatValue($config, $value);                     

                    // Wert auf die angegebene Präzision runden
                    $value = round($value, $precision);  // Runden auf die angegebene Präzision                    

                    // Hier kürzen wir den Float, wenn notwendig
                    $value = round($value, 2);  // Beispiel: auf 2 Dezimalstellen kürzen (ganz einfach)

                    $record[$field] = $value;  // Wert setzen
                    break;                     

                /*********  STRING/TEXT  ***********/
                case 'string':
                    case 'text': // NEU: text wie string behandeln, aber ohne Längenlimit
                        $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? '');
                    
                        if ($config['dataType'] === 'string') {
                            $value = $this->validateStringLength($value, $config); // Nur bei string
                        }
                    
                        $record[$field] = $value;
                        break;

                /*********  ENUM  ***********/
                case 'enum':
                    $enumValues = isset($config['enumValues']) ? explode(',', $config['enumValues']) : [];
                    $inputValue = $insertrecord[$field] ?? null;

                    if ($inputValue !== null && in_array($inputValue, $enumValues, true)) {
                        // Wenn ein gültiger Wert übergeben wurde → übernehmen
                        $record[$field] = $inputValue;
                    } elseif (isset($config['defaultValue']) && in_array($config['defaultValue'], $enumValues, true)) {
                        // Wenn gültiger defaultValue → setzen
                        $record[$field] = $config['defaultValue'];
                    } else {
                        // Sonst leeres Feld
                        $record[$field] = '';
                    }
                    break;

                /*********  DATETIME  ***********/
                case 'datetime':
                    // String-Wert: Keine Min-Max-Prüfung, nur Längenbegrenzung
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? '');
                    $value = $this->validateDateTime($value, '1970-01-01 00:00:00');    
                    
                    // Hier haben wir eine Valide Zeit -> Also chekcn ob Auto Zeitstempel gesetzt werden muss
                    $value = $this->applyAutoModified($config, $value, true);                
                    $value = $this->applyAutoModified($config, $value, false);                                    


                    $record[$field] = $value;  // Wert setzen
                    break;


                /*********  DATE  ***********/
                case 'date':
                    // String-Wert: Keine Min-Max-Prüfung, nur Längenbegrenzung
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? '');
                    $value = $this->validateDate($value, '1970-01-01');                   
                    $record[$field] = $value;  // Wert setzen
                    break;     
                    

                /*********  DATE  ***********/
                case 'time':
                    // String-Wert: Keine Min-Max-Prüfung, nur Längenbegrenzung
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? '');
                    $value = $this->validateTime($value, '00:00:00');                   
                    $record[$field] = $value;  // Wert setzen
                    break;    
                    
                /*********  DATE  ***********/
                case 'timestamp':
                    // String-Wert: Keine Min-Max-Prüfung, nur Längenbegrenzung
                    $value = isset($insertrecord[$field]) ? $insertrecord[$field] : ($config['defaultValue'] ?? 'NOW()');
                    $value = $this->validateTimestamp($value, 'NOW()');                   
                    $record[$field] = $value;  // Wert setzen
                    break;                     



            }
        } 

        // Auto-Increment
        if (!empty($config['autoincrement'])) {
            $step = isset($config['autoincrement_step']) ? (int)$config['autoincrement_step'] : 1;
            $config['autoincrement_value'] ??= 1;

            $record[$field] = $config['autoincrement_value'];
            $this->lastInsertId = $config['autoincrement_value'];
            $config['autoincrement_value'] += $step;
            $this->systemConfig['fields'][$field] = $config;
        }

        // Auto Created Timestamp
        if ($field === 'created_at' && !isset($record[$field])) {
            $record[$field] = date('Y-m-d H:i:s'); // Setze created_at, falls nicht vorhanden
        }

        // Auto Updated Timestamp
        if ($field === 'updated_at' && !isset($record[$field])) {
            $record[$field] = date('Y-m-d H:i:s'); // Setze updated_at, falls nicht vorhanden
        }

        // UUID (falls notwendig, für UUID-Felder wie `id` oder `logo`)
        if ($this->isUuidField($field) && !isset($record[$field])) {
            $record[$field] = $this->generateUuid();
        }

        // Verschlüsselung nur für String-Felder
        if (isset($config['encrypt']) && $config['encrypt'] && is_string($record[$field])) {
            $record[$field] = $this->encryptValue($record[$field]);
        }

        // Auto-Hash (für das `hash` Feld)
        if (isset($config['autohash']) && $config['autohash'] === true && !isset($record[$field])) {
            // Wenn der Wert für das `hash`-Feld nicht gesetzt ist, eine leere Zeichenkette verwenden
            $valueToHash = isset($record[$field]) ? (string)$record[$field] : '';

            // Hole den Algorithmus und die Länge aus der Systemkonfiguration
            $algorithm = $config['algorithm'] ?? 'sha256'; // Standardwert: sha256
            $maxLength = $config['length'] ?? 64; // Standardlänge: 64 Zeichen

            // Generiere den Hash unter Berücksichtigung der Länge
            $record[$field] = $this->generateHash($valueToHash, $algorithm, $maxLength);
        }
    }

    $this->saveSystemConfig();        
    return $record;
}



// Diese Methode fügt Felder ein, die nicht in der Systemkonfiguration definiert sind
private function insertAdditionalFields_(array $record): array {
    if (!$this->isAllowingAdditionalFields()) {
        return $record;  // Wenn das Hinzufügen zusätzlicher Felder deaktiviert ist, nichts tun
    }

    foreach ($record as $field => $value) {
        if (!isset($this->systemConfig['fields'][$field])) {
            // Feld ist nicht in der Systemkonfiguration definiert
            // Hier kannst du zusätzliche Prüfungen oder eine dynamische Behandlung vornehmen
            // Beispiel: Auf Datentypen oder spezielle Validierungen prüfen
            // Wenn du das Feld trotzdem in der Datenbank speichern möchtest, dann füge es hier hinzu
            $record[$field] = $value; // Der Wert des Feldes wird beibehalten
        }
    }

    return $record;  // Rückgabe des aktualisierten Datensatzes
}

// Funktion, um zu prüfen, ob das Hinzufügen zusätzlicher Felder zulässig ist
private function isAllowingAdditionalFields(): bool {
    // Hier kannst du eine Option aus der Systemkonfiguration oder einen Parameter verwenden
    return isset($this->systemConfig['allowAdditionalFields']) && $this->systemConfig['allowAdditionalFields'] === true;
}


// Insert für zusätzliche Felder
private function insertAdditionalFields(array $newrecord, array $originalRecord): array {
    if (!$this->isAllowingAdditionalFields()) {
        return $newrecord;  // Wenn das Hinzufügen zusätzlicher Felder deaktiviert ist, nichts tun
    }

    // Überprüfe die Felder, die nicht von applyAutoFields bearbeitet wurden
    foreach ($originalRecord as $field => $value) {
        if (!isset($newrecord[$field])) {
            $newrecord[$field] = $value; // Füge das fehlende Feld hinzu
        }
    }

    // Rückgabe des vollständigen Datensatzes
    return $newrecord;
}




/**
 * Prüft, ob alle als `required` markierten Felder in der system.json im Datensatz vorhanden sind.
 *
 * Diese Methode wird vor dem Speichern eines Datensatzes aufgerufen, um sicherzustellen,
 * dass alle Pflichtfelder (`required: true`) gemäß system.json vorhanden und nicht leer sind.
 * Wird ein erforderliches Feld nicht übergeben oder ist leer, wird eine Exception ausgelöst
 * und der Insert-Vorgang abgebrochen.
 *
 * Hinweise:
 * - Leere Werte (z. B. `''` oder `null`) gelten als nicht gesetzt.
 * - Die Prüfung erfolgt **vor** dem Anwenden von Defaultwerten oder Autofeldern.
 * - Nur relevant für Insert-Vorgänge, nicht für Update (außer dort explizit gewünscht).
 *
 * @param array $inputRecord Der vom Benutzer übergebene Datensatz (vor Anwendung von Auto-Feldern).
 *
 * @throws \Exception Wenn ein oder mehrere Pflichtfelder fehlen oder leer sind.
 *
 * @return void
 */
private function validateRequiredFields(array $inputRecord): void {
    if (!isset($this->systemConfig['fields'])) {
        return; // Wenn keine Felder definiert sind, überspringen
    }

    $missingFields = [];

    foreach ($this->systemConfig['fields'] as $field => $config) {
        if (!empty($config['required'])) {
            $value = $inputRecord[$field] ?? null;
            
            // Prüfen: Feld fehlt oder ist leer (z. B. '', null)
            if ($value === null || $value === '') {
                $missingFields[] = $field;
            }
        }
    }

    if (!empty($missingFields)) {
        $fieldList = implode(', ', $missingFields);
        $fieldList = implode('<except-seperator>,</except-seperator>', array_map(fn($f) => "<except>$f</except>", $missingFields));        
        throw new \Exception("Fehlende erforderliche Felder: <except-wrapper>$fieldList</except-wrapper>");
    }
}



    /**
     * Gibt alle Datensätze zurück, die beim letzten Insert übersprungen wurden (z. B. wegen UNIQUE).
     *
     * @return array Liste der übersprungenen Datensätze
     */
    public function getSkippedInserts(): array {
        return $this->skippedInserts;
    }

    /**
     * Leert die Liste der übersprungenen Datensätze.
     *
     * @return void
     */
    public function clearSkippedInserts(): void {
        $this->skippedInserts = [];
    }

    /**
     * Gibt die Anzahl der beim letzten Insert übersprungenen Datensätze zurück.
     *
     * @return int Anzahl der übersprungenen Einträge
     */
    public function getSkippedInsertsCount(): int {
        return count($this->skippedInserts);
    }    



    /**
     * Prüft, ob ein Datensatz mit identischen UNIQUE-Feldern bereits vorhanden ist.
     *
     * Diese Methode durchsucht die vorhandenen Datensätze (aus `$this->currentData` oder optional übergeben)
     * nach Übereinstimmungen in Feldern, die in der `system.json` als `unique: true` markiert sind.
     *
     * Wird ein solcher Datensatz gefunden, schlägt der Insert fehl (bei aktiviertem Abbruch oder stillschweigendem Skip).
     *
     * @param array $record      Der zu prüfende neue Datensatz.
     * @param array|null $searchData Optional: Datenarray, gegen das geprüft werden soll (z. B. `$this->currentData`).
     *                               Wird nichts übergeben, wird automatisch `$this->currentData` verwendet.
     *
     * @return bool `true`, wenn ein Datensatz mit denselben UNIQUE-Feldern existiert, sonst `false`.
     *
     * @author Dscho
     * @since 2025-04-19
     */
    private function recordExistsByUniqueFields(array $record, array $searchData = null): bool {
        if (!isset($this->systemConfig['fields']) || empty($record)) {
            return false;
        }

        $data = $searchData ?? $this->currentData;

        if (empty($data)) {
            return false;
        }

        foreach ($this->systemConfig['fields'] as $field => $config) {
            if (!empty($config['unique']) && isset($record[$field])) {
                foreach ($data as $row) {
                    if (isset($row[$field]) && $row[$field] === $record[$field]) {
                        return true; // Datensatz mit gleichem UNIQUE-Wert existiert
                    }
                }
            }
        }

        return false;
    }



/**
 * Fügt einen oder mehrere Datensätze in die aktuell gesetzte JSON-Tabelle ein.
 *
 * Unterstützt:
 * - Einzel- oder Mehrfacheinfügen
 * - Autoincrement, UUIDs, Hashwerte, Timestamps
 * - Verschlüsselung definierter Felder
 *
 * @param array $record Einzelner oder mehrere Datensätze
 * @throws \Exception Bei fehlender Tabelle oder Sperrproblemen
 */
public function insert(array $record): void {
    if (!$this->currentTableFile) {
        throw new \Exception("Keine Tabelle ausgewählt.");
    }

    if ($this->systemConfig === null) {
        $this->loadSystemConfig();
    }

    $this->clearSkippedInserts();    

    // Lade bestehende Daten einmalig – wichtig für Unique-Prüfung
    $this->loadTableData();
    $current = $this->currentData; // eigene Arbeitskopie, wird innerhalb der Schleife erweitert

    $fp = fopen($this->currentTableFile, 'c+');
    if (!$fp) {
        throw new \Exception("Datei konnte nicht geöffnet werden.");
    }

    try {
        if (!flock($fp, LOCK_EX)) {
            throw new \Exception("Datei konnte nicht gesperrt werden (insert).");
        }

        $content = stream_get_contents($fp);
        $data = $content ? json_decode($content, true) : [];

        $records = isset($record[0]) && is_array($record[0]) ? $record : [$record];

        foreach ($records as $rec) {
            // Prüfen gegen aktuelle + geplante Daten
            if ($this->recordExistsByUniqueFields($rec, $current)) {
                $this->skippedInserts[] = $rec;              
                continue;
            }

            $this->validateRequiredFields($rec);

            $newRecord = $this->applyAutoFields($rec);
            $finalRecord = $this->insertAdditionalFields($newRecord, $rec);

            $data[] = $finalRecord;
            $current[] = $finalRecord;          // Speicher erweitern für weitere Unique-Prüfungen
        }

        rewind($fp);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        fflush($fp);

        $this->currentData = $current;          // 🧠 am Ende final übernehmen
        $this->saveSystemConfig();

    } finally {
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}






    /**
     * Fügt einen neuen Datensatz in die aktuell gesetzte JSON-Tabelle ein.
     *
     * Diese Methode übernimmt alle systemdefinierten Automatismen gemäß system.json:
     * - Autoincrement-Felder (inkl. konfigurierbarem Startwert & Schrittweite)
     * - Automatische Timestamps für `created_at` und `updated_at`
     * - Automatisch generierte Hashwerte (`md5`, `sha1`, `sha256`)
     * - Automatisch generierte UUIDs
     * - Verschlüsselung definierter Felder
     *
     * Die Methode aktualisiert bei Bedarf auch die system.json-Datei
     * (z. B. bei Autoincrement-Zählerweiterung).
     *
     * @param array $record Der zu speichernde Datensatz als assoziatives Array.
     *
     * @throws \Exception Wenn keine Tabelle ausgewählt wurde (`use()` vergessen)
     *                    oder wenn die Datei nicht gesperrt werden konnte.
     *
     * @return void
     */    
    public function insert_old(array $record): void {
        global $debugger;

        if (!$this->currentTableFile) {
            throw new \Exception("Keine Tabelle ausgewählt.");
        }

        // Auto-Fields anwenden
        $newrecord = $this->applyAutoFields($record);

        // Alle anderen Felder, die nicht durch applyAutoFields abgedeckt sind, hinzufügen
       $finalRecord = $this->insertAdditionalFields($newrecord, $record);  

    
        // Datensatz einfügen
        $fp = fopen($this->currentTableFile, 'c+');
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $data = $content ? json_decode($content, true) : [];
            $data[] = $finalRecord;
    
            rewind($fp);
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
    
            // Geänderte system.json speichern
            $this->saveSystemConfig();
        } else {
            throw new \Exception("Datei konnte nicht gesperrt werden (insert).");
        }
    }
    





    /**
     * Setzt automatische Update-Zeitstempel gemäß system.json.
     *
     * Felder mit `"auto_modified_timestamp": true` werden automatisch
     * auf das aktuelle Datum/Zeit gesetzt – mit Format und Zeitzone aus system.json.
     *
     * @param array $record Der Datensatz, der aktualisiert werden soll.
     * @return array Der aktualisierte Datensatz mit gesetzten Auto-Feldern.
     */
    private function applyUpdateFields(array $record): array {
        if ($this->systemConfig === null || !isset($this->systemConfig['fields'])) {
            return $record;
        }

        foreach ($this->systemConfig['fields'] as $field => $config) {
            if (!empty($config['auto_modified_timestamp'])) {
                $format = $config['format'] ?? 'Y-m-d H:i:s';
                $timezone = $config['timezone'] ?? 'UTC';
                $dt = new \DateTime('now', new \DateTimeZone($timezone));
                $record[$field] = $dt->format($format);
            }
        }

        return $record;
    }






    /**
     * Aktualisiert Datensätze in der aktuell gesetzten JSON-Tabelle, basierend auf gesetzten Filtern.
     *
     * Diese Methode führt ein selektives Update durch:  
     * Nur die Datensätze, die den aktuell gesetzten Filterbedingungen entsprechen (`where()` / `filter()`),
     * werden durch die im Parameter übergebenen Werte aktualisiert.
     *
     * Zusätzlich wird – falls in der system.json definiert – das `autoupdated`-Feld (z. B. `updated_at`)
     * mit einem aktuellen Zeitstempel versehen.
     *
     * @param array $fieldsToUpdate Ein assoziatives Array der zu ändernden Felder im Format:
     *                               [ 'feldname' => 'neuerWert', ... ]
     *
     * @throws \Exception Wenn keine Tabelle ausgewählt wurde (`use()` vergessen)
     *                    oder wenn die Datei nicht gesperrt werden konnte.
     *
     * @return int Die Anzahl der erfolgreich aktualisierten Datensätze.
     */    
    public function update(array $fieldsToUpdate): int {
        if (!$this->currentTableFile) {
            throw new \Exception("Keine Tabelle ausgewählt.");
        }
    
        // system.json laden, wenn sie noch nicht geladen wurde
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        $updatedCount = 0;
    
        $fp = fopen($this->currentTableFile, 'c+');
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $data = $content ? json_decode($content, true) : [];
    
            $filteredData = $this->applyFilters($data);
            $newData = [];
    
            foreach ($data as $index => $row) {
                if (in_array($row, $filteredData, true)) {

                    $fieldsToUpdate = $this->applyUpdateFields($fieldsToUpdate);
    
                    $data[$index] = array_merge($row, $fieldsToUpdate);
                    $updatedCount++;
                }
            }
    
            // Datei zurücksetzen und die aktualisierten Daten speichern
            rewind($fp);
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            throw new \Exception("Datei konnte nicht gesperrt werden (update).");
        }
    
        return $updatedCount;
    }
    
    
    
    /**
     * Löscht alle Datensätze aus der aktuell ausgewählten Tabelle, die den gesetzten Filterbedingungen entsprechen.
     *
     * Die Methode öffnet die zugehörige JSON-Datei exklusiv, liest alle Daten,
     * wendet die gesetzten Filter an (`where()`), und entfernt alle passenden Zeilen.
     * Anschließend wird die Datei mit den verbleibenden Datensätzen überschrieben.
     *
     * **Wichtig**:
     * - Die Methode verwendet `flock()` zur Datei-Sperrung und stellt so sicher, dass keine parallelen Schreibkonflikte auftreten.
     * - Es wird ein strenger Vergleich (`in_array(..., true)`) verwendet, um exakte Übereinstimmungen zu prüfen.
     * - Die gesetzten Filter werden nach dem Löschen **nicht zurückgesetzt** – dies sollte ggf. manuell erfolgen.
     *
     * @throws \Exception Wenn keine Tabelle gesetzt ist oder die Datei nicht gesperrt werden kann.
     *
     * @return int Anzahl der erfolgreich gelöschten Datensätze.
     *
     * @example
     * ```php
     * $db->from('produkte')
     *    ->where([['vendor', '=', 'Aldi']])
     *    ->delete(); // löscht alle Produkte von "Aldi"
     * ```
     */
    public function delete(): int {
        if (!$this->currentTableFile) {
            throw new \Exception("Keine Tabelle ausgewählt.");
        }

        $deletedCount = 0;

        $fp = fopen($this->currentTableFile, 'c+');
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $data = $content ? json_decode($content, true) : [];

            // Sicherstellen, dass $filteredData ein Array ist
            $filteredData = $this->applyFilters($data);
            if (!is_array($filteredData)) {
                throw new \Exception("Filterfunktion gibt kein Array zurück.");
            }

            $newData = [];

            foreach ($data as $row) {
                if (!in_array($row, $filteredData, true)) {
                    $newData[] = $row;
                } else {
                    $deletedCount++;
                }
            }

            rewind($fp);
            ftruncate($fp, 0);
            fwrite($fp, json_encode($newData, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            throw new \Exception("Datei konnte nicht gesperrt werden (delete).");
        }

        return $deletedCount;
    }

    
    
    
    
    /**
     * Führt eine vollständige Datenabfrage durch – von Filterung bis Gruppierung.
     * 
     * Ablauf:
     * 1. Filter anwenden
     * 2. Joins anwenden
     * 3. Sortierung anwenden
     * 4. Auswahl (select) anwenden
     * 5. Limitierung anwenden
     * 6. Gruppierung anwenden (optional)
     * 7. Verschlüsselte Felder entschlüsseln
     * 
     * Danach werden die internen Query-Zustände zurückgesetzt.
     * 
     * @param array $groupByColumns Optional: Gruppierungsspalten, wenn von außen gesetzt.
     * @return array Die verarbeiteten und zurückgegebenen Datensätze.
     * 
     * @throws \Exception Wenn keine Tabelle ausgewählt wurde.
     */
    public function get(array $groupByColumns = []): array
    {
        // Sicherstellen, dass eine Tabelle gesetzt wurde
        if (!$this->currentTableName) {
            throw new \Exception("Es wurde keine Tabelle gesetzt. Bitte zuerst 'from()' aufrufen.");
        }

        // Schritt 1: Filter anwenden (z. B. WHERE-Klausel)
        $data = $this->applyFilters($this->currentData);

        // Schritt 2: Joins anwenden (nur wenn implementiert)
        $data = $this->applyJoins($data);

        // Schritt 3: Sortierung anwenden (ORDER BY)
        $data = $this->applyOrderBy($data);

        // Schritt 4: Auswahl anwenden (SELECT Spalten, ggf. mit Alias)
        $data = $this->applySelect($data);

        // Schritt 5: Limitierung anwenden (LIMIT, OFFSET)
        $data = $this->applyLimit($data);

        // Schritt 6: Gruppierung anwenden, falls angegeben oder intern gesetzt
        if (!empty($groupByColumns) || !empty($this->groupBy)) {
            // Gruppierungsspalten entweder extern übergeben oder intern gesetzt
            $groupByColumns = !empty($groupByColumns) ? $groupByColumns : $this->groupBy;
            $data = $this->applyGroupBy($data, $groupByColumns);
        }

        // Schritt 7: system.json laden (falls noch nicht geschehen)
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }

        // Schritt 8: Verschlüsselte Felder entschlüsseln (falls konfiguriert)
        if (isset($this->systemConfig['fields'])) {
            foreach ($data as &$row) {
                foreach ($row as $key => $value) {
                    if (isset($this->systemConfig['fields'][$key]['encrypt'])) {
                        try {
                            $row[$key] = $this->decryptValue((string)$value);
                        } catch (\Exception $e) {
                            $row[$key] = null; // Oder Originalwert behalten: $value
                        }
                    }
                }
            }
        }

        // Optional: Log-Eintrag bei leerem Ergebnis
        if (empty($data)) {
            error_log("Hinweis: get() liefert ein leeres Ergebnis für Tabelle '{$this->currentTableName}'");
        }

        // Aufräumen / Reset der internen Zustände für neue Abfragen
        $this->filters = [];
        $this->select = [];
        $this->orderBy = [];
        $this->limit = 0;
        $this->offset = 0;
        $this->selectCalled = false;
        $this->groupBy = [];
        $this->selectCalled = false;        

        // Final: Rückgabe der verarbeiteten Daten
        return $data;
    }

    
 
    /**
     * Gibt die ID des zuletzt eingefügten Datensatzes zurück.
     *
     * Diese Methode kann nach einem erfolgreichen `insert()`-Aufruf verwendet werden,
     * um die automatisch vergebene ID (z. B. durch `autoincrement`) des zuletzt
     * eingefügten Eintrags abzurufen.
     *
     * @return int|null Die letzte eingefügte ID oder `null`, falls keine gesetzt wurde.
     */
    public function getLastInsertId(): ?int {
        return $this->lastInsertId;
    }



    /**
     * Prüft, ob ein Datensatz zur aktuellen Abfrage existiert.
     *
     * Gibt `true` zurück, wenn mindestens ein Treffer vorhanden ist.
     *
     * Beispiel:
     * ---------
     * if ($db->from('users')->where('email', '=', 'test@example.com')->exists()) {
     *   echo "Benutzer existiert.";
     * }
     *
     * @return bool
     */
    public function exists(): bool {
        $results = $this->limit(1)->get(); // Schnellste Variante: nur 1 Datensatz laden
        return !empty($results);
    }



    /**
     * Gibt den ersten passenden Datensatz der aktuellen Abfrage zurück.
     *
     * Beispiel:
     * ---------
     * $user = $db->from('users')
     *            ->where('email', '=', 'alice@example.com')
     *            ->first();
     *
     * @return array|null Der erste Datensatz oder null, wenn nichts gefunden wurde.
     */
    public function first(): ?array {
        $results = $this->limit(1)->get();
        return $results[0] ?? null;
    }



/**
 * Gibt den Wert eines bestimmten Feldes zurück.
 *
 * @param string $column    Feldname, der ausgegeben werden soll
 * @param bool   $all       Wenn true, gibt ein Array aller Werte zurück (Standard: false)
 *
 * @return mixed            Einzelwert oder Array von Werten
 */
public function pluck(string $column, bool $all = false) {
    $this->select($column);

    $results = $this->get();

    if ($all) {
        return array_map(fn($row) => $row[$column] ?? null, $results);
    }

    return $results[0][$column] ?? null;
}





}    
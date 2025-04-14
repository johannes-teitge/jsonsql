<?php
namespace Src\JsonSQL;

trait JS_Helper
{


    public function distinct___(string $column): array {
        $data = $this->applyFilters($this->currentData);
        $values = array_column($data, $column);
        return array_values(array_unique($values));
    }     
    
    /**
     * UUID Generator (falls nicht vorhanden)
     */
    private function generateUuid(): string {
        return bin2hex(random_bytes(16));
    }



    // Generiere eine zufällige Ganzzahl im Bereich [min, max]
    public static function generateRandomInt(int $min = 1, int $max = 1000): int
    {
        return random_int($min, $max);
    }

    // Generiere eine zufällige Gleitkommazahl im Bereich [min, max]
    public static function generateRandomFloat(float $min = 0.1, float $max = 1000.0): float
    {
        return mt_rand() / mt_getrandmax() * ($max - $min) + $min;
    }    

    /**
     * Generiert einen zufälligen String der angegebenen Länge.
     * 
     * @param int $length Die Länge des generierten zufälligen Strings.
     * @return string Der zufällige String.
     */
    public static function generateRandomString(int $length = 32): string
    {
        // Erlaubte Zeichen: Zahlen, Groß- und Kleinbuchstaben sowie Sonderzeichen
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+[]{}|;:,.<>?/~';
        
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            // Zufälliges Zeichen aus der Zeichenkette auswählen
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
    
        return $randomString;
    }    


/**
 * Generiert einen Hash-Wert mit einem angegebenen Algorithmus und prüft die Länge.
 *
 * @param string $value Der Wert, der gehasht werden soll.
 * @param string $algorithm Der Hash-Algorithmus (z.B. 'sha256', 'md5', 'sha1', 'sha512').
 * @param int $maxLength Die maximale Länge des Hashes.
 * 
 * @return string Der generierte Hash.
 * 
 * @throws \Exception Wenn der Hash-Algorithmus ungültig ist.
 */
public static function generateHash(string $value, string $algorithm = 'sha256', int $maxLength = PHP_INT_MAX): string
{
    // Hash-Generierung
    switch (strtolower($algorithm)) {
        case 'md5':
            $hash = md5($value);
            break;
        case 'sha1':
            $hash = sha1($value);
            break;
        case 'sha256':
            $hash = hash('sha256', $value);
            break;
        case 'sha512':
            $hash = hash('sha512', $value);
            break;
        default:
            throw new \Exception("Ungültiger Hash-Algorithmus:  >>> '$algorithm' <<< . Gültige Optionen: md5, sha1, sha256, sha512.");
    }

    // Wenn eine maximale Länge definiert ist, kürze den Hash
    if ($maxLength < PHP_INT_MAX && strlen($hash) > $maxLength) {
        $hash = substr($hash, 0, $maxLength);
    }

    return $hash;
}



    public function getEnumValues(string $fieldName): array {
        // Sicherstellen, dass systemConfig geladen ist
        if ($this->systemConfig === null) {
            $this->loadSystemConfig();
        }
    
        // Überprüfen, ob das Feld existiert
        if (isset($this->systemConfig['fields'][$fieldName]) && 
            isset($this->systemConfig['fields'][$fieldName]['enumValues'])) {
            
            // Enum-Werte aus der Systemdefinition holen
            $enumValues = $this->systemConfig['fields'][$fieldName]['enumValues'];
            
            // Umwandeln von Komma-getrennten Werten in ein Array
            return explode(',', $enumValues);
        }
    
        // Rückgabe von leeren Array, wenn kein Enum-Wert gefunden wurde
        return [];
    }



}
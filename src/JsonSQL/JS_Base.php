<?php

namespace Src\JsonSQL;



class JS_Base {
    protected string $jsonSQLVersion = '1.0.6'; 
    protected string $jsonSQLVersionDate = '2025-04-21';     
    protected array $databases = [];
    protected ?string $currentDbPath = null;
    protected ?string $currentTableFile = null;
    protected array $currentData = [];
    protected bool $tableLoaded = false;
    protected array $filters = [];
    protected array $select = [];
    protected array $orderBy = [];
    protected string $mergeCondition = 'OR';
    protected array $joinedTables = []; // für gespeicherte gejointe Tabellen
    protected int $limit = 0;
    protected int $offset = 0;    
    protected ?\Closure $having = null;    
    protected ?array $systemConfig = null;   
    protected ?string $encryptionKey = null;    
    protected ?int $lastInsertId = null;   
    protected array $aliasMap = [];
    protected ?array $groupBy = null; 
    protected $selectCalled = false; // Flag für select-Aufruf    
    
    protected ?string $currentTableName = null;    
    protected ?array $tableInfo = null;  // Die Tabelle-Info wird hier gespeichert  
    
    // Transaktions-Unterstützung, added 08-04-2025
    protected bool $isTransaction = false;
    protected array $transactionBuffer = [];    
    protected ?array $lastError = null;  // Fehlerbehandlung    
    protected ?array $lastMessage = null;  // Fehlerbehandlung          

    // ============================================================================
    // 🔧 Variablen-Platzhalter für erlaubte Datentypen und Feldoptionen
    // ============================================================================
    // Diese Variablen werden im Trait JS_System befüllt und zentral verwaltet.
    // Sie dienen der Validierung von system.json-Feldern und stehen allen Modulen zur Verfügung.
    //
    // added: 2025-04-18 by Dscho
    // ============================================================================
    protected static array $allowedDataTypes = [];         // Wird in JS_System gesetzt
    protected static array $allowedFieldProperties = [];   // Wird in JS_System gesetzt

    // ============================================================================
    // 📌 Übersprungene Inserts (z. B. wegen UNIQUE)
    // ============================================================================
    // Diese Variable sammelt alle Datensätze, die beim Insert-Vorgang übersprungen
    // wurden – etwa weil sie gegen ein UNIQUE-Feld verstoßen haben.
    // Kann später ausgewertet oder im UI angezeigt werden.
    //
    // added: 2025-04-19 by Dscho
    // ============================================================================
    protected array $skippedInserts = [];




    public function __construct(array $databases) {
        $this->databases = [];
        $this->initSystemDefaults();
    
        foreach ($databases as $alias => $path) {
            $cleanPath = rtrim($path, DIRECTORY_SEPARATOR);
    
            // Automatisch Ordner anlegen, wenn nicht vorhanden
            if (!is_dir($cleanPath)) {
                if (!mkdir($cleanPath, 0777, true)) {
                    throw new \Exception("❌ Verzeichnis für Alias '$alias' konnte nicht erstellt werden: $cleanPath");
                }
            }
    
            $this->databases[$alias] = $cleanPath;
        }
    }

    public function clearLastError(): void {
        $this->lastError = null;  // Setzt das Fehler-Array zurück
    } 

    // Getter für lastError
    public function getLastError(): ?array {
        return $this->lastError;
    }

    
    public function setLastError(string $function, string $errorMessage): void {
        $this->lastError = [
            'function' => $function,
            'errorMessage' => $errorMessage
        ];
    }

    // Setzt die letzte Erfolgsmeldung
    public function setLastMessage(string $action, string $message, array $values = []): void {
        $this->lastMessage = [
            'action' => $action,  // 'create' oder 'update'
            'message' => $message,
            'values' => $values  // Optional, kann leer bleiben
        ];
    }

    // Gibt die letzte Erfolgsmeldung zurück
    public function getLastMessage(): ?array {
        return $this->lastMessage;
    }

    // Setzt die Erfolgsmeldung zurück
    public function clearLastMessage(): void {
        $this->lastMessage = null;  // Setzt die Erfolgsmeldung zurück
    }

        
    
   
}

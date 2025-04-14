<?php
namespace Src\JsonSQL;

trait JS_Export
{


    public function exportTable(string $tableName): array {
        $this->setTable($tableName, true);
        $this->loadSystemConfig();
    
        $data = json_decode(file_get_contents($this->currentTableFile), true);
    
        return [
            'table'         => $tableName,
            'folder'        => $this->currentDbPath,
            'filename'      => $this->currentTableFile,          
            'system'        => $this->systemConfig,
            'data'          => $data,
            'count'         => count($data),
            'last_modified' => file_exists($this->currentTableFile)
                ? date('Y-m-d H:i:s', filemtime($this->currentTableFile))
                : null,
        ];
    }
    

    
    public function exportDatabase(): array {
        if (!$this->currentDbPath) {
            throw new \Exception("Keine Datenbank ausgewählt.");
        }
    
        $tables = $this->listTables();
        $export = ['database' => basename($this->currentDbPath), 'tables' => []];
    
        foreach ($tables as $table) {
            if (str_ends_with($table, '.system')) continue; // überspringe *.system.json
    
            $this->setTable($table);
            $this->loadSystemConfig();
    
            $export['tables'][$table] = [
                'system' => $this->systemConfig,
                'data' => json_decode(file_get_contents($this->currentTableFile), true),
            ];
        }
    
        return $export;
    }
    



}    
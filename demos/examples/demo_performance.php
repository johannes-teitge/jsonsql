<?php
$pageTitle = "JsonSQL Performance Test";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;


// Datenbank und Tabelle definieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']); // Pfad zur JSON-Datenbank
$db->use('demo'); // Datenbank verwenden

// Die Tabelle 'stresstest' mit 1000 Datensätzen füllen
$table = 'stresstest';

$db->truncate($table); // Tabelle leeren


// Daten generieren und einfügen
for ($i = 1; $i <= 1000; $i++) {
    $data = [
        'id' => $i,
        'timestamp' => date('Y-m-d H:i:s', strtotime("+$i seconds")),
        'response_time' => rand(10, 500), // Zufällige Antwortzeit zwischen 10 und 500 ms
        'status' => $i % 2 === 0 ? 'success' : 'error', // Zufälliger Status (success oder error)
        'message' => $i % 2 === 0 ? 'Test successful' : 'Test failed', // Zufällige Nachricht
    ];

    // Einfügen der Daten in die Tabelle 'stresstest' (Korrekt: nur das Daten-Array übergeben)
    $db->insert($data); // Nur die Daten (ohne den Tabellennamen) übergeben
}
?>

<div class="container">
    <h1>JsonSQL Performance Test 🚀</h1>

    <p>Simuliere die Anzahl von Anfragen pro Sekunde und teste die Leistung von JsonSQL.</p>

    <div class="mb-3">
        <label for="requestsPerSecond" class="form-label">Anfragen pro Sekunde für 10 Sekunden.</label>
        <input type="number" id="requestsPerSecond" class="form-control" value="10" min="1" max="1000">
    </div>

    <button id="startTest" class="btn btn-primary">Performance Test starten</button>

    <!-- Performance-Statistiken Output -->
    <div id="performanceStats" class="mt-4">
        <h3>Performance-Statistiken:</h3>
        <!-- Hier werden die Statistiken live aktualisiert -->
    </div>

    <div class="mt-4">
        <h3>Ergebnisse:</h3>
        <pre id="testResults" style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;"></pre>
    </div>

    <!-- Diese Nachrichten erscheinen nur einmal -->
    <p id="statusMessage" style="text-align: center;"></p>
</div>

<script>
// Variablen für Performance-Tracking
let successCount = 0;
let errorCount = 0;
let totalResponseTime = 0; // Zum Berechnen der Gesamtantwortzeit
let requestCount = 0; // Zum Zählen der gesamten Anfragen

// Funktion zum Senden einer Testanfrage
async function sendRequest(packageNumber, requestNumber) {
    const startTime = performance.now(); // Startzeit der Anfrage messen

    try {
        const response = await fetch('stresstest.php'); // Die PHP-Datei, die die Anfrage verarbeitet
        const data = await response.json();  // Hier nehmen wir die Antwort als JSON

        // Prüfen, ob die Antwort erfolgreich war
        if (!data || !data.status) {
            console.error('Keine gültigen Daten erhalten:', data);
            logTestResult({status: 'error', error: 'Ungültige Antwort'}, packageNumber, requestNumber, 'error', 0);
            return;
        }

        // Berechnung der Antwortzeit
        const endTime = performance.now();
        const responseTime = endTime - startTime;

        // Erfolgreiche Antwort loggen und in die Ergebnisse einfügen
        console.log(`Paket ${packageNumber}, Anfrage ${requestNumber}:`, data);

        // Antwortzeit zur Gesamtsumme hinzufügen und Erfolgszähler erhöhen
        totalResponseTime += responseTime;
        requestCount++;

        // Erfolgreiche Anfrage loggen
        logTestResult(data, packageNumber, requestNumber, 'success', responseTime);
    } catch (error) {
        console.error('Fehler bei der Anfrage', error);

        // Fehlerhafte Anfrage loggen
        logTestResult({status: 'error', error: error.message}, packageNumber, requestNumber, 'error', 0);
    }
}

// Funktion, um das Testergebnis live zu loggen
function logTestResult(result, packageNumber, requestNumber, status, responseTime) {
    const testResults = document.getElementById('testResults');
    
    // Erstellen eines neuen Ergebnis-Elements
    const resultElement = document.createElement('div');
    
    if (status === 'error') {
        resultElement.textContent = `Fehler bei der Anfrage: ${result.error} (Paket ${packageNumber}, Anfrage ${requestNumber})`;
        resultElement.style.color = 'red';
        errorCount++;
    } else {
        resultElement.textContent = `Erfolgreiche Antwort: ${result.timestamp} (Paket ${packageNumber}, Anfrage ${requestNumber}) - Antwortzeit: ${responseTime.toFixed(2)}ms`;
        resultElement.style.color = 'green';
        successCount++;
    }

    // Füge das Ergebnis dem "testResults"-Element hinzu
    testResults.appendChild(resultElement);

    // Leerzeile nach jedem Paket hinzufügen
    if (requestNumber >= 10) {
        const blankLine = document.createElement('div');
        blankLine.style.height = '10px'; // Leere Zeile, um die Pakete visuell zu trennen
        testResults.appendChild(blankLine);
    }

    // Automatisches Scrollen nach unten, wenn das Fenster die maximale Anzahl von Zeilen überschreitet
    if (testResults.scrollHeight > testResults.clientHeight) {
        testResults.scrollTop = testResults.scrollHeight;
    }

    // Aktualisiere die Performance-Statistiken
    updatePerformanceStats();
}

// Funktion zur Berechnung und Anzeige der Performance-Statistiken
function updatePerformanceStats() {
    const avgResponseTime = totalResponseTime / requestCount; // Durchschnittliche Antwortzeit
    const requestsPerSec = requestCount / 10; // Anfragen pro Sekunde

    // Ergebnisse im Performance-Output anzeigen
    document.getElementById('performanceStats').innerHTML = `
        <strong>Performance-Statistiken:</strong><br>
        Erfolgreiche Anfragen: ${successCount} <br>
        Fehlerhafte Anfragen: ${errorCount} <br>
        Durchschnittliche Antwortzeit: ${avgResponseTime.toFixed(2)} ms <br>
        Anfragen pro Sekunde: ${requestsPerSec.toFixed(2)} <br>
    `;
}

// Funktion zum Starten des Performance-Tests
document.getElementById('startTest').addEventListener('click', function() {
    const requestsPerSecond = document.getElementById('requestsPerSecond').value;
    const interval = 1000 / requestsPerSecond; // Zeit zwischen den Anfragen in ms

    let packageNumber = 1; // Zähler für Pakete
    let requestNumber = 1; // Zähler für Anfragen im aktuellen Paket

    document.getElementById('statusMessage').textContent = 'Test läuft...'; // Test läuft Nachricht anzeigen
    document.getElementById('testResults').textContent = ''; // Leeren Bereich für Ergebnisse
    document.getElementById('performanceStats').textContent = ''; // Leeren Bereich für Performance-Statistiken

    // Setze eine Schleife, die alle X ms die Anfragen abfeuert
    const testInterval = setInterval(async function() {
        await sendRequest(packageNumber, requestNumber);

        // Wenn die maximale Anzahl von Anfragen im aktuellen Paket erreicht ist
        if (requestNumber >= 10) {
            packageNumber++; // Neues Paket beginnen
            requestNumber = 1; // Anfragen-Zähler zurücksetzen
        } else {
            requestNumber++; // Anfrage-Zähler erhöhen
        }
    }, interval);

    // Stoppe den Test nach 10 Sekunden
    setTimeout(function() {
        clearInterval(testInterval);
        document.getElementById('statusMessage').textContent = 'Test abgeschlossen.'; // Test abgeschlossen Nachricht anzeigen
        
        // Ergebnisse am Ende des Tests in das Performance-Statistik-Feld verschieben
        const summary = document.createElement('div');
        summary.innerHTML = `
            <strong>Test abgeschlossen!</strong><br>
            Erfolgreiche Anfragen: ${successCount} <br>
            Fehlerhafte Anfragen: ${errorCount} <br>
            Durchschnittliche Antwortzeit: ${(totalResponseTime / successCount).toFixed(2)} ms
            <br> Anfragen pro Sekunde: ${requestsPerSecond}
        `;
        document.getElementById('performanceStats').appendChild(summary);
    }, 10000); // Test läuft 10 Sekunden
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; // Footer einbinden ?>

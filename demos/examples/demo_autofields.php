<?php
$pageTitle = "JsonSQL AutoFields Demo: Farbverläufe";

$JsonSQLpath = __DIR__ . '/../../src/JsonSQL.php';
if (!file_exists($JsonSQLpath)) {
    die("❌ Datei nicht gefunden!");
}
require_once $JsonSQLpath;

require_once __DIR__ . '/../includes/header.php';

use Src\JsonSQL;



// Berechnung der Helligkeit der Farben, um den Text anzupassen (Helligkeit basierend auf dem Farbwert)
function getLuminance($hexColor) {
    $hexColor = str_replace('#', '', $hexColor);
    $r = hexdec(substr($hexColor, 0, 2));
    $g = hexdec(substr($hexColor, 2, 2));
    $b = hexdec(substr($hexColor, 4, 2));

    // Berechnet die Helligkeit (Luminanz)
    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

// Datenbank und Tabelle definieren
$db = new JsonSQL(['demo' => __DIR__ . '/../testdb']);
$db->use('demo');
$farbverlaufTabelle = 'farbverlaeufe';



// 1. Tabelle überprüfen und ggf. anlegen
$db->setTable($farbverlaufTabelle);

// Prüfen, ob die Tabelle existiert und ob sie Daten enthält
if (!$db->tableExists($farbverlaufTabelle) || $db->getRecordCount($farbverlaufTabelle) === 0) {
    $db->truncate($farbverlaufTabelle);

    // Autoincrement für 'id' setzen, wenn nicht bereits gesetzt
    if (!$db->isAutoincrementField('id')) {
        $db->addAutoincrementField('id', 1);
        echo "<div class='alert_ alert-info'>⚙️ Autoincrement für 'id' wurde gesetzt (Startwert 1).</div>";
    } else {
        echo "<div class='alert_ alert-success'>✅ Autoincrement für 'id' ist gesetzt</div>";
    }

    // "created_at" und "updated_at" Felder setzen, falls nicht bereits gesetzt
    if (!$db->isCreatedAtField('created_at')) {
        $db->addCreatedAtField('created_at');
        echo "<div class='alert_ alert-info'>⚙️ 'created_at' Feld wurde gesetzt.</div>";
    } else {
        echo "<div class='alert_ alert-success'>✅ 'created_at' Feld ist gesetzt.</div>";
    }

    if (!$db->isUpdatedAtField('modified_at')) {
        $db->addUpdatedAtField('modified_at');
        echo "<div class='alert_ alert-info'>⚙️ 'modified_at' Feld wurde gesetzt.</div>";
    } else {
        echo "<div class='alert_ alert-success'>✅ 'modified_at' Feld ist gesetzt.</div>";
    }

}

// Farbverläufe anzeigen
$verlaeufe = $db->from($farbverlaufTabelle)->get();

?>

<style>

.list-group {
    margin-top: 30px;
}

.list-group-item.farbverlauf-container {
    position: relative;
    cursor: pointer;


    border-radius: 12px;
    border: 2px solid rgb(232, 232, 232);

    transition: all 0.3s ease-in-out;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);  
    margin-bottom: 5px;  
    min-height: 80px; /* oder z. B. 120px – je nach Geschmack */
       

}

.list-group-item.farbverlauf-container:hover {
    border: 2px solid rgba(101, 101, 101, 0.37);
    transition: all 0.3s ease-in-out;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);      
}    

.edit-hint {
    position: absolute;
    top: 5px;
    right: 10px;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 4px;
    display: none;
}

.farbverlauf-container:hover .edit-hint {
    display: inline-block;
}
</style>

<h3 class='mt-5'>🌈 Farbverläufe:</h3>

<p>
    In dieser Demo zeigen wir, wie man mit <strong>JsonSQL</strong> Tabellen mit automatischen Feldern wie 
    <strong>Autoincrement</strong>, <strong>AutoCreated</strong> und <strong>AutoUpdated</strong> erstellt und bearbeitet.
</p>
<p>
    Du kannst hier Farbverläufe erstellen, bearbeiten und löschen. Jeder Farbverlauf hat automatisch generierte Felder:
    <ul>
        <li><strong>Autoincrement</strong>: Automatisch steigende ID-Werte</li>
        <li><strong>AutoCreated</strong>: Das Erstellungsdatum des Farbverlaufs</li>
        <li><strong>AutoUpdated</strong>: Das Datum der letzten Bearbeitung des Farbverlaufs</li>
    </ul>
</p>
<p>
    Alle Operationen (Erstellen, Bearbeiten, Löschen) werden mit <strong>Ajax</strong> durchgeführt, sodass keine Seitenaktualisierung notwendig ist.
    Die Änderungen werden direkt im Hintergrund gespeichert und reflektieren sofort in der UI.
</p>
<p>
    <strong>Probiere es aus!</strong> Klicke unten auf "Neuen Farbverlauf erstellen", um ein Beispiel zu sehen und die CRUD-Funktionen zu testen.
</p>


<div class="row">
<!-- Button zum Erstellen eines neuen Farbverlaufs -->
<div class="col-6">
<button class="btn btn-success mb-3" onclick="createNewField()">Neuen Farbverlauf erstellen</button>
</div>

<!-- Formular für den neuen Farbverlauf (initial unsichtbar) -->
<div id="newFarbverlaufForm" class="card p-3" style="display: none; width: 60%; float: left;">
    <h5>Neuen Farbverlauf hinzufügen</h5>
    <div class="mb-3">
        <label for="newTitle" class="form-label">Titel</label>
        <input type="text" id="newTitle" placeholder="Titel" class="form-control">
    </div>

    <!-- Farbauswahl nebeneinander -->
    <div class="row mb-3">
        <div class="col-2">
            <label for="newColor1" class="form-label">Farbe 1</label>
            <input type="color" id="newColor1" class="form-control form-control-color" value="#000000">
        </div>
        <div class="col-2">
            <label for="newColor2" class="form-label">Farbe 2</label>
            <input type="color" id="newColor2" class="form-control form-control-color" value="#ffffff">
        </div>
    </div>

    <div class="d-flex justify-content-between col-4">
        <button class="btn btn-primary" onclick="saveNewField()">Speichern</button>
        <button class="btn btn-secondary" onclick="cancelNewField()">Abbrechen</button>
    </div>
</div>
</row>


<div class="list-group">
    <?php
    foreach ($verlaeufe as $verlauf) {
        $color1 = $verlauf['color1'];
        $color2 = $verlauf['color2'];
        $id = $verlauf['id'];  // ID für spätere Bearbeitung
        $createdAt = $verlauf['created_at']; // Datum der Erstellung
        $modifiedAt = $verlauf['modified_at']; // Datum der letzten Änderung

        $luminance1 = getLuminance($color1);
        $luminance2 = getLuminance($color2);

        // Berechnet die durchschnittliche Helligkeit des Farbverlaufs
        $averageLuminance = ($luminance1 + $luminance2) / 2;

        // Wenn die Helligkeit dunkel ist, ändern wir die Textfarbe auf weiß, sonst auf schwarz
        $textColor = $averageLuminance < 128 ? '#FFFFFF' : '#000000';



        echo "<div id='list-item-$id' class='list-group-item farbverlauf-container' style='background: linear-gradient(to right, $color1, $color2); padding: 10px;' onclick='handleClick(event, $id, \"$verlauf[title]\", \"$color1\", \"$color2\")'>";

        echo "<strong class='gradient_title' style='color: $textColor;' id='title-$id'>{$verlauf['title']}</strong>";
        echo "<span class='edit-hint'>Klicken, um zu bearbeiten…</span>";
        
        echo "</div>";
        
        echo "<div class='iteminfo' id='iteminfo-$id'>";
        echo "<small>";
        echo "<i class='bi bi-key' style='color: #6c757d;'></i> ID: $id &nbsp; | &nbsp;";
        echo "<i class='bi bi-calendar-plus' style='color:rgb(198, 25, 25);'></i> Erstellt am: $createdAt &nbsp; | &nbsp;";
        echo "<i class='bi bi-calendar-check' style='color:rgb(81, 146, 0);'></i> Geändert am: $modifiedAt &nbsp; | &nbsp;";
        echo "<i class='bi bi-palette' style='color:#6c757d;'></i> Farben: ";
        echo "<span id='color1-$id' style='color: $color1;'>$color1</span>, ";
        echo "<span id='color2-$id' style='color: $color2;'>$color2</span>";
        echo "</small>";
        echo "</div>";
        


/*

        echo "<div id='list-item-$id' class='list-group-item' style='background: linear-gradient(to right, $color1, $color2); padding: 10px;'>";

        // Nur den Titel anzeigen
        echo "<strong style='color: $textColor;' id='title-$id'>{$verlauf['title']}</strong><br>";

        // Button für das Bearbeiten
        echo " <button class='btn btn-primary btn-sm float-end' onclick='editField($id, \"$verlauf[title]\", \"$color1\", \"$color2\")'>Bearbeiten</button>";

        echo "</div>";

        // Farbwerte in der iteminfo anzeigen
        echo "<div class='iteminfo' id='iteminfo-$id'>";
        echo "<small>";
        echo "<i class='bi bi-key' style='color: #6c757d;'></i> ID: $id &nbsp; | &nbsp;";
        echo "<i class='bi bi-calendar-plus' style='color:rgb(198, 25, 25);'></i> Erstellt am: $createdAt &nbsp; | &nbsp;";
        echo "<i class='bi bi-calendar-check' style='color:rgb(81, 146, 0);'></i> Geändert am: $modifiedAt &nbsp; | &nbsp;";
        echo "<i class='bi bi-palette' style='color:#6c757d;'></i> Farben: ";
        echo "<span id='color1-$id' style='color: $color1;'>$color1</span>, ";
        echo "<span id='color2-$id' style='color: $color2;'>$color2</span>";
        echo "</small>";
        echo "</div>";
*/


    }
    ?>
</div>


<style>
    .snackbar {
        visibility: hidden;
        min-width: 250px;
        margin-left: -125px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        left: 50%;
        bottom: 30px;
        font-size: 17px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: visibility 0.5s, opacity 0.5s linear;
    }

    .snackbar.show {
        visibility: visible;
        opacity: 1;
    }

    .iteminfo {
        margin-bottom: 10px;
        padding-left: 10px;
        font-size: 14px;
        color: #6c757d;
    }

    .iteminfo i {
        margin-right: 5px;
    }

    .iteminfo small {
        display: flex;
        align-items: center;
    }
</style>

<script>

function handleClick(event, id, title, color1, color2) {
    const tag = event.target.tagName.toLowerCase();
    if (['button', 'input', 'label', 'svg', 'path'].includes(tag)) {
        return; // Keine Bearbeitung auslösen, wenn ein Button oder Feld angeklickt wurde
    }

    editField(id, title, color1, color2);
}


// Funktion zum Bearbeiten des Titels, der Farben und Löschen
function editField(id, currentTitle) {
    var titleElement = document.getElementById('title-' + id);

    var currentButton = null; // Wir brauchen keinen Button mehr verstecken    

    // Hole die aktuellen Farben aus den span-Tags
    var currentColor1 = document.getElementById('color1-' + id).innerText;
    var currentColor2 = document.getElementById('color2-' + id).innerText;

    // Erstelle ein Container div für Titel und Farbauswahl
    var editContainer = document.createElement('div');
    editContainer.classList.add('mb-3');
    editContainer.classList.add('editform');    

    // Titel Input
    var titleWrapper = document.createElement('div');
    titleWrapper.classList.add('mb-2');
    titleWrapper.classList.add('titleWrapper');  

    // Titel Label
    var titleLabel = document.createElement('label');
    titleLabel.classList.add('form-label');
    titleLabel.innerText = 'Titel:';
    titleWrapper.appendChild(titleLabel);

    var titleInput = document.createElement('input');
    titleInput.value = currentTitle; // Setze den aktuellen Titel
    titleInput.classList.add('form-control');
    titleInput.classList.add('titleInput');    
    titleInput.placeholder = 'Titel bearbeiten';
    titleWrapper.appendChild(titleInput);
    editContainer.appendChild(titleWrapper);

    // Farb-Input für Farbe 1
    var color1Wrapper = document.createElement('div');
    color1Wrapper.classList.add('mb-2');

    // Farbe 1 Label
    var color1Label = document.createElement('label');
    color1Label.classList.add('form-label');
    color1Label.innerText = 'Farbe 1:';
    color1Wrapper.appendChild(color1Label);

    var color1Input = document.createElement('input');
    color1Input.value = currentColor1; // Setze den aktuellen Farbwert für Farbe 1
    color1Input.type = 'color';
    color1Input.classList.add('form-control');
    color1Wrapper.appendChild(color1Input);
    editContainer.appendChild(color1Wrapper);

    // Farb-Input für Farbe 2
    var color2Wrapper = document.createElement('div');
    color2Wrapper.classList.add('mb-2');

    // Farbe 2 Label
    var color2Label = document.createElement('label');
    color2Label.classList.add('form-label');
    color2Label.innerText = 'Farbe 2:';
    color2Wrapper.appendChild(color2Label);

    var color2Input = document.createElement('input');
    color2Input.value = currentColor2; // Setze den aktuellen Farbwert für Farbe 2
    color2Input.type = 'color';
    color2Input.classList.add('form-control');
    color2Wrapper.appendChild(color2Input);
    editContainer.appendChild(color2Wrapper);

    // Ersetze den Titel mit den Input-Feldern
    titleElement.innerHTML = '';
    titleElement.appendChild(editContainer);


    // Zeige die "Speichern", "Abbrechen" und "Löschen" Buttons an
    var saveButton = document.createElement('button');
    saveButton.classList.add('btn', 'btn-success', 'btn-sm', 'float-end', 'me-2');
    saveButton.innerText = 'Speichern';
    saveButton.onclick = function() {
        var newTitle = titleInput.value;
        var newColor1 = color1Input.value;
        var newColor2 = color2Input.value;

        // Update den Titel im HTML
        titleElement.innerHTML = newTitle;

        // Update den Farbverlauf im Hintergrund
        var listItem = document.getElementById('list-item-' + id);
        listItem.style.background = 'linear-gradient(to right, ' + newColor1 + ', ' + newColor2 + ')';

        // Update die Farbwerte für die Anzeige
        color1Wrapper.innerHTML = `Farbe 1: ${newColor1}`;
        color2Wrapper.innerHTML = `Farbe 2: ${newColor2}`;



        // Speichern der Änderungen
        saveUpdateFields(id, newTitle, newColor1, newColor2);
    };

    // Abbrechen-Button
    var cancelButton = document.createElement('button');
    cancelButton.classList.add('btn', 'btn-secondary', 'btn-sm', 'float-end');
    cancelButton.innerText = 'Abbrechen';
    cancelButton.onclick = function() {
        // Setze die Eingabefelder auf die ursprünglichen Werte zurück
        titleElement.innerHTML = currentTitle;
        color1Wrapper.innerHTML = `Farbe 1: ${currentColor1}`;
        color2Wrapper.innerHTML = `Farbe 2: ${currentColor2}`;

    };

    // Löschen-Button
    var deleteButton = document.createElement('button');
    deleteButton.classList.add('btn', 'btn-danger', 'btn-sm', 'float-end', 'ms-2');
    deleteButton.innerText = 'Löschen';
    deleteButton.onclick = function() {
        // Lösche den Farbverlauf aus der UI
        deleteField(id);
    };

    // Füge die Buttons hinzu
    titleElement.appendChild(saveButton);
    titleElement.appendChild(cancelButton);
    titleElement.appendChild(deleteButton);
}




// Berechnung der Luminanz (Helligkeit) eines Farbwerts
function getLuminance(hexColor) {
    hexColor = hexColor.replace('#', '');
    var r = parseInt(hexColor.substr(0, 2), 16);
    var g = parseInt(hexColor.substr(2, 2), 16);
    var b = parseInt(hexColor.substr(4, 2), 16);

    // Berechnet die Helligkeit (Luminanz)
    return 0.2126 * r + 0.7152 * g + 0.0722 * b;
}


// Funktion zum Speichern der Änderungen im Backend
function saveUpdateFields(id, newTitle, newColor1, newColor2) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "demo_autofields_save.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    var data = {
        id: id,
        title: newTitle,
        color1: newColor1,
        color2: newColor2
    };

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    showSnackbar("✅ Die Änderungen wurden erfolgreich gespeichert!");

                    // Farben & Text aktualisieren
                    updateTextColor(id, newColor1, newColor2);
                    updateColorInfo(id, newColor1, newColor2);
                } else {
                    const msg = response.message || "Unbekannter Fehler";
                    showSnackbar("❌ Fehler: " + msg);
                    console.warn("Antwort mit Fehler:", response);
                }
            } catch (e) {
                console.error("Fehler beim Parsen der Serverantwort", e);
                console.log("Rohantwort:", xhr.responseText);
                showSnackbar("❌ Ungültige Serverantwort (kein JSON)");
            }
        } else {
            showSnackbar("❌ Serverfehler: HTTP-Status " + xhr.status);
            console.error("XHR-Fehler:", xhr);
        }
    };

    xhr.onerror = function () {
        showSnackbar("❌ Netzwerkfehler beim Speichern");
        console.error("XHR-Netzwerkfehler:", xhr);
    };

    xhr.send(JSON.stringify(data));
}



// Funktion zum Aktualisieren der Farbwerte in der iteminfo
// Funktion zum Aktualisieren der Farbwerte in der iteminfo
function updateColorInfo(id, newColor1, newColor2) {
    // Finde das iteminfo-Element anhand der ID
    var itemInfo = document.getElementById('iteminfo-' + id);
    console.log("itemInfo:", itemInfo);  // Logge itemInfo für Debugging
    
    // Finde die Farb-Spans mit den neuen IDs
    var color1Span = document.getElementById('color1-' + id);
    var color2Span = document.getElementById('color2-' + id);
    console.log("color1Span:", color1Span);  // Logge color1Span
    console.log("color2Span:", color2Span);  // Logge color2Span

    // Überprüfen, ob die Elemente gefunden wurden
    if (!color1Span || !color2Span) {
        alert("Fehler: Farb-Spans nicht gefunden!");
        return;
    }

    // Logge die neuen Farbwerte
    console.log("Neuer Farbwert 1:", newColor1);
    console.log("Neuer Farbwert 2:", newColor2);
    
    // Aktualisiere die Textinhalte der Farb-Spans
    color1Span.innerText = newColor1;
    color2Span.innerText = newColor2;

    // Passen die Farben direkt an die Anzeige an
    color1Span.style.color = newColor1;
    color2Span.style.color = newColor2;

    // Bestätigungs-Alert
//    alert("Farben wurden aktualisiert: " + newColor1 + " und " + newColor2);
}





function updateTextColor(id, newColor1, newColor2) {
    // Berechne die Luminanz der Farben
    var luminance1 = getLuminance(newColor1);
    var luminance2 = getLuminance(newColor2);

    // Berechne die durchschnittliche Helligkeit des Farbverlaufs
    var averageLuminance = (luminance1 + luminance2) / 2;

    // Wenn die Helligkeit dunkel ist, ändern wir die Textfarbe auf weiß, sonst auf schwarz
    var textColor = averageLuminance < 128 ? '#FFFFFF' : '#000000';

    // Passe die Textfarbe an
    var titleElement = document.getElementById('title-' + id);
    titleElement.style.color = textColor;

    // Update die Farbwerte in der iteminfo
    var itemInfo = document.getElementById('iteminfo-' + id);
    var colorSpans = itemInfo.querySelectorAll("span");
    colorSpans[0].style.color = newColor1;
    colorSpans[1].style.color = newColor2;
}

// Snackbar anzeigen
function showSnackbar(message) {
    var snackbar = document.createElement('div');
    snackbar.classList.add('snackbar');
    snackbar.textContent = message;
    document.body.appendChild(snackbar);

    // Snackbar nach einer bestimmten Zeit ausblenden
    setTimeout(function() {
        snackbar.classList.add('show');
        setTimeout(function() {
            snackbar.classList.remove('show');
            setTimeout(function() {
                snackbar.remove();
            }, 300); // Verzögerung zum Entfernen nach dem Ausblenden
        }, 3000); // Dauer der Anzeige (3 Sekunden)
    }, 100);
}

// Funktion, um das Formular für einen neuen Farbverlauf anzuzeigen
function createNewField() {
    document.getElementById('newFarbverlaufForm').style.display = 'block';
}

// Funktion, um das Erstellen eines neuen Farbverlaufs abzubrechen
function cancelNewField() {
    document.getElementById('newFarbverlaufForm').style.display = 'none';
}

// Funktion zum Speichern eines neuen Farbverlaufs
function saveNewField() {
    var title = document.getElementById('newTitle').value;
    var color1 = document.getElementById('newColor1').value;
    var color2 = document.getElementById('newColor2').value;

    // Daten vorbereiten
    var data = {
        title: title,
        color1: color1,
        color2: color2
    };

    // AJAX-Request
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "demo_autofields_save.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    showSnackbar("✅ Neuer Farbverlauf erfolgreich gespeichert!");

                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    // Fehlerhafte Antwort mit Nachricht
                    var msg = response.message || "Unbekannter Fehler beim Speichern.";
                    showSnackbar("❌ Fehler: " + msg);
                    console.warn("Fehlerdetails:", response);
                }

            } catch (e) {
                // Wenn JSON kaputt ist (z. B. durch HTML oder PHP-Warnung)
                showSnackbar("❌ Antwort vom Server war kein gültiges JSON.");
                console.error("Rohantwort:", xhr.responseText);
            }
        } else {
            // HTTP-Fehlerstatus
            showSnackbar("❌ Netzwerkfehler: Server antwortete mit Status " + xhr.status);
            console.error("XHR-Fehler:", xhr);
        }
    };

    xhr.onerror = function () {
        showSnackbar("❌ Netzwerkfehler beim Senden der Daten.");
    };

    xhr.send(JSON.stringify(data));
}


// Funktion zum Löschen eines Farbverlaufs
function deleteField(id) {
    if (confirm("Möchtest du diesen Farbverlauf wirklich löschen?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "demo_autofields_delete.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        var data = { id: id };
        xhr.send(JSON.stringify(data));

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Farbverlauf aus der UI entfernen
                    var listItem = document.getElementById('list-item-' + id);
                    listItem.remove(); // Entferne das Listenelement

                    showSnackbar("Farbverlauf wurde erfolgreich gelöscht!");
                } else {
                    showSnackbar("Fehler beim Löschen des Farbverlaufs.");
                }
            } else {
                showSnackbar("Fehler beim Löschen des Farbverlaufs.");
            }
        };
    }
}


</script>

<?php
// Quellcode anzeigen
$scriptName = basename(__FILE__);

$scriptContent = file_get_contents(__FILE__);
$scriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $scriptContent);
?>

<!-- Exclude Begin -->
<div class="container mt-5 mb-3">
    <hr class="shadow-lg rounded">
    <div class="accordion" id="codeAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCode">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCode" aria-expanded="false" aria-controls="collapseCode">
                    📄 Quellcodeauszug dieser Demo anzeigen (<?= htmlspecialchars($scriptName) ?>)
                </button>
            </h2>
            <div id="collapseCode" class="accordion-collapse collapse" aria-labelledby="headingCode" data-bs-parent="#codeAccordion">
                <div class="accordion-body">
                    <pre class="code-block"><code><?php echo htmlspecialchars($scriptContent); ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Exclude End -->

<?php
// Quellcode der Save-Datei anzeigen
$saveScriptName = 'demo_autofields_save.php';
$saveScriptContent = file_get_contents($saveScriptName);
$saveScriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $saveScriptContent);

// Quellcode der Delete-Datei anzeigen
$deleteScriptName = 'demo_autofields_delete.php';
$deleteScriptContent = file_get_contents($deleteScriptName);
$deleteScriptContent = preg_replace('/<!-- Exclude Begin -->.*?<!-- Exclude End -->/s', '', $deleteScriptContent);
?>

<!-- Exclude Begin -->
<div class="container mb-5">
    <div class="accordion" id="codeAccordion">
        <!-- Anzeige des Quellcodes für Save -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSaveCode">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSaveCode" aria-expanded="false" aria-controls="collapseSaveCode">
                    📄 Quellcode der Save-Datei (<?= htmlspecialchars($saveScriptName) ?>)
                </button>
            </h2>
            <div id="collapseSaveCode" class="accordion-collapse collapse" aria-labelledby="headingSaveCode" data-bs-parent="#codeAccordion">
                <div class="accordion-body">
                    <pre class="code-block"><code><?php echo htmlspecialchars($saveScriptContent); ?></code></pre>
                </div>
            </div>
        </div>

        <!-- Anzeige des Quellcodes für Delete -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingDeleteCode">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeleteCode" aria-expanded="false" aria-controls="collapseDeleteCode">
                    📄 Quellcode der Delete-Datei (<?= htmlspecialchars($deleteScriptName) ?>)
                </button>
            </h2>
            <div id="collapseDeleteCode" class="accordion-collapse collapse" aria-labelledby="headingDeleteCode" data-bs-parent="#codeAccordion">
                <div class="accordion-body">
                    <pre class="code-block"><code><?php echo htmlspecialchars($deleteScriptContent); ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Exclude End -->


<h3 class="mt-5">🛠️ System-Konfiguration der Auto-Felder</h3>

<p>
    In dieser Demo werden Felder wie <strong>Autoincrement</strong>, <strong>AutoCreated</strong> und <strong>AutoUpdated</strong> automatisch durch JsonSQL verwaltet. 
    Diese Felder sind in der <code>system.json</code> Datei definiert, die die automatische Handhabung dieser Felder regelt.
</p>

<p>Die <code>system.json</code> Datei enthält die folgenden Felder, die die Funktionsweise der Auto-Felder steuern:</p>

<pre class="code-block">
<code>
<?php
// Holen der system.json-Daten durch Aufruf der getRawSystemData Methode
$systemData = $db->getRawSystemData();

// Den Inhalt der system.json in einem lesbaren Format anzeigen
echo trim(json_encode($systemData, JSON_PRETTY_PRINT));
?>
</code>
</pre>

<p>
    <ul>
        <li><strong>id</strong>: Dieses Feld wird automatisch mit einer fortlaufenden Zahl inkrementiert, wenn ein neuer Datensatz erstellt wird.</li>
        <li><strong>created_at</strong>: Dieses Feld wird automatisch auf das aktuelle Datum gesetzt, wenn der Datensatz erstellt wird.</li>
        <li><strong>updated_at</strong>: Dieses Feld wird automatisch auf das aktuelle Datum gesetzt, wenn der Datensatz aktualisiert wird.</li>
    </ul>
</p>

<p>
    Hier kannst du sehen, wie das System die Felder bei jeder Datenbankoperation automatisch verwaltet und aktualisiert, ohne dass du manuell eingreifen musst.
</p>

</div>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>

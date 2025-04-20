// Funktion zum Anzeigen der Snackbar
function showSnackbar() {
    var snackbar = document.getElementById("snackbar");
    snackbar.classList.remove("hide");
    snackbar.classList.add("show");

    setTimeout(function() {
        snackbar.classList.remove("show");
        snackbar.classList.add("hide");
    }, 3000); // Snackbar bleibt für 3 Sekunden sichtbar
}

document.getElementById('dataType').addEventListener('change', function() {
    var enumValuesField = document.getElementById('enumValues');
    if (this.value === 'enum') {
        enumValuesField.style.display = 'block'; // Zeige Enum-Werte-Feld
    } else {
        enumValuesField.style.display = 'none'; // Verstecke Enum-Werte-Feld
    }
});

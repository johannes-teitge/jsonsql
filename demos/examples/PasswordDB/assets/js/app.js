document.addEventListener("DOMContentLoaded", () => {
  const list = document.getElementById("passwordList");
  const searchInput = document.getElementById("search");

  const getFaviconFromUrl = (url) => {
    try {
      const domain = new URL(url).origin;
      return `${domain}/favicon.ico`;
    } catch (e) {
      return 'assets/images/default-icon.png';
    }
  };

  const renderEntries = (entries) => {
    list.innerHTML = entries.map(entry => `
      <div class="col-md-6">
        <div class="card entry-card">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <img src="${getFaviconFromUrl(entry.url)}" 
                   alt="Icon" width="16" height="16" class="me-2"
                   onerror="this.src='assets/images/default-icon.png'">
              <h5 class="card-title mb-0">${entry.title}</h5>
            </div>
            <p class="card-text mt-2">
              👤 <strong>${entry.username}</strong><br>
              🔗 <a href="${entry.url}" target="_blank">${entry.url}</a><br>
              📝 ${entry.note || ''}
            </p>
          </div>
        </div>
      </div>
    `).join("");
  };

  const loadEntries = async (query = "") => {
    try {
      const res = await fetch(`api/load.php?search=${encodeURIComponent(query)}`);
      const json = await res.json();
  
      if (!json.success) {
        throw new Error(json.error || "Unbekannter Fehler");
      }
  
      renderEntries(json.data);
    } catch (error) {
      console.error("Fehler beim Laden der Daten:", error.message);
      list.innerHTML = `<div class="col-12 text-danger">❌ Fehler: ${error.message}</div>`;
    }
  };
  
  

  // Initial laden
loadEntries();

  // Suche
  searchInput.addEventListener("input", (e) => {
    loadEntries(e.target.value);
    //loadEntries();    
  });
});

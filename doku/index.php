<?php
$pageTitle = "Dokumentation";
require_once __DIR__ . '/includes/header.php'; // Header laden, wenn nötig

$baseUrl = dirname($_SERVER['PHP_SELF']);


?>

<style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
}

.layout {
    display: flex;
    height: 100vh;
}



.nav-link:hover {
    background-color:rgba(255, 255, 255, 0);
    font-weight: bold;
}



/* Content */
.content-area {
    flex-grow: 1;
    padding: 2rem;
    overflow-y: auto;
}

mark.highlight {
  background-color: yellow;
  color: black;
  padding: 0 2px;
  border-radius: 2px;
}

mark.highlight.current-hit {
  background-color: orange;
}


</style>

<?php
/**
 * Lädt ein Template aus dem angegebenen Pfad.
 */
function loadTemplate($templatePath) {
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        echo "<div class='alert alert-warning'>⚠️ Die Sektion wurde noch nicht erstellt. Bitte später wiederkommen.</div>";
    }
}
?>

<div class="layout">

<!-- Sidebar Navigation -->
<nav id="sidebar" class="d-none d-md-block bg-light border-end p-3" style="min-width: 250px;">
    <div class="headlogo" id="home">
            <img src="assets/images/JsonSQL-Logo-FullWhite.svg" alt="JsonSQL Logo" style="max-height: 55px;">    

<style>
#doku-search {
  border: =;
  padding: 4px;
  border-radius: 2px;
  overflow: hidden;
  display: flex;
  max-width: 90%;
  background: linear-gradient(180deg, #ffffff,rgb(226, 226, 226));
  transition: all 0.3s ease-in-out;
  box-shadow: 0 8px 14px rgba(0, 0, 0, 0.16);
  margin-top: 35px;
}


#doku-search input,
#doku-search button {
  border: none !important;
  box-shadow: none !important;
  background-color: transparent;
}

#doku-search button {
  background-color:rgba(219, 219, 219, 0.2);
  border-radius: 3px;
}  

#doku-search input:focus {
  border-bottom: 1px solid rgba(136, 150, 169, 0.29) !important;
}

#doku-search input::placeholder {
  color: #bbb;
}

#clearSearch, #nextHit, #prevHit {
  background-color:rgba(219, 219, 219, 0.2);  
  color: black !important;
  font-weight: bold;
  margin: 2px;
  padding: 1px;
}

#clearSearch {
  background-color:rgba(182, 0, 0, 0.2) !important;
  padding: 2px 4px;

}

#clearSearch:hover {
  background-color:rgb(178, 0, 0) !important;
  color: white;
  padding: 2px 4px;
}

#clearSearch:hover i {
  color: white !important;
}
</style>

            
<div id="searchWrapper" class="mb-3" Style="margin-left:8px;margin-top: 4px;">
  <div class="input-group input-group-sm" id="doku-search">
    <input type="text" id="searchDoc" class="form-control" placeholder="🔍 Suche...">
    
    <button class="btn btn-outline-light" id="prevHit" title="Vorheriger Treffer">
    <i class="bi bi-arrow-left-square"></i>
    </button>
    
    <button class="btn btn-outline-light" id="nextHit" title="Nächster Treffer">
    <i class="bi bi-arrow-right-square"></i>
    </button>
    
    <button class="btn btn-outline-light" id="clearSearch" title="Zurücksetzen">
      <i class="bi bi-x-lg text-dark"></i>
    </button>

  </div>

  <div class="d-flex justify-content-end" id="SearchcaseSensitive">
    <div class="form-check mt-2 d-flex align-items-center justify-content-end" title="Groß-/Kleinschreibung beachten">
      <input class="form-check-input me-2" type="checkbox" id="caseSensitive">
      <label class="form-check-label small text-white-50 d-flex align-items-center" for="caseSensitive">
        <i class="bi bi-type" style="font-size: 1.1rem;"></i>
      </label>
    </div>
  </div>

  <div style="margin-top:-25px;margin-left: 15px;font-size:0.8rem;padding:0" class="text-white-50 " id="searchCounter"></div>
</div>
          

    </div>  



<div class="nav-divider"></div>



  <ul class="nav flex-column">
    <li><a class="nav-link" href="#introduction"><span class="m-item"><i class="bi bi-chat-text"></i> Einführung</a></span></li>
    <li><a class="nav-link" href="#installation"><span class="m-item"><i class="bi bi-gear"></i> Installation</span></a></li>
    <li><a class="nav-link" href="#start"><span class="m-item"><i class="bi bi-lightning-fill"></i> Start</span></a></li>
    <li><a class="nav-link" href="#grundlagen"><span class="m-item"><i class="bi bi-diagram-3-fill"></i> Architektur & Grundlagen</span></a></li>    


    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#coreFunctions" role="button" aria-expanded="true" aria-controls="coreFunctions">
        <span class="m-item"><i class="bi bi-caret-down-fill"></i> Kernfunktionen</span>
      </a>
      <div class="collapse ms-3 show" id="coreFunctions">
        <ul class="nav flex-column">
          <li><a class="nav-link" href="#insert">📥 insert()</a></li>
          <li><a class="nav-link" href="#update">🛠️ update()</a></li>
          <li><a class="nav-link" href="#delete">🗑️ delete()</a></li>
          <li><a class="nav-link" href="#select">🎯 select()</a></li>
          <li><a class="nav-link" href="#get">📦 get()</a></li>
          <li><a class="nav-link" href="#exists">❓ exists()</a></li>
          <li><a class="nav-link" href="#pluck">🎯 pluck()</a></li>
          <li><a class="nav-link" href="#first">🥇 first()</a></li>
          <li><a class="nav-link" href="#clearTable">🧹 clearTable()</a></li>
          <li><a class="nav-link" href="#clear">☠️ clear() <span class="JsonSQL-danger small">vorsichtig!</span></a></li>
          <li><a class="nav-link" href="#paginate">📄 paginate()</a></li>
        </ul>
      </div>
    </li>



    <li class="nav-item">
      <a class="nav-link" href="#filter"><span class="m-item"><i class="bi bi-funnel-fill"></i> Filterlogik</span></a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#aggregation"><span class="m-item"><i class="bi bi-bar-chart-fill"></i> Aggregation & Statistik</span></a>
    </li>    

    <li class="nav-item">
      <a class="nav-link" href="#system"><span class="m-item"><i class="bi bi-sliders"></i> Systemkonfiguration</span></a>
    </li>    
    
    <li class="nav-item">
      <a class="nav-link" href="#joins"><span class="m-item"><i class="bi bi-link-45deg"></i> Joins & Beziehungen</span></a>
    </li>       
    
    <li class="nav-item">
      <a class="nav-link" href="#tools"><span class="m-item"><i class="bi bi-tools"></i> Extras & Tools</span></a>
    </li>       

    <li class="nav-item">
      <a class="nav-link" href="#module"><span class="m-item"><i class="bi bi-puzzle-fill"></i> Erweiterung & eigene Module</span></a>
    </li>     
    
    <li class="nav-item">
      <a class="nav-link" href="#api"><span class="m-item"><i class="bi bi-journal-code"></i> API-Referenz</span></a>
    </li>      

    <li class="nav-item">
      <a class="nav-link" href="#teitge"><span class="m-item"><i class="bi bi-person-lines-fill"></i> Über den Autor</span></a>
    </li>       

    
    
    



</ul>
</nav>









    <!-- Hauptinhalt -->
    <main class="content-area" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-offset="0" tabindex="0">

        <div class="tophead">
            <h1>JsonSQL Dokumentation</h1>
        </div>
        
            <hr class="cool_sep">



        <span id="introduction"></span>
        <?php loadTemplate(__DIR__ . '/sections/einleitung.php'); ?>

        <hr>
        <span id="installation"></span>
        <?php loadTemplate(__DIR__ . '/sections/installation.php'); ?>

        <span id="start"></span>
        <?php loadTemplate(__DIR__ . '/sections/start.php'); ?>    

        <span id="grundlagen"></span>
        <?php loadTemplate(__DIR__ . '/sections/grundlagen.php'); ?>  

        <span id="kern"></span>
        <?php loadTemplate(__DIR__ . '/sections/kernfunktionen.php'); ?>     
        
        <span id="filter"></span>
        <?php loadTemplate(__DIR__ . '/sections/filterlogik.php'); ?>        
        
        <span id="aggregation"></span>
        <?php loadTemplate(__DIR__ . '/sections/aggregation.php'); ?>        
        
        <span id="system"></span>
        <?php loadTemplate(__DIR__ . '/sections/system.php'); ?>     
        
        <span id="joins"></span>
        <?php loadTemplate(__DIR__ . '/sections/joins.php'); ?>    
        
        <span id="tools"></span>
        <?php loadTemplate(__DIR__ . '/sections/tools.php'); ?>    
        
        <span id="module"></span>
        <?php loadTemplate(__DIR__ . '/sections/module.php'); ?>     
        
        <span id="api"></span>
        <?php loadTemplate(__DIR__ . '/sections/api.php'); ?>     
        
        <span id="teitge"></span>
        <?php loadTemplate(__DIR__ . '/sections/teitge.php'); ?>               
        
        

        <?php require_once __DIR__ . '/includes/footer.php'; ?>           
    </main> 


</div>

<script>
const scrollSpy = new bootstrap.ScrollSpy(document.querySelector('.content-area'), {
  target: '#sidebar',
  offset: 0
});
</script>  

<script>
document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("searchDoc");
  const nextBtn = document.getElementById("nextHit");
  const prevBtn = document.getElementById("prevHit");
  const clearBtn = document.getElementById("clearSearch");
  const counter = document.getElementById("searchCounter");
  const context = new Mark(document.querySelector(".content-area"));
  const caseCheckbox = document.getElementById("caseSensitive");  

  let current = 0;
  let marks = [];

  function jumpTo(index) {
    if (marks[index]) {
      marks[index].scrollIntoView({ behavior: "smooth", block: "center" });
      marks.forEach(m => m.classList.remove("current-hit"));
      marks[index].classList.add("current-hit");
      counter.textContent = `Treffer ${index + 1} von ${marks.length}`;
    }
  }

  input.addEventListener("input", function () {
    const keyword = input.value.trim();
    context.unmark({
      done: function () {
        if (keyword.length > 1) {
          context.mark(keyword, {
            separateWordSearch: true,
            className: "highlight",
            caseSensitive: caseCheckbox.checked, // 👈 wichtig!
            done: function () {
              marks = Array.from(document.querySelectorAll("mark.highlight"));
              current = 0;
              if (marks.length > 0) {
                jumpTo(current);
              } else {
                counter.textContent = "Keine Treffer";
              }
            }
          });
        } else {
          counter.textContent = "";
        }
      }
    });
  });

  nextBtn.addEventListener("click", function () {
    if (marks.length > 0) {
      current = (current + 1) % marks.length;
      jumpTo(current);
    }
  });

  prevBtn.addEventListener("click", function () {
    if (marks.length > 0) {
      current = (current - 1 + marks.length) % marks.length;
      jumpTo(current);
    }
  });

  clearBtn.addEventListener("click", function () {
    input.value = "";
    context.unmark();
    marks = [];
    counter.textContent = "";
  });
});
</script>

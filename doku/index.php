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




/* Stylischer Link zur Hello-Demo */
.demo-nav-link {
    margin-top: 1rem;
    padding: 0.1rem 0.87rem;
    background: rgba(255, 255, 255, 0.88); /* Helles Blau mit Transparenz */
    border-radius: 12px;
    text-align: center;
    transition: background 0.3s ease;
    margin-right: 2rem;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 8px 14px rgba(0, 0, 0, 0.16);
  }
  
  .demo-nav-link a {
    color: #007bff;
    font-weight: 600;
    text-decoration: none;
    display: block;
  }
  
  .demo-nav-link:hover {
    background: rgb(255, 255, 255); /* Helles Blau mit Transparenz */
    transition: all 0.3s ease-in-out;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.53);    
  }
  
  .demo-nav-link a:hover {
    text-decoration: none;
    color: #0056b3; /* Dunkleres Blau beim Hover */
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

            <div class="demo-nav-link">
      <a href="<?= $baseUrl ?>/../demos/examples/hello-json-sql.php" target="_blank">
        🚀 Direkt zur Hello-Demo
      </a>
    </div> 

    </div>  

   


  <ul class="nav flex-column">
    <li><a class="nav-link" href="#introduction"><span class="m-item"><i class="bi bi-chat-text"></i> Einführung</a></span></li>
    <li><a class="nav-link" href="#installation"><span class="m-item"><i class="bi bi-gear"></i> Installation</span></a></li>
    <li><a class="nav-link" href="#start"><span class="m-item"><i class="bi bi-lightning-fill"></i> Start</span></a></li>
    <li><a class="nav-link" href="#grundlagen"><span class="m-item"><i class="bi bi-diagram-3-fill"></i> Architektur & Grundlagen</span></a></li>    


    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#coreFunctions" role="button" aria-expanded="false" aria-controls="coreFunctions">
      <span class="m-item"><i class="bi bi-caret-right-fill"></i> Kernfunktionen</span>
      </a>
      <div class="collapse ms-3" id="coreFunctions">
        <ul class="nav flex-column">
          <li><a class="nav-link" href="#insert">📥 insert()</a></li>
          <li><a class="nav-link" href="#update">🛠️ update()</a></li>
          <li><a class="nav-link" href="#delete">🗑️ delete()</a></li>
          <li><a class="nav-link" href="#select">🔎 select() / get()</a></li>
          <li><a class="nav-link" href="#exists">❓ exists()</a></li>
          <li><a class="nav-link" href="#pluck">🎯 pluck()</a></li>
          <li><a class="nav-link" href="#first">🥇 first()</a></li>
          <li><a class="nav-link" href="#clear">♻️ clear()</a></li>
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


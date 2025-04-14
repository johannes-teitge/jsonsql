</div> <!-- .container -->

<?php
$runtime = round((microtime(true) - $__start) * 1000, 2);

// Wenn der Debugger aktiviert ist, den Dump ausgeben

if ($showDebugger) {
  if ($debugger->getStackCount() > 0) {
    $debugger->dumpOut();
  }  
}

?>

<footer class="footer mt-auto py-4 bg-primary text-white">
  <div class="container">
    <div class="row">
      <!-- Spalte 1: Logo und Laufzeit -->
      <div class="col-md-2-5">
        <img src="<?= APP_ASSETS_URL ?>/images/JsonSQL-Logo-FullWhite.svg" alt="JsonSQL Logo" style="height: 40px;" id="logo_jsonsql">
        <p class="mt-2 mb-0">JsonSQL Library</p>
        <p class="mt-2 mb-0">
          ⏱️ Laufzeit: <?= $runtime ?> ms
        </p>
      </div>

      <!-- Spalte 2: FancyDumpVar Logo und Debugger-Checkbox -->
      <div class="col-md-3">
        <!-- Detektiv im Footer -->
        <div id="footer_detective_wrapper">
          <img id="footer_detective" src="<?= APP_ASSETS_URL ?>/images/fdc-logo-animated01.webp" alt="Detektiv im Footer">
        </div>
        <!-- Die Checkbox unter dem Logo anzeigen -->
        <div style="margin-top: 0px;margin-left:36px">
            <label for="debugger_checkbox">
                <input type="checkbox" id="debugger_checkbox" onclick="toggleDebugger()"
                      <?php echo $showDebugger ? 'checked' : ''; ?>>
                Debugger anzeigen
            </label>
        </div>
      </div>

      <!-- Spalte 3: Developer Information -->
      <div class="col-md-3">
        <h6 class="text-uppercase">Developer</h6>
        <p class="mb-0">Johannes Teitge</p>
        <p class="mb-0">Mail: <a class="text-white" href="mailto:johannes@teitge.de"><span class="at-wrap">johannes<span class="at">@</span>teitge.de</span></a></p>
        <p class="mb-0">www: <a class="text-white" href="https://www.teitge.de" target="_blank">teitge.de</a></p>
      </div>
      <style>
    .at-wrap {
      display: inline-block;
      perspective: 600px; /* Wichtig für 3D-Effekt */
    }

    .at {
      display: inline-block;
      transition: transform 0.6s ease;
      transform-style: preserve-3d;
    }

    .at-wrap:hover .at {
      transform: rotateY(360deg);
    }
  </style>      

      <!-- Spalte 4: Ressourcen -->
      <div class="col-md-3">
        <h6 class="text-uppercase">Ressourcen</h6>
        <ul class="list-unstyled">
        <li><a class="text-white" href="../../doku/">Dokumentation</a></li>          
          <li><a class="text-white" href="../examples/faq.php">FAQ</a></li>
          <li><a class="text-white" href="../examples/">Demos (Index)</a></li>
          <li><a class="text-white" href="../../LICENSE.html">Lizenz</a></li>
          <li><a class="text-white" href="https://github.com/jteitge/jsonsql" target="_blank">GitHub</a></li>
        </ul>
      </div>
    </div>

    <div class="text-center mt-3 small">
      © <?= date('Y') ?> JsonSQL – Made with ☕ by Johannes Teitge
    </div>
  </div>
</footer>




<script>
  const baseUrl = '<?= APP_ASSETS_URL ?>';

document.addEventListener("DOMContentLoaded", function () {
    const frames = [
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 2000 },
      { src: baseUrl + '/images/fdc-logo-animated02.webp', duration: 200 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 500 },
      { src: baseUrl + '/images/fdc-logo-animated02.webp', duration: 200 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 200 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 2500 },
      { src: baseUrl + '/images/fdc-logo-animated02.webp', duration: 450 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 450 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 2000 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 2500 },
      { src: baseUrl + '/images/fdc-logo-animated02.webp', duration: 650 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 250 },
      { src: baseUrl + '/images/fdc-logo-animated01.webp', duration: 2000 }      
    ];

    let index = 0;
    const img = document.getElementById('footer_detective');

    function animateDetective() {
      if (!img) return;
      img.src = frames[index].src;
      setTimeout(() => {
        index = (index + 1) % frames.length;
        animateDetective();
      }, frames[index].duration);
    }

    animateDetective();
  });


    // Klick auf das JsonSQL-Logo, um den Debugger-Checkbox-Status zu toggeln
    document.getElementById('logo_jsonsql').addEventListener('click', function() {
        toggleDebuggerCheckbox();
    });

    // Klick auf das FancyDumpVar-Logo, um den Debugger-Checkbox-Status zu toggeln
    document.getElementById('footer_detective_wrapper').addEventListener('click', function() {
        toggleDebuggerCheckbox();
    });

    function toggleDebugger() {
        var isChecked = document.getElementById('debugger_checkbox').checked;
        var url = new URL(window.location.href);
        url.searchParams.set('debugger', isChecked ? 'true' : 'false');  // URL-Parameter setzen
        window.location.href = url.toString();  // Seite mit neuem URL laden
    }




  hljs.highlightAll();
  hljs.initLineNumbersOnLoad();  



</script>


<style>
.hljs-ln-numbers {
  background: #f7f7f7;
  color:rgb(167, 167, 167);
  padding-right: 30px !important;
  border-right: 2px solid #aaa;
}

.hljs-ln-code {
  padding-left: 15px !important;
}

#footer_detective_wrapper {
  text-align: left;
/*  padding: 20px; */
}

#footer_detective {
  width: 140px; /* kleiner für Footer */
  height: auto;
}

.col-md-2-5 {
    flex: 0 0 19%;
    max-width: 19%;
  }

  #footer_detective_wrapper {
  text-align: left;
}

#footer_detective {
  transition: transform 0.5s ease; /* Übergang für Vergrößern und Verkleinern */
  transform: scale(1.0); /* Ausgangsgröße */
}

#footer_detective_wrapper:hover #footer_detective {
  transform: scale(1.35); /* Vergrößert das Bild beim Hover */
}


</style>



<!-- Bootstrap Bundle (inkl. Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>

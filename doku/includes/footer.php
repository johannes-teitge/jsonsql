<?php
$runtime = round((microtime(true) - $__start) * 1000, 2);
?>
<footer class="footer mt-auto py-4 bg-primary text-white">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <img src="assets/images/JsonSQL-Logo-FullWhite.svg" alt="JsonSQL Logo" style="height: 40px;">
        <p class="mt-2 mb-0">JsonSQL Library</p>
        <p class="mt-2 mb-0">
          ⏱️ Laufzeit: <?= $runtime ?> ms
        </p>
      </div>
      <div class="col-md-4">
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

      <div class="col-md-4">
        <h6 class="text-uppercase">Ressourcen</h6>
        <ul class="list-unstyled">
          <li><a class="text-white" href="../demos/examples/faq.php">FAQ</a></li>
          <li><a class="text-white" href="../demos/examples/index.php">Demos (Index)</a></li>
          <li><a class="text-white" href="https://github.com/jteitge/jsonsql" target="_blank">GitHub</a></li>
          <li><a class="text-white" href="../LICENSE.html">Lizenz</a></li>          
        </ul>
      </div>
    </div>
    <div class="text-center mt-3 small">
      © <?= date('Y') ?> JsonSQL – Made with ☕ by Johannes Teitge
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
</style>

</body>
</html>

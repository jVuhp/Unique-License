
<footer class="bg-<?php if ($_SESSION['theme'] == 'dark') { echo "dark"; } else { echo "light"; } ?> text-center text-<?php if ($_SESSION['theme'] == 'dark') { echo "white"; } else { echo "dark"; } ?>">

  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
    © 2022 - 2023 Copyright:
    <a class="text-<?php if ($_SESSION['theme'] == 'dark') { echo "info"; } else { echo "primary"; } ?>" href="https://vanityproyect.com">Unique License</a>
	| Version: v1.0.1-RE
  </div>
  <!-- Copyright -->
</footer>

    <!-- MDB -->
	<?php if ($_SESSION['theme'] == 'dark') { ?>
    <script type="text/javascript" src="https://proyectojp.com/static/dark/js/mdb.min.js"></script>
	<?php } else { ?>
    <script type="text/javascript" src="https://proyectojp.com/static/light/js/mdb.min.js"></script>
	<?php } ?>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>
  </body>
</html>

<!-- pie de página (footer) -->
<small class="d-block text-right mt-3">
<a class="nav-link">
          Ya somos
          <span class="badge badge-pill bg-light align-text-bottom">
          <?php
		//devuelve los usuarios totales creados 
          if ($result = $link->query("SELECT * FROM cuentas")) {

            $row_cnt = $result->num_rows;
            printf("%d", $row_cnt);
        
            $result->close();
          }
        ?></span> usuarios en <?php echo $WEB_TITLE; ?>. ¡Gracias!<br><a href="creadores.php#<?php echo $WEB_TITLE; ?>">¿Quienes son los creadores de <?php echo $WEB_TITLE; ?>?</a><br>
        <?php echo $WEB_TITLE; ?> © 2018-2019.
        </a>
<!-- script google translate -->
        <div id="google_translate_element"></div>
<script  type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'es'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        </small>
        </div>
    </main>

<!-- carga de scripts de boostrap y demás. -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>

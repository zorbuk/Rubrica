<?php
ob_start();
require '../config/checkConnected.php';
require '../config/db.php';
require '../gParts/header.php';
?>
    <!-- Genera resultados de una rubrica -->
      <div class="my-3 p-3 bg-white rounded shadow-sm flex-wrap">
        <h6 style="word-wrap: break-word;" class="border-bottom border-gray pb-2 mb-0"><b>Resultados de <?php echo getRubricaName($link,@$_GET["rubrica"]) ?></b></h6>
        <div style="word-wrap: break-word;" class="media text-muted pt-3">
        
            <?php
		//Obtiene la id del creador de la rubrica, si la rubrica no es del usuario redirecciona fuera.
            if(@$_SESSION["id"]==getIdCreadorRubrica($link,@$_GET["rubrica"])){
                echo '<ul style="width:100%" class="list-group">';
		
		echo '<li class="list-group-item active"><b>Mediana global</b></li>
<li style="background-color:lightblue;" class="list-group-item">';
	// obtiene la mediana global, calculando la media de todas las respuestas en todas las preguntas.
	echo 'Puntuación media '. getMediaGlobal($link,@$_GET["rubrica"]) . ' de '.getRubricaPuntuacion($link,$_GET["rubrica"]);
	echo '</li>';
		
                for ($i = 1; $i <= getRubricaNumeroPreguntas($link,$_GET["rubrica"]); $i++) {

		//Obtiene la pregunta
                    echo '<li class="list-group-item active"><b>'.getRubricaPregunta($link,getRespuestaPorIndice($link,$_GET["rubrica"],$i)).'</b></li>';

		//Obtiene la mediana de respuestas de esa pregunta y la devuelve.
                    echo '<li class="list-group-item">Puntuación media '.round(getMediaRespuestas($link,$_GET["rubrica"],$i),2).' de '.getRubricaPuntuacion($link,$_GET["rubrica"]).'</li>';

                    //echo '<li class="list-group-item">'.getRubricaRespuestaPorId($link,getRubricaPreguntaId($link,getRespuestaPorIndice($link,$_GET["rubrica"],$i))).'</li>'; //getRespuestaPorIndice($link,$_GET["rubrica"],$i)

                }
                echo '</ul>';
                //getRubricaRespuestas($link,@$_GET["rubrica"]);

            }else{
                //¡Esta rubrica no es tuya!
                header("location: ../");
            }
            ?>
            
    
    </div>
        
        <?php
require '../gParts/footer.php';
ob_end_flush();
        ?>

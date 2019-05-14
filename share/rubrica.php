<?php
include_once '../config/definitions.php';
include_once '../config/db.php';
include_once "../phpqrcode/qrlib.php";
// obtiene el enlace actual de la página, para que el QR funcione desde cualquier lugar.
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../<?php echo $WEB_LOGO; ?>">

    <title><?php echo $WEB_TITLE; ?> - <?php echo getRubricaName($link,@$_GET["id"]) ?></title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">

    <body class="bg-light">

<?php
// al responderse la rubrica se crea una variable de sesión especial con el que se puede verificar si la rubrica ya ha sido repsondida por un usuario, sólo el usuario que ha creado la rubrica puede responderla varias veces.
if(isset($_SESSION["rubricaRespondida-".$_GET["id"].""])){
  
  if(@$_SESSION["id"]==getIdCreadorRubrica($link,@$_GET["id"])){
  }else{

  die('<div class="alert alert-dark" role="alert">
  Ya has respondido esta Rubrica. <a href="..">Prosigue tu camino.</a>
</div>');

  }

}
?>
    <!-- genera el formulario para responder la rubrica -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo @$_GET["id"]; ?>" method="post">
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark">
      <img heigth="168px" width="168px" src="../<?php echo $WEB_LOGO; ?>"> <a class="navbar-brand mr-auto mr-lg-0" href="#"></a>
    </nav>
    <main role="main" class="container">
    <div class="alert alert-light" role="alert">
  <a href="..">Volver a la página principal</a>
</div>
      <div class="d-flex align-items-center p-3 my-3 text-lightgreen-50 bg-purple rounded shadow-sm">
        <div class="lh-100">
<!-- obtiene el nombre de la rubrica -->
          <h2 class="mb-0 1h-100"><?php echo htmlspecialchars(getRubricaName($link,$_GET["id"])) ?></h2>
          <!-- obtiene la imágen con el enlace QR hacia la rubrica -->
		<img class="mr-3" src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?php echo $actual_link; ?>" title="QR: <?php echo $actual_link; ?>" />
<!-- obtiene la descripción de la rubrica -->
          <p><textarea style="height:80px;width:100%;border:0px solid black;" readonly><?php echo htmlspecialchars(getRubricaDescription($link,$_GET["id"])) ?></textarea></p>
          
          <table class="table table-light">
  <thead>
    <tr>
      <th scope="col"></th>
<!-- obtiene la tabla de puntuaciones de la rubrica -->
      <?php echo htmlspecialchars(getRubricaQualifies(getRubricaPuntuacion($link,$_GET["id"]))) ?>
    </tr>
  </thead>
  <tbody>
<!-- obtiene las preguntas de la rubrica -->
    <?php echo htmlspecialchars(getRubricaQuestions($link,$_GET["id"])) ?>
  </tbody>
</table>

        </div>
      </div>
      <div class="form-label-group text-truncate">
        <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="validationTooltipUsernamePrepend"><img src="../config/captcha.php" /></span>
        </div>
        <input name="captcha" type="text" id="captcha" class="form-control" minlength="6" required>
      </div><br>

        <input class="btn btn-primary" type="submit" value="Enviar respuesta" name="">
      </form>
      <br><br>
      <?php
      //Enviando Rubrica
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // ¿Capcha correcto?
  if(isset($_POST["captcha"]))  
  $todoRespondido = true;
            if(@$_SESSION["captcha"]==$_POST["captcha"])  
            {   
              //Revisar si está todo respondido.
              for ($i = 1; $i <= getRubricaNumeroPreguntas($link,@$_GET["id"]); $i++) {
                if(!isset($_POST[$i])){
                  $todoRespondido=false;
                }
              }
              //Está todo respondido, enviar respuestas a la base de datos.
              if($todoRespondido){

                for ($i = 1; $i <= getRubricaNumeroPreguntas($link,@$_GET["id"]); $i++) {
			//Guarda las respuestas de la rubrica en la base de datos.
                  guardarRespuestaEnBaseDeDatos($link,@$_GET["id"],$i,htmlspecialchars($_POST[$i]));
                }

                header('Location: '.htmlspecialchars($_SERVER["PHP_SELF"]).'?id='.$_GET["id"]);

              }else{
                echo '<div class="alert alert-danger" role="alert">
              ¡Hay algunas preguntas que no has respondido!
            </div>';
              }
            }else{
              echo '<div class="alert alert-danger" role="alert">
              ¡El captcha es incorrecto!
            </div>';
            }

}
      ?>

<!-- script google translate -->
              <div id="google_translate_element"></div>
<script  type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'es'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </body>
  </head>

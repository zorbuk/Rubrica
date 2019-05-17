<?php
ob_start();
require '../config/checkConnected.php';
require '../config/db.php';
require '../gParts/header.php';
?>

<script>
var contadorPreguntas = 0;

// Funcion para eliminar las preguntas de la rubrica.
function eliminarPreguntasRubrica(){
//Contenedor
var container = document.getElementById("preguntas");
var infocontainer = document.getElementById("info");
container.innerHTML = "";
infocontainer.innerHTML = "";
contadorPreguntas=0;
}

// Funcion para añadir preguntas a la rubrica.
function agregarPreguntaRubrica(){
contadorPreguntas++;
//Elementos
var input = document.createElement("input");
var br = document.createElement("br");
var spanInicio = document.createElement("span");
var spanFinal = document.createElement("span");

//Contenedor
var container = document.getElementById("preguntas");
var infocontainer = document.getElementById("info");

//...
input.type = "text";
input.className = "form-control";
input.maxLength = "200";
input.placeholder = "...";
input.name="pregunta[]";

//Generar
spanInicio.innerHTML = '<div class="card"><div class="card-body"><b>Pregunta ' + contadorPreguntas +'</b> ';
spanFinal.innerHTML = '</div></div><br>';
container.appendChild(spanInicio);
container.appendChild(input);
container.appendChild(spanFinal);
infocontainer.innerHTML = '<div class="alert alert-secondary" role="alert">Has creado un total de ' + contadorPreguntas+ ' pregunta/s<br></div>';
}
</script>
    
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0"><b>Crear Rubrica</b></h6>
        <div class="media text-muted pt-3">   
<!-- Formulario creación de Rubricas -->
<form style="width:100%" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		
<label class="sr-only" for="inlineFormInputGroupUsername2">Nombre</label>
  <div class="input-group mb-2 mr-sm-2">
    <div class="input-group-prepend">
      <div class="input-group-text">Nombre</div>
    </div>
    <input maxlength="100" type="text" class="form-control" id="inlineFormInputGroupUsername2" name="nombre">
  </div>

<label class="sr-only" for="inlineFormInputGroupUsername2">Descripcion</label>
  <div class="input-group mb-2 mr-sm-2">
    <div class="input-group-prepend">
      <div class="input-group-text">Descripcion</div>
    </div>
    <textarea maxlength="200" class="form-control" id="exampleFormControlTextarea1" rows="3" name="descripcion"></textarea>
  </div>

  <div class="input-group mb-2 mr-sm-2">
    <div class="input-group-prepend">
      <div class="input-group-text">Puntuación máxima: </div>
    </div>

<select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="puntuacion">
        <option selected>Escoge...</option>
<?php
for($x=2;$x<10+1;$x++){
echo '<option name="puntuacion" value="'.$x.'">'.$x.'</option>';
}
?>
      </select>
  </div>

<br><br>

<span id="info">

</span>

<span id="preguntas">

</span>

<input class="btn btn-success" type="button" onclick="agregarPreguntaRubrica()" value="Agregar pregunta"/><input style="float:right;" class="btn btn-danger" type="button" onclick="eliminarPreguntasRubrica()" value="Eliminar todas las preguntas"/>

 <!--<div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="validationTooltipUsernamePrepend"><img src="../config/captcha.php" /></span>
        </div>
       <input name="captcha" type="text" id="captcha" class="form-control" minlength="6" required>
      </div><br>-->

        <input class="btn btn-primary" type="submit" value="¡Crear!" name="">
		</form>

<?php
      //Creando Rubrica
if($_SERVER["REQUEST_METHOD"] == "POST"){
$todoRespondido=true;
	//if(@$_SESSION["captcha"]==$_POST["captcha"])  
            //{   
              //Revisar si está todo respondido.
				//Obtener número preguntas.
if(isset($_POST["pregunta"])){
              
foreach ($_POST["pregunta"] as $key => $val) {
    // Comprobaciones
	if(!isset($val)){
		$todoRespondido=false;
	}
	if(!isset($key)){
		$todoRespondido=false;
	}
	if(empty($val)){
		$todoRespondido=false;
	}
}

}else{
	$todoRespondido=false;
}
//Esta todo respondido.
if($todoRespondido){

	$correcto = true;
	// ver si nombre, descripcion y puntuación no están en blanco.
	if(!isset($_POST["nombre"])){
		$correcto = false;
	}
	if(empty($_POST["nombre"])){
		$correcto = false;
	}
	if(!isset($_POST["descripcion"])){
		$correcto = false;
	}
	if(empty($_POST["descripcion"])){
		$correcto = false;
	}
	if(!isset($_POST["puntuacion"])){
		$correcto = false;
	}
	if(empty($_POST["puntuacion"])){
		$correcto = false;
	}
	if($_POST["puntuacion"]=="Escoge..."){
		$correcto = false;
	}

if($correcto){

  // Guardar rubrica y obtener id
$idRubrica = guardarRubricaEnBaseDeDatos($link,htmlspecialchars($_POST["nombre"]),htmlspecialchars($_POST["descripcion"]),@$_SESSION["id"],htmlspecialchars($_POST["puntuacion"]));

// Si la id rubrica devuelve f, es que la rubrica ha FALLADO al crearse.
if($idRubrica=="f"){

	 echo '<div class="alert alert-danger" role="alert">
              ¡No se ha podido crear la Rubrica!
            </div>';

}else{

foreach ($_POST["pregunta"] as $key => $val) {
  // Guardar preguntas de la rubrica con la id de la propia rubrica
	guardarPreguntasEnBaseDeDatos($link,$idRubrica,htmlspecialchars($val));
}

// Fin. Redireccionar.
header("location: ../");

}

}else{
 echo '<div class="alert alert-danger" role="alert">
              ¡Te has dejado información sin responder!
            </div>';
}

}else{
echo '<div class="alert alert-danger" role="alert">
              ¡Hay algunas preguntas que no has respondido!
            </div>';
}

/*}else{
echo '<div class="alert alert-danger" role="alert">
              ¡El captcha es incorrecto!
            </div>';
}*/

}
?>

    
    </div>
        
        <?php
require '../gParts/footer.php';
ob_end_flush();
        ?>

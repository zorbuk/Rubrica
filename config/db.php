<?php
// ----------------- Información de conexión a la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'alumne'); // alumne
define('DB_NAME', 'rubrica');

@$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
@mysqli_set_charset($link, 'utf8');

// ----------------- Comprobar conexión
if($link === false){
    die("Parece que ha fallado la conexión... Es posible que estemos en mantenimiendo, intentalo de nuevo más tarde. Informe de error generado: " . mysqli_connect_error());
}/*else{
    echo '<a href="#"><img style="position:fixed;z-index:100;bottom:5;left:5;" heigth="20" width="20" src="https://i.stack.imgur.com/chwM4.png" title="El servidor está recibiendo conexión."></a>';
}*/

// Funciones
// ----------------- Obtener rubricas creadas
function getRubricasCreadas($link){
    $_SESSION["rubricasTotalesUsuario"]=0;
    $sql = "SELECT id,idCreador FROM rubricas WHERE idCreador='". $_SESSION["id"] ."'";
    $result = $link->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["idCreador"]==$_SESSION["id"]){
                $_SESSION["rubricasTotalesUsuario"]++;
            }
        }
        return $_SESSION["rubricasTotalesUsuario"];
    }
}

// ----------------- Obtener nombre rubrica
function getRubricaName($link,$id){
    $sql = "SELECT id,nombre FROM rubricas where id = '". $id ."'";
    @$result = $link->query($sql);
    
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
              return $row["nombre"];
        }
    }else{
        header('Location: ..');
    }
}

// ----------------- Obtener descripcion rubrica
function getRubricaDescription($link,$id){
    $sql = "SELECT id,descripcion FROM rubricas where id = '". $id ."'";
    @$result = $link->query($sql);
    
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
              return $row["descripcion"];
        }
    }else{
        echo "Sin Descripción";
    }
}

// ----------------- Obtener rango de puntuaciones del selector rubrica
function getRubricaQualifies($puntuacion){ 
    for ($i = 1; $i <= $puntuacion; $i++) {
        echo '<th scope="col">'. $i .'</th>';
    }
}

// ----------------- Obtener puntuaciones de la rubrica
function getRubricaPuntuacion($link,$id){
    $sql = "SELECT puntuacion FROM rubricas WHERE id='".@$id."'";
    $result = $link->query($sql);
    if (@$result->num_rows > 0) {
    while($row = @$result->fetch_assoc()) {
        return $row["puntuacion"];
    }
    }
}

// ----------------- Obtener preguntas de la rubrica
function getRubricaQuestions($link,$id){
    $index = 1;
    $sql = "SELECT id,idRubrica,pregunta FROM rubricas_preguntas where idRubrica = '".@$id."'";
    @$result = $link->query($sql);
    if (@$result->num_rows > 0) {
        
        while($row = @$result->fetch_assoc()) {
            echo '<tr>';
            echo '<th scope="row"><textarea style="height:80px;width:300px;border:0px solid black;" readonly>'.$row["pregunta"].'</textarea></th>';
            for ($i = 1; $i <= getRubricaPuntuacion($link,$id); $i++) {
                echo '<td><input type="radio" name="'.$index.'" value="'.$i.'"></td>';
            }
            echo '</tr>';
            $index++;
        }
        
    }
}

// ----------------- Obtener numero de preguntas de la rubrica
function getRubricaNumeroPreguntas($link,$id){
    $preguntasTotales=0;
    $sql = "SELECT idRubrica FROM rubricas_preguntas";
    $result = $link->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["idRubrica"]==$id){
                $preguntasTotales++;
            }
        }
        return $preguntasTotales;
    }
}

// ----------------- Guardar rubrica en la base de datos
function guardarRubricaEnBaseDeDatos($link,$nombre,$descripcion,$idCreador,$puntuacion){
/*
BaseDeDatos: rubricas
Formato: informaciónDeConexión,nombre,descripcion,idCreador,puntuacion
*/

// comprobaciones
/*$n = htmlEntities($nombre, ENT_QUOTES);
$d = htmlEntities($descripcion, ENT_QUOTES);
$id = htmlEntities($idCreador, ENT_QUOTES);
$p = htmlEntities($puntuacion, ENT_QUOTES);*/

$n = str_replace("'",' ',$nombre);
$d = str_replace("'",' ',$descripcion);
$id = str_replace("'",' ',$idCreador);
$p = str_replace("'",' ',$puntuacion);
// insertar
$sql = "INSERT INTO rubricas (nombre, descripcion, idCreador, puntuacion)
VALUES ('$n','$d','$id','$p')";

if ($link->query($sql)=== TRUE) {
	//Rubrica creada
return $last_id = $link->insert_id;

} else {
echo "Error: " . $sql . "<br>" . $link->error;
return "f";
    
}
}

// ----------------- Guardar las preguntas de la rubrica en la base de datos
function guardarPreguntasEnBaseDeDatos($link,$idRubrica,$pregunta){
/*
BaseDeDatos: rubricas_preguntas
Formato: informaciónDeConexión,idRubrica,pregunta
*/

// comprobaciones
/*$id = htmlEntities($idRubrica, ENT_QUOTES);
$p = htmlEntities($pregunta, ENT_QUOTES);*/

$id = str_replace("'",' ',$idRubrica);
$p = str_replace("'",' ',$pregunta);
// insertar
$sql = "INSERT INTO rubricas_preguntas (idRubrica, pregunta)
VALUES ('$id','$p')";

if ($link->query($sql)=== TRUE) {
	//pregunta creada
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}
}

// ----------------- Guardar las respuestas de las rubricas en la base de datos
function guardarRespuestaEnBaseDeDatos($link,$IdRubrica,$IdPregunta,$Respuesta){
/*
BaseDeDatos: rubricas_respuestas
Formato: informaciónDeConexión,IdRubrica,IdPregunta,Respuesta
*/

// comprobaciones
/*$id = htmlEntities($IdPregunta, ENT_QUOTES);
$re = htmlEntities($Respuesta, ENT_QUOTES);
$idr = htmlEntities($IdRubrica, ENT_QUOTES);*/

$id = str_replace("'",' ',$IdPregunta);
$re = str_replace("'",' ',$Respuesta);
$idr = str_replace("'",' ',$IdRubrica);
// insertar
$sql = "INSERT INTO rubricas_respuestas (idPregunta, respuesta, idRubrica)
VALUES ('$id','$re','$idr')";
if ($link->query($sql)=== TRUE) {
	// si la rubrica se ha respondido correctamente crea una variable de sesión especial para evitar que pueda volver a ser respondida. (excepto si el que la responde es el creador de la rubrica)
    $_SESSION["rubricaRespondida-".$IdRubrica.""]=true;
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}
}

// ----------------- Obtener el ID del creador de la rubrica
function getIdCreadorRubrica($link,$IdRubrica){
    $sql = "SELECT idCreador FROM rubricas WHERE id=$IdRubrica";
    $result = $link->query($sql);
    if (@$result->num_rows > 0) {
    while($row = @$result->fetch_assoc()) {
        return $row["idCreador"];
    }
    }
}

// ----------------- Obtener respuestas de la rubrica por índice: del 1 al número de preguntas que tenga esa pregunta.
function getRespuestaPorIndice($link,$idRubrica,$idPregunta){
    $sql = "SELECT id FROM rubricas_preguntas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);
    $i = 1;
    $respuestas = [];
    if (@$result->num_rows > 0) {
    while($row = @$result->fetch_assoc()) {
        $respuestas[$i] = $row["id"];
        $i++;
    }

    foreach ($respuestas as $key => $val) {
        if($key==$idPregunta){
            return $val;
        }
    }

    }
}

// ----------------- Obtener el número de respuestas totales de una rubrica.
function getRubricaNumeroRespuestas($link,$idRubrica,$indicePregunta){
    $sql = "SELECT idRubrica,idPregunta,respuesta FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);
    $p = 0;
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            if($row["idPregunta"]==$indicePregunta){
                $p++;
            }
        }
    }
    if($p==0){
        return 1;
    }else{
    return $p;
    }
}

// ----------------- Obtener la media de las respuestas de una rubrica.
function getMediaRespuestas($link,$idRubrica,$indicePregunta){
    $sql = "SELECT idRubrica,idPregunta,respuesta FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);
    $media = 0;
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            if($row["idPregunta"]==$indicePregunta){
                $media+=$row["respuesta"];
            }
        }
    }

    try{
        return $media/getRubricaNumeroRespuestas($link,$idRubrica,$indicePregunta);
    }catch(Exception $e){
        return 0;
    }
    
}

// ----------------- Obtener el número de TODAS las respuestas de una rubrica.
function getRubricaNumeroRespuestasGlobal($link,$idRubrica){
    $sql = "SELECT idRubrica,idPregunta,respuesta FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);
    $p = 0;
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
                $p++;
        }
    }
    if($p==0){
        return 1;
    }else{
    return $p;
    }
}

// ----------------- Obtener la mediana global de todas las respuestas de una rubrica.
function getMediaGlobal($link,$idRubrica){
	$sql = "SELECT idRubrica,idPregunta,respuesta FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);
    $media = 0;
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
                $media+=$row["respuesta"];
        }
}

 try{
        return $media/getRubricaNumeroRespuestasGlobal($link,$idRubrica);
    }catch(Exception $e){
        return 0;
    }
}

// ----------------- Obtener la pregunta de una rubrica
function getRubricaPregunta($link,$idPregunta){
    $sql = "SELECT pregunta FROM rubricas_preguntas WHERE id=$idPregunta";
    $result = $link->query($sql);
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            return $row["pregunta"];
        }
    }

}

// ----------------- Obtener la pregunta de una rubrica y otorgar su ID
function getRubricaPreguntaId($link,$id){
    $sql = "SELECT id FROM rubricas_preguntas WHERE id=$id";
    $result = $link->query($sql);
    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            echo $row["id"];
        }
    }
}

// ----------------- Obtener las respuestas de la rubrica
function getRubricaRespuestas($link,$idRubrica){
    $sql = "SELECT idPregunta,respuesta FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    $result = $link->query($sql);

    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            //echo getRubricaPregunta($link,getRespuestaPorIndice($link,$idRubrica,$row["idPregunta"])) .' - ' .$row["respuesta"].' - I: <br>';
            return $row["respuesta"];
        }
    }
}

// ----------------- Obtener las respuestas de una rubrica según su ID
function getRubricaRespuestaPorId($link,$id){
    $sql = "SELECT respuesta FROM rubricas_respuestas WHERE id=$id";
    $result = $link->query($sql);

    if (@$result->num_rows > 0) {
        while($row = @$result->fetch_assoc()) {
            return $row["respuesta"];
        }
    }
}

// ----------------- Eliminar usuarios y rubricas relacionadas.
function eliminarUsuarioyRubricasRelacionadas($link,$idUsuario){

}

// ----------------- Eliminar rubricas relacionadas.
function eliminarRubricayRelacionados($link,$idRubrica){
    //Eliminar Rubrica Completamente
    $eliminarRubricas = "DELETE FROM rubricas WHERE id=$idRubrica";
    $eliminarPreguntas = "DELETE FROM rubricas_preguntas WHERE idRubrica=$idRubrica";
    $eliminarRespuestas = "DELETE FROM rubricas_respuestas WHERE idRubrica=$idRubrica";
    
    $link->query($eliminarRubricas);
    $link->query($eliminarPreguntas);
    $link->query($eliminarRespuestas);
}

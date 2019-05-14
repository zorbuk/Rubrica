<?php
require 'config/db.php';
include_once 'config/definitions.php';
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo $WEB_LOGO; ?>">

    <title><?php echo $WEB_TITLE; ?></title>
    <link href="community/boostrap/bootstrap.min.css" rel="stylesheet">
    <link href="community/boostrap/floating-labels.css" rel="stylesheet">
  </head>

<body>

<?php
session_start();
 
// Comprueba si el usuario está conectado, si es así lo lleva al espacio de usuarios conectados.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: community");
    exit;
}
 
// Definimos las variables necesarias y las iniciamos vacías.
$username = $password = "";
$username_err = $password_err = "";
 
// Si obtenemos un POST...
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Comprobamos si el nombre de usuario esta vacío.
    if(empty(trim($_POST["username"]))){
        $username_err = "Entra un nombre de usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Comprobamos si la contraseña está vacía.
    if(empty(trim($_POST["password"]))){
        $password_err = "Entra tu contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validamos.
    if(empty($username_err) && empty($password_err)){
        // Preparamos la consulta.
        $sql = "SELECT id, cuenta, clave, fRegistro, baneado, textoEstado, avatar, rango FROM cuentas WHERE cuenta = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Aseguramos las variables.
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Seteamos el nombre d eusuario.
            $param_username = $username;
            
	    // Comprobamos que el captcha ha sido introducido y es correcto.
            if(isset($_POST["captcha"]))  
            if(@$_SESSION["captcha"]==$_POST["captcha"])  
            {    
               
                 // Ejecutamos la consulta.
            if(mysqli_stmt_execute($stmt)){
                // Almacenamos el resultado.
                mysqli_stmt_store_result($stmt);
                
                // Comprobamos si el usuario existe, si existe entonces pasamos a comprobar la contraseña.
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Aseguramos el resultado.
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $fRegistro, $baneado, $textoEstado, $avatar, $rango);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // La contraseña es correcta, por lo tanto iniciamos sesion.
                            session_start();
                            
                            // Almacenamos los valores de la sessión en variables de $_SESSION[];
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["fRegistro"] = $fRegistro;   
                            $_SESSION["baneado"] = $baneado;
                            $_SESSION["textoEstado"] = $textoEstado;  
                            $_SESSION["avatar"] = $avatar; 
                            $_SESSION["rango"] = $rango; 

                            // Redirigimos al usuario al espacio de usuarios conectados.
                            header("location: community");
                        } else{
                            // Muestra un mensaje de error si la contraseña no es correcta.
                            $password_err = "La contraseña otorgada no es válida.";
                        }
                    }
                } else{
                    // Muestra un mensaje de error si no encuentra ninguna cuenta con ese nombre.
                    $username_err = "No se ha encontrado ninguna cuenta con este nombre.";
                }
            } else{
		// Muestra un mensaje de error general si algo falla.
                echo "Oops! Algo fue mal, intentalo de nuevo más tarde.";
            }
            
            }  
            else
            {  
		// El captcha es incorrecto.
                echo '<div class="alert alert-danger">¡El captcha introducido no es correcto!</div>';  
            }

        }
        
        // Cierra la consulta.
        mysqli_stmt_close($stmt);
    }
    
    // Cierra la conexión.
    mysqli_close($link);
}
?>

	<!-- Formulario de Login -->
    <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="text-center mb-4">
      <img class="mb-4" src="<?php echo $WEB_LOGO; ?>" alt="" width="250" height="72">
        <p>Bienvenido, conectate a <?php echo $WEB_TITLE; ?> para acceder a nuestros servicios. Si no dispones de Cuenta <?php echo $WEB_TITLE; ?> puedes crear una desde <a href="registration.php">este enlace.</a></p>
      </div>

      <div class="form-label-group">
        <input name="username" type="text" id="inputText" class="form-control" placeholder="Account" required autofocus>
        <label for="inputText">Cuenta <?php echo $WEB_TITLE; ?></label>
      </div>

      <div class="form-label-group">
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <label for="inputPassword">Contraseña</label>
      </div>

      <div class="form-label-group">
        <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="validationTooltipUsernamePrepend"><img src="config/captcha.php" /></span>
        </div>
        <input name="captcha" type="text" id="captcha" class="form-control" minlength="6" required>
      </div>
<br>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Conectarse</button>
      <hr>
<!-- script google translate -->
      <div id="google_translate_element"></div>
<script  type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'es'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<hr>
      <p class="mt-5 mb-3 text-muted text-center">&copy; 2018-2019</p>
    </form>
  </body>
</html>

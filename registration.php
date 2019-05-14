<?php
require 'config/db.php';
include_once 'config/definitions.php';
session_start();  
//require_once 'config/captcha.php';
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
    <link href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.1/examples/floating-labels/floating-labels.css" rel="stylesheet">
  </head>

<body>

<?php
// Definimos las variables necesarias y las iniciamos vacías.
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Si obtenemos un POST...
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Comprobamos si el nombre de usuario esta vacío.
    if(empty(trim($_POST["username"]))){
        $username_err = "Entra un nombre de usuario.";
    } else{
        // Preparamos la consulta.
        $sql = "SELECT id FROM cuentas WHERE cuenta = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Aseguramos las variables.
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Seteamos el nombre de usuario.
            $param_username = trim($_POST["username"]);
            
            // Ejecutamos la consulta.
            if(mysqli_stmt_execute($stmt)){
                // Almacenamos el resultado.
                mysqli_stmt_store_result($stmt);
                
		//Comprobamos si el nombre de usuario ya existe.
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Algo ha ido mal, intentalo de nuevo más tarde.";
            }
        }
         
        // Cerramos consulta
        mysqli_stmt_close($stmt);
    }
    
    // Validamos la contraseña
    if(empty(trim($_POST["password"]))){
        $password_err = "Entra una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña tiene que ser de mínimo 6 carácteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validamos la confirmación de contraseña
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirmar la contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no cohinciden.";
        }
    }
    
    // Vemos si hay errores antes de insertar a la base de datos.
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Preparamos la consulta SQL.
        $sql = "INSERT INTO cuentas (cuenta, clave) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bindeamos los parámetros username y password.
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Seteamos los parametros.
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); //<- Encripta la contraseña con HASH.
            
	    // Comprobamos que el captcha es correcto y está introducido.
            if(isset($_POST["captcha"]))  
            if(@$_SESSION["captcha"]==$_POST["captcha"])  
            {    
                // Ejecutamos la consulta.
            if(mysqli_stmt_execute($stmt)){
                // Si la consulta es correcta, vuelve al login.
                header("location: index.php");
            } else{
                echo "Oops! Algo ha ido mal, intentalo de nuevo más tarde.";
            }
            }  
            else
            {  
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

<!-- Formulario de Registro -->
    <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="text-center mb-4">
        <img class="mb-4" src="<?php echo $WEB_LOGO; ?>" alt="" width="250" height="72">
        <p>¿Ya tienes cuenta en <?php echo $WEB_TITLE; ?>? <a href="index.php">Conectate.</a></p>
      </div>

<p style="border:1px solid teal;border-radius:5px;padding:5px;">Datos de la Cuenta</p>
      <div class="form-label-group">
        <input name="username" type="text" id="Username" class="form-control" placeholder="Account" minlength="2" maxlength="16" required autofocus>
        <label for="Username">Cuenta <?php echo $WEB_TITLE; ?></label>
      </div>

      <div class="form-label-group">
        <input name="password" type="password" id="password" class="form-control" placeholder="Password" minlength="6" required>
        <label for="password">Contraseña</label>
      </div>

      <div class="form-label-group">
        <input name="confirm_password" type="password" id="confirm_password" class="form-control" placeholder="RepeatPassword" minlength="6" required>
        <label for="confirm_password">Repetir Contraseña</label>
      </div>

      <p style="border:1px solid purple;border-radius:5px;padding:5px;">Captcha</p>
      <div class="form-label-group">
        <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="validationTooltipUsernamePrepend"><img src="config/captcha.php" /></span>
        </div>
        <input name="captcha" type="text" id="captcha" class="form-control" minlength="6" required>
      </div>


      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Registrarse</button>
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

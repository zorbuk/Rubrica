<!-- header -->
<?php
// incluimos las definiciones en todas las páginas.
include_once '../config/definitions.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../<?php echo $WEB_LOGO; ?>">

    <title><?php echo $WEB_TITLE; ?></title>

	<!-- css boostrap + custom -->
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="community/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="community/bootstrap/bootstrap_n.min.css" rel="stylesheet">
    <link href="community/bootstrap/floating-labels.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
    <link href="bootstrap/offcanvas.css" rel="stylesheet">
  </head>

  <body class="bg-light">

    </div>
<!-- navegador de página (enlaces) -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark">

      <img heigth="168px" width="168px" src="../<?php echo $WEB_LOGO; ?>"> <a class="navbar-brand mr-auto mr-lg-0" href="#"></a>

    </nav>

    <div class="nav-scroller-flex shadow-sm">

      <nav class="nav nav-underline">

      <a class="nav-link" href="logout.php">Desconectarse</a>

      <a class="nav-link" href="index.php">

        Lísta de Rubricas
					<!-- obtener número de rubricas creadas por el usuario -->
          <span class="badge badge-pill bg-light align-text-bottom"><?php echo getRubricasCreadas($link); ?></span>
        </a>
        <a class="nav-link" href="crear.php">Crear rubrica</a>


      </nav>

<!-- perfil del usuario con su fecha de registro, avatar, nombre y el texto de estado -->
    <main role="main" class="container">
      <div class="d-flex align-items-center p-3 my-3 bg-purple rounded shadow-sm">
        <img class="mr-3" src="<?php echo htmlspecialchars($_SESSION["avatar"]); ?>" alt="" width="58px" height="62px">
        <div class="lh-100">
          <h6 class="mb-0 lh-100"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h6>
          <small><?php echo htmlspecialchars($_SESSION["textoEstado"]); ?></small><br>
          <?php
          $fechas = explode(" ", $_SESSION["fRegistro"]);;
          ?>
          <small>Registrado el <?php echo $fechas[0]; ?> a las <?php echo $fechas[1]; ?></small>
        </div>
      </div>

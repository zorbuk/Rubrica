<?php
require '../config/checkConnected.php';
require '../config/db.php';
require '../gParts/header.php';

//Paginación
if (isset($_GET['p']) && $_GET['p']!="") {
    $page_no = $_GET['p'];
    } else {
        $page_no = 1;
        }

  $total_records_per_page = 5;
  
  $offset = ($page_no-1) * $total_records_per_page;
  $previous_page = $page_no - 1;
  $next_page = $page_no + 1;
  $adjacents = "2";
  @$result_count = mysqli_query(
    $link,
    "SELECT COUNT(*) As total_records FROM `rubricas` where idCreador = '".@$_SESSION["id"]."'"
    );
    $total_records = mysqli_fetch_array(@$result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1;
    //Fin Paginación

    //Función: Borrar
    if(isset($_GET["borrar"])){
        if(getIdCreadorRubrica($link,$_GET["borrar"])==$_SESSION["id"]){
            eliminarRubricayRelacionados($link,$_GET["borrar"]);
            header("location: index.php");
        }
    }
?>
    
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0"><b>Tus Rubricas</b></h6>
	<!-- Lista de Rubricas de un usuario -->
        <?php
$sql = "SELECT id,nombre,descripcion,idCreador FROM rubricas ORDER by id DESC LIMIT $offset, $total_records_per_page";
$result = $link->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if($row["idCreador"]==$_SESSION["id"]){
            echo '<div style="word-wrap: break-word;" class="media text-muted pt-3 text-truncate">
            <img width="42" height="42" src="../images/checklist.png" alt="" class="mr-2 rounded">
            <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <a href="../share/rubrica.php?id=';
            echo $row["id"];
            echo '">Compartir <strong class="text-gray-dark">';
            echo $row["nombre"];
            echo '</strong></a><br>';
            echo $row["descripcion"];
            echo '<br><a href="resultados.php?rubrica=';
            echo $row["id"];
            echo '">Ver Resultados</a> <a style="word-wrap: break-word;float:right;" href="index.php?borrar='.$row["id"].'"><i style="color:red;"><img src="../images/trash.png"></i></a>';
            echo '
              </p>
          </div>'; 
        }
    }

// si el usuario no tiene rubricas.
/*if($_SESSION["rubricasTotalesUsuario"]==0){
        echo '<div class="alert alert-warning" role="alert"><img heigth="100" width="100" src="https://d3ow3r1jtq29si.cloudfront.net/bVB3MzykSGyddlV3XIQe_dancing+banana.gif"><br>Parece que <b>no has creado rubricas</b> por ahora,<br>si quieres crear tu primera rubrica puedes hacerlo <a href="crear.php">en este enlace</a>.</div>';
    }*/

} else {
// si el usuario no tiene rubricas.
if($_SESSION["rubricasTotalesUsuario"]==0){
        echo '<div class="alert alert-warning" role="alert"><img heigth="100" width="100" src="https://d3ow3r1jtq29si.cloudfront.net/bVB3MzykSGyddlV3XIQe_dancing+banana.gif"><br>Parece que <b>no has creado rubricas</b> por ahora,<br>si quieres crear tu primera rubrica puedes hacerlo <a href="crear.php">en este enlace</a>.</div>';
    }
}
//$result->close();
//$link->close();
if($_SESSION["rubricasTotalesUsuario"]>=1){
?>

<!-- Paginación -->
<nav aria-label="Page navigation example">
<ul class="pagination overflow-auto flex-wrap">
<?php if($page_no > 1){
echo "<li><a class='page-link' href='index.php?p='>Primera</a></li>";
} ?>
    
<li class="page-item" <?php if($page_no <= 1){ echo "class='page-item'"; } ?>>
<a class='page-link' <?php if($page_no > 1){
echo "href='index.php?p=$previous_page'";
} ?>>Anterior</a>
</li>
    
<li <?php if($page_no >= $total_no_of_pages){
echo "class='page-item'";
} ?>>
<a class='page-link' <?php if($page_no < $total_no_of_pages) {
echo "href='index.php?p=$next_page'";
} ?>>Siguiente</a>
</li>

<?php if($page_no < $total_no_of_pages){
echo "<li class='page-item'><a class='page-link' href='index.php?p=$total_no_of_pages'>Última</a></li>";
} ?>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
Página <?php echo $page_no." de ".$total_no_of_pages; ?>
</div>
</ul>
</nav>
<!-- Fin de Paginación -->

        <?php
        }
require '../gParts/footer.php';
        ?>

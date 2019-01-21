<?php require_once('../Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('../includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "../");
//Grand Levels: Level
$restrict->addLevel("2");
$restrict->Execute();
//End Restrict Access To Page

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "admintema")) {
  $updateSQL = sprintf("UPDATE admintema SET temaadi=%s WHERE id=%s",
                       GetSQLValueString($_POST['tema'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglanti, $baglanti);
  $Result1 = mysql_query($updateSQL, $baglanti) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_baglanti, $baglanti);
$query_tema = "SELECT * FROM admintema";
$tema = mysql_query($query_tema, $baglanti) or die(mysql_error());
$row_tema = mysql_fetch_assoc($tema);
$totalRows_tema = mysql_num_rows($tema);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-stack-overflow"></i> ADMİN PANEL TEMASI</h3>
  </div>
  <div class="panel-body">
  ŞUAN AKTİF OLAN TEMA; <?php echo $row_tema['temaadi']; ?>
   <form action="<?php echo $editFormAction; ?>" name="admintema" class="form-inline" method="POST" id="admintema">
  <div class="form-group">
    <label for="tema">Tema Seç</label>
   <select name="tema" id="tema" class="form-control">
     <option value="cerulean">Cerulean</option>
     <option value="cosmo">Cosmo</option>
     <option value="cyborg">Cyborg</option>
     <option value="darkly">Darkly</option>
     <option value="flatly">Flatly</option>
     <option value="journal">Journal</option>
     <option value="lumen">Lumen</option>
     <option value="paper">Paper</option>
     <option value="readable">Readable</option>
     <option value="sandstone">Sandstone</option>
     <option value="simplex">Simplex</option>
     <option value="slate">Slate</option>
     <option value="spacelab">Spacelab</option>
     <option value="superhero">Superhero</option>
     <option value="united">United</option>
     <option value="yeti">Yeti</option>
      </select>
  </div>
  <button id="myButton" type="submit" class="btn btn-default" data-loading-text="Loading..." autocomplete="off">KAYDET</button>
  <input name="id" type="hidden" value="1" />
  <input type="hidden" name="MM_update" value="admintema" />
   </form><script>
  $('#myButton').on('click', function () {
    var $btn = $(this).button('loading')
    // business logic...
    $btn.button('reset')
  })
</script>
   <hr />
   <table width="100%" border="0">
     <tr>
       <td align="center"><img src="https://bootswatch.com/cerulean/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/cosmo/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/cyborg/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/paper/thumbnail.png" alt="..." class="img-thumbnail"></td>
     </tr>
     <tr>
       <td align="center"><img src="https://bootswatch.com/darkly/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/flatly/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/journal/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/lumen/thumbnail.png" alt="..." class="img-thumbnail"></td>
     </tr>
     <tr>
       <td align="center"><img src="https://bootswatch.com/readable/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/sandstone/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/simplex/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/slate/thumbnail.png" alt="..." class="img-thumbnail"></td>
     </tr>
     <tr>
       <td align="center"><img src="https://bootswatch.com/spacelab/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/superhero/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/united/thumbnail.png" alt="..." class="img-thumbnail"></td>
       <td align="center"><img src="https://bootswatch.com/yeti/thumbnail.png" alt="..." class="img-thumbnail"></td>
     </tr>
     
   </table>
   <hr />
  </div>
</div>

</body>
</html>
<?php
mysql_free_result($tema);
?>

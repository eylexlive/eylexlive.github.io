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

$colname_uyegels = "-1";
if (isset($_GET['id'])) {
  $colname_uyegels = $_GET['id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_uyegels = sprintf("SELECT * FROM authme WHERE id = %s", GetSQLValueString($colname_uyegels, "int"));
$uyegels = mysql_query($query_uyegels, $baglanti) or die(mysql_error());
$row_uyegels = mysql_fetch_assoc($uyegels);
$totalRows_uyegels = mysql_num_rows($uyegels);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<?php if ($totalRows_uyegels > 0) { // Show if recordset not empty ?>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">KULLANICI; <?php echo $row_uyegels['username']; ?></h3>
    </div>
    <div class="panel-body"> Üye Adı; <?php echo $row_uyegels['username']; ?><br />
      Üye E-Posta; <?php echo $row_uyegels['email']; ?><br />
      Yetki Durumu; 
      <?php 

if ($row_uyegels['level'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-success">Normal ÜYE</span> '; 
} 
elseif ($row_uyegels['level'] == '2') 
{ 
    echo '<span class="btn btn-xs btn-warning">YÖNETİCİ <small>(Administrator)</small></span>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger">404 Hata</span>'; 
} 

?>
      <br />
    </div>
  </div>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_uyegels == 0) { // Show if recordset empty ?>
  <center><p>
  <h2>404 HATA</h2><br />
    ÜYE BULUNAMADI...
  </p>
  </center>
  <?php } // Show if recordset empty ?>
</body>
</html>
<?php
mysql_free_result($uyegels);
?>

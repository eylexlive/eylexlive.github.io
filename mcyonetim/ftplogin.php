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

mysql_select_db($database_baglanti, $baglanti);
$query_ftpgel = "SELECT * FROM ftpsettings WHERE id = 1";
$ftpgel = mysql_query($query_ftpgel, $baglanti) or die(mysql_error());
$row_ftpgel = mysql_fetch_assoc($ftpgel);
$totalRows_ftpgel = mysql_num_rows($ftpgel);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body><div class="alert alert-dismissible alert-info">
  <strong>BİLGİ!</strong> Default kullanıcı adı; admin Parola; admin giriş yaptıkdan sonra parolanızı değiştirmeyi unutmayınız
</div><ul class="nav nav-pills">
<li ><a href="index.php?mc=ftp"><i class="fa fa-cogs"></i> FTP AYARLARI</a></li>
    <li class="active"><a href="?mc=ftplogin"><i class="fa fa-sign-in"></i> FTP GİRİŞİ</a></li>
  
</ul>
<hr />
<?php 
    echo '<iframe width=800 frameborder=0 height=700 src=ftpserver/index.php></iframe>' ; 
?>

</body>
</html>
<?php
mysql_free_result($ftpgel);
?>

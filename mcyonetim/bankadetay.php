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
$query_bank = "SELECT * FROM bankahesaplari ORDER BY id DESC";
$bank = mysql_query($query_bank, $baglanti) or die(mysql_error());
$row_bank = mysql_fetch_assoc($bank);
$totalRows_bank = mysql_num_rows($bank);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=bankadetay"><i class="fa fa-university"></i> Eklenen Bankalar</a></li>
    <li><a href="?mc=bankaadd"><i class="fa fa-university"></i> Yeni Banka Ekle</a></li>
  
</ul>

<hr />

<table width="100%" class="table">
  <tr>
    <th width="4%" height="30">#ID</th>
    <th width="31%" height="30">Banka Adı</th>
    <th width="20%">Hesab NO</th>
    <th width="23%">Hesab Sahibi</th>
    <th width="12%" height="30">Durum</th>
    <th width="7%" height="30">İŞLEM</th>
  </tr>
  <?php do { ?>
    <tr>
      <td width="4%" height="30"><?php echo $row_bank['id']; ?></td>
      <td height="30"><?php echo $row_bank['bankaadi']; ?></td>
      <td height="30"><?php echo $row_bank['hesapno']; ?></td>
      <td height="30"><?php echo $row_bank['hesabsahibi']; ?></td>
      <td height="30" align="center"><?php 

if ($row_bank['aktif'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-success btn-block">AKTİF</span> '; 
} 
elseif ($row_bank['aktif'] == '0') 
{ 
    echo '<span class="btn btn-xs btn-warning btn-block">PASİF</span>'; 
} 

else 
{ 
    echo '<span class="btn btn-xs btn-danger btn-block">404 Hata</span>'; 
} 

?></td>
      <td height="30"><a class="btn btn-danger btn-xs btn-block" onclick="if(deleteConfirm()) window.location='?mc=bankadelete&amp;id=<?php echo $row_bank['id']; ?>';" role="button"><i class="fa fa-trash"></i> SİL</a></td>
    </tr>
    <?php } while ($row_bank = mysql_fetch_assoc($bank)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($bank);
?>

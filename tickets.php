<?php require_once('Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "");
//Grand Levels: Level
$restrict->addLevel("1");
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

$colname_ticgel = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_ticgel = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_ticgel = sprintf("SELECT * FROM ticket WHERE musteriID = %s ORDER BY id DESC", GetSQLValueString($colname_ticgel, "int"));
$ticgel = mysql_query($query_ticgel, $baglanti) or die(mysql_error());
$row_ticgel = mysql_fetch_assoc($ticgel);
$totalRows_ticgel = mysql_num_rows($ticgel);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-ticket"></i> Ticket Sistemi</span></div>
    <div class="content">
    
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#DEDEDE">&nbsp;&nbsp;<strong>&nbsp;Toplam Ticket Sayısı;  </strong><?php echo $totalRows_ticgel ?></td>
          <td align="right" bgcolor="#DEDEDE"><a href="newticket" title="YENİ DESTEK TALEBİ GÖNDER"><strong>YENİ DESTEK TALEBİ GÖNDER</strong></a>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><?php if ($totalRows_ticgel == 0) { // Show if recordset empty ?>
  <center><h1>Aktif destek talebi bulunumadı...</h1></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_ticgel > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0">
    <tr class="table border">
      <td width="20%" height="35" bgcolor="#8F8F8F"><strong>Departman</strong></td>
      <td width="50%" height="35" bgcolor="#8F8F8F"><strong>Başlık</strong></td>
      <td height="35" colspan="2" bgcolor="#8F8F8F"><strong>Durum</strong></td>
    </tr>
    <?php do { ?>
      <tr class="table border">
        <td height="35" bgcolor="#D6D6D6"><strong><span style="font-size:12px"><?php echo $row_ticgel['departman']; ?></span></strong></td>
        <td height="35" bgcolor="#D6D6D6"><a onclick="javascript(<?php echo $row_ticgel['musteriID']; ?>)" >#<?php echo $row_ticgel['id']; ?> </a><?php echo $row_ticgel['baslik']; ?></td>
        <td width="24%" height="35" bgcolor="#D6D6D6"><?php 

if ($row_ticgel['durum'] == '0') 
{ 
    echo ' <button class="button danger loading-pulse lighten mini-button">Cevap Bekliyor</button> '; 
} 
elseif ($row_ticgel['durum'] == '1') 
{ 
    echo '<button class="button mini-button">Cevaplandı</button>'; 
} 
elseif ($row_ticgel['durum'] == '2') 
{ 
    echo '<button class="button mini-button">KAPANDI</button>'; 
} 
else 
{ 
    echo 'HATA'; 
} 

?></td>
        <td width="6%" height="35" bgcolor="#D6D6D6">
        <?php 

if ($row_ticgel['durum'] == '0') 
{ ?>
   <form id="form<?php echo $row_ticgel['id']; ?>" name="form<?php echo $row_ticgel['id']; ?>" method="post" action="d<?php echo $row_ticgel['id']; ?>-<?php echo permalink($row_ticgel['baslik']); ?>.htm">
          <label>
            <button class="button mini-button" type="submit" name="durum" id="durum"> İncele</button>
            
            </label>
        </form> 
<?php }
elseif ($row_ticgel['durum'] == '1') 
{ ?> 
    <form id="form<?php echo $row_ticgel['id']; ?>" name="form<?php echo $row_ticgel['id']; ?>" method="post" action="d<?php echo $row_ticgel['id']; ?>-<?php echo permalink($row_ticgel['baslik']); ?>.htm">
          <label>
            <button class="button mini-button" type="submit" name="durum" id="durum"> İncele</button>
            
            </label>
        </form>
<?php } 
elseif ($row_ticgel['durum'] == '2') 
{ ?>
   <strong>KAPALI</strong>
<?php } 
else 
{ 
?>  
  <strong>HATA</strong>
<?php } 

?>
        </td>
      </tr>
      <?php } while ($row_ticgel = mysql_fetch_assoc($ticgel)); ?>
  </table>
  <?php } // Show if recordset not empty ?></td>
        </tr>
      </table>
    </div></div>
</body>
</html>
<?php
mysql_free_result($ticgel);
?>

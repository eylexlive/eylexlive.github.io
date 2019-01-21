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
$query_credito = "SELECT * FROM credituser WHERE durum = '1' ORDER BY id DESC";
$credito = mysql_query($query_credito, $baglanti) or die(mysql_error());
$row_credito = mysql_fetch_assoc($credito);
$totalRows_credito = mysql_num_rows($credito);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="breadcrumb">
	
    <li><a href="?mc=credit">Onay Bekleyen Kredi Hareketleri</a></li>
    <li class="active">Onaylanan Kredi hareketleri</li>
</ul>

<hr />
<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-credit-card"></i> Onaylanan Kredi Hareketleri</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_credito == 0) { // Show if recordset empty ?>
  <center>
    <h3>Onaylanan  Kredi Yok...</h3></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_credito > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table">
    <tr>
      <th width="25%" height="35"><i class="fa fa-user"></i> MÜŞTERİ</th>
      <th width="35%" height="35"><i class="fa fa-shopping-cart"></i> SATILAN KREDİ</th>
      <th width="26%" height="35"><i class="fa fa-university"></i> ÖDEME YÖNTEMİ</th>
      <th width="14%">DURUM</th>
      </tr>
    <?php do { ?>
      <tr style="font-size:18px">
        <td><?php echo $row_credito['musteriadi']; ?></td>
        <td><span class="btn btn-xs btn-success"><?php echo $row_credito['miktar']; ?>.00 <i class="fa fa-try"></i></span></td>
        <td><?php 

if ($row_credito['method'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-default"><i class="fa fa-money"></i> Banka Havalesi</span> '; 
} 
elseif ($row_credito['method'] == '2') 
{ 
    echo '<span class="btn btn-xs btn-default"><i class="fa fa-cc-paypal"></i> Paypal ile Öde</span>'; 
} 
else 
{ 
    echo '<span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>'; 
} 

?></td>
        <td><strong>ÖDENDİ</strong></td>
        </tr>
      <?php } while ($row_credito = mysql_fetch_assoc($credito)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>
<div class="alert alert-dismissible alert-danger">
  <h4><i class="fa fa-exclamation-triangle"></i> UYARI!</h4>
  <p>Müşteri siparişi verdikten sonra krediyi eklemeniz gerekmektedir. Aksi taktirde müşterinin kredisi 0 görünür.</p>
</div>

</body>
</html>
<?php
mysql_free_result($credito);
?>

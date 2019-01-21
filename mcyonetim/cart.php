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
$query_onay = "SELECT * FROM cart WHERE servisdurumu = '0' ORDER BY siparisID DESC";
$onay = mysql_query($query_onay, $baglanti) or die(mysql_error());
$row_onay = mysql_fetch_assoc($onay);
$totalRows_onay = mysql_num_rows($onay);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=cart"><i class="fa fa-balance-scale"></i> Ödeme Bekleyen Siparişler</a></li>
    <li><a href="?mc=cartaktif"><i class="fa fa-cube"></i> Onaylanan Siparişler</a></li>
    <li><a href="?mc=cartpasif"><i class="fa fa-flag"></i> Hizmet Süresi Sona Eren Siparişler</a></li>
  
</ul>
<hr />

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-basket"></i> Satın Alma Geçmişi <small style="float:right">Verilen En Son Siparişler</small></h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_onay == 0) { // Show if recordset empty ?>
  <center><h1>Onay bekleyen sipariş bulunamadı!</h1></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_onay > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table table-bordered table-hover">
    <tr class="info">
      <th width="10%"><i class="fa fa-key"></i> S-ID</th>
      <th width="43%"><i class="fa fa-user"></i> Müşteri Adı</th>
      <th width="25%"><center><i class="fa fa-question-circle"></i> Durum</center></th>
      <th width="25%"><center><i class="fa fa-pencil-square-o"></i> İŞLEM</center></th>
    </tr>
    <?php do { ?>
      <tr class="warning">
        <td width="10%"><strong><?php echo $row_onay['siparisID']; ?></strong></td>
        <td><?php echo $row_onay['musteriadi']; ?></td>
        <td>
          <center><?php 

if ($row_onay['servisdurumu'] == '1') 
{ 
    echo ' <span class="btn btn-xs btn-success">AKTİF</span> '; 
} 
elseif ($row_onay['servisdurumu'] == '0') 
{ 
    echo '<span class="btn btn-xs btn-warning">ÖDEME ONAYI BEKLİYOR</span>'; 
} 
elseif ($row_onay['servisdurumu'] == '2') 
{ 
    echo '<span class="btn btn-xs btn-default">HİZMET SÜRESİ DOLDU</span>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger">404 Hata</span>'; 
} 

?></center>
        </td>
        <td><center><a class="btn btn-primary btn-block btn-xs" href="?mc=cart_detay&amp;siparisID=<?php echo $row_onay['siparisID']; ?>" role="button"><i class="fa fa-paper-plane-o"></i> Sipariş İşlemleri</a></center></td>
      </tr>
      <?php } while ($row_onay = mysql_fetch_assoc($onay)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>
<div class="alert alert-dismissible alert-warning">
  <h4><i class="fa fa-bell"></i> DİKKAT!</h4>
  <p>Destek sistemini kontrol ederek, siparişi onaylamayı unutmayınız. Onayladıgınız siparişleri oyun içerisinde yetkili olarak vere bilirsiniz. Panel üzerinden onaylanan market ürünleri otomtik olarak onaylanmamaktadır. </p>
</div>
</body>
</html>
<?php
mysql_free_result($onay);
?>

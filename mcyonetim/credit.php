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
$query_credito = "SELECT * FROM credituser WHERE durum = '0' ORDER BY id DESC";
$credito = mysql_query($query_credito, $baglanti) or die(mysql_error());
$row_credito = mysql_fetch_assoc($credito);
$totalRows_credito = mysql_num_rows($credito);

mysql_select_db($database_baglanti, $baglanti);
$query_kredionay = "SELECT * FROM credituser WHERE eklendimi = '0' && durum = '1' ORDER BY id ASC";
$kredionay = mysql_query($query_kredionay, $baglanti) or die(mysql_error());
$row_kredionay = mysql_fetch_assoc($kredionay);
$totalRows_kredionay = mysql_num_rows($kredionay);

mysql_select_db($database_baglanti, $baglanti);
$query_uyecont = "SELECT * FROM credit ORDER BY musteriID ASC";
$uyecont = mysql_query($query_uyecont, $baglanti) or die(mysql_error());
$row_uyecont = mysql_fetch_assoc($uyecont);
$totalRows_uyecont = mysql_num_rows($uyecont);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


</head>
<body>
<ul class="breadcrumb">
	<li><a href="?mc=creditactive">Onaylanan Kredi hareketleri</a></li>
    <li class="active">Onay Bekleyen Kredi Hareketleri</li>
  
</ul>

<hr />
<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-credit-card"></i> Onay Bekleyen Kredi Hareketleri</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_credito == 0) { // Show if recordset empty ?>
  <center><h3>Onay Bekleyen Kredi Yok...</h3></center>
  <?php } // Show if recordset empty ?>
  
<?php if ($totalRows_credito > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table">
    <tr>
      <th width="22%" height="35"><i class="fa fa-user"></i> MÜŞTERİ</th>
      <th width="32%" height="35"><i class="fa fa-shopping-cart"></i> EKLENECEK KREDİ</th>
      <th width="35%" height="35"><i class="fa fa-university"></i> ÖDEME YÖNTEMİ</th>
      <th width="11%" height="35"><i class="fa fa-filter"></i> İŞLEM</th>
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
        <td><a href="index.php?mc=creditedit&id=<?php echo $row_credito['id']; ?>" class="btn btn-primary btn-xs">İNCELE</a></td>
      </tr>
      <?php } while ($row_credito = mysql_fetch_assoc($credito)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>

<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-credit-card"></i> Kredisi eklenmemiş üyeler</h3>
  </div>
  <div class="panel-body">

 <?php if ($totalRows_kredionay == 0) { // Show if recordset empty ?>
  <center><h3>Kredi hareketi Yok...</h3></center>
  <?php } // Show if recordset empty ?>
  <?php if ($totalRows_kredionay > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" class="table">
    <tr>
      <th width="25%">MÜŞTERİ</th>
      <th width="21%">KREDİ MİKTARI</th>
      <th width="35%">EKLENDİMİ</th>
      <th width="19%">İŞLEM</th>
      </tr>
    <?php do { ?>
      <tr style="font-size:18px">
        <td><?php echo $row_kredionay['musteriadi']; ?></td>
        <td><span class="btn btn-xs btn-success"><?php echo $row_kredionay['miktar']; ?>.00 <i class="fa fa-try"></i></span></td>
        <td style="color:#C00">KREDİ EKLENMEDİ</td>
        <td align="center">
           
<?php 
	
	$mid= $row_kredionay['musteriID'];
	$adsoyad=mysql_query("Select musteriID from credit where musteriID='{$mid}'");
	$adsoyadsonuc=mysql_fetch_object($adsoyad);
					
					
if ($row_kredionay['musteriID'] == $adsoyadsonuc->musteriID) 
{ ?>
   <form method="POST" action="index.php?mc=creditregister" id="forms<?php echo $row_kredionay['token']; ?>">
              <input type="hidden" name="musteriID" id="musteriID" value="<?php echo $row_kredionay['musteriID']; ?>" />
              <input type="hidden" name="miktar" id="miktar" value="<?php echo $row_kredionay['miktar']; ?>" />
              <input type="hidden" name="madsoyad" id="madsoyad" value="<?php echo $row_kredionay['musteriadi']; ?>" />
              <input type="hidden" name="id" id="id" value="<?php echo $row_kredionay['id']; ?>" />
              <label>
                <input onclick="return confirm('<?php echo $row_kredionay['miktar']; ?>.00 TL Miktar krediyi <?php echo $row_kredionay['musteriadi']; ?> hesabına eklenecektir. ONAYLIYORMUSUNUZ!');" class="btn btn-success btn-xs" type="submit" name="ekle" id="ekle" value="ONAYLA VE EKLE" />
              </label>
            </form>
<?php } 
elseif ($row_kredionay['musteriID'] != $adsoyadsonuc->musteriID) 
{?> 
    <form id="form" method="post" action="index.php?mc=creditmolustur" >
  <input type="hidden" name="musteriID" id="musteriID" value="<?php echo $row_kredionay['musteriID']; ?>" />
  <input type="hidden" name="musteriadi" id="musteriadi" value="<?php echo $row_kredionay['musteriadi']; ?>" />
  <input class="btn btn-success btn-xs" type="submit" value="HESABI OLUŞTUR" /><span style="font-size:10px; color:#C00">Müşterinin Hesabı Bulunmuyor</span> 
</form>
<?php } 
else 
{ ?>
    <span class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> 404 Hata</span>
<?php } 

?>
            
     


   
          
          
        </td>
      </tr>
      <?php } while ($row_kredionay = mysql_fetch_assoc($kredionay)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div></div>




<div class="alert alert-dismissible alert-info">
  <h4><i class="fa fa-bell"></i> DİKKAT!</h4>
  <p>Destek sistemini kontrol ederek, siparişi onaylamayı unutmayınız. Onayladıgınız siparişleri oyun içerisinde yetkili olarak vere bilirsiniz. Panel üzerinden onaylanan market ürünleri otomtik olarak onaylanmamaktadır. </p>
</div>

</body>
</html>
<?php
mysql_free_result($credito);

mysql_free_result($kredionay);

mysql_free_result($uyecont);
?>

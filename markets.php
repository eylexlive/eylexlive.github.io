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

mysql_select_db($database_baglanti, $baglanti);
$query_carts = "SELECT * FROM market WHERE marketdurumu = '1' ORDER BY marketID DESC";
$carts = mysql_query($query_carts, $baglanti) or die(mysql_error());
$row_carts = mysql_fetch_assoc($carts);
$totalRows_carts = mysql_num_rows($carts);

$colname_kredigel = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_kredigel = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_kredigel = sprintf("SELECT * FROM credit WHERE musteriID = %s", GetSQLValueString($colname_kredigel, "int"));
$kredigel = mysql_query($query_kredigel, $baglanti) or die(mysql_error());
$row_kredigel = mysql_fetch_assoc($kredigel);
$totalRows_kredigel = mysql_num_rows($kredigel);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
#market {
	padding: 10px;
}

.planMine1 h2 {
	margin: 0px;
	font-family: Tahoma, Geneva, sans-serif;
	line-height: 23px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #D6D6D6;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 5px;
	padding-left: 20px;
	font-weight: bold;
	color: #3A3A3A;
}
#market .planMine1 .s {
	margin-top: 10px;
	margin-bottom: 10px;
	text-align: center;
	width: 130px;
	float: right;
}
#market .planMine1 .doller1 {
	font-size: 25px;
	font-weight: bold;
	width: auto;
	float: left;
	border: 1px dashed #939393;
	display: block;
	line-height: 45px;
	margin-bottom: -30px;
	margin-left: 25px;
	padding-left: 10px;
	background-color: #FF9;
}
.planMine1 .doller1 .datess {
	font-size: 16px;
	font-weight: normal;
	color: #666;
	margin-left: 10px;
	line-height: 45px;
	margin-right: 10px;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.planMine1 {
	width: 95%;
	height: auto;
	margin-bottom: 10px;
	padding: 15px;
	background: rgba(255,255,255,1);
	background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
	background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,1)), color-stop(47%, rgba(246,246,246,1)), color-stop(100%, rgba(237,237,237,1)));
	background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
	background: -o-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
	background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
	background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(246,246,246,1) 47%, rgba(237,237,237,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed', GradientType=0 );
	-webkit-box-shadow: 0px 0px 20px 2px rgba(0,0,0,0.25);
-moz-box-shadow: 0px 0px 20px 2px rgba(0,0,0,0.25);
box-shadow: 0px 0px 20px 2px rgba(0,0,0,0.25);
	float: left;
	margin-right: 10px;
	margin-left: 3px;
}

-->
</style>
</head>

<body>
<div id="market">
  <?php if ($totalRows_carts == 0) { // Show if recordset empty ?>
  <center><h1>Markette henüz ürün bulunmuyor!</h1></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_carts > 0) { // Show if recordset not empty ?>
    <?php do { ?>
      <div class="planMine1">
        <h2><span><i class="fa fa-gamepad"></i></span> <?php echo $row_carts['baslik']; ?></h2>
        <ul>
          <li><?php echo $row_carts['ozellik1']; ?></li>
          <li><?php echo $row_carts['ozellik2']; ?></li>
          <li><?php echo $row_carts['ozellik3']; ?></li>
          <li><?php echo $row_carts['kisaaciklama']; ?></li>
        </ul>
        <div class="doller1"><?php echo $row_carts['fiyati']; ?>.00 <i class="fa fa-try"></i><span class="datess"><?php echo $row_carts['gunu']; ?></span></div>
        <div style="clear:both"></div>
        <div class="s"> 
        <?php 
		if ($row_carts['fiyati'] <= $row_kredigel['miktar']){
			
		?>
		<form method="post" action="cart.php">
          <input type="hidden" name="token" id="usertoken" value="<?php echo md5($row_carts['baslik']); ?>"/>
          <button class="button success rounded" onclick="return confirm('Sayın <?php echo $_SESSION['kt_username']; ?> \n <?php echo $row_carts['fiyati']; ?>.00 TL Tutarındaki \n <?php echo $row_carts['baslik']; ?> isimli ürünü satın almak üzeresiniz. \n Bunun geri dönüşü olmayacaktır.  \n SATIN ALMAK İSTEDİĞİNİZDEN EMİNMİSİNİZ?')"><i class="fa fa-shopping-cart"></i> SATIN AL</button>
          <input type="hidden" name="marketID" id="marketID" value="<?php echo $row_carts['marketID']; ?>" />
          <input type="hidden" name="musteriID" id="musteriID" value="<?php echo $_SESSION['kt_login_id']; ?>" />
          <input type="hidden" name="musteriadi" id="musteriadi" value="<?php echo $_SESSION['kt_username']; ?>" />
          <input type="hidden" name="servisadi" id="servisadi" value="<?php echo $row_carts['baslik']; ?>" />
          <input type="hidden" name="servisaciklama" id="servisaciklama" value="<?php echo $row_carts['kisaaciklama']; ?>" />
          <input type="hidden" name="servisfiyat" id="servisfiyat" value="<?php echo $row_carts['fiyati']; ?>" />
          <input type="hidden" name="servissure" id="servissure" value="<?php echo $row_carts['gunu']; ?>" />
          <input type="hidden" name="servismiktar" id="servismiktar" value="1" />
          <input type="hidden" name="mkredi" id="mkredi" value="<?php echo $row_kredigel['miktar']; ?>" />
          <input type="hidden" name="siparisverilentarih" id="siparisverilentarih" value="<?php date_default_timezone_set('Europe/Istanbul'); echo date("d/m/Y H:i"); ?>" />
        </form>
		<?php	}else{?>
				YETERLİ KREDİN MEVCUT DEĞİL
				
		 <?php 		}
		?>
        
 </div>
      </div>
      <?php } while ($row_carts = mysql_fetch_assoc($carts)); ?>
    <?php } // Show if recordset not empty ?>
<div style="clear:both"></div>
</div>
<div>
  <h3><strong>SATIN ALMADAN ÖNCE OKU</strong></h3>
  <div>
    <ul>
      <li>Hesabında yeteri kadar kredinin olması <small>(<strong>Kredin;</strong><?php echo $row_kredigel['miktar']; ?>.00 TL )</small></li>
      <li>Ürünü satın aldıktan sonra destek sisteminden bize bildirmen gerek</li>
      <li>Destek sisteminde Satın alma bildirimini açarken satın alınan urunu seçmelisiniz!</li>
      <li>7/24 Destek sistemi</li>
    </ul>
    <ul>
      <li>100% Müşteri Garantisi</li>
      <li>Sorunsuz Alışveriş</li>
      <li>Satın alınan ürünler geri iade edilemez.</li>
    </ul>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($carts);

mysql_free_result($kredigel);
?>

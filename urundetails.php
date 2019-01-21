<?php require_once('Connections/baglanti.php'); 
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

$musteriidgel = $_SESSION['kt_login_id'];

$colname_urungeldty = "-1";
if (isset($_POST['siparisID'])) {
$colname_urungeldty = $_POST['siparisID'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_urungeldty = sprintf("SELECT * FROM cart WHERE siparisID = %s AND musteriID = $musteriidgel", GetSQLValueString($colname_urungeldty, "int"));
$urungeldty = mysql_query($query_urungeldty, $baglanti) or die(mysql_error());
$row_urungeldty = mysql_fetch_assoc($urungeldty);
$totalRows_urungeldty = mysql_num_rows($urungeldty);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.panel .content table tr td .urundiz {
	margin: 10px;
}
-->
</style>
</head>

<body>
<!-- Default -->
<?php if ($totalRows_urungeldty == 0) { // Show if recordset empty ?>
  <?php header('Location: products');?>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_urungeldty > 0) { // Show if recordset not empty ?>
  <div class="panel" >
    <div class="heading">
      <span class="title"><i class="fa fa-product-hunt"></i> ÜRÜN & HİZMETLER DETAY</span></div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#D6D6D6">&nbsp;<strong>&nbsp;Ürün &amp; Hizmeti incele </strong></td>
        </tr>
        <tr>
          <td><div class="urundiz">
            <table width="100%" border="0">
              <tr>
                <td width="37%" height="35">Ürün / Hizmet</td>
                <td width="4%" height="35" align="center"><strong>:</strong></td>
                <td width="59%" height="35"><?php echo $row_urungeldty['servisadi']; ?></td>
                </tr>
              <tr>
                <td height="35">Açıklama</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td><?php echo $row_urungeldty['servisaciklama']; ?></td>
                </tr>
              <tr>
                <td height="35">İlk ödeme miktarı</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php echo $row_urungeldty['servisfiyat']; ?>.00 TL</td>
                </tr>
              <tr>
                <td height="35">Kayıt Tarihi</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php echo $row_urungeldty['baslangictarihi']; ?></td>
                </tr>
              <tr>
                <td height="35">Yinelenen miktar</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php echo $row_urungeldty['servisfiyat']; ?>.00 TL</td>
                </tr>
              <tr>
                <td height="35">Sonraki Ödeme Tarihi</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php echo $row_urungeldty['bitistarihi']; ?></td>
                </tr>
              <tr>
                <td height="35">Hizmet süresi</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php echo $row_urungeldty['servissure']; ?></td>
                </tr>
              <tr>
                <td height="35">Ödeme şekli</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35">&nbsp;</td>
                </tr>
              <tr>
                <td height="35">Servis durumu</td>
                <td height="35" align="center"><strong>:</strong></td>
                <td height="35"><?php 

if ($row_urungeldty['servisdurumu'] == '1') 
{ 
    echo ' <small style="font-size:10px"><strong>AKTİF</strong></small> '; 
} 
elseif ($row_urungeldty['servisdurumu'] == '0') 
{ 
    echo '<small style="font-size:10px"><strong>ÖDEME ONAYI BEKLİYOR</strong></small>'; 
} 
elseif ($row_urungeldty['servisdurumu'] == '2') 
{ 
    echo '<small style="font-size:10px"><strong>HİZMET SÜRESİ DOLDU</strong></small>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger">404 Hata</span>'; 
} 

?></td>
                </tr>
              <tr>
                <td height="35">&nbsp;</td>
                <td height="35" align="center">&nbsp;</td>
                <td height="35"><form id="form1" name="form1" method="post" action="products">
                  <label>
                    <button class="button" type="submit" name="button" id="button" /> <i class="fa fa-angle-double-left"></i> Ürün/Hizmetlerine Geri dön </button>
                    </label>
                  </form></td>
                </tr>
              </table>
          </div></td>
        </tr>
      </table>
      </div></div>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($urungeldty);
?>

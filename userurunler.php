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

$colname_udetay = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_udetay = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_udetay = sprintf("SELECT * FROM cart WHERE musteriID = %s ORDER BY siparisID DESC", GetSQLValueString($colname_udetay, "int"));
$udetay = mysql_query($query_udetay, $baglanti) or die(mysql_error());
$row_udetay = mysql_fetch_assoc($udetay);
$totalRows_udetay = mysql_num_rows($udetay);
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-product-hunt"></i> ÜRÜN & HİZMETLER</span>
    / <small><strong>Toplam Ürün : </strong><?php echo $totalRows_udetay ?> </small></div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="40" colspan="5" bgcolor="#D6D6D6">&nbsp;&nbsp;&nbsp;&nbsp;<strong>Satın almış oldugunuz Ürün &amp; Hizmetler listesi</strong></td>
        </tr>
         <?php if ($totalRows_udetay > 0) { // Show if recordset not empty ?>
        <tr class="table border">
          <td width="38%" height="35" bgcolor="#E6E6E6"><strong>&nbsp;&nbsp;Ürün / Hizmet</strong></td>
          <td width="19%" height="35" bgcolor="#E6E6E6"><strong>Tutar</strong></td>
          <td width="18%" height="35" bgcolor="#E6E6E6"><strong>Hizmet Süre</strong></td>
          <td height="35" colspan="2" bgcolor="#E6E6E6"><strong>Durum</strong></td>
        </tr>
        <?php do { ?>
           
              <tr bgcolor="#FFFFCC" class="table border hovered">
                <td height="35">&nbsp;&nbsp;<?php echo $row_udetay['servisadi']; ?></td>
                <td height="35"><?php echo $row_udetay['servisfiyat']; ?>.00 TL</td>
                <td height="35"><?php echo $row_udetay['servissure']; ?></td>
                <td width="19%" height="35" ><?php 

if ($row_udetay['servisdurumu'] == '1') 
{ 
    echo ' <small style="font-size:10px"><strong>AKTİF</strong></small> '; 
} 
elseif ($row_udetay['servisdurumu'] == '0') 
{ 
    echo '<small style="font-size:10px"><strong>SATIN ALMA ONAYI BEKLİYOR</strong></small>'; 
} 
elseif ($row_udetay['servisdurumu'] == '2') 
{ 
    echo '<small style="font-size:10px"><strong>HİZMET SÜRESİ DOLDU</strong></small>'; 
}
else 
{ 
    echo '<span class="btn btn-xs btn-danger">404 Hata</span>'; 
} 

?></td>
                <td width="6%" height="35"><form method="post" name="siparisID" action="urundetay">
                  <input id="token" type="hidden" name="siparisTID" value="<?php echo $tokenid = md5(mt_rand()); ?>"  />
                  <input type="hidden" name="siparisID" id="siparisID" value="<?php echo $row_udetay['siparisID']; ?>" />
                  <button class="button mini-button">İncele</button>
                </form></td>
              </tr>
              
<?php } while ($row_udetay = mysql_fetch_assoc($udetay)); ?>
<?php } // Show if recordset not empty ?>
<?php if ($totalRows_udetay == 0) { // Show if recordset empty ?>
  <tr>
    <td colspan="5" align="center"><h3>Ürün / Hizmet bulunamadı....</h3></td>
  </tr>
  <?php } // Show if recordset empty ?>
      </table>
    </div></div>
</body>
</html>
<?php
mysql_free_result($udetay);
?>

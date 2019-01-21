<?php require_once('Connections/baglanti.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);



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
$query_banks = "SELECT * FROM bankahesaplari WHERE aktif = '1' ORDER BY bankaadi ASC";
$banks = mysql_query($query_banks, $baglanti) or die(mysql_error());
$row_banks = mysql_fetch_assoc($banks);
$totalRows_banks = mysql_num_rows($banks);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.panel .content table tr td .banka {
	padding: 5px;
	border: 3px solid #666;
}
.panel .content table tr td .banka {
	margin: 20px;
	background-color: #DBDBFF;
	font-family: Tahoma, Geneva, sans-serif;
}
.panel .content table tr td .banka table tr td .bank .logo {
	background-color: #D6D6D6;
	margin: 0px;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 16px;
	color: #C00;
	padding-left: 10px;
	height: 35px;
	line-height: 35px;
	border-left-width: 5px;
	border-left-style: solid;
	border-left-color: #8D8D8D;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #323232;
}
.panel .content table tr td .banka table tr td .bank .icerik {
	padding: 3px;
}
.panel .content table tr td .banka table tr td .bank .icerik .ic1 {
	height: 30px;
	line-height: 30px;
	padding-left: 20px;
	font-size: 16px;
	font-family: Tahoma, Geneva, sans-serif;
	margin-bottom: 3px;
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #8B8B8B;
}
.panel .content table tr td .banka:hover {
	background-color: #FFC;
}
-->
</style>
</head>

<body>
<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-university"></i> HESAP BİLGİLERİMİZ</span>
  </div>
    <div class="content">
      <table width="100%" border="0">
        <tr>
          <td height="45" bgcolor="#E6E6E6"><strong>&nbsp;&nbsp;&nbsp;Hesap Numaralarımız</strong></td>
        </tr>
        <tr>
          <td bgcolor="#D6D6D6"><?php if ($totalRows_banks == 0) { // Show if recordset empty ?>
  <center><h2>Hesap numaraları eklenecektir...</h2></center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_banks > 0) { // Show if recordset not empty ?>
              <?php do { ?>
              <div class="banka">
                <table width="100%" border="0">
                  <tr>
                    <td><div class="bank">
                        <div class="logo">
                          <h3><?php echo $row_banks['bankaadi']; ?></h3>
                        </div>
                        <div class="icerik">
                          <div class="ic1">
                            <b>HESAP SAHİBİ</b>
                          <?php echo $row_banks['hesabsahibi']; ?></div>
                          <div class="ic1">
                            <b>İBAN NO</b>
                            <?php echo $row_banks['ibanno']; ?></div>
                          <div class="ic1">
                            <b>HESAP NO</b>
                            <?php echo $row_banks['hesapno']; ?></div>
                          <div class="ic1">
                            <b>ŞUBE</b>
                            <?php echo $row_banks['sube']; ?></div>
                        </div>
                    </div></td>
                  </tr>
                </table>
              </div>
              <?php } while ($row_banks = mysql_fetch_assoc($banks)); ?>
          <?php } // Show if recordset not empty ?></td>
        </tr>
      </table>
    </div></div>
</body>
</html>
<?php
mysql_free_result($banks);
?>

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

$maxRows_ticno = 10;
$pageNum_ticno = 0;
if (isset($_GET['pageNum_ticno'])) {
  $pageNum_ticno = $_GET['pageNum_ticno'];
}
$startRow_ticno = $pageNum_ticno * $maxRows_ticno;

mysql_select_db($database_baglanti, $baglanti);
$query_ticno = "SELECT * FROM ticket ORDER BY id DESC";
$query_limit_ticno = sprintf("%s LIMIT %d, %d", $query_ticno, $startRow_ticno, $maxRows_ticno);
$ticno = mysql_query($query_limit_ticno, $baglanti) or die(mysql_error());
$row_ticno = mysql_fetch_assoc($ticno);

if (isset($_GET['totalRows_ticno'])) {
  $totalRows_ticno = $_GET['totalRows_ticno'];
} else {
  $all_ticno = mysql_query($query_ticno);
  $totalRows_ticno = mysql_num_rows($all_ticno);
}
$totalPages_ticno = ceil($totalRows_ticno/$maxRows_ticno)-1;

$maxRows_tcvpg = 20;
$pageNum_tcvpg = 0;
if (isset($_GET['pageNum_tcvpg'])) {
  $pageNum_tcvpg = $_GET['pageNum_tcvpg'];
}
$startRow_tcvpg = $pageNum_tcvpg * $maxRows_tcvpg;

mysql_select_db($database_baglanti, $baglanti);
$query_tcvpg = "SELECT * FROM ticketyanit ORDER BY id DESC";
$query_limit_tcvpg = sprintf("%s LIMIT %d, %d", $query_tcvpg, $startRow_tcvpg, $maxRows_tcvpg);
$tcvpg = mysql_query($query_limit_tcvpg, $baglanti) or die(mysql_error());
$row_tcvpg = mysql_fetch_assoc($tcvpg);

if (isset($_GET['totalRows_tcvpg'])) {
  $totalRows_tcvpg = $_GET['totalRows_tcvpg'];
} else {
  $all_tcvpg = mysql_query($query_tcvpg);
  $totalRows_tcvpg = mysql_num_rows($all_tcvpg);
}
$totalPages_tcvpg = ceil($totalRows_tcvpg/$maxRows_tcvpg)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=ticket"><i class="fa fa-ticket"></i> Cevap Bekleyen Destek</a></li>
    <li><a href="index.php?mc=ticketclose"><i class="fa fa-ticket"></i>  Kapanan Ticketler</a></li>
  
</ul>
<hr />

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-ticket"></i> Yeni Destek Talepleri</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_ticno == 0) { // Show if recordset empty ?>
  <center>
    <h1>Cevap Bekleyen Destek Talebi Bulunamadı!</h1>
  </center>
  <?php } // Show if recordset empty ?>
    <?php if ($totalRows_ticno > 0) { // Show if recordset not empty ?>
      <table width="100%" border="0" class="table">
        <tr>
          <th width="17%" height="35">Müşteri Adı</th>
          <th width="52%" height="35">Soru</th>
          <th width="20%" height="35">Durum</th>
          <th height="35">&nbsp;</th>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_ticno['isim']; ?></td>
            <td><?php echo $row_ticno['baslik']; ?></td>
            <td><?php 

if ($row_ticno['durum'] == '0') 
{ 
    echo ' <span class="btn btn-danger btn-xs btn-block">Cevap Bekliyor</span> '; 
} 
elseif ($row_ticno['durum'] == '1') 
{ 
    echo '<span class="btn btn-success btn-xs btn-block">Cevap Landı</span>'; 
} 
elseif ($row_ticno['durum'] == '2') 
{ 
    echo '<span class="btn btn-default btn-xs btn-block">KAPANDI</span>'; 
} 
else 
{ 
    echo 'HATA'; 
} 

?></td>
            <td width="11%"><a href="index.php?mc=ticketdetay&id=<?php echo $row_ticno['id']; ?>&ticID=<?php echo $row_ticno['id']; ?>#CavapYaz" class="btn btn-info btn-xs btn-block">CEVAP YAZ</a></td>
          </tr>
          <?php } while ($row_ticno = mysql_fetch_assoc($ticno)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">En Son Cevaplanan Destekler</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_tcvpg == 0) { // Show if recordset empty ?>
  <center>
    <h1>Destek Talebi Bulunamadı!</h1>
  </center>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_tcvpg > 0) { // Show if recordset not empty ?>
      <table width="100%" border="0" class="table">
        <tr>
          <th width="17%" height="35">Müşteri Adı</th>
          <th width="52%" height="35">Cevap Yazan</th>
          <th width="20%" height="35">Durum</th>
          <th height="35">&nbsp;</th>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_tcvpg['musteriadi']; ?></td>
            <td><?php echo $row_tcvpg['yetkiliadi']; ?></td>
            <td><?php 

if ($row_tcvpg['durum'] == '1') 
{ 
    echo ' <span class="btn btn-success btn-xs btn-block">Yönetici Yanıtı</span> '; 
} 
elseif ($row_tcvpg['durum'] == '2') 
{ 
    echo '<span class="btn btn-default btn-xs btn-block">Kapandı</span>'; 
} 
elseif ($row_tcvpg['durum'] == '3') 
{ 
    echo '<span class="btn btn-warning btn-xs btn-block">Müşteri Yanıtı</span>'; 
} 

else 
{ 
    echo 'HATA'; 
} 

?></td>
            <td width="11%"><a href="index.php?mc=ticketdetay&amp;id=<?php echo $row_tcvpg['ticketID']; ?>&amp;ticID=<?php echo $row_tcvpg['ticketID']; ?>#Commend<?php echo $row_tcvpg['id']; ?>" class="btn btn-danger btn-xs btn-block">GÖRÜNTÜLE</a></td>
          </tr>
          <?php } while ($row_tcvpg = mysql_fetch_assoc($tcvpg)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
  </div>
</div>

</body>
</html>
<?php
mysql_free_result($ticno);

mysql_free_result($tcvpg);

?>

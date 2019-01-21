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
$query_shoplist = "SELECT * FROM market ORDER BY marketID DESC";
$shoplist = mysql_query($query_shoplist, $baglanti) or die(mysql_error());
$row_shoplist = mysql_fetch_assoc($shoplist);
$totalRows_shoplist = mysql_num_rows($shoplist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>


<ul class="nav nav-pills">
  <li><a href="?mc=shopadd"><i class="fa fa-cart-plus"></i> Yeni Ürün Ekle</a></li>
  <li class="active"><a href="?mc=shop">Ürünleri Listele</a></li>
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-cart-arrow-down"></i> EKLENEN MARKET ÜRÜNLERİ</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_shoplist > 0) { // Show if recordset not empty ?>
      <table width="100%" border="0" class="table table-striped table-hover ">
        <tr>
          <th width="5%">#ID</th>
          <th width="50%">Ürün Adı</th>
          <th width="19%">Ürün Fiyatı</th>
          <th width="20%" colspan="2">İŞLEM</th>
        </tr>
        <?php do { ?>
          <tr>
            <td width="5%"><?php echo $row_shoplist['marketID']; ?></td>
            <td><?php echo $row_shoplist['baslik']; ?></td>
            <td><?php echo $row_shoplist['fiyati']; ?></td>
            <td align="center"><a class="btn btn-danger" onclick="if(deleteConfirm()) window.location='?mc=shopdelete&amp;marketID=<?php echo $row_shoplist['marketID']; ?>';" role="button"><i class="fa fa-trash"></i> SİL</a></td>
            <td align="center"><a class="btn btn-success" href="?mc=shopedit&amp;marketID=<?php echo $row_shoplist['marketID']; ?>" role="button"><i class="fa fa-pencil-square-o"></i> DÜZENLE</a></td>
          </tr>
          <?php } while ($row_shoplist = mysql_fetch_assoc($shoplist)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
    <?php if ($totalRows_shoplist == 0) { // Show if recordset empty ?>
  <div class="alert alert-dismissible alert-warning">
    <h4>İçerik Bulunamadı!</h4>
    <p>Henüz eklenmiş Market ürünü bulunamadı <a class="btn btn-primary" href="?mc=shopadd" role="button">Yeni Ürün Ekle</a></p>
  </div>
  <?php } // Show if recordset empty ?>
  </div>
</div>


</body>
</html>
<?php
mysql_free_result($shoplist);
?>

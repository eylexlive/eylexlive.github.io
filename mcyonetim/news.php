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
$query_news = "SELECT * FROM news ORDER BY id DESC";
$news = mysql_query($query_news, $baglanti) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="nav nav-pills">
	<li class="active"><a href="index.php?mc=news"><i class="fa fa-newspaper-o"></i> Eklenen haberler</a></li>
    <li><a href="?mc=newsadd"><i class="fa fa-plus"></i> Yeni haber Ekle</a></li>
  
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list-ol"></i> Eklenen Haberler</h3>
  </div>
  <div class="panel-body">
    <?php if ($totalRows_news == 0) { // Show if recordset empty ?>
  <center>
    <h1>Kayıtlı Haber Bulunamadı!</h1>
  </center>
  <?php } // Show if recordset empty ?>
    <?php if ($totalRows_news > 0) { // Show if recordset not empty ?>
      <table width="100%" class="table table-hover" >
        <tr class="info">
          <th width="7%">#ID</th>
          <th width="55%">Haber Başlıgı</th>
          <th width="28%">Ekleme Tarihi</th>
          <th width="10%">İŞLEM</th>
        </tr>
        <?php do { ?>
          <tr>
            <td width="7%"><?php echo $row_news['id']; ?></td>
            <td><?php echo $row_news['title']; ?></td>
            <td><?php echo $row_news['tarih']; ?></td>
            <td><a class="btn btn-danger btn-xs" onclick="if(deleteConfirm()) window.location='?mc=newsdelete&amp;id=<?php echo $row_news['id']; ?>';" role="button"><i class="fa fa-trash"></i> SİL</a></td>
          </tr>
          <?php } while ($row_news = mysql_fetch_assoc($news)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
  </div>
</div>

</body>
</html>
<?php
mysql_free_result($news);
?>

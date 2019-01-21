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
$query_pagess = "SELECT * FROM pages ORDER BY id DESC";
$pagess = mysql_query($query_pagess, $baglanti) or die(mysql_error());
$row_pagess = mysql_fetch_assoc($pagess);
$totalRows_pagess = mysql_num_rows($pagess);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="nav nav-pills">
	<li  class="active"><a href="index.php?mc=pages"><i class="fa fa-file-o"></i> Sayfaları Listele</a></li>
    <li><a href="?mc=pagesadd"><i class="fa fa-file-o"></i> Yeni Sayfa Ekle</a></li>
  
</ul>
<hr />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-info"></i> Eklenen Sayfalar</h3>
  </div>
  <div class="panel-body">
    <table width="100%" border="0" class="table">
    <?php if ($totalRows_pagess > 0) { // Show if recordset not empty ?>
      <tr>
        <th width="7%">SayfaID</th>
        <th width="43%">Sayfa Adı</th>
        <th width="31%">Sayfa &lt;title&gt; Başlık &lt;/title&gt;</th>
        <th colspan="2">İŞLEM</th>
      </tr>
      
        <?php do { ?>
          <tr>
            <td><?php echo $row_pagess['id']; ?></td>
            <td><?php echo $row_pagess['pagetitle']; ?></td>
            <td><?php echo $row_pagess['pageheadtitle']; ?></td>
            <td width="8%" align="center"><a onclick="if(deleteConfirm()) window.location='?mc=pagesdelete&amp;id=<?php echo $row_pagess['id']; ?>';" class="btn btn-danger btn-xs">SİL</a></td>
            <td width="11%" align="center"><a href="?mc=pagesedit&id=<?php echo $row_pagess['id']; ?>" class="btn btn-success btn-xs">DÜZENLE</a></td>
          </tr>
          <?php } while ($row_pagess = mysql_fetch_assoc($pagess)); ?>
        <?php } // Show if recordset not empty ?>
      <?php if ($totalRows_pagess == 0) { // Show if recordset empty ?>
  <tr>
    <td colspan="5" align="center"><h4>Kayıtlı sayfa bulunmuyor...</h4><hr />
</td>
  </tr>
  <?php } // Show if recordset empty ?>
    </table>
  </div>
</div>

</body>
</html>
<?php
mysql_free_result($pagess);
?>

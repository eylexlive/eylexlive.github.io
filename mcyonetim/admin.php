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
$query_admins = "SELECT * FROM authme WHERE `level` = '2' ORDER BY id DESC";
$admins = mysql_query($query_admins, $baglanti) or die(mysql_error());
$row_admins = mysql_fetch_assoc($admins);
$totalRows_admins = mysql_num_rows($admins);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="nav nav-pills">
  <li class="active"><a href="index.php?mc=admin"><i class="fa fa-user-md"></i> Adminleri Listele</a></li>
  <li><a href="?mc=adminadd"><i class="fa fa-user-plus"></i> Yeni Admin Ekle</a></li>
</ul>
<hr />
<?php if ($totalRows_admins > 0) { // Show if recordset not empty ?>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-user-md"></i> YÖNETİCİLER</h3>
    </div>
    <div class="panel-body">
      
      <table width="100%" border="0" class="table">
        <tr>
          <th width="5%" height="30">#ID</th>
          <th width="47%" height="30">Yetkili Adı</th>
          <th width="35%" height="30">E-Posta</th>
          <th width="13%" height="30">İŞLEM</th>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_admins['id']; ?></td>
            <td><?php echo $row_admins['username']; ?></td>
            <td><?php echo $row_admins['email']; ?></td>
            <td align="center">
            
              <?php 
// Show IF Conditional region1 
if (@$row_admins['id'] == @$_SESSION['kt_login_id']) {
?>
                <a href="index.php?mc=profilme" class="btn btn-success btn-xs">Sizin Profil</a>
                <?php } 
// endif Conditional region1
?>
              <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] != @$row_admins['id']) {
?>
                <a href="index.php?mc=adminedits&id=<?php echo $row_admins['id']; ?>" class="btn btn-primary btn-xs">DÜZENLE</a>
                <?php } 
// endif Conditional region2
?></td>
          </tr>
          <?php } while ($row_admins = mysql_fetch_assoc($admins)); ?>
      </table>
      <blockquote class="alert alert-danger"><b><i class="fa fa-lightbulb-o" style="font-size:18px"> BİLGİ</i></b> Eklenen yöneticiler silinemez sadece normal kullanıcı olarak değiştire bilirsiniz.</blockquote>
    </div>
  </div>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($admins);
?>

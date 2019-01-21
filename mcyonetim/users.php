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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_uye = 20;
$pageNum_uye = 0;
if (isset($_GET['pageNum_uye'])) {
  $pageNum_uye = $_GET['pageNum_uye'];
}
$startRow_uye = $pageNum_uye * $maxRows_uye;

mysql_select_db($database_baglanti, $baglanti);
$query_uye = "SELECT * FROM authme WHERE `level` = '1' ORDER BY id DESC";
$query_limit_uye = sprintf("%s LIMIT %d, %d", $query_uye, $startRow_uye, $maxRows_uye);
$uye = mysql_query($query_limit_uye, $baglanti) or die(mysql_error());
$row_uye = mysql_fetch_assoc($uye);

if (isset($_GET['totalRows_uye'])) {
  $totalRows_uye = $_GET['totalRows_uye'];
} else {
  $all_uye = mysql_query($query_uye);
  $totalRows_uye = mysql_num_rows($all_uye);
}
$totalPages_uye = ceil($totalRows_uye/$maxRows_uye)-1;

$queryString_uye = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_uye") == false && 
        stristr($param, "totalRows_uye") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_uye = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_uye = sprintf("&totalRows_uye=%d%s", $totalRows_uye, $queryString_uye);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<ul class="breadcrumb">
  <li class="active"><a href="index.php?mc=users">Kayıt Olan Kullanıcılar</a></li>
</ul>

<table width="100%" class="table table-hover">
  <tr>
    <th colspan="4">TOPLAM ÜYE SAYISI; <strong class="btn btn-primary btn-xs"><?php echo $totalRows_uye ?></strong></th>
  </tr>
  <tr>
    <th width="4%">ÜYE</th>
    <th width="49%">Üye Adı</th>
    <th width="35%">E-Posta</th>
    <th width="12%">Dünya</th>
  </tr>
  <?php do { ?>
    <tr>
      <td class="success"><center><img class="img-circle" src="https://cravatar.eu/helmavatar/<?php echo $row_uye['username']; ?>" alt="Üye" /></center></td>
      <td style="font-size:16px"><?php echo $row_uye['username']; ?></td>
      <td><a href="mailto:<?php echo $row_uye['email']; ?>" class="btn btn-primary btn-xs btn-block"><?php echo $row_uye['email']; ?></a></td>
      <td align="center" class="warning"><strong class="btn btn-warning btn-xs"><?php echo $row_uye['world']; ?></strong></td>
    </tr>
    <?php } while ($row_uye = mysql_fetch_assoc($uye)); ?>
<tr>
    <td colspan="4">
      <table border="0" align="center">
        <tr>
          <td><?php if ($pageNum_uye > 0) { // Show if not first page ?>
              <a class="btn btn-default" href="<?php printf("%s?pageNum_uye=%d%s", $currentPage, 0, $queryString_uye); ?>">İlk Sayfa</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_uye > 0) { // Show if not first page ?>
              <a class="btn btn-default" href="<?php printf("%s?pageNum_uye=%d%s", $currentPage, max(0, $pageNum_uye - 1), $queryString_uye); ?>">Geri</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_uye < $totalPages_uye) { // Show if not last page ?>
              <a class="btn btn-default" href="<?php printf("%s?pageNum_uye=%d%s", $currentPage, min($totalPages_uye, $pageNum_uye + 1), $queryString_uye); ?>">İleri</a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_uye < $totalPages_uye) { // Show if not last page ?>
              <a class="btn btn-default" href="<?php printf("%s?pageNum_uye=%d%s", $currentPage, $totalPages_uye, $queryString_uye); ?>">Son Sayfa</a>
              <?php } // Show if not last page ?></td>
        </tr>
    </table></td>
  </tr>
</table>


</body>
</html>
<?php
mysql_free_result($uye);
?>

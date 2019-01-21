<?php
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


$maxRows_uyelist = 5;
$pageNum_uyelist = 0;
if (isset($_GET['pageNum_uyelist'])) {
  $pageNum_uyelist = $_GET['pageNum_uyelist'];
}
$startRow_uyelist = $pageNum_uyelist * $maxRows_uyelist;

mysql_select_db($database_baglanti, $baglanti);
$query_uyelist = "SELECT * FROM authme WHERE `level` = '1' ORDER BY id DESC";
$query_limit_uyelist = sprintf("%s LIMIT %d, %d", $query_uyelist, $startRow_uyelist, $maxRows_uyelist);
$uyelist = mysql_query($query_limit_uyelist, $baglanti) or die(mysql_error());
$row_uyelist = mysql_fetch_assoc($uyelist);

if (isset($_GET['totalRows_uyelist'])) {
  $totalRows_uyelist = $_GET['totalRows_uyelist'];
} else {
  $all_uyelist = mysql_query($query_uyelist);
  $totalRows_uyelist = mysql_num_rows($all_uyelist);
}
$totalPages_uyelist = ceil($totalRows_uyelist/$maxRows_uyelist)-1;
?>
<?php if ($totalRows_uyelist > 0) { // Show if recordset not empty ?>
  <ul class="numeric-list large-bullet dark-bullet square-bullet">
    <?php do { ?>
      <li title="Kullanici Istatistikleri / <?php echo $row_uyelist['username']; ?>"><img src="http://cravatar.eu/helmavatar/<?php echo $row_uyelist['username']; ?>" alt="" /> <?php echo $row_uyelist['username']; ?></li>
      <?php } while ($row_uyelist = mysql_fetch_assoc($uyelist)); ?>          
  </ul>
  <?php } // Show if recordset not empty ?>
<?php
mysql_free_result($uyelist);
?>

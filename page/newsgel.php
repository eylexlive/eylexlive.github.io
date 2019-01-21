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

$colname_news = "-1";
if (isset($_GET['ID'])) {
  $colname_news = $_GET['ID'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_news = sprintf("SELECT * FROM news WHERE id = %s", GetSQLValueString($colname_news, "int"));
$news = mysql_query($query_news, $baglanti) or die(mysql_error());
$row_news = mysql_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

<style type="text/css">
<!--
.haber {
	padding: 10px;
}
-->
</style>
</head>

<body>
<div class="panel warning">
    <div class="heading">
        <span class="title"><i class="fa fa-newspaper-o" style="color:#333; font-weight:bold"></i> BİZDEN HABERLER </span>
  </div>
    <div class="content" style="background-color:#FFF">
    
    
    
    
    </div></div>
<div class="haber">
  <?php if ($totalRows_news == 0) { // Show if recordset empty ?>
    <meta http-equiv="refresh" content="0;URL=index.php" />
    <?php } // Show if recordset empty ?>
<?php if ($totalRows_news > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0">
    <tr>
      <td width="33%" align="center"><img src="<?php echo $row_news['images']; ?>" alt="<?php echo $row_news['title']; ?>" width="150" height="150" border="3" /></td>
      <td width="67%" valign="top"><small><i class="fa fa-calendar-o"></i> <?php echo $row_news['tarih']; ?></small><br />
        <p style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#333"><?php echo $row_news['subcontent']; ?></p></td>
      </tr>
    <tr>
      <td colspan="2"><?php echo $row_news['content']; ?></td>
      </tr>
    <tr>
      <td colspan="2"><?php echo $row_news['video']; ?></td>
      </tr>
    <tr>
      <td colspan="2"><blockquote><strong>Etiketler;</strong> <?php echo $row_news['key']; ?></blockquote></td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php
mysql_free_result($news);
?>

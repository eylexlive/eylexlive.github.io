<?php require_once('Connections/baglanti.php'); ?>
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

mysql_select_db($database_baglanti, $baglanti);
$query_ayar = "SELECT * FROM ayarlar WHERE id = 1";
$ayar = mysql_query($query_ayar, $baglanti) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);
 ob_start();?>
<?php /*
---------------------- CNGame Minecraft Server Portalı ---------------------------------

@@@@@@@@   @@@@@@@@      @@@@@@@@     @@@@@     @@@@@   @@@@@   @@@@@@@@
@@@@@@@@   @@@@@@@@      @@@@@@@@   @@@@ @@@@   @@@@@@ @@@@@@   @@@@@@@@
@@         @@@  @@@      @@@        @@@   @@@   @@@@ @@@ @@@@   @@@
@@         @@@  @@@      @@@  @@@   @@@@@@@@@   @@@@  @  @@@@   @@@@@@@@
@@         @@@  @@@      @@@   @@   @@@@@@@@@   @@@@     @@@@   @@@
@@@@@@@@   @@@  @@@      @@@@@@@@   @@@   @@@   @@@@     @@@@   @@@@@@@@
@@@@@@@@   @@@  @@@      @@@@@@@@   @@@   @@@   @@@@     @@@@   @@@@@@@@


Bu Minecraft portal yazılımı CNGame tarafından oluşturulmuş ve kodlanmıştır.
Script'in alt tarafındaki tasarım ve kodlama yazısını silmezseniz sevinirim. :)
  ----------------------- http://www.cngame.enjin.com -----------------------
*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<base href="<?php echo $row_ayar['siteurl']; ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="<?php echo $row_ayar['description']; ?>">
    <meta name="keywords" content="<?php echo $row_ayar['keywords']; ?>" />
    <meta name="author" content="CNGame - www.cndesing.com">
	<meta http-equiv="refresh" content="1500;URL=">
	<title><?php echo $row_ayar['title']; ?></title>
	<link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/metro.js"></script>
    <script src="js/docs.js"></script>
    <script src="js/prettify/run_prettify.js"></script>
    <script src="js/ga.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>

<body>

<div class="container">
	<?php include("page/header.php"); ?>
	
<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 2) {
?>
	
	<?php include("page/slider.php"); ?>
	<hr>
<?php } 
// endif Conditional region2
?>     
<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
	
	<?php include("page/slider.php"); ?>
	<hr>
<?php } 
// endif Conditional region2
?> 	
	
	
	
    <table width="100%" border="0">
  <tr>
    <td width="65%" valign="top">
	<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
	<?php include("page/newshome.php"); ?>
<?php } 
// endif Conditional region2
?>  


<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?> 
 <?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_level'] == 1) {
?>
<?php include("musteribilgi.php"); ?>
<?php } 
// endif Conditional region1
?>
 <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 2) {
?>
<?php include("page/newshome.php"); ?>
 <?php } 
// endif Conditional region2
?>

<?php } 
// endif Conditional region1
?> 	
	
	
	</td>
    <td width="30%" valign="top">
	 <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
	<?php include("page/sidebar.php"); ?>
<?php } 
// endif Conditional region2
?>  

<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?>  
<?php include("page/sidebaruser.php"); ?>
<?php } 
// endif Conditional region1
?>   	
	</td>
  </tr>
</table>
    <?php include("page/footer.php"); ?>
    
    </div>
</body>
</html>
<?php ob_end_flush();?>
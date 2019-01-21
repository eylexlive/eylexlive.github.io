<?php

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_baglanti, "");

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
$query_serhead = "SELECT * FROM serverconfig WHERE id = 1";
$serhead = mysql_query($query_serhead, $baglanti) or die(mysql_error());
$row_serhead = mysql_fetch_assoc($serhead);
$totalRows_serhead = mysql_num_rows($serhead);

mysql_select_db($database_baglanti, $baglanti);
$query_uyetop = "SELECT * FROM authme WHERE `level` = '1'";
$uyetop = mysql_query($query_uyetop, $baglanti) or die(mysql_error());
$row_uyetop = mysql_fetch_assoc($uyetop);
$totalRows_uyetop = mysql_num_rows($uyetop);

$colname_crdtophead = "-1";
if (isset($_SESSION['kt_login_id'])) {
  $colname_crdtophead = $_SESSION['kt_login_id'];
}
mysql_select_db($database_baglanti, $baglanti);
$query_crdtophead = sprintf("SELECT * FROM credit WHERE musteriID = %s AND durum = '1'", GetSQLValueString($colname_crdtophead, "int"));
$crdtophead = mysql_query($query_crdtophead, $baglanti) or die(mysql_error());
$row_crdtophead = mysql_fetch_assoc($crdtophead);
$totalRows_crdtophead = mysql_num_rows($crdtophead);
 // Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');


?>
<?php 
$APIkey = $row_serhead['sadi'];
$json = file_get_contents("http://minecraftpocket-servers.com/api/?object=servers&element=detail&key=$APIkey");
$data = json_decode($json);
	
	$serveradi = $data->name;
	$bizeoyver = $data->id;
	$serverip = $data->address;
	$sport = $data->port;
	$ulke = $data->location;
	$durum = $data->is_online;
	$online = $data->players;
	$max = $data->maxplayers;
	$versiyon = $data->version;
	$oylar = $data->votes;
	$rank = $data->score;
	$up = $data->uptime;
	$link = $data->url;
	
?>
<table width="100%" border="0">
  <tr>
    <td colspan="2">
    <button class="button mini-button" onclick="location.href='<?php echo $row_ayar['siteurl']; ?>'"><i class="fa fa-gamepad"></i> <?php echo $row_ayar['title']; ?></button>
    <button type="submit" class="button mini-button" onclick="location.href='<?php echo $row_ayar['facebook']; ?>'" lang="tr" title="Facebook'da Bizi Bulun"><i class="fa fa-facebook-square" style="color:#039"></i> Facebook</button>
    <button type="submit" class="button mini-button" onclick="location.href='<?php echo $row_ayar['twitter']; ?>'" lang="tr" title="Twitter'da Bizi Bulun"><i class="fa fa-twitter-square" style="color:#09C"></i> Twitter</button>
    <button type="submit" class="button mini-button" onclick="location.href='<?php echo $row_ayar['youtube']; ?>'" lang="tr" title="Youtube'da Bizi Bulun"><i class="fa fa-youtube-square" style="color:#F00"></i> Youtube</button>
    <a class="active" href="rss.xml" target="_blank" lang="tr" title="RSS Atom Besleme"><i class="fa fa-rss" style="color:#F60"></i> RSS</a>
    
    </td>
  </tr>
  <tr>
    <td width="29%" height="100"><img src="images/<?php echo $row_ayar['sitelogo']; ?>" width="300" height="60" alt="<?php echo $row_ayar['title']; ?>" />
  
    
    
    </td>
    <td width="71%"><table width="90%" border="0" align="right">
      <tr>
        <td>
          <blockquote class="place-right">
            <p><h3><i class="fa fa-thumbs-o-up"></i> <?php echo $row_serhead['sip']; ?>:<?php echo $row_serhead['port']; ?> </h3></p>
            <small><i class="fa fa-cube"></i> <cite title="Source Title">Oyuna Yukarıdaki IP adresi ile gire bilirsiniz</cite> </small>Toplam Üye Sayımız  <button class="button mini-button cycle-button success" type="button"><?php echo $totalRows_uyetop ?></button>
            </blockquote>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

<div class="app-bar">
    
   
<ul class="app-bar-menu">
<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
        <li><a href="<?php echo $row_ayar['siteurl']; ?>"><i class="fa fa-home"></i> Anasayfa</a></li>
<?php } 
// endif Conditional region2
?> 

<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 2) {
?>
<li><a href="<?php echo $row_ayar['siteurl']; ?>"><i class="fa fa-home"></i> Anasayfa</a></li>

<?php } 
// endif Conditional region2
?> 




<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 1) {
?>
<li><a href="ClientArea"><i class="fa fa-home"></i> Client Area</a></li>

<?php } 
// endif Conditional region2
?> 
        <li><a href="webstore"><i class="fa fa-shopping-cart"></i> Market</a></li>
        <li><a href="<?php echo $row_ayar['siteurl']; ?>forum"><i class="fa fa-chain-broken"></i> Forum</a></li>
        <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
        <li><a href="supports"><i class="fa fa-life-ring"></i> Destek</a></li>
        <li><a class="dropdown-toggle"><i class="fa fa-info-circle"></i> Bilgi</a>
        <ul class="d-menu" data-role="dropdown">
                <li><a href="account_information.php"><i class="fa fa-university"></i> Hesap Bilgilerimiz</a></li>
                <li><a href="payment_methods.php"><i class="fa fa-credit-card"></i> Ödeme Yöntemleri</a></li>
                <li><a href="embed.php"><i class="fa fa-link"></i> Sitene Ekle</a></li>
        </ul>
    </li>
<?php } 
// endif Conditional region2
?> 
        
    
    </ul>
    <div class="app-bar-element place-right">
    <ul class="app-bar-menu">
    
    <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
        <li><a class="dropdown-toggle"><span class="mif-paper-plane mif-ani-float mif-ani-slow"></span> Giriş Yap / Kayıt Ol</a>
        <ul class="split-content d-menu place-right" data-role="dropdown">
                <li><a href="login"><i class="fa fa-sign-in"></i> Giriş Yap</a></li>
                <li><a href="register"><i class="fa fa-user-plus"></i> Kayıt Ol</a></li>
                <li><a href="sifremi-unuttum"><i class="fa fa-lock"></i> Şifremi Unuttum</a></li>
              </ul>
              </li>
<?php } 
// endif Conditional region2
?>              
<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?>          

<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 1) {
?> 
           <li><a href="kredilerim"><span class="mif-dollars"></span> KREDİ <button class="button warning  block-shadow-warning  text-warning rounded small-button"><span style="font-size:16px">
           <?php if ($totalRows_crdtophead == 0) { // Show if recordset empty ?>
             0
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_crdtophead > 0) { // Show if recordset not empty ?>
  <?php echo $row_crdtophead['miktar']; ?>.00
  <?php } // Show if recordset not empty ?>
<span style="font-size:19px" class="mif-try"></span></span> </button></a></li>  
<?php } 
// endif Conditional region2
?> 
           <li><a href="settings">Hoş Geldiniz ; <img src="https://cravatar.eu/helmavatar/<?php echo $_SESSION['kt_username']; ?>" alt="Profil" width="20" height="20" />  <strong><?php echo $_SESSION['kt_username']; ?></strong></a> </li> 

            
 <?php } 
// endif Conditional region1
?>              
              
      </ul>
    </div>
</div>
<?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 1) {
?>

<div class="padding10 bg-green fg-white  no-margin-right no-margin-left align-center" style="font-size:20px">
            <span class="mif-bell mif-ani-ring mif-ani-slow"></span> Sayın <a class="text-accent text-bold fg-white"><?php echo $_SESSION['kt_username']; ?></a> giriş yaptığınız için teşekkür ederiz. <a href="webstore" class="text-accent text-bold fg-white">Market</a> ürünlerimizi inceleye bilirsiniz.
        </div>
        <?php } 
// endif Conditional region1
?> 
<?php
mysql_free_result($uyetop);

mysql_free_result($serhead);

mysql_free_result($crdtophead);
?>

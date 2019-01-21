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
	
	<!-- KAYIT OL -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-user-plus"></i> KAYIT OL</span>
    </div>
    <div class="content">
	<table width="100%" border="0">
  <tr>
    <td height="45" bgcolor="#D6D6D6">&nbsp;&nbsp;&nbsp;<strong>Kayıt OL</strong></td>
  </tr>
  <tr>
    <td>
	
<script>
        function showCharm(id){
            var  charm = $("#"+id+"-charm").data("charm");
            if (charm.element.data("opened") === true) {
                charm.close();
            } else {
                charm.open();
            }
        }
    </script>
	<?php
			session_start();
			// Kontrol ve kayıt sistemi
			if ($_POST) {
				$username = $_POST["username"];
				$email = $_POST["email"];
				$password = md5($_POST["password"]);
				$kod = $_POST["kod"];
				$ip = $_POST["ip"];
				$level = $_POST["level"];
			if(strtoupper($_POST['kod']) == $_SESSION['dogrulamakodu']){
				
			}else{
				echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#C30; color:#FFF ; border:solid 1px #666;"><strong><i class="fa fa-exclamation-triangle"></i></strong> <strong>Doğrulama kodu hatalı.</strong></div>';
			}if (!$username || !$email || !$password) {
				echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#C30; color:#FFF ; border:solid 1px #666;"><strong><i class="fa fa-exclamation-triangle"></i></strong> <strong>
				Tüm alanları doldurmanız gerekiyor</strong></div>
								</h1></div>';
				echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#F33; color:#FFF; border:solid 1px #666;"><ul style="margin:0px; padding:0px; padding-left:10px;">
				<li>Tüm alanları doldurmanız gerekiyor</li>
				<li>Doğrulama kodunu yanlış girdiniz!</li>
				</ul></div>';
			
				}else{
					$kontrol = mysql_query("SELECT * FROM authme WHERE  username= '$username'");
					if(mysql_affected_rows()){
						
						echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#F60; color:#FFF ; border:solid 1px #666;"><strong style="color:#000"><i class="fa fa-exclamation-triangle"></i> HATA</strong> <strong> '.$username.' adlı  bir kullanıcı mevcut.</strong></div>
									';}
						
					
					$kontrol2 = mysql_query("SELECT * FROM authme WHERE  email= '$email'");
					if(mysql_affected_rows()){
						
						echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#F60; color:#FFF ; border:solid 1px #666;"><strong style="color:#000"><i class="fa fa-exclamation-triangle"></i> HATA</strong> <strong> '.$email.' adlı  bir E-Posta mevcut.</strong>
									</div>';}
									
					$kontrol3 = mysql_query("SELECT * FROM authme WHERE  ip= '$ip'");
					if(mysql_affected_rows()){
						
						echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#F60; color:#FFF ; border:solid 1px #666;"><strong style="color:#000"><i class="fa fa-exclamation-triangle"></i> HATA</strong> <strong> '.$ip.' adlı  bir IP kayıtlı.</strong>
									</div>';	}			
					
					
					else{
						
						$kayitol = mysql_query("INSERT INTO authme SET
						username = '$username',
						password = '$password',
						ip = '$ip',
						email = '$email',
						level = '$level'");
						if($kayitol){
							echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#090; color:#FFF ; border:solid 1px #666;"><strong><i class="fa fa-check-square-o"></i></strong> <strong>KAYIT İŞLEMİ BAŞARIYLA GERÇEKLEŞTİ</strong> - Yönlendiriliyorsunuz bekleyiniz....</div>';
							echo '<meta http-equiv="refresh" content="3;URL=login" />';	
						}else{
							echo '<div style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#C30; color:#FFF ; border:solid 1px #666;"><strong><i class="fa fa-exclamation-triangle"></i>HATA</strong> <strong>KAYIT İŞLEMİ YAPILAMADI</strong> '.mysql_Error().'</div>';
						}
					}
				}
			   
			} else {

			}
			
			
?>
	<div class="kayit" style="margin:10px; padding:15px; font-size:15px; font-family:Tahoma, Geneva, sans-serif; background-color:#E1F5DE; border:solid 1px #666;">
	 
      <form method="post" action="">
        <table width="100%" border="0">
          <tr>
            <td width="30%" height="35">Kullanıcı Adınız</td>
            <td width="4%" align="center"><strong>:</strong></td>
            <td width="66%" height="35"><label>
			<div class="input-control text full-size" data-role="input">
              <input type="text" size="30" data-role="popover" data-popover-position="left" data-popover-text="Bu alan zorunludur!" data-popover-background="bg-red" data-popover-color="fg-white" data-popover-mode="focus" name="username" id="username" placeholder="Kullanıcı Adı" maxlength="20"  required/>
			  <button class="button helper-button clear"><span class="mif-cross"></span></button>
</div>
            </label></td>
          </tr>
          <tr>
            <td height="35">E-posta Adresiniz</td>
            <td align="center"><strong>:</strong></td>
            <td height="35"><label>
			<div class="input-control text full-size" data-role="input">
              <input type="text" name="email" id="email" maxlength="90" data-role="popover" data-popover-position="left" data-popover-text="Bu alan zorunludur!" data-popover-background="bg-red" data-popover-color="fg-white" data-popover-mode="focus" placeholder="E-Posta Adresinizi Giriniz" required/>
			  <button class="button helper-button clear"><span class="mif-cross"></span></button>
</div>
            </label></td>
          </tr>
          <tr>
            <td height="35">Parolanız</td>
            <td align="center"><strong>:</strong></td>
            <td height="35"><label>
			<div class="input-control password" data-role="input">
              <input type="password" data-role="popover" data-popover-position="left" data-popover-text="Bu alan zorunludur!" data-popover-background="bg-red" data-popover-color="fg-white" data-popover-mode="focus" name="password" id="password" placeholder="Parola Belirtiniz" maxlength="20" required/>
			  <button class="button helper-button reveal"><span class="mif-looks"></span></button>
</div>
            </label></td>
          </tr>
          <tr>
            <td height="35">Güvenlik Kodu</td>
            <td align="center"><strong>:</strong></td>
            <td height="35"><label>
              <input class="input-control text" data-role="popover" data-popover-position="left" data-popover-text="Bu alan zorunludur!" data-popover-background="bg-red" data-popover-color="fg-white" data-popover-mode="focus" type="text" name="kod" id="kod" placeholder="Yandaki kodu giriniz" maxlength="6" required/><img src="guvenlik-kodu.php" height="25" width="75"/>
            </label></td>
          </tr>
          
          <tr>
            <td height="35">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td height="35"><label>
              <input type="submit" class="button success" name="button" onclick="showCharm('bottom')" id="button" value="KAYIT OL" />
            </label>
              <input type="hidden" name="ip" id="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
              <input type="hidden" name="level" id="level" value="1" /></td>
          </tr>
        </table>
		
		<table width="100%" border="0" align="center">
                  <tr>
                    <td height="35"><i class="icon-lock" style="color:#FC0"></i> <strong>İp Adresiniz : <span style="color:#C00"><?php echo $_SERVER['REMOTE_ADDR']; ?></span></strong><br />Aynı IP adresi ile maximum 1 kayıt yapa bilirsiniz.</td>
                    </tr>
                </table>
      </form>
    </div></td>
  </tr>
</table>
	
	
	
	
	
	
	</div></div>
	
	
	
	
	
	
	
	
	
	
	
	
	
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
	<?php include("page/sidebar2.php"); ?>
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
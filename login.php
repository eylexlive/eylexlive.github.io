<?php require_once('Connections/baglanti.php'); ?>
<?php require_once('Connections/baglanti.php'); 
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_baglanti = new KT_connection($baglanti, $database_baglanti);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("kt_login_user", true, "text", "", "", "", "");
$formValidation->addField("kt_login_password", true, "text", "", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make a login transaction instance
$loginTransaction = new tNG_login($conn_baglanti);
$tNGs->addTransaction($loginTransaction);
// Register triggers
$loginTransaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "kt_login1");
$loginTransaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$loginTransaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$loginTransaction->addColumn("kt_login_user", "STRING_TYPE", "POST", "kt_login_user");
$loginTransaction->addColumn("kt_login_password", "STRING_TYPE", "POST", "kt_login_password");
// End of login transaction instance

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rscustom = $tNGs->getRecordset("custom");
$row_rscustom = mysql_fetch_assoc($rscustom);
$totalRows_rscustom = mysql_num_rows($rscustom);


 ob_start();?>
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
?>
<?php
mysql_select_db($database_baglanti, $baglanti);
$query_ayar = "SELECT * FROM ayarlar WHERE id = 1";
$ayar = mysql_query($query_ayar, $baglanti) or die(mysql_error());
$row_ayar = mysql_fetch_assoc($ayar);
$totalRows_ayar = mysql_num_rows($ayar);
 /*
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
	<title>Giriş Yap / <?php echo $row_ayar['title']; ?></title>
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
   
	<style>
        .login-form {
            width: 25rem;
            height: 18.75rem;
            position: fixed;
            top: 50%;
            margin-top: -9.375rem;
            left: 50%;
            margin-left: -12.5rem;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>

    <script>

       


        $(function(){
            var form = $(".login-form");

            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
        });
    </script>
    <link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
    <script src="includes/common/js/base.js" type="text/javascript"></script>
    <script src="includes/common/js/utility.js" type="text/javascript"></script>
    <script src="includes/skins/style.js" type="text/javascript"></script>
    <?php echo $tNGs->displayValidationRules();?>
</head>

<body class="bg-darkTeal">

<div class="login-form padding20 block-shadow">

<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?>  

<center><h1 class="text-light">404 HATA</h1></center>
<center>
<h3>Sayın; <?php echo $_SESSION['kt_username']; ?></h3>
Zaten giriş yapmış bulunuyorsunuz...<br>
Anasayfaya yönlendiriliyor<br>
<br><br>
<h1>
<div data-role="preloader" data-type="metro" data-style="dark"></div>
</h1>
<br><br>
<meta http-equiv="refresh" content="7;URL=index.php" />
</center>


<?php } 
// endif Conditional region1
?> 







 <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
        <form method="post" id="form1" class="KT_tngformerror" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
            <h1 class="text-light">GİRİŞ YAP</h1>
			
            <hr class="thin"/>
            <?php
	echo $tNGs->getLoginMsg();
?>
            <br />
            
          <div class="input-control text full-size" data-role="input">
                <label for="kt_login_user">Kullanıcı Adı:</label>
                <input type="text" name="kt_login_user" id="kt_login_user" value="<?php echo KT_escapeAttribute($row_rscustom['kt_login_user']); ?>" size="32" required />
                <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>
            <br />
            <br />
            <div class="input-control password full-size" data-role="input">
                <label for="kt_login_password">Parolanız:</label>
                <input type="password" name="kt_login_password" id="kt_login_password" value="" size="32" required/>
                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
            </div>
            <br />
            <div class="form-actions">
      <button  class="button loading-cube lighten success block-shadow-success" type="submit" name="kt_login1" id="kt_login1"> GİRİŞ </button>
                <a href="<?php echo $row_ayar['siteurl']; ?>" type="button" class="button link">Anasayfa'ya Dön</a>
				<a href="register" type="button" class="button link">Kayıt OL</a>
				<?php echo $tNGs->displayFieldHint("kt_login_user");?>
				<?php echo $tNGs->displayFieldError("custom", "kt_login_user"); ?>
				<?php echo $tNGs->displayFieldHint("kt_login_password");?>
				<?php echo $tNGs->displayFieldError("custom", "kt_login_password"); ?>
            </div>
			
        </form>
		<?php } 
// endif Conditional region2
?> 
    </div>
</body>
</html>
<?php
mysql_free_result($ayar);
 ob_end_flush();?>

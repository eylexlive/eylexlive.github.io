<?php require_once('../Connections/baglanti.php'); ?>

<?php ob_start(); ?>
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
$query_ayars = "SELECT * FROM ayarlar WHERE id = 1";
$ayars = mysql_query($query_ayars, $baglanti) or die(mysql_error());
$row_ayars = mysql_fetch_assoc($ayars);
$totalRows_ayars = mysql_num_rows($ayars);

mysql_select_db($database_baglanti, $baglanti);
$query_tema = "SELECT * FROM admintema WHERE id = 1";
$tema = mysql_query($query_tema, $baglanti) or die(mysql_error());
$row_tema = mysql_fetch_assoc($tema);
$totalRows_tema = mysql_num_rows($tema);
 

function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

?>
<?php 

function buttonactive($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'active';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli / <?php echo $row_ayars['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" href="https://bootswatch.com/<?php echo $row_tema['temaadi']; ?>/bootstrap.css" media="screen">
<link rel="stylesheet" href="https://bootswatch.com/<?php echo $row_tema['temaadi']; ?>/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
// Only enable if the document has a long scroll bar
// Note the window height + offset
if ( ($(window).height() + 100) < $(document).height() ) {
    $('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
    });
}
</script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>
body { padding-top: 70px; }
#top-link-block.affix-top {
    position: absolute; /* allows it to "slide" up into view */
    bottom: -82px;
    left: 10px;
}
#top-link-block.affix {
    position: fixed; /* keeps it on the bottom once in view */
    bottom: 18px;
    left: 10px;
}
/* Chrome, Safari Scroll Bar Rengi*/
::-webkit-scrollbar {
width: 5px;
height: 10px;
}
::-webkit-scrollbar-track-piece  {
background-color: #C2D2E4;
}
::-webkit-scrollbar-thumb:vertical {
height: 30px;
background-color:#F90;
}
</style>
</head>

<body>

<?php include("page/head.php"); ?>
<div class="container" id="top">

<div class="g_12 separator"><h3>Server Günlük İstatistikleri</h3></div>
   <center>             
                <iframe id="iframe1" src="http://minecraft-mp.com/statistics/chart/daily/players/<?php echo $bizeoyver; ?>/" frameborder="0" height="300" width="100%" scrolling="yes" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
                
               <hr />
                
                <iframe id="iframe2" src="http://minecraft-mp.com/statistics/chart/daily/uptime/<?php echo $bizeoyver; ?>/" frameborder="0" height="300" width="100%" scrolling="yes" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
                
                <hr />
                
                <iframe id="iframe3" src="http://minecraft-mp.com/statistics/chart/daily/rank/<?php echo $bizeoyver; ?>/" frameborder="0" height="300" width="100%" scrolling="yes" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
                
               <hr />
                
                <iframe id="iframe4" src="http://minecraft-mp.com/statistics/chart/daily/votes/<?php echo $bizeoyver; ?>/" frameborder="0" height="300" width="100%" scrolling="yes" marginheight="0" marginwidth="0" allowtransparency="true"></iframe>
                
                <hr />
                
          </center>



<hr />
<div class="footer">
                        <p>Copyright &copy; 2015-<?php echo date('Y'); ?> <?php echo $row_ayars['copy']; ?> <a href="#top" class="well well-sm" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
        <i class="glyphicon glyphicon-chevron-up"></i> Yukarı Çık
    </a><small style="float:right">Tasarim & Kodlama <a href="http://www.mc-tr.com/forumlar/" title="CNGame Web Master" target="_blank">CNGame <img src="../images/arti.gif" width="9" height="9" /></a></small></p>
                    </div>
                    
                    
</div>
<?php
date_default_timezone_set('Europe/Istanbul');

function bosluksil($string)
{
   $string = preg_replace("/\s+/", " ", $string);
   $string = trim($string);
   return $string;
}

?>
<?php 
function tarihcevir($tarih){  
$tarih= date('d F Y D H:i', strtotime($tarih));   
$tarihdegistir =array(  
'Mon'=>'Pazartesi',  
'Tue'=>'Salı',  
'Wed'=>'Çarşamba',  
'Thu'=>'Perşembe',  
'Fri'=>'Cuma',  
'Sat'=>'Cumartesi',  
'Sun' => 'Pazar',  
'January' => 'Ocak',  
'February' => 'Şubat',  
'March' => 'Mart',  
'April' => 'Nisan',  
'May' => 'Mayıs',  
'June' => 'Haziran',  
'July' => 'Temmuz',  
'August' => 'Ağustos',  
'September' => 'Eylül',  
'October' => 'Ekim',  
'November' => 'Kasım',  
'December' => 'Aralık');  
$yenitarih= strtr($tarih, $tarihdegistir); 
return $yenitarih;  
} 
?>
<script src="js/function.js" type="text/javascript"></script>
</body>
</html>
<?php
mysql_free_result($ayars);

?>
<?php

ob_end_flush();

?>
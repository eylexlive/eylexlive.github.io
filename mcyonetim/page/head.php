
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
$query_ticyok = "SELECT * FROM ticket WHERE durum = '0'";
$ticyok = mysql_query($query_ticyok, $baglanti) or die(mysql_error());
$row_ticyok = mysql_fetch_assoc($ticyok);
$totalRows_ticyok = mysql_num_rows($ticyok);

mysql_select_db($database_baglanti, $baglanti);
$query_onlinepayr = "SELECT * FROM serverconfig WHERE id = 1";
$onlinepayr = mysql_query($query_onlinepayr, $baglanti) or die(mysql_error());
$row_onlinepayr = mysql_fetch_assoc($onlinepayr);
$totalRows_onlinepayr = mysql_num_rows($onlinepayr);
?>
<?php 
$APIkey = $row_onlinepayr['sadi'];
$json = file_get_contents("http://minecraft-mp.com/api/?object=servers&element=detail&key=$APIkey");
$data = json_decode($json);
	
	
	$onlinep = $data->players;
	$maxp = $data->maxplayers;
	$bizeoyver = $data->id;
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php?mc">MC-PANEL</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php echoActiveClassIfRequestMatches("index.php?mc")?>><a href="index.php?mc"><i class="fa fa-home"></i> Anasayfa</a></li>
        <li <?php echoActiveClassIfRequestMatches("index.php?mc=ticket")?>><a href="?mc=ticket"><i class="fa fa-ticket"></i> Gelen Destek Talepleri <span class="label label-success"><?php echo $totalRows_ticyok ?> </span></a></li>
        <li <?php echoActiveClassIfRequestMatches("index.php?mc=filedownload")?>><a href="?mc=filedownload"><i class="fa fa-download"></i> Dosya İndir</a></li>
        <li <?php echoActiveClassIfRequestMatches("index.php?mc=server")?>><a href="?mc=server"><i class="fa fa-user"></i> Online Player <span class="label label-success"><?php echo $onlinep; ?> </span>/<span class="label label-danger"><?php echo $maxp; ?> </span></a></li>
        
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="hidden-sm"><a href="<?php echo $row_ayars['siteurl']; ?>" target="_blank"><span class="fa fa-mail-forward"></span> Siteyi  Önizle</a></li>
            <li class="dropdown hidden-sm">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="fa fa-book"></span> Yardım <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="http://www.mc-tr.com/konu/windows-10-hyper-v-sunucu-kurulumu.8186" title="Hyper-V Sunucu Kurulumu" target="_blank">Hyper-V Sunucu Kurulumu</a></li>
                <li><a href="http://dev.bukkit.org/bukkit-plugins/" title="Craft Bukkit Plugin" target="_blank">Craft Bukkit Plugin</a></li>
                <li><a href="https://www.spigotmc.org/resources/categories/bukkit.4/" title="Spigot Plugin" target="_blank">Spigot Plugin</a></li>
                <li><a href="https://www.spigotmc.org/resources/categories/bungee-bukkit.2/" target="_blank">Bungeecord Plugin</a></li>
                <li><a href="http://www.mc-tr.com/forumlar/" target="_blank">Forum</a></li>
              </ul>
        </li>
            <li class="dropdown ">
              <a href="#" class="dropdown-toggle visible-sm visible-md" data-toggle="dropdown"><span class="fa fa-user"></span> User <b class="caret"></b></a>
              <a href="#" class="dropdown-toggle hidden-sm hidden-md" data-toggle="dropdown"><span class="fa fa-user"></span> <?php echo $_SESSION['kt_username']; ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="index.php?mc=profilme">Profil Ayarları</a></li>
                <li><a href="index.php?mc=passeditme">Şifre Değiştir</a></li>
                <li><a href="index.php?mc=adminadd">Yeni Admin Ekle</a></li>
                <li><a href="<?php echo $row_ayars['siteurl']; ?>cikis.php?KT_logout_now=1">Çıkış Yap</a></li>
              </ul>
            </li>
      </ul>
    </div>
  </div>
</nav>
<?php
mysql_free_result($ticyok);

mysql_free_result($onlinepayr);
?>

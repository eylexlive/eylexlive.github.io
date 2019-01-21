<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<?php
mysql_select_db($database_baglanti, $baglanti);
$query_serhead = "SELECT * FROM serverconfig WHERE id = 1";
$serhead = mysql_query($query_serhead, $baglanti) or die(mysql_error());
$row_serhead = mysql_fetch_assoc($serhead);
$totalRows_serhead = mysql_num_rows($serhead);
 // Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');


?>

<div class="masthead"><h3 style="float:right; color:#666; font-family:Tahoma, Geneva, sans-serif; font-weight:300; font-size:16px"><small>Server IP;</small> <?php echo $row_serhead['sip']; ?>:<?php echo $row_serhead['port']; ?> </h3>
 <h3 class="muted"><img src="images/<?php echo $row_ayar['sitelogo']; ?>" width="300" height="60" alt="<?php echo $row_ayar['title']; ?>" /> </h3>
<div class="navbar">
  <div class="navbar-inner">
        <ul class="nav nav-pills">
          <li><a href="<?php echo $row_ayar['siteurl']; ?>" title="<?php echo $row_ayar['title']; ?>"><i class="icon-home"></i> Anasayfa</a></li>
          <li><a href="webstore.html"><i class="icon-shopping-cart"></i> Market</a></li>
          <li><a href="news.html"><i class="icon-tasks"></i> Haberler</a></li></ul>
          <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
            <ul class="nav pull-right">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Giriş Yap / Kayıt OL<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="login.html"><i class="icon-lock"></i> Giriş Yap</a></li>
                  <li><a href="register.html"><i class="icon-user"></i> Kayıt OL</a></li>
                  <li class="divider"></li>
                  <li><a href="forgot.html"><i class="icon-refresh"></i> Şifremi Unuttum!</a></li>
                </ul>
              </li>
              </ul>
          <?php } 
// endif Conditional region2
?>
<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?>
              <!--Giris Yapti-->
              <ul class="nav pull-right">
                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="https://cravatar.eu/helmavatar/<?php echo $_SESSION['kt_username']; ?>" alt="Profil" width="20" height="20" /> <?php echo $_SESSION['kt_username']; ?><b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="cikis.php?KT_logout_now=1"><i class="icon-off"></i> Çıkış Yap</a></li>
                    <li><a href="register.html"><i class="icon-user"></i> Profilim</a></li>
                    <li class="divider"></li>
                    <li><a href="forgot.html"><i class="icon-wrench"></i> Şifre Değiştir</a></li>
                  </ul>
                </li>
              </ul>
              <!--Giris Yapti end-->
              <?php } 
// endif Conditional region1
?>
      </ul>
        </div>
</div>
       
      </div>
<?php
mysql_free_result($serhead);
?>

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
<!--
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
-->
<?php ob_start(); 
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
?>
<script type="text/javascript">
   $(function() {
     var pgurl = window.location.href.substr(window.location.href
.lastIndexOf("/")+1);
     $(".nav-collapse li a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
          $(this).addClass("active");
     })
});
</script>
<div class="masthead"><form class="form-search" style="float:right">
  <div class="input-append">
    <input type="text" class="span2 search-query" placeholder="Site içi player arama..." required>
    <button type="submit" class="btn">ARA</button>
  </div>
</form>
        <h3 class="muted"><img src="images/<?php echo $row_ayar['sitelogo']; ?>" width="300" height="60" alt="<?php echo $row_ayar['title']; ?>" /> 
  </h3>
        <div class="navbar">
          <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo $row_ayar['siteurl']; ?>">Minecraft</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="<?php echo $row_ayar['siteurl']; ?>"><i class="icon-home"></i> Anasayfa</a></li>
              <li><a href="about.html"><i class="icon-bookmark"></i> Hakkımızda</a></li>
              <li><a href="top-on.html"><i class="icon-star"></i> Top 10</a></li>
              <li><a href="webstore.html"><i class="icon-shopping-cart"></i> Market</a></li>
              <li><a href="forum.html"><i class="icon-th"></i> Forum</a></li>
              <li><a href="destek.html"><i class="icon-flag"></i> Destek</a></li>
            </ul>
            
             
            <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_id'] == "") {
?>
              <ul class="nav pull-right">
                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-plus-sign"></i> Giriş Yap / Kayıt OL<b class="caret"></b></a>
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
          </div><!--/.nav-collapse -->
        </div>
      </div>
        </div><!-- /.navbar -->
      </div>
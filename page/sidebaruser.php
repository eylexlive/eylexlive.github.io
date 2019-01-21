<!-- Default -->
<style type="text/css">
<!--
.usermenu {
	list-style-type: none;
	margin: 0px;
	padding: 0px;
}
.usermenu li a {
	text-decoration: none;
	line-height: 30px;
	display: block;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 16px;
	padding-top: 5px;
	padding-right: 10px;
	padding-bottom: 5px;
	padding-left: 20px;
	color: #333;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #666;
}
.usermenu li a:hover {
	background-color: #999;
}
-->
</style>

<div class="panel">
    <div class="heading">
        <span class="title"><i class="fa fa-bars"></i> KULLANICI MENUSU</span>
    </div>
    <div class="content">
      <ul class="usermenu">
        <?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_level'] == 1) {
?>
        <li><a href="products"><i class="fa fa-product-hunt"></i> Ürün & Hizmetlerim</a></li>
        <li><a href="supports"><i class="fa fa-ticket"></i> Destek Taleplerim</a></li>
        <li><a href="settings"><i class="fa fa-cogs"></i> Profil Ayarlarım</a></li>
        <li><a href="forgot"><i class="fa fa-lock"></i> Şifre Değiştir</a></li>
        <li><a href="account_information.php"><i class="fa fa-university"></i> Hesap Bilgilerimiz</a></li>
        <li><a href="payment_methods.php"><i class="fa fa-credit-card"></i> Ödeme Yöntemleri</a></li>
        <li><a href="news"><i class="fa fa-newspaper-o"></i> Haberler</a></li>
        <li><a href="embed.php"><i class="fa fa-link"></i> Sitene Ekle</a></li>
          <?php } 
// endif Conditional region1
?>
        <?php 
// Show IF Conditional region2 
if (@$_SESSION['kt_login_level'] == 2) {
?>
          <li><a href="mcyonetim/index.php?mc"><i class="fa fa-product-hunt"></i> Yönetim Paneli</a></li>
          <?php } 
// endif Conditional region2
?>
<li><a href="logout"><i class="fa fa-sign-out"></i> Çıkış Yap</a></li>
        
      </ul>
    </div>
</div>
<br>
<div class="panel success">
    <div class="heading">
        <span class="title"><i class="fa fa-users" style="color:#333; font-weight:bold"></i> EN SON KAYIT OLANLAR</span>
    </div>
    <div class="content" style="background-color:#FFF">
        <?php include("ensonkayitside.php"); ?>
    </div>
</div>
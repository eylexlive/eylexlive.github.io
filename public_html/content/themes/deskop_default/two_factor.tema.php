<?php !session('login') ? go(URL."/kayitol") : null; ?>
<?php
  $uye_id = session("uye_id");
  $uye = query("SELECT * FROM uyeler WHERE uye_id='$uye_id'");
  $row = row($uye);
  
  require_once 'includes/class/GoogleAuthenticator.php';
  $ga = new PHPGangsta_GoogleAuthenticator();
?>
<div class="panel panel-default">
  <div class="panel-heading">İki Faktörlü Doğrulama Sistemi 
    <?php if ($row["uye_oauth_uid"] != "0"): ?>
      <span style="float: right">
        <form action="" method="post">
          <button onclick="return confirm('Kapatmak istediğinizden emin misiniz? Geri dönüşü yoktur.')" type="submit" name="kapat" value="true" class="btn btn-danger btn-xs">Kapat</button>
        </form>
      </span>
    <?php endif; ?>
  </div>
  <div class="panel-body">
    <h4>İki faktörlü kimlik doğrulaması nedir?</h4>
    <p>Üyeliğiniz için ek bir güvenlik katmanı olan iki faktörlü kimlik doğrulama, başka biri parolanızı bilse bile hesabınıza yalnızca sizin erişmenizi sağlamak için tasarlanmıştır.</p>
    <?php if ($row["uye_oauth_uid"] != "0"): ?>
      <?php 
        if (isset($_POST["kapat"])) {
          $update = query("UPDATE uyeler SET uye_oauth_uid='0' WHERE uye_id='$uye_id'");
          if ($update) {
            go(ANLIK_URL);
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
        }
        if (isset($_POST["yeni_kod"])) {
          $secret = $ga->createSecret();
          $update = query("UPDATE uyeler SET uye_oauth_uid='$secret' WHERE uye_id='$uye_id'");
          if ($update) {
            go(ANLIK_URL);
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
        }
      
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($row["uye_kadi"], $row["uye_oauth_uid"], 'CraftWeb['.$_SERVER['HTTP_HOST']."]");
      ?>
      <div class="row">
        <div class="col-md-4">
          <center>
            <img src="<?=$qrCodeUrl?>" alt="CraftWeb" class="img-responsive">
            <form action="" method="post"><button onclick="return confirm('Değiştirmek istediğinizden emin misiniz? Geri dönüşü yoktur.')" type="submit" class="btn btn-success" name="yeni_kod" value="true">Yeni Kod Oluştur</button></form>
          </center>
        </div>
        <div class="col-md-8">
          Manual kullanım için "Güvenlik Kodunuz": <b><?=$row["uye_oauth_uid"]?></b><br><br>
          Önerilen Authenticator Uygulamaları:
          <ul>
            <li><a href="https://support.google.com/accounts/answer/1066447?co=GENIE.Platform%3DAndroid&hl=tr" target="_blank">Google Authenticator</a> (google.com)</li>
            <li><a href="https://chrome.google.com/webstore/detail/authenticator/bhghoamapcdpbohphigoooaddinpkbai" target="_blank">Authenticator</a> (sneezry.com)</li>
            <li><a href="https://itunes.apple.com/us/app/hde-otp-generator/id571240327" target="_blank">HDE OTP Generator</a> (HDE)</li>
          </ul><br>
          <div class="alert alert-warning">
            Eğer Authenticator kodunu kaybederseniz hesabınıza bir daha ulaşamayabilirsiniz!<br>
            Bu yüzden Manual Authenticator kodunu güvenli bir yerde saklayın.
          </div>
        </div>
      </div>
    <?php else: ?>
      <?php 
        if (isset($_POST["ac"])) {
          $secret = $ga->createSecret();
          $update = query("UPDATE uyeler SET uye_oauth_uid='$secret' WHERE uye_id='$uye_id'");
          if ($update) {
            go(ANLIK_URL);
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
        }
      ?>
      <form action="" method="post">
        <button onclick="return confirm('Açmak istediğinizden emin misiniz? Geri dönüşü yoktur.')" type="submit" name="ac" value="true" class="btn btn-block btn-success btn-lg">İki faktörlü kimlik doğrulamayı Etkinleştir</button>
      </form>
    <?php endif; ?>
  </div>
</div>
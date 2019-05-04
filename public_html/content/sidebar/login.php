<?php
  if (!session("login") AND cookie("CWACCOUNTID")) {
    $uye_token = @htmlspecialchars(addslashes(trim(cookie("CWACCOUNTID"))));
    $cookie = query("SELECT * FROM uyeler WHERE uye_token = '$uye_token'");
    if(rows($cookie) > 0){
      $row = row($cookie);
      session_olustur(array(
        'login' => true,
        'uye_id' => $row['uye_id'],
        'uye_hesap_tur' => $row['uye_hesap_tur']
      ));
      cookie_olustur("CWACCOUNTID",$row["uye_token"],"600000");
      go(URL);
    }else{
      cookie_sil("CWACCOUNTID");
    }
  }else if(!session("login") AND !cookie("CWACCOUNTID")){
    if (isset($_POST["giris"])) {
      $uye_kadi = post("uye_kadi");
      $uye_sifre = post("uye_sifre");
      $beni_hatirla = post("beni_hatirla");
      if (!$uye_kadi OR !$uye_sifre) {
        echo alert("Lütfen boş alan bırakmayınız.","danger");
      }else{
        $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi'");
        if (rows($varmi) < 1) {
            echo alert("Böyle bir oyuncu bulunamadı.", "danger");
        }else{
          $row = row($varmi);
          
          if (mset("authme_sifre") == "sha256"){
            $k_sifre = sha256Kontrol($uye_sifre,$row["uye_sifre"]);
          }else{
            $k_sifre = ($row["uye_sifre"] != md5($uye_sifre))?false:true;
          }
          if ($k_sifre == false) {
              echo alert("Yazdığınız parola uyuşmuyor.", "danger");
          }else{
            if ($row["uye_oauth_uid"] != "0") {
              session_olustur(array('two_factor' => true,'uye_o_id' => $row['uye_id']));
            }else{
              session_olustur(array('login' => true,'uye_id' => $row['uye_id']));
              if ($beni_hatirla == 1) {
                  cookie_olustur("CWACCOUNTID", $row["uye_token"], "600000");
              }else{
                  cookie_sil("CWACCOUNTID");
              }
              echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...", "success");
              go(ANLIK_URL, 2);
            }
          }
        }
      }
    }
  ?>
  <?php if (session("two_factor") == true): ?>
    <?php
      if (isset($_POST["kontrol"])) {
        require_once 'includes/class/GoogleAuthenticator.php';
        $ga = new PHPGangsta_GoogleAuthenticator();
        
        $oneCode = post("code1").post("code2").post("code3").post("code4").post("code5").post("code6");
        $oneCode = empty($oneCode)?"999999":$oneCode;
        $uyeQ = query("SELECT * FROM uyeler WHERE uye_id = '".session("uye_o_id")."'");
        $uRow = row($uyeQ);
        
        $checkResult = $ga->verifyCode($uRow["uye_oauth_uid"], $oneCode, 2);
        if ($checkResult) {
          session_sil("two_factor");
          session_sil("uye_o_id");
          
          session_olustur(array(
            'login' => true,
            'uye_id' => $uRow['uye_id']
          ));
          cookie_olustur("CWACCOUNTID", $uRow["uye_token"], "600000");
          echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...", "success");
          go(ANLIK_URL, 2);
        }else{
          echo alert("Güvenlik Kodu Hatalı!", "danger");
        }
      }
    ?>
    <div class="panel panel-default">
      <div class="panel-heading">Giriş Yap</div>
      <div class="panel-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="login-username">Güvenlik Kodu:</label>
            <div class="gkod">
              <input type="text" class="form-control input-lg" maxlength="1" name="code1">
              <input type="text" class="form-control input-lg" maxlength="1" name="code2">
              <input type="text" class="form-control input-lg" maxlength="1" name="code3">
              <span class="cizgi">-</span>
              <input type="text" class="form-control input-lg" maxlength="1" name="code4">
              <input type="text" class="form-control input-lg" maxlength="1" name="code5">
              <input type="text" class="form-control input-lg" maxlength="1" name="code6">
            </div>
          </div>
          <p>Authenticator Uygulamanızdaki 6 Haneli giriş kodunu girmelisiniz.</p>
          <div class="form-group" style="margin-bottom:0;">
            <button class="btn btn-block btn-success" name="kontrol" value="true" type="submit">Giriş Yap</button>
          </div>
        </form>
      </div>
    </div>
    <script type="text/javascript">
      var input = $(".gkod input");
      for (var i = 0; i < input.length; i++) {
        input[i].index = i;
        input[i].oncopy = function(e) {
          return false;
        }
        input[i].oncut = function(e) {
          return false;
        }
        input[i].onpaste = function(e) {
          return false;
        }
      }
      for (var i = 0; i < input.length; i++) {
        input[i].onkeyup = function(e) {
          if (e.keyCode == 8) {
            input[this.index - 1].focus();
          }
          if (/[0-9]/.test(this.value)) {
            if (input.length != this.index + 1) {
              input[this.index + 1].focus();
            }else{
              $("button[name=kontrol]").click();
            }
          } else {
            this.value = "";
          }
        }
      }
    </script>
  <?php else: ?>
    <div class="panel panel-default">
      <div class="panel-heading">Giriş Yap</div>
      <div class="panel-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="uye_kadi">Kullanıcı Adı</label>
            <input type="text" class="form-control" name="uye_kadi" id="uye_kadi">
          </div>
          <div class="form-group">
            <label for="uye_sifre">Şifre</label>
            <input type="password" class="form-control" name="uye_sifre" id="uye_sifre">
          </div>
          <div class="form-group">
            <input type="checkbox" name="beni_hatirla" value="1"> Beni Hatırla
          </div>
          <button type="submit" name="giris" value="giris" class="btn btn-block btn-success">Giriş Yap</button>
          <a href="<?=URL?>/kayitol" class="btn btn-block btn-default">Kayıt Ol</a>
          <a href="<?=URL?>/sifremi_unuttum" class="btn btn-block btn-default">Şifremi Unuttum</a>
        </form>
      </div>
    </div>
  <?php endif; ?>
<?php }else{ ?>
  <?php $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'")); ?>
  <div class="panel panel-default">
    
    <table class="table">
      <tbody>
        <tr>
          <td style="width:40%" class="text-right"><img src="//cravatar.eu/avatar/<?=$user["uye_kadi"]?>/40.png" alt="<?=$user["uye_kadi"]?> Avatar"></td>
          <td style="width:60%;vertical-align:middle;">
            <strong><?=$user["uye_kadi"]?></strong><br><?=$user["uye_email"]?>
          </td>
        </tr>
        <tr>
          <td style="width:40%;color:#DA4453" class="text-right">Kredin:</td>
          <td style="width:60%;color:#ED5565"><i class="fa fa-try"></i> <?=$user["uye_kredi"]?> <a href="<?=URL?>/kredi" data-toggle="tooltip" data-placement="right" title="" class="fa fa-plus-circle" data-original-title="Kredi Ekle"></a></td>
        </tr>
      </tbody>
    </table>
    
    <div class="panel-body">
      <?php if ($user["uye_rutbe"] > 0): ?>
        <a class="btn btn-block btn-danger" href="<?=ADMIN_URL?>">Yönetim Paneli</a>
      <?php endif; ?>
      <a class="btn btn-block btn-default" href="<?=URL?>/profil">Profil</a>
      <a class="btn btn-block btn-default" href="<?=URL?>/kredi">Kredi Yükle</a>
      <a class="btn btn-block btn-default" href="<?=URL?>/two_factor">İki Adımlı Doğrulama</a>
      <a class="btn btn-block btn-default" href="<?=URL?>/cikis">Çıkış Yap</a>
    </div>
  </div>
<?php } ?>
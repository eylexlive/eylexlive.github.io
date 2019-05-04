<?php (session("login"))?go_js(URL):null; ?>
<h3 class="ui-bar ui-bar-a ui-corner-all text-center">Giriş Yap</h3>
<?php
if(!session("login") AND !cookie("CWACCOUNTID")){
  if (isset($_POST["giris"])) {
    $uye_kadi = post("uye_kadi");
    $uye_sifre = md5(post("uye_sifre"));
    $beni_hatirla = post("beni_hatirla");
    if (!$uye_kadi OR !$uye_sifre) {
      echo alert("Lütfen boş alan bırakmayınız.","danger");
    }else{
      $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi'");
      if(rows($varmi) < 1){
        echo alert("Böyle bir oyuncu bulunamadı.","danger");
      }else {
        $row = row($varmi);
        if ($row["uye_hesap_tur"] != "1") {
          echo alert("Bu hesap Facebook hesabıdır. Facebook üzerinden giriş yapınız.");
        }else{
          if ($row["uye_sifre"] != $uye_sifre) {
            echo alert("Yazdığınız parola uyuşmuyor.","danger");
          }else{
            session_olustur(array(
              'login' => true,
              'uye_id' => $row['uye_id'],
              'uye_hesap_tur' => $row['uye_hesap_tur']
            ));
            if ($beni_hatirla == 1) {
              cookie_olustur("CWACCOUNTID",$row["uye_token"],"600000");
            }else{
              cookie_sil("CWACCOUNTID");
            }
            go_js(URL,2);
            echo alert("Giriş işlemi başarılı. Sayfa Yenileniyor...","success");
          }
        }
      }
    }
  }
?>
  <div class="ui-body ui-body-a ui-corner-all">
    <form action="" method="post">
      <div class="form-group">
        <label>Kullanıcı Adı:</label>
        <input type="text" class="form-control" name="uye_kadi">
      </div>
      <div class="form-group">
        <label>Şifre:</label>
        <input type="password" class="form-control" name="uye_sifre">
      </div>
      <fieldset data-role="controlgroup">
        <input type="checkbox" name="beni_hatirla" id="beni_hatirla">
        <label for="beni_hatirla">Beni Hatırla</label>
      </fieldset>
      <button type="submit" name="giris" value="giris" class="btn btn-block btn-success">Giriş Yap</button>
    </form>
  </div>
<?php } ?>
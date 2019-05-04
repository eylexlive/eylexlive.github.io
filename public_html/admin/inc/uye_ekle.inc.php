<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<?php
	if(isset($_POST["uye_ekle"])){
		$uye_kadi   = post("uye_kadi");
		$uye_sifre  = post("uye_sifre1");
		$uye_sifre2 = post("uye_sifre2");
    $uye_eposta = post("uye_email");
    $uye_kredi  = post("uye_kredi");
		$uye_rutbe  = post("uye_rutbe");
		
		if(!$uye_kadi || !$uye_eposta || !$uye_sifre || !$uye_sifre2){
			echo alert("Lütfen boş alan bırakmayınız.","danger");
		}else if($uye_sifre != $uye_sifre2){
			echo alert("Yazdığınız şifreler birbiri ile uyuşmuyor!");
		}else{
			$varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi' OR uye_email = '$uye_eposta'");
			if(rows($varmi) < 1){
        $uye_sifre = (mset("authme_sifre") == "md5") ? md5($uye_sifre) : sha256Olustur($uye_sifre);
				$insert = query("INSERT INTO uyeler SET
  				uye_kadi = '$uye_kadi',
  				uye_sifre = '$uye_sifre',
  				uye_email = '$uye_eposta',
          uye_kayit_tarih = '".time()."',
          uye_token = '".rasgeleSifre(32)."',
          uye_kredi = '$uye_kredi',
          uye_rutbe = '$uye_rutbe'
        ");
				
				if($insert){
					echo alert('Kullanıcı Ekleme Başarılı... Yönlendiriliyorsunuz...',"success");
					go(ADMIN_URL."/?go=uyeler",2);
				}else{
          echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
				}
				
			}else{
				echo alert('<strong>'.ss($uye_kadi).'</strong> veya <strong>'.ss($uye_eposta).'</strong> kullanılıyor. Lütfen başka bir tane deneyin.');
			}
		}
	}
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
    <h3 class="block-title">Üye Oluştur</h3>
  </div>
  <div class="block-content">
    <form action="" method="post">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="uye_kadi">Üye Kullanıcı Adı:</label>
            <input type="text" class="form-control" id="uye_kadi" name="uye_kadi" value="<?=isset($uye_kadi)?$uye_kadi:null;?>">
          </div>
          <div class="form-group">
            <label for="uye_sifre1">Üye Şifre:</label>
            <input type="password" class="form-control" id="uye_sifre1" name="uye_sifre1">
            <p class="help-block">Ayarlar üzerindeki <b>Şifreleme Methodu</b> kullanılır.</p>
          </div>
          <div class="form-group">
            <label for="uye_sifre2">Üye Şifre (Tekrar):</label>
            <input type="password" class="form-control" id="uye_sifre2" name="uye_sifre2">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="uye_email">Üye Email:</label>
            <input type="email" class="form-control" id="uye_email" name="uye_email" value="<?=isset($uye_eposta)?$uye_eposta:null;?>">
          </div>
          <div class="form-group">
            <label for="uye_kredi">Üye Kredi:</label>
            <input type="number" class="form-control" id="uye_kredi" name="uye_kredi" value="0">
            <p class="help-block">Maksimum 11 haneli bir kredi atanabilir.</p>
          </div>
          <div class="form-group">
            <label for="uye_rutbe">Üye Yetki:</label>
            <select class="form-control" id="uye_rutbe" name="uye_rutbe">
              <option value="0" selected>Üye</option>
              <option value="1">Yönetici</option>
              <option value="2">Moderatör</option>
              <option value="3">Ticket Yetkilisi</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" name="uye_ekle" value="true" class="btn btn-block btn-primary">Üye Oluştur</button>
      </div>
    </form>
  </div>
</div>    
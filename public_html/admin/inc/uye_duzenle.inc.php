<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
  
  if(isset($_GET["uid"])){
		$uid = get("uid");
		$varmi = query("SELECT * FROM uyeler WHERE uye_id = '$uid'");
		if(rows($varmi) < 1){
      go(ADMIN_URL."/?go=uyeler",2);
			exit;
		}else{
			$uyeRow = row($varmi);
		}
	}else{
    go(ADMIN_URL."/?go=uyeler",2);
		exit;
	}
  
	if(isset($_POST["uye_duzenle"])){
		$uye_kadi   = post("uye_kadi");
    $uye_sifre  = post("uye_sifre1");
		$uye_sifre2 = post("uye_sifre2");
    $uye_eposta = post("uye_email");
    $uye_kredi  = post("uye_kredi");
		$uye_rutbe  = post("uye_rutbe");
		
		if(!$uye_kadi || !$uye_eposta){
			echo alert("Lütfen boş alan bırakmayınız.","danger");
		}else if($uye_sifre != $uye_sifre2){
			echo alert("Yazdığınız şifreler birbiri ile uyuşmuyor!");
		}else{
      if($uye_sifre && $uye_sifre2){
        $uye_sifre = (mset("authme_sifre") == "md5") ? md5($uye_sifre) : sha256Olustur($uye_sifre);
  		}else{
  			$uye_sifre = $uyeRow["uye_sifre"];
  			$uye_sifre2 = $uyeRow["uye_sifre"];
  		}
      
			$varmi = query("SELECT * FROM uyeler WHERE (uye_kadi = '$uye_kadi' OR uye_email = '$uye_eposta') AND uye_id != '$uid'");
			if(rows($varmi) < 1){
				$insert = query("UPDATE uyeler SET
  				uye_kadi = '$uye_kadi',
  				uye_sifre = '$uye_sifre',
  				uye_email = '$uye_eposta',
          uye_kredi = '$uye_kredi',
          uye_rutbe = '$uye_rutbe'
        WHERE uye_id='$uid'");
				
				if($insert){
					echo alert('Kullanıcı Güncelleme Başarılı... Yönlendiriliyorsunuz...',"success");
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
            <input type="text" class="form-control" id="uye_kadi" name="uye_kadi" value="<?=$uyeRow["uye_kadi"]?>">
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
            <input type="email" class="form-control" id="uye_email" name="uye_email" value="<?=$uyeRow["uye_email"]?>">
          </div>
          <div class="form-group">
            <label for="uye_kredi">Üye Kredi:</label>
            <input type="number" class="form-control" id="uye_kredi" name="uye_kredi" value="<?=$uyeRow["uye_kredi"]?>">
            <p class="help-block">Maksimum 11 haneli bir kredi atanabilir.</p>
          </div>
          <div class="form-group">
            <label for="uye_rutbe">Üye Yetki:</label>
            <select class="form-control" id="uye_rutbe" name="uye_rutbe">
              <option <?=($uyeRow["uye_rutbe"] == "0")?"selected":null;?> value="0">Üye</option>
              <option <?=($uyeRow["uye_rutbe"] == "1")?"selected":null;?> value="1">Yönetici</option>
              <option <?=($uyeRow["uye_rutbe"] == "2")?"selected":null;?> value="2">Moderatör</option>
              <option <?=($uyeRow["uye_rutbe"] == "3")?"selected":null;?> value="3">Ticket Yetkilisi</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" name="uye_duzenle" value="true" class="btn btn-block btn-primary">Üye Güncelle</button>
      </div>
    </form>
  </div>
</div>
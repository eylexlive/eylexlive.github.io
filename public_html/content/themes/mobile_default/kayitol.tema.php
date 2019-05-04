<?php session("login") ? go_js(URL."/profil") : null; ?>
<h3 class="ui-bar ui-bar-a ui-corner-all text-center">Kayıt Ol</h3>
<?php
  $uye_kadi   = rasgeleSifre("32");
  $uye_sifre  = rasgeleSifre("32");
  $uye_sifre2 = rasgeleSifre("32");
	$uye_email  = rasgeleSifre("32");
  
  $salt = base64_encode("$uye_kadi||$uye_sifre||$uye_sifre2||$uye_email");
  
	if(isset($_POST["CwSubmit"])){
    $coz = base64_decode(post("CwToken"));
    $coz = explode("||",$coz);
    
		$kadi   = post($coz[0]);
		$sifre  = post($coz[1]);
		$sifre2 = post($coz[2]);
		$email  = post($coz[3]);
			
		if(!$kadi || !$sifre || !$sifre2 || !$email){
			echo '<div class="alert alert-warning">Boş alan bırakmayınız.</div>';
		}else if($sifre != $sifre2){
			echo '<div class="alert alert-warning">Şifreler uyuşmuyor.</div>';
		}else{
			if(kontrol($kadi)){
				echo '<div class="alert alert-danger">Girdiğiniz kullanıcı adı uygun olmayan karakter içeriyor.</div>';
			}else{
				$varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$kadi'");
				if(rows($varmi)){
					echo '<div class="alert alert-warning"><strong>'.$kadi.'</strong> adlı bir üye zaten mevcut.</div>';
				}else{
					$varmi = query("SELECT * FROM uyeler WHERE uye_email = '$email'");
					if(rows($varmi)){
						echo '<div class="alert alert-warning"><strong>'.ss($email).'</strong> Girdiğiniz email başkası tarafından kullanılıyor.</div>';
					}else{
            $sifre = (mset("authme_sifre") == "MD5") ? md5($sifre2) : sha256Olustur($sifre2);
						$insert = query("INSERT INTO uyeler SET
						uye_kadi = '$kadi',
						uye_sifre = '$sifre',
						uye_email = '$email',
                        uye_kayit_tarih = '".time()."',
                        uye_kayit_ip = '".GetIP()."',
                        uye_token = '".rasgeleSifre(32)."',
						uye_rutbe = 0");
						if($insert){
							$id = mysqli_insert_id($baglan);
							$row = row(query("SELECT * FROM uyeler WHERE uye_id = '$id'"));
                              session_olustur(array(
                                'login' => true,
                                'uye_id' => $row['uye_id'],
                                'uye_hesap_tur' => $row['uye_hesap_tur']
                              ));
							echo '<div class="alert alert-success">Başarıyla kayıt oldunuz. Yönlendiriliyorsunuz.</div>';
							go_js(URL,2);
						}else{
							echo '<div class="alert alert-danger"><strong>Mysql Hatası: </strong> '.mysqli_error($insert).'</div>';
						}
					}
				}
			}
		}
	}
?>
<div class="ui-body ui-body-a ui-corner-all">
	<form action="" method="post" id="kayitform">
		<div class="form-group">
			<label class="control-label">Kullanıcı Adı:</label>
			<input type="text" name="<?=$uye_kadi?>" maxlength="30" class="form-control" placeholder="Kullanıcı Adını giriniz." value="<?=isset($kadi)?$kadi:null;?>">
		</div>
    
		<div class="form-group">
			<label class="control-label">Şifre:</label>
			<input type="password" name="<?=$uye_sifre?>" class="form-control" placeholder="Şifre Giriniz.">
		</div>
    
		<div class="form-group">
			<label class="control-label">Şifre (Tekrar):</label>
			<input type="password" name="<?=$uye_sifre2?>" class="form-control" placeholder="Şifre Giriniz.">
		</div>
    
		<div class="form-group">
			<label class="control-label">Email:</label>
      <input type="email" name="<?=$uye_email?>" class="form-control" placeholder="Geçerli bir email giriniz." value="<?=isset($email)?$email:null;?>">
		</div>
    
    <input type="hidden" name="CwToken" value="<?=$salt?>">
		<input type="hidden" name="CwSubmit" value="register">
		<button style="width: 100%;" class="btn btn-primary" name="kayitol">Kayıt Ol</button>
	</form>
</div>
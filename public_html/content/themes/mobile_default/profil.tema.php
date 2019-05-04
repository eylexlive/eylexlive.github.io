<?php
  !session('login') ? go_js(URL."/kayitol") : null;
  $uye_id = session("uye_id");
	$query = query("SELECT * FROM uyeler WHERE uye_id = '$uye_id'");
	$row = row($query);
?>
<h3 class="ui-bar ui-bar-a ui-corner-all">Profil Detayları</h3>
<div data-demo-html="true">			
  <div data-role="collapsible-set">
    <div data-role="collapsible">
      <h3>Şifre Değiştir</h3>
      
      <?php
        if(isset($_POST["sifre_degistir"])){
          $eski_sifre = post("eski_sifre");
          $yeni_sifre = post("yeni_sifre");
          $yeni_sifre2 = post("yeni_sifre2");
          if(!$eski_sifre || !$yeni_sifre || !$yeni_sifre2){
            echo alert('Şifre bölümlerini doldurun.');
          }else if($yeni_sifre != $yeni_sifre2){
            echo alert('Yeni şifreler uyuşmuyor.');
          }else{
						if (mset("authme_sifre") == "sha256"){
	            $k_sifre = sha256Kontrol($eski_sifre,$row["uye_sifre"]);
	          }else{
	            $k_sifre = ($row["uye_sifre"] != md5($eski_sifre))?false:true;
	          }
	          if ($k_sifre == false) {
	              echo alert('Mevcut şifrenizi yanlış girdiniz.');
	          }else{
							$yeni_sifre = (mset("authme_sifre") == "md5") ? md5($yeni_sifre2) : sha256Olustur($yeni_sifre2);
              $update = query("UPDATE uyeler SET uye_sifre = '$yeni_sifre' WHERE uye_id = '$uye_id'");
              if($update){
                echo alert('Şifreniz güncellendi! Yönlendiriliyorsunuz...','success');
                go(ANLIK_URL,1);
              }else{
                echo alert('Mysql Hatası: '.mysqli_error($baglan));
              }
            }
          }
        }
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label for="">Mevcut Şifreniz:</label>
          <input type="password" name="eski_sifre" id="" class="form-control" />
        </div>
        <div class="form-group">
          <label for="">Yeni Şifreniz:</label>
          <input type="password" name="yeni_sifre" id="" class="form-control" />
        </div>
        <div class="form-group">
          <label for="">Yeni Şifreniz(Tekrar):</label>
          <input type="password" name="yeni_sifre2" id="" class="form-control" />
        </div>
        <button name="sifre_degistir" class="btn btn-success btn-block">Güncelle</button>
      </form>
    </div>
    <div data-role="collapsible">
      <h3>Email Değiştir</h3>
      <?php
        if(isset($_POST["email_degistir"])){
          $mevcut_sifre = post("mevcut_sifre");
          $uye_email = post("uye_email");
          if(!$mevcut_sifre || !$uye_email){
            echo alert('Email değiştirme bölümünün tamamını doldurun.');
          }else{
						if (mset("authme_sifre") == "sha256"){
	            $k_sifre = sha256Kontrol($mevcut_sifre,$row["uye_sifre"]);
	          }else{
	            $k_sifre = ($row["uye_sifre"] != md5($mevcut_sifre))?false:true;
	          }
	          if ($k_sifre == false) {
	              echo alert('Mevcut şifrenizi yanlış girdiniz.');
	          }else{
              $update = query("UPDATE uyeler SET uye_email = '$uye_email' WHERE uye_id = '".session('uye_id')."'");
              if($update){
                echo alert('Email adresiniz değişti. Yönelndiriliyorsunuz.','success');
                go(ANLIK_URL,1);
              }else{
                echo alert('Mysql Hatası: '.mysqli_error($baglan));
              }
            }
          }
        }
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label for="">Mevcut Şifreniz:</label>
          <input type="password" name="mevcut_sifre" id="" class="form-control" />
        </div>
        <div class="form-group">
          <label for="">Email Hesabınız:</label>
          <input type="text" name="uye_email" id="" class="form-control" value="<?php echo $row["uye_email"]; ?>" />
        </div>
        <button name="email_degistir" class="btn btn-success btn-block">Güncelle</button>
      </form>  
    </div>
  </div>
</div>
<br>
<div class="alert alert-info text-center"><strong>İşlem geçmişini görmek için masaüstü sitesine giriniz.</strong></div>
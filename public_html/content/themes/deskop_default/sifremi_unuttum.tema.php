<?php session("login") ? go(URL."/profil") : null; ?>
<?php if (isset($_GET["token"])): ?>
  <?php if (session("token") == get("token")): ?>
    <?php
      if (isset($_POST["sifremi_yenile"])) {
        $sifre = post("sifre");
        $sifre2 = post("sifre2");
        
        if (!$sifre OR !$sifre2) {
          echo alert("Lütfen boş alan bırakmayınız.","danger");
        }else if($sifre != $sifre2){
          echo alert("Yazdığınız şifreler uyuşmuyor!","danger");
        }else{
          $token = get("token");
          $varmi = query("SELECT * FROM sifre_token WHERE st_token = '$token'");
          if (rows($varmi) > 0) {
            $row = row($varmi);
            $yeni_sifre = (mset("authme_sifre") == "md5") ? md5($sifre) : sha256Olustur($sifre);
            $update = query("UPDATE uyeler SET uye_sifre = '$yeni_sifre' WHERE uye_id = '".$row['st_uye']."'");
            if($update){
              echo alert('Şifreniz başarıyla değiştirildi. Yönlendiriliyorsunuz.','success');
              go(URL,1); session_destroy();
            }else{
              echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
          }else{
            echo alert('Bir hata oluştu. Lütfen tekrar deneyiniz.',"danger");
          }
        }
      }
    ?>
    <div class="panel panel-default">
      <div class="panel-heading">Şifremi Sıfırla</div>
      <div class="panel-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="uye_kadi">Şifre:</label>
            <input type="password" class="form-control" id="sifre" name="sifre">
          </div>
          <div class="form-group">
            <label for="uye_email">Şifre: (Tekrar)</label>
            <input type="password" class="form-control" id="sifre2" name="sifre2">
          </div>
          <button type="submit" class="btn btn-block btn-success" name="sifremi_yenile" value="sifremi_yenile">Şifremi Güncelle</button>
        </form>
      </div>
    </div>
  <?php else:
    go(URL."/sifremi_unuttum");
  endif; ?>
<?php else: ?>
  <?php 

    if (isset($_POST["sifremi_unuttum"])) {
      $uye_kadi = post("uye_kadi");
      $uye_email = post("uye_email");
      
      if ($uye_kadi AND $uye_email) {
        if (!kontrol($uye_kadi)) {
          $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi' AND uye_email = '$uye_email'");
          if(rows($varmi) == 1) {
            $row = row($varmi);
            $uye_iid = $row["uye_id"];
            $kod = rasgeleSifre("64");
            session_olustur(array('token' => $kod));
            $s_url = URL."/sifremi_unuttum/".$kod;
            $insert = query("INSERT INTO sifre_token SET st_token = '$kod',st_uye = '$uye_iid',st_tarih ='".time()."'");
            
            include 'includes/class/class.phpmailer.php';
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            $mail->IsSMTP();
            $mail->SMTPSecure = mset("smtp_secu");
            $mail->SMTPAuth = true;
            $mail->Host = mset("smtp_host");
            $mail->Port = mset("smtp_port");
            $mail->Username = mset("smtp_mail");
            $mail->Password = mset("smtp_pass");
            $mail->SetFrom($mail->Username, mset("site_adi"));
            $mail->AddAddress($uye_email, $uye_kadi);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = mset("site_adi").' Parola Sıfırlama';
            $mail->IsHTML(true);
            $mail->MsgHTML('<div style="padding:10px"> <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;"> <tbody> <tr> <td style="background-color: #47456b;padding: 10px 13px;font-family: \'Trebuchet MS\',Helvetica,Arial,sans-serif;font-size: 12px;line-height: 1.231;"> <a href="'.URL.'" style="color: #ffffff;text-decoration:none;">Parola Sıfırlama | '.mset("site_adi").'</a> <a href="'.base64_decode("aHR0cHM6Ly9jcmFmdHdlYi5jbw==").'" style="color:#ffffff;text-decoration:none;float:right;">CraftWeb v5 Scripti</a> </td></tr><tr> <td style="background-color:#fcfcff;padding:1em;color:#141414;font-family:\'Trebuchet MS\',Helvetica,Arial,sans-serif;font-size:13px;line-height:1.231"> <h2 style="font-size:18pt;font-weight:normal;margin: 0px 0 10px"><a href="#" style="color:#176093;text-decoration:none">Parolamı Unuttum</a></h2> <p style="margin-top:0">Merhaba, '.$uye_kadi.' parola sıfırlama talebinde bulunuldu. <br>Parola sıfırlama isteğini siz yapmadıysanız, bu maili dikkate almayın.</p><hr style="height:1px;margin:10px 0;border:0;color:#d7edfc;background-color:#d7edfc"> <p style="margin-top:0">Parolanızı sıfırlamak için aşağıda bulunan bağlantıya veya butona tıklayınız.</p><a href="'.$s_url.'" style="padding:5px 10px;background-color:#47466b;border:none;border-radius:3px;font-size:11px;color:white;text-decoration:none" target="_blank">Parolamı Sıfırla</a> <p style="font-size:11px;color:#969696;margin-top: 15px;"><a href="'.$s_url.'" style="color:#969696;text-decoration:underline" target="_blank">'.$s_url.'</a></p></td></tr></tbody> </table></div>');
            if($mail->Send()) {
                echo alert('Sıfırlama Bağlantısı, eposta adresinize gönderilmiştir.<br>Dikkat: <b>Gelen e-Posta spam klasöründe olabilir!</b>',"success");
            } else {
                echo alert('Mail gönderilirken bir hata oluştu: ' . $mail->ErrorInfo, "danger");
            }
          }else{
            echo alert("Kullanıcı adı ile eposta adresi uyuşmuyor!","danger");
          }
        }else{
          echo alert("Girdiğiniz kullanıcı adı uygun olmayan karakter içeriyor.","danger");
        }
      }else{
        echo alert("Lütfen boş alan bırakmayınız!","danger");
      }
    }

  ?>
  <div class="panel panel-default">
    <div class="panel-heading">Şifremi Unuttum</div>
    <div class="panel-body">
      <form action="" method="post">
        <div class="form-group">
          <label for="uye_kadi">Kullanıcı Adı:</label>
          <input type="text" class="form-control" id="uye_kadi" name="uye_kadi">
        </div>
        <div class="form-group">
          <label for="uye_email">Email Adresiniz:</label>
          <input type="text" class="form-control" id="uye_email" name="uye_email">
            <div class="help-block">Yazdığınız <b>Kullanıcı Adına</b> bağlı olan eposta adresini yazınız.</div>
        </div>
        <button type="submit" class="btn btn-block btn-success" name="sifremi_unuttum" value="sifremi_unuttum">Gönder</button>
      </form>
    </div>
  </div>
<?php endif; ?>
<?php
    require_once '../../config.php';
    if(!session("login")){
      
      if ($_POST) {
        $uye_kadi = post("uye_kadi");
        $uye_sifre = post("uye_sifre");

        if (!$uye_kadi OR !$uye_sifre) {
          $cevap = array('mesaj'=>'Lütfen boş alan bırakmayınız.', 'class'=>'danger');
        }else{
          $varmi = query("SELECT * FROM uyeler WHERE uye_kadi = '$uye_kadi'");
          if (rows($varmi) < 1) {
              $cevap = array('mesaj'=>'Böyle bir oyuncu bulunamadı.', 'class'=>'danger');
          }else{
            $row = row($varmi);
            
            if (empty($row["uye_token"])) {
              $token = rasgeleSifre(32);
              $token_ekle = query("UPDATE uyeler SET uye_token = '$token' WHERE uye_id='".$row["uye_id"]."'");
              if ($token_ekle) {
                $row["uye_token"] = $token;
              }else{
                $cevap = array('mesaj'=>'Bir hata meydana geldi. Lütfen daha sonra tekrar deneyiniz.', 'class'=>'danger');
              }
            }
            
            if (mset("authme_sifre") == "sha256"){
              $k_sifre = sha256Kontrol($uye_sifre,$row["uye_sifre"]);
            }else{
              $k_sifre = ($row["uye_sifre"] != md5($uye_sifre))?false:true;
            }
            if ($k_sifre == false) {
              $cevap = array('mesaj'=>'Yazdığınız parola uyuşmuyor.', 'class'=>'danger');
            }else{
              if ($row["uye_oauth_uid"] != "0") {
                session_olustur(array('two_factor' => true,'uye_o_id' => $row['uye_id']));
                $cevap = array('mesaj'=>'Giriş işlemi başarılı. Güvenlik kodunu yazınız.', 'class'=>'success', 'yenile'=>'true');
              }else{
                session_olustur(array('login' => true,'uye_id' => $row['uye_id']));
                cookie_olustur("CWACCOUNTID", $row["uye_token"], "600000");
                $cevap = array('mesaj'=>'Giriş işlemi başarılı. Sayfa Yenileniyor...', 'class'=>'success', 'basari'=>'true');
                go(ANLIK_URL, 2);
              }
            }
          }
        }
      }else{
        $cevap = array('mesaj'=>'Post gönderilemedi.', 'class'=>'danger');
      }
    }else{
      $cevap = array('mesaj'=>'Zaten giriş yapılı.', 'class'=>'danger');
    }
    
    if (isset($cevap)){
        echo json_encode($cevap);
    }
?>
<?php
    error_reporting(0);
    require_once '../../config.php';
    if(!session('login')) {
        $cevap = array('mesaj'=>'Session Hatası', 'button'=>'danger', 'icon'=>'times');
    }else{
      $uye_id = session("uye_id");
      $user = row(query("SELECT * FROM uyeler WHERE uye_id='$uye_id'"));
      if ($user["uye_rutbe"] == 1) {
        // Bağlantı testi yapılacak kısım.
        $send_method = mset("send_method");
        if ($send_method == "rcon") {
          // Rcon Bağlantısı
          require_once "../../includes/class/rcon.php";
          $ws = new Rcon(post("sunucu_ip"),post("sunucu_port"),post("sunucu_sifre"),3);
        }elseif ($send_method == "websender") {
          // Websender Bağlantısı
          require_once "../../includes/class/WebsenderAPI.php";
          $ws = new WebsenderAPI(post("sunucu_ip"),post("sunucu_sifre"),post("sunucu_port"));
        }else{
          // Websend Bağlantısı
          require_once "../../includes/class/Websend.php";
          $ws = new Websend(post("sunucu_ip"),post("sunucu_port"));
          $ws->password = post("sunucu_sifre");
        }
        
        if($ws->connect()){
            $cevap = array('mesaj'=>'Bağlantı Başarılı', 'button'=>'success', 'icon'=>'check');
        }else{
            $cevap = array('mesaj'=>'Bağlantı Hatası', 'button'=>'danger', 'icon'=>'times');
        }
      }else{
        $cevap = array('mesaj'=>'Yetki Hatası', 'button'=>'danger', 'icon'=>'times');
      }
    }

    if (isset($cevap)){
        echo json_encode($cevap);
    }
?>
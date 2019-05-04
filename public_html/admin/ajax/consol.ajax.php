<?php 
  error_reporting(0);
	require_once "../../config.php";
	$post_server_id 	= post("server_id");
	$post_consol_cmd 	= post("consol_cmd");

  if(!session('login')) {
    echo "Session Hatası"; exit;
  }else{
    $uye_id = session("uye_id");
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='$uye_id'"));
    if ($user["uye_rutbe"] == 1) {
      if (!empty($post_server_id) AND !empty($post_consol_cmd)) {
        $kontrol = query("SELECT * FROM sunucular where sunucu_id='$post_server_id'");
        if (rows($kontrol) < 1) {
          echo "Sunucu Bulunamad"; exit;  
        }else{
          $row = row($kontrol);
          // Bağlantı testi yapılacak kısım.
          $send_method = mset("send_method");
          if ($send_method == "rcon") {
            // Rcon Bağlantısı
            require_once "../../includes/class/rcon.php";
            $ws = new Rcon($row["sunucu_ip"],$row["sunucu_port"],$row["sunucu_sifre"],3);
          }elseif ($send_method == "websender") {
            // Websender Bağlantısı
            require_once "../../includes/class/WebsenderAPI.php";
            $ws = new WebsenderAPI($row["sunucu_ip"],$row["sunucu_sifre"],$row["sunucu_port"]);
          }else{
            // Websend Bağlantısı
            require_once "../../includes/class/Websend.php";
            $ws = new Websend($row["sunucu_ip"],$row["sunucu_port"]);
            $ws->password = $row["sunucu_sifre"];
          }
      		if($ws->connect()){
      			$ekle_k = query("INSERT INTO konsol SET
      				uye_id = '".session("uye_id")."',
      				sunucu_id = '".$post_server_id."',
      				komut_n = '".$post_consol_cmd."'
      			");

      			if ($ekle_k) {
              if ($send_method == "rcon" OR $send_method == "websender") {
                $ws->sendCommand($post_consol_cmd);
              }else{
                $ws->doCommandAsConsole($post_consol_cmd);
              }
      				echo "ok"; exit;
      			}else{
              echo "Komut Gönderilemedi"; exit;
      			}
      		}else{
            echo "Bağlantı Hatası"; exit;
      		}
        }
      }else{
        echo "Gelen Veriler Hatalı"; exit;
      }
    }else{
      echo "Yetki Hatası"; exit;
    }
  }
?>
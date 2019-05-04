<?php
!session('login') ? go(URL."/kayitol") : null;
if(!isset($_GET["uid"])){
  go(URL."/depo");
  exit;
}else{
  $uid = get("uid");
  if(!$uid){
    go(URL."/depo");
    exit;
  }else{
    
    $uye_id = session("uye_id");
    $varmi = query("SELECT * FROM depo INNER JOIN uyeler ON uyeler.uye_id=depo.depo_uye INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE depo_uye='$uye_id' AND depo_durum='0' AND depo_id='$uid'");
    if(rows($varmi) < 1){
      go(URL."/depo");
      exit;
    }else{
      $row = row($varmi);
      
      // Bağlantı testi yapılacak kısım.
      $send_method = mset("send_method");
      if ($send_method == "rcon") {
        // Rcon Bağlantısı
        require_once "includes/class/rcon.php";
        $ws = new Rcon($row["sunucu_ip"],$row["sunucu_port"],$row["sunucu_sifre"],3);
      }elseif ($send_method == "websender") {
        // Websender Bağlantısı
        require_once "includes/class/WebsenderAPI.php";
        $ws = new WebsenderAPI($row["sunucu_ip"],$row["sunucu_sifre"],$row["sunucu_port"]);
      }else{
        // Websend Bağlantısı
        require_once "includes/class/Websend.php";
        $ws = new Websend($row["sunucu_ip"],$row["sunucu_port"]);
        $ws->password = $row["sunucu_sifre"];
      }
      
      // Bağlantı başarılı ise çalışacak kısım.
      if ($ws->connect()) {
        $depo_up = query("UPDATE depo SET depo_durum = '1' WHERE depo_id = '$uid'");
        $market_in = query("INSERT INTO market_aktif SET
          aktif_market_id = '$uid',
          aktif_sure = '".$row["depo_urun_gun"]."',
          aktif_tarih2 = '".time()."',
          aktif_durum = '1'
        ");
        if($depo_up AND $market_in){
          $urun_komut = explode("-/",$row["urun_komut"]);
          foreach($urun_komut as $komut){
            $son = str_ireplace("%player%",$row["uye_kadi"],$komut);
            if ($send_method == "rcon" OR $send_method == "websender") {
              $ws->sendCommand($son);
            }else{
              $ws->doCommandAsConsole($son);
            }
          }
          echo alert('Depo ürünü başarıyla aktifleştirildi. Yönlendiriliyorsunuz.','success');
          go(URL."/profil#aktif_urunler",1);
        }else{
          echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
        }
      }else{
        echo alert("Sunucuya bağlantı sağlanamadı.");
      }
    }
  }
}
?>
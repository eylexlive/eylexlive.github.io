<?php
    error_reporting(0);
    require_once '../../config.php';
    if(!session('login')) {
      $cevap = array('mesaj'=>'Session Hatası', 'hata'=>'true');
    }else{
      $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'"));
      if ($user["uye_rutbe"] > 0) {
        // Bu günkü kazanç.
        $bugun = query("SELECT * FROM krediler WHERE kredi_tarih LIKE '".date("Y-m-d")."%'");
        $bugun_t = 0; while($row = row($bugun)){
          $bugun_t = $bugun_t + $row["kredi_miktar"];
        }
        // Bu ayki kazanç.
        $buay = query("SELECT * FROM krediler WHERE kredi_tarih LIKE '".date("Y-m")."%'");
        $buay_t = 0; while($row = row($buay)){
          $buay_t = $buay_t + $row["kredi_miktar"];
        }
        // Bu yıl kazanç.
        $buyil = query("SELECT * FROM krediler WHERE kredi_tarih LIKE '".date("Y")."%'");
        $buyil_t = 0; while($row = row($buyil)){
          $buyil_t = $buyil_t + $row["kredi_miktar"];
        }
        // Tüm Üyerler
        $tum_uyeler = query("SELECT uye_id FROM uyeler");
        // Yanıt Bekleyen talep
        $talepler = query("SELECT ticket_id FROM ticketler WHERE (ticket_turu = 1 or ticket_turu = 3) AND ticket_id = ticket_ana_id");
        // Aktif Ürünler
        $aktif = query("SELECT aktif_id FROM market_aktif WHERE aktif_durum=1");
        // Bekleyen Yorumlar
        $yorumlar = query("SELECT yorum_id,yorum_onay FROM yorumlar WHERE yorum_onay=0");
        $yorumlar = rows($yorumlar)?rows($yorumlar):"0";
        
        if ($user["uye_rutbe"] != 1) {
          $bugun_t = 0; $buay_t = 0; $buyil_t = 0; $yorumlar = 0;
        }
        
        $cevap = array(
          "kazanc_buGun" => $bugun_t,
          "kazanc_buGun2" => $bugun_t,
          "kazanc_buAy" => $buay_t,
          "kazanc_buYil" => $buyil_t,
          "tum_uyeler" => rows($tum_uyeler)?rows($tum_uyeler):"0",
          "talepler" => rows($talepler)?rows($talepler):"0",
          "aktif" => rows($aktif)?rows($aktif):"0",
          "yorumlar" => $yorumlar,
          "ziyaret" => "0"
        );
      }else{
        $cevap = array('mesaj'=>'Yetki Hatası', 'hata'=>'true');
      }
    }

    if (isset($cevap)){
      echo json_encode($cevap);
    }
?>
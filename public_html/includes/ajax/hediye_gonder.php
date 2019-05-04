<?php
    require_once '../../config.php';
    if(!session('login')){
        $cevap = array('mesaj'=>'Session Hatası', 'class'=>'danger');
    }else{
      $urun_id = post("urun");
      $uye_kadi = post("uye_kadi");
      if (!empty($uye_kadi) AND !empty($urun_id)) {
        $uyeQuery = query("SELECT * FROM uyeler WHERE uye_kadi='$uye_kadi'");
        if (rows($uyeQuery) > 0) {
          $uRow = row($uyeQuery);
          
          if ($uRow["uye_id"] != session("uye_id")) {
            $depoQuery = query("SELECT * FROM depo WHERE depo_id='$urun_id' AND depo_durum='0' AND depo_uye='".session("uye_id")."'");

            if (rows($depoQuery) > 0) {
              $ekle = query("INSERT INTO hediye_gecmis SET hediye_depo_id='$urun_id',hediye_gonderen_id='".session("uye_id")."',hediye_alan_id='".$uRow["uye_id"]."',hediye_tarih='".time()."'");
              if($ekle){
                $update = query("UPDATE depo SET depo_uye='".$uRow["uye_id"]."',depo_tur='1',depo_hg_id='".session("uye_id")."' WHERE depo_id='$urun_id'");
                if ($update) {
                  $cevap = array('mesaj'=>'Hediye başarıyla gönderildi.', 'class'=>'success', 'basari'=>'true');
                }else{
                  $cevap = array('mesaj'=>'<strong>Mysql Hatası: </strong>'.mysqli_error($baglan), 'class'=>'danger', 'basari'=>'true');
                }
              }else{
                $cevap = array('mesaj'=>'<strong>Mysql Hatası: </strong>'.mysqli_error($baglan), 'class'=>'danger', 'basari'=>'true');
              }
            }else{
              $cevap = array('mesaj'=>'Deponuzda bu ürün bulunmamaktadır.', 'class'=>'danger');
            }  
          }else{
            $cevap = array('mesaj'=>'Kendi kendinize hediye gönderemezsiniz.', 'class'=>'danger');
          }
          
        }else{
          $cevap = array('mesaj'=>'Böyle bir üye bulunamadı!', 'class'=>'danger');
        }
      }else{
        $cevap = array('mesaj'=>'Boş alan bırakmayınız!', 'class'=>'danger');
      }
    }

    if (isset($cevap)){
        echo json_encode($cevap);
    }
?>
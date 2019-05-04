<?php
  require_once '../../config.php';
  if(!session('login')){
      $cevap = array('mesaj'=>'Session Hatası', 'class'=>'danger');
  }else{
    $yorum = post("yorum");
    $yazi = post("yazi");
    $uye_id = session("uye_id");
    $time = time();
    $miktar = 1;
    if (!empty($yorum) AND !empty($yazi)) {
      $varmi = query("SELECT * FROM yazi WHERE yazi_id='$yazi'");
      if (rows($varmi) > 0) {
        $uyeQuery = query("SELECT * FROM yorumlar WHERE yorum_gonderen='$uye_id' AND yorum_yazi='$yazi'");
        if (rows($uyeQuery) < $miktar) {
          $ekle = query("INSERT INTO yorumlar SET yorum_gonderen='$uye_id', yorum_yazi='$yazi', yorum_icerik='$yorum', yorum_tarih='$time'");
          if ($ekle) {
            $cevap = array('mesaj'=>'Yorum ekleme işlemi başarılı. Onaylandıktan sonra yorumunuz gözükecektir.', 'class'=>'success', 'basari'=>'true', 'date'=>date("d-m-Y H:i",$time));
          } else {
            $cevap = array('mesaj'=>'Bir hata oluştu.', 'class'=>'danger');
          }
        }else{
          $cevap = array('mesaj'=>'Aynı yazıya '.$miktar.' kez yorum bırakabilirsiniz.', 'class'=>'danger');
        }
      } else {
        $cevap = array('mesaj'=>'Böyle bir yazı bulunamadı! ', 'class'=>'danger');
      }
    }else{
      $cevap = array('mesaj'=>'Boş alan bırakmayınız!', 'class'=>'danger');
    }
  }

  if (isset($cevap)){
    echo json_encode($cevap);
  }
?>
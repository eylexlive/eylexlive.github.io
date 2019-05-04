<?php !session('login') ? go_js(URL."/kayitol") : null; ?>
<h3 class="ui-bar ui-bar-a ui-corner-all text-center">Hediye Ürün Gönder</h3>
<?php
  if ($_POST) {
    $urun_id = post("urun");
    $uye_kadi = post("uye_kadi");
    if ($uye_kadi AND $urun_id) {
      $uyeQuery = query("SELECT * FROM uyeler WHERE uye_kadi='$uye_kadi'");
      if (rows($uyeQuery) > 0) {
        $uRow = row($uyeQuery);
        
        if ($uRow["uye_id"] != session("uye_id")) {
          $depoQuery = query("SELECT * FROM depo WHERE depo_id='$urun_id' AND depo_durum='0' AND depo_uye='".session("uye_id")."'");

          if (rows($depoQuery) > 0) {
            $update = query("UPDATE depo SET depo_uye='".$uRow["uye_id"]."',depo_tur='1',depo_hg_id='".session("uye_id")."' WHERE depo_id='$urun_id'");
            if($update){
              $cevap = array('mesaj'=>'Hediye başarıyla gönderildi.', 'class'=>'success');
            }else{
              $cevap = array('mesaj'=>'<strong>Mysql Hatası: </strong>'.mysqli_error($baglan), 'class'=>'danger');
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
      echo alert($cevap["mesaj"],$cevap["class"]);
  }
?>
<div class="ui-body ui-body-a ui-corner-all">
  <form action="" method="post">
    <div class="form-group">
      <label>Gönderilecek Ürün:</label>
      <input type="text" class="form-control" disabled>
    </div>
    <div class="form-group">
      <label>Alıcı Kullanıcı Adı:</label>
      <input type="text" class="form-control" name="uye_kadi" placeholder="Göndereceğiniz kişinin kullanıcı adı.">
      <input type="hidden" name="urun" value="<?=get("link")?>">
    </div>
    <button type="submit" name="giris" value="giris" class="btn btn-block btn-success">Gönder</button>
    <a href="<?=URL?>/depo" class="ui-btn ui-btn-b ui-corner-all">Geri Dön</a>    
  </form>
</div>
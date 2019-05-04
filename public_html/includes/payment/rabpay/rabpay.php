<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='rabpay' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
    
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'"));
  }else{
    go(URL); die();
  }

  $fiyat = isset($_GET["fiyat"])?get("fiyat"):"10";
  $fiyat = (round($fiyat) == 0)?"1":round($fiyat);
?>
<form action="https://odeme.rabisu.com/odeme.php" method="post" id="payment">
  <input type="hidden" name="oyuncu_adi" value="<?=$user["uye_kadi"]?>">
  <input type="hidden" name="basarili_url" value="<?=URL?>/kredi/basarili">
  <input type="hidden" name="basarisiz_url" value="<?=URL?>/kredi/basarisiz">
  <input type="hidden" name="post_url" value="<?=URL?>/includes/payment/rabpay/rabpay_post.php">
  <input type="hidden" name="urun_adi" value="Kredi Yükleme">
  <input type="hidden" name="bayi_id" value="<?=$api["rabpay_id"]?>">
  <input type="hidden" name="yontem" value="mobil">
  <input type="hidden" name="fiyat" value="<?=$fiyat?>">
</form>
<script type="text/javascript">
  document.getElementById("payment").submit();
</script>
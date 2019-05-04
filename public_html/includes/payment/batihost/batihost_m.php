<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='batihost_m' AND odeme_durum='1'");
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
<form action="http://batigame.com/vipgateway/viprec.php" method="post" id="payment">
  <input type="hidden" name="oyuncu" value="<?=$user["uye_kadi"]?>">
  <input type="hidden" name="odemeolduurl" value="<?=URL?>/kredi/basarili">
  <input type="hidden" name="odemeolmadiurl" value="<?=URL?>/kredi/basarisiz">
  <input type="hidden" name="vipname" value="Kredi Yükleme">
  <input type="hidden" name="raporemail" value="<?=$api["email"]?>">
  <input type="hidden" name="posturl" value="<?=URL?>/includes/payment/batihost/batihost_m_post.php">
  <input type="hidden" name="batihostid" value="<?=$api["batihost_id"]?>">
  <input type="hidden" name="amount" value="<?=$fiyat?>">
</form>
<script type="text/javascript">
  document.getElementById("payment").submit();
</script>
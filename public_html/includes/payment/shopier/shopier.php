<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='shopier' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);

    go($api["urunler"]);
  }else{
    go(URL); die();
  }
?>
<script type="text/javascript">
  window.location = "<?=$api["urunler"]?>";
</script>
<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='maxepin' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
    
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'"));
  }else{
    go(URL); die();
  }
  
	$merchant_id = $api["apiKey"]; // mağaza numarası
	$merchant_key = $api["apiSecret"]; // mağaza şifresi
	$return_id = $user["uye_id"]; // size geri dönecek yorum alanı 
	
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://api2.maxepin.com/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1) ;
	curl_setopt($ch, CURLOPT_POSTFIELDS,"merchant_id=$merchant_id&merchant_key=$merchant_key&return_id=$return_id");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$result = @curl_exec($ch);
	 
	if(curl_errno($ch)) {
		die("Hata:".curl_error($ch));
	}
	curl_close($ch);
  $jsonDecode = json_decode($result,true);
  if(!empty($jsonDecode["link"])){
    go($jsonDecode["link"]);
  } else {
    echo '<div style="margin-top:8px;font-family:monospace;line-height:1.3;text-align:left;font-size:100%;border:1px solid #555;border-radius:5px;background:moccasin;padding:8px;"><b> Maxepin '.$result.' </b></div>';
  }
?>
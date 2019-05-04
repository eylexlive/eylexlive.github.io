<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='kodzer' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
    
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'"));
  }else{
    go(URL); die();
  }

  if($_GET){

  	$fiyat = get("fiyat");
    $fiyat = (round($fiyat) == 0)?"1":round($fiyat);
  	$fiyat_saf = $fiyat*100;
    
    // api anahtarı
  	$apiKey	= $api["apiKey"];
    // api gizli anahtarı
  	$apiSecret = $api["apiSecret"];
    // kullanıcı id
  	$userID	= $user["uye_id"];
    // Ödeme yapılacak kanal. 0 = Mobil ödeme | 1 = Kredi kartı
  	$odemeTuru = "1";
    // sipariş kodu
  	$returnData	= $user["uye_kadi"];
  
    $fields = array(
      "uyeid" => $apiKey,
      "miktar" => $fiyat,
      "returnid" => $returnData,
      "odemeturu" => $odemeTuru,
      "secret" => $apiSecret
    );
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://pay.kodzer.games/api/linkGen.php",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => http_build_query($fields),
    ));
  	
  	$response = curl_exec($curl);
  	$err = curl_error($curl);
  	
  	if ($err) {
      echo '<div style="margin-top:8px;font-family:monospace;line-height:1.3;text-align:left;font-size:100%;border:1px solid #555;border-radius:5px;background:moccasin;padding:8px;"><b> PHP [Hata: '.$err.'] </b></div>';
  	} else {
      if (filter_var($response, FILTER_VALIDATE_URL)) {
        go($response);
      } else {
        echo '<div style="margin-top:8px;font-family:monospace;line-height:1.3;text-align:left;font-size:100%;border:1px solid #555;border-radius:5px;background:moccasin;padding:8px;"><b> Kodzer [Hata: '.$response.'] </b></div>';
      }
  	}

  	curl_close($curl);
  }else{
  	go(URL);
  }
<?php 
  require_once '../../../config.php';
  !session('login') ? go(URL."/kayitol") : null;
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='paywant' AND odeme_durum='1'");
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
    // kullanıcı e-mail adresi
  	$userEmail = $user["uye_email"]?$user["uye_email"]:"null@null.com";
    // sipariş kodu
  	$returnData	= $user["uye_kadi"];
  	
  	$hashOlustur = base64_encode(hash_hmac('sha256',"$returnData|$userEmail|$userID".$apiKey,$apiSecret,true));

  	$productData = array(
  		"name" =>  $fiyat." Kredi Yükleme",
  		"amount" => $fiyat_saf,
  		"extraData" => 1,
  		"paymentChannel" => $api["method"],
      // Komisyon tipi, 1: Yansıt, 2: Üstlen
  		"commissionType" => 1
  	);

  	$postData = array(
  		'apiKey' => $apiKey,
  		'hash' => $hashOlustur,
  		'returnData'=> $returnData,
  		'userEmail' => $userEmail,
  		'userIPAddress' => GetIP(),
  		'userID' => $userID,
  		'proApi' => true,
  		'productData' => $productData
  	);
  	
  	$curl = curl_init();
  	curl_setopt_array($curl, array(
  	  CURLOPT_URL => "http://api.paywant.com/gateway.php",
  	  CURLOPT_RETURNTRANSFER => true,
  	  CURLOPT_ENCODING => "",
  	  CURLOPT_MAXREDIRS => 10,
  	  CURLOPT_TIMEOUT => 30,
  	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	  CURLOPT_CUSTOMREQUEST => "POST",
  	  CURLOPT_POSTFIELDS =>  http_build_query($postData),
  	));
  	
  	$response = curl_exec($curl);
  	$err = curl_error($curl);
  	
  	if ($err) {
  	  echo "cURL Error #:" . $err;
  	} else {
  	  $jsonDecode = json_decode($response,false);
  	  if($jsonDecode->Status == 100){
  		  go($jsonDecode->Message);
  	  } else {
        echo '<div style="margin-top:8px;font-family:monospace;line-height:1.3;text-align:left;font-size:100%;border:1px solid #555;border-radius:5px;background:moccasin;padding:8px;"><b> PayWant [Hata: '.$jsonDecode->Message.' (#'.$jsonDecode->Status.')] </b></div>';
      }
  	}

  	curl_close($curl);
  }else{
  	go(URL);
  }
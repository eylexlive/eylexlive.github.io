<?php
  require_once '../../../config.php';
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='paywant' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
    
    $user = row(query("SELECT * FROM uyeler WHERE uye_id='".session("uye_id")."'"));
  }else{
    go(URL); die();
  }

  if($_POST){
			
		$SiparisID 		= post("SiparisID");
		$ExtraData		= post("ExtraData");
		$UserID			  = post("UserID");
		$ReturnData		= post("ReturnData");
		$Status			  = post("Status");
		$OdemeKanali	= post("OdemeKanali");
		$OdemeTutari	= post("OdemeTutari");
		$NetKazanc		= post("NetKazanc");
		$Hash 			  = post("Hash");
	
		if (empty($SiparisID) || empty($ExtraData) || empty($UserID) || empty($ReturnData) || empty($Status) || empty($OdemeKanali) || empty($OdemeTutari) || empty($NetKazanc) || empty($Hash)) {
		  exit("Eksik veri var! Lütfen sitemizi kurcalamayı bırakın...");
		}
	
		$hashKontrol = base64_encode(hash_hmac('sha256',"$SiparisID|$ExtraData|$UserID|$ReturnData|$Status|$OdemeKanali|$OdemeTutari|$NetKazanc".$api["apiKey"],$api["apiSecret"],true));
  	if($Hash != $hashKontrol){
	   exit("Doğrulama hatalı. Lütfen sitemizi kurcalamayı bırakın...");
	 	}
		 
	
	 	$query = query("SELECT * FROM uyeler WHERE uye_id = '$UserID'");
		if(rows($query) > 0){
      $row = row($query);
			if($row["uye_transid"] == $SiparisID){
				die("OK");
			}else{
				$insert = query("INSERT INTO krediler SET
				kredi_ekleyen = '$UserID',
				kredi_miktar = '$OdemeTutari',
				odeme_slug = 'paywant',
				kredi_hesap = '$SiparisID--$NetKazanc'");
				if($insert){
					$yeniKredi = $row["uye_kredi"] + $OdemeTutari;
					$update = query("UPDATE uyeler SET uye_kredi = '$yeniKredi', uye_transid = '$SiparisID' WHERE uye_id = '$UserID'");
					if($update){
						echo "OK";
						exit;
					}else{
						echo "Mysql Hatası: ".mysqli_error($baglan);
					}
				}else{
					echo "Mysql Hatası: ".mysqli_error($baglan);
				}
			}
		}else{
			echo "Kullanıcı Bulunamadi...";
		}
	}else{
    echo "POST verisi gönderilemedi...";
  }
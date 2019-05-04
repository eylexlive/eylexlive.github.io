<?php
  require_once '../../../config.php';
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='kodzer' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
  }else{
    go(URL); die();
  }

  if(isset($_SERVER["HTTP_CLIENT_IP"])) {
  	$ip = $_SERVER["HTTP_CLIENT_IP"];
  } elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
  	$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  } else {
  	$ip = $_SERVER["REMOTE_ADDR"];
  }

  if ($ip == "185.120.5.8") {
    if($_POST){
      
      $guvenlik = post("secret");
      $oyuncu = post("returnid");
  		$fiyat	= post("odenenmiktar");
  		$netfiyat = post("netkazanc");
  		$transid = post("uniqid");
      $otur = post("otur"); // Ödeme türü
      
  	  if ($guvenlik == $api["apiSecret"]){
        
    	 	$query = query("SELECT * FROM uyeler WHERE uye_kadi = '$oyuncu'");
    		if(rows($query) > 0){
          $row = row($query);
    			if($row["uye_transid"] == $transid){
    				die("OK");
    			}else{
    				$insert = query("INSERT INTO krediler SET
    				kredi_ekleyen = '{$row['uye_id']}',
    				kredi_miktar = '$fiyat',
    				odeme_slug = 'kodzer',
    				kredi_hesap = '$transid'");
    				if($insert){
    					$yeniKredi = $row["uye_kredi"] + $fiyat;
    					$update = query("UPDATE uyeler SET uye_kredi = '$yeniKredi', uye_transid = '$transid' WHERE uye_id = '".$row["uye_id"]."'");
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
        echo "Doğrulama hatalı. Lütfen sitemizi kurcalamayı bırakın...";
      }
  	}else{
    	echo "POST verisi gönderilemedi...";
    }
  } else {
    echo "IP Adresi Hatalı!";
  }
<?php
  require_once '../../../config.php';
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='batihost_kk' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
  }else{
    go(URL); die();
  }

  if($_POST){
    
    $oyuncu 	  = post("user");
		$fiyat		  = post("credit");
		$transid	  = post("transid");
		$guvenlik 	= post("guvenlik");
		 
	  if ($guvenlik == $api["guvenlik_kodu"]){
      
  	 	$query = query("SELECT * FROM uyeler WHERE uye_kadi = '$oyuncu'");
  		if(rows($query) > 0){
        $row = row($query);
  			if($row["uye_transid"] == $transid){
  				die("OK");
  			}else{
  				$insert = query("INSERT INTO krediler SET
  				kredi_ekleyen = '".$row["uye_id"]."',
  				kredi_miktar = '$fiyat',
  				odeme_slug = 'batihost_kk',
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
<?php
  require_once '../../../config.php';
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='maxepin' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
  }else{
    go(URL); die();
  }

  if($_POST){
    
    $merchant_key  = post("merchant_key");
    $return_id     = post("return_id");
    $pay_id        = post("pay_id");
    $pay_amount    = post("pay_amount");
    $product_id    = post("product_id");
		 
	  if ($merchant_key == $api["apiSecret"]){
      
  	 	$query = query("SELECT * FROM uyeler WHERE uye_id = '$return_id'");
  		if(rows($query) > 0){
        $row = row($query);
  			if($row["uye_transid"] == $product_id){
  				die("OK");
  			}else{
  				$insert = query("INSERT INTO krediler SET
  				kredi_ekleyen = '$return_id',
  				kredi_miktar = '$pay_amount',
  				odeme_slug = 'maxepin',
  				kredi_hesap = '$product_id--$pay_amount'");
  				if($insert){
  					$yeniKredi = $row["uye_kredi"] + $pay_amount;
  					$update = query("UPDATE uyeler SET uye_kredi = '$yeniKredi', uye_transid = '$product_id' WHERE uye_id = '$return_id'");
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
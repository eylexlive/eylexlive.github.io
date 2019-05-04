<?php
  require_once '../../../config.php';
  
  $payment = query("SELECT * FROM odeme WHERE odeme_slug='shopier' AND odeme_durum='1'");
  if (rows($payment) > 0) {
    $oRow = row($payment);
    $api = json_decode($oRow["odeme_resp"],true);
  }else{
    go(URL); die();
  }

  if($_POST){
    
    $res 	  = post("res");
    $hash		= post("hash");
    
    if (!isset($res) AND !isset($hash)) {

      $guvenlik = hash_hmac('sha256',$res.$api["username"],$api["key"],false);
      if (strcmp($guvenlik,$hash) != 0){
        echo "Doğrulama hatalı. Lütfen sitemizi kurcalamayı bırakın...";
      }else{
        $array = json_decode(base64_decode($res),true);

        $oyuncu  = $array["customernote"];
        $transid = $array["orderid"];
        $fiyat   = $array["price"]-(($array["price"]/100)*$api["komisyon"]);

        $query = query("SELECT * FROM uyeler WHERE uye_kadi = '$oyuncu'");
        if(rows($query) > 0){
          $row = row($query);
          if($row["uye_transid"] == $transid){
            die("OK");
          }else{
            $insert = query("INSERT INTO krediler SET
            kredi_ekleyen = '".$row["uye_id"]."',
            kredi_miktar = '$fiyat',
            odeme_slug = 'shopier',
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
      }

    }else{
      echo "Eksik veri. Lütfen sitemizi kurcalamayı bırakın...";
    }
	}else{
  	echo "POST verisi gönderilemedi...";
  }
echo "success";
?>
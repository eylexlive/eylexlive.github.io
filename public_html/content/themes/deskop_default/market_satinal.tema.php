<?php
	!session('login') ? go(URL."/kayitol") : null;
	if(!isset($_GET["uid"])){
		go(URL."/market");
		exit;
	}else{
		$uid = get("uid");
		if(!$uid){
			go(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM urunler INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE urun_id = '$uid'");
			if(rows($varmi) < 1){
				go(URL."/market");
				exit;
			}else{
				$row = row($varmi);
				$uyeQuery = query("SELECT * FROM uyeler WHERE uye_id = '".session("uye_id")."'");
				$uyeRow = row($uyeQuery);
				if($uyeRow["uye_kredi"] >= $row["urun_fiyat"]){
          
          $varmi = query("SELECT * FROM depo WHERE depo_urun_id = '".$row["urun_id"]."' AND depo_uye = '".session("uye_id")."' and depo_durum = 0");
          if(rows($varmi) < 1){
            $query = query("INSERT INTO depo SET
            depo_uye = '".session("uye_id")."',
            depo_urun_id = '".$row["urun_id"]."',
            depo_urun_gun = '".$row["urun_gun"]."',
            depo_tarih1 = '".time()."',
            depo_durum = 0");
          }else{
            $srow = row($varmi);
            if ($row["urun_gun"] == "0" OR $srow["depo_urun_gun"] == "0") {
              $query = query("INSERT INTO depo SET
              depo_uye = '".session("uye_id")."',
              depo_urun_id = '".$row["urun_id"]."',
              depo_urun_gun = '".$row["urun_gun"]."',
              depo_tarih1 = '".time()."',
              depo_durum = 0");
            }else{
              $yeniGun = $srow["depo_urun_gun"] + $row["urun_gun"];
              $query = query("UPDATE depo SET depo_urun_gun = '$yeniGun' WHERE depo_id = '".$srow["depo_id"]."'");
            }
          }
          if($query){
            $yeniKredi = $uyeRow["uye_kredi"] - $row["urun_fiyat"];
            $update = query("UPDATE uyeler SET uye_kredi = '$yeniKredi' WHERE uye_id = '".session("uye_id")."'");
						$ekle = query("INSERT INTO market SET
							market_uye = '".session("uye_id")."',
              market_urun_id = '".$row["urun_id"]."',
							market_urun_gun = '".$row["urun_gun"]."',
              market_urun_fiyat = '".$row["urun_fiyat"]."',
              market_tarih1 = '".time()."'		
						");
            if($update AND $ekle){
              echo alert('Satın alma işlemi başarılı. Depo\'ya yönlendiriliyorsunuz...','success');
              go(URL."/depo",2);
            }else{
              echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
          
          
				}else{
					echo alert('<strong>Yeterli Krediniz bulunmamaktadır.</strong>','danger');
				}
			}
		}
	}

?>
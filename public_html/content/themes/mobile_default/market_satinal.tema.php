<?php
	!session('login') ? go_js(URL."/kayitol") : null;
	if(!isset($_GET["uid"])){
		go_js(URL."/market");
		exit;
	}else{
		$uid = get("uid");
		if(!$uid){
			go_js(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM urunler INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE urun_id = '$uid'");
			if(rows($varmi) < 1){
				go_js(URL."/market");
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
            if($update){
              echo alert('Satın alma işlemi başarılı. Depo\'ya yönlendiriliyorsunuz...','success');
							echo '<script>function bzaman(){window.location=URL+"/depo"} setInterval(bzaman, 2000);</script>';
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
<?php
!session('login') ? go(URL."/kayitol") : null;
	if(isset($_GET["tid"])){
		$tid = get("tid");
		if(!$tid){
			go(URL."/destek");
			exit;
		}else{
			$varmi = query("SELECT * FROM ticketler WHERE ticket_id = '$tid' and ticket_durum = 1 and ticket_atan_id = '".session("uye_id")."'");
			if(rows($varmi) < 1){
				go(URL."/destek");
				exit;
			}else{
				$ticRow = row($varmi);
			}
		}
	}
	
	if($ticRow["ticket_turu"] == 4){
		echo alert("Destek talebi zaten kapalı! Yönlendiriliyorsunuz...","danger");
		go(URL."/destek",1);
	}else{
		$update = query("UPDATE ticketler SET ticket_turu = 4 WHERE ticket_id = '".$ticRow["ticket_id"]."'");
		if($update){
			echo alert("Destek talebiniz kapatıldı. Okuma sayfasına yönlendiriliyorsunuz...","success");
			go(URL."/destek/duzenle/".$ticRow["ticket_id"],1);
		}else{
			echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
		}
	}
	
?>
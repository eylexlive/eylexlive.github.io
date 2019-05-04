<?php
	!session('login') ? go_js(URL."/kayitol") : null;
	if(!isset($_GET["suid"])){
		go_js(URL."/market");
		exit;
	}else{
		$suid = get("suid");
		if(!$suid){
			go_js(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM sunucular WHERE sunucu_link = '$suid'");
			if(rows($varmi) < 1){
				go_js(URL."/market");
				exit;
			}else{
				$srow = row($varmi);
			}
		}
	}
?>
<h3 class="ui-bar ui-bar-a ui-corner-all">Market - Kategoriler</h3>
<ul data-role="listview" data-inset="true"> 
<?php
	$varmi = query("SELECT * FROM kategoriler_urun WHERE kategori_sunucu = '".$srow["sunucu_id"]."' ORDER BY kategori_id ASC");
	if(rows($varmi) < 1){
		echo alert('Bu sunucuya ait hiç kategori eklenmemiş');
	}else{
		while($row = row($varmi)) {
	?>
			<li><a href="<?=URL?>/market/<?=$srow["sunucu_link"]?>/<?=$row["kategori_link"]?>"><img src="<?=INC_PATH?>/items/<?=str_ireplace(":","-",$row["kategori_icon_id"])?>.png" class="ui-li-icon" style="top: 0.8em;" alt="<?=$row["kategori_link"]?>"><?=$row["kategori_adi"]?></a></li>
	<?php } ?>
<?php } ?>
</ul>
<a href="<?=URL?>/market" class="ui-btn ui-btn-b ui-corner-all">Geri Dön</a>


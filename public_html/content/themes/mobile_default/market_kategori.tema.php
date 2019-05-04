<?php
  !session('login') ? go_js(URL."/kayitol") : null;
	if(!isset($_GET["suid"]) || !isset($_GET["kid"])){
		go_js(URL."/market");
		exit;
	}else{
		$suid = get("suid");
		$kid = get("kid");
		if(!$suid || !$kid){
			go_js(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM sunucular INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_sunucu = sunucu_id WHERE sunucu_link = '$suid' and kategori_link = '$kid'");
			if(rows($varmi) < 1){
				go_js(URL."/market");
				exit;
			}else{
				$srow = row($varmi);
			}
		}
	}
?>

<h3 class="ui-bar ui-bar-a ui-corner-all">Market - Ürünler</h3>
<?php $varmi = query("SELECT * FROM urunler WHERE urun_kategori = '".$srow["kategori_id"]."' ORDER BY urun_id ASC"); ?>
<?php if (rows($varmi) < 1): ?>
  <?php echo alert('Bu kategoriye ait hiç ürün eklenmemiş'); ?>
<?php else: ?>
  <div data-demo-html="true">			
		<div data-role="collapsible-set">
      <?php while ($row = row($varmi)): ?>
			<div data-role="collapsible">
				<h3><?=$row["urun_adi"]?></h3>
        <p><?php echo ss(nl2br($row["urun_aciklama"])); ?></p>
        <a onclick="return confirm('Gerçekten satın almak istiyor musunuz? Geri dönüşü yoktur.')" href="<?php echo URL."/market/satinal/".$row["urun_id"]; ?>" class="ui-btn ui-corner-all" style="text-align:left">Satın Al <span style="float:right"><?php echo $row["urun_fiyat"]; ?> Kredi (<?php echo $row["urun_gun"] == 0 ? 'Sınırsız': $row["urun_gun"]." Gün"; ?>)</span></a>
			</div>
			<?php endwhile; ?>
		</div>
	</div>
<?php endif; ?>

<a href="<?=URL?>/market/<?=$suid?>" class="ui-btn ui-btn-b ui-corner-all">Geri Dön</a>
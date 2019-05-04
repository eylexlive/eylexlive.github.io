<?php
	!session('login') ? go(URL."/kayitol") : null;
	if(!isset($_GET["suid"])){
		go(URL."/market");
		exit;
	}else{
		$suid = get("suid");
		if(!$suid){
			go(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM sunucular WHERE sunucu_link = '$suid'");
			if(rows($varmi) < 1){
				go(URL."/market");
				exit;
			}else{
				$srow = row($varmi);
			}
		}
	}
?>
<div class="panel panel-default">
  <div class="panel-heading">Market - Kategoriler</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
			<ol class="breadcrumb breadcrumb-arrow">
			  <li><a href="<?php echo URL; ?>/market">Market</a></li>
			  <li class="active"><span><?php echo ss($srow["sunucu_adi"]); ?></span></li>
			</ol>
			<?php
				$varmi = query("SELECT * FROM kategoriler_urun WHERE kategori_sunucu = '".$srow["sunucu_id"]."' ORDER BY kategori_id ASC");
				if(rows($varmi) < 1){
					echo alert('Bu sunucuya ait hiç kategori eklenmemiş');
				}else{
					while($row = row($varmi)) {
				?>
					<div class="text-center">
						<a href="<?=URL?>/market/<?=$srow["sunucu_link"]?>/<?=$row["kategori_link"]?>">
							<img src="<?php echo $row["kategori_resim"]; ?>" alt="<?=$row["kategori_adi"]?>">
						</a>
					</div>
				<?php } ?>
      <?php } ?>
			</div>
		</div>
	</div>
</div>
<?php !session('login') ? go(URL."/kayitol") : null; ?>
<div class="panel panel-default">
	<div class="panel-heading">Market - Sunucular</div>
	<div class="panel-body">
		<?php
			$varmi = query("SELECT * FROM sunucular ORDER BY sunucu_id ASC");
			if(rows($varmi) < 1){
				echo alert('Hiç sunucu eklenmemiş.');
			}elseif (rows($varmi) == 1) {
				go(URL."/market/".row($varmi)["sunucu_link"]);
			}else{
				while($row = row($varmi)) {
			?>
				<div class="text-center">
					<a href="<?=URL?>/market/<?=$row["sunucu_link"]?>">
						<img src="<?php echo $row["sunucu_resim"]; ?>" style="max-width: 100%;" alt="<?=$row["sunucu_adi"]?>">
					</a>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>
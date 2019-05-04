<?php
  !session('login') ? go(URL."/kayitol") : null;
	if(!isset($_GET["suid"]) || !isset($_GET["kid"])){
		go(URL."/market");
		exit;
	}else{
		$suid = get("suid");
		$kid = get("kid");
		if(!$suid || !$kid){
			go(URL."/market");
			exit;
		}else{
			$varmi = query("SELECT * FROM sunucular INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_sunucu = sunucu_id WHERE sunucu_link = '$suid' and kategori_link = '$kid'");
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
		<div class="panel-heading">Market - Ürünler</div>
		<div class="panel-body">
			<ol class="breadcrumb breadcrumb-arrow">
			  <li><a href="<?php echo URL; ?>/market">Market</a></li>
			  <li><a href="<?php echo URL."/market/".$srow["sunucu_link"]; ?>"><?php echo ss($srow["sunucu_adi"]); ?></a></li>
			  <li class="active"><span><?php echo ss($srow["kategori_adi"]); ?></span></li>
			</ol>
      <?php $varmi = query("SELECT * FROM urunler WHERE urun_kategori = '".$srow["kategori_id"]."' ORDER BY urun_id ASC"); ?>
      <?php if (rows($varmi) < 1): ?>
        <?php echo alert('Bu kategoriye ait hiç ürün eklenmemiş'); ?>
      <?php else: ?>
        <div class="row">
          <?php while ($row = row($varmi)): ?>
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading"><?php echo ss($row["urun_adi"]); ?></div>
                <div class="panel-body">
                  <?php if ($row["urun_resim"]): ?>
                    <a href="<?=$row["urun_resim"]?>" target="<?=$row["urun_id"]?>"><img src="<?=$row["urun_resim"]?>" alt="<?php echo ss($row["urun_adi"]); ?>" class="img-responsive img-rounded"></a><br>
                  <?php endif; ?>
                  <div style="word-wrap: break-word;">
                    <?php echo ss(nl2br($row["urun_aciklama"])); ?>												
                  </div><div class="clearfix"></div>
                  <div class="spacer"></div>
                  <div class="label label-danger pull-right"><?php echo $row["urun_gun"] == 0 ? 'Sınırsız': $row["urun_gun"]." Gün"; ?></div>
                  <div class="spacer" style="margin-top:30px"></div>
                  <hr>
                  <a onclick="return confirm('Gerçekten satın almak istiyor musunuz? Geri dönüşü yoktur.')" href="<?php echo URL."/market/satinal/".$row["urun_id"]; ?>" class="btn btn-default pull-right">Satın Al - <?php echo $row["urun_fiyat"]; ?> Kredi</a>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
		</div>
	</div>
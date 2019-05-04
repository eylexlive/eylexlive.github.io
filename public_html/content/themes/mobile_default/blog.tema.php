<?php
	@$link = get("link") ? get("link") : false;
	if(!$link){
		go_js(URL);
		exit;
	}
	$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_link = '$link' AND yazi_durum = '1'");
	if(rows($query)){
		$row = row($query);
?>
<h3 class="ui-bar ui-bar-a ui-corner-all"><?php echo ss($row["yazi_baslik"]); ?></span></h3>
<div class="ui-body ui-body-a ui-corner-all">
	<?php echo nl2br($row["yazi_icerik"]); ?>
</div>
<h4 class="ui-bar ui-bar-a ui-corner-all text-center" style="font-weight:400;">
	Yazan: <?=$row["uye_kadi"]?> | Kategori: <?=ss($row["kategori_baslik"])?> | Tarih: <?php echo tarih_t($row["yazi_tarih"],"d m Y h:i"); ?><br>
	Etiketler: <?=$row["yazi_etiket"]?>
</h4>
<?php }else{ go_js(URL); } ?>
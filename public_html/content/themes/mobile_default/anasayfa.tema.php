<?php
	@$sayfa = get("s") ? get("s") : 1;
	$ksayisi = rows(query("SELECT * FROM yazi"));
	$limit = mset("blog_limit");
	$ssayisi = ceil($ksayisi/$limit);
	$baslangic = $sayfa * $limit - $limit;
	if($ksayisi > $limit){
		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id ORDER BY yazi_id DESC LIMIT $baslangic,$limit");
		$oncekiSayfa = $sayfa > 1 ? $sayfa - 1 : 1;
		$onceki = URL.'/s/'.$oncekiSayfa;
		$sonrakiSayfa = $sayfa < $ssayisi ? $sayfa + 1 : $ssayisi;
		$sonraki = URL.'/s/'.$sonrakiSayfa;
	}else{
		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id ORDER BY yazi_id DESC");
	}
	if(rows($query)){
		while($row = row($query)){
			if ($row["yazi_durum"] == '1') {
?>
<div class="card ks-card-header-pic">
  <div style="background-image:url(http://lorempixel.com/1000/600/nature/3/)" valign="bottom" class="card-header color-white no-border">Ben Bir eğlence yazısıyım hoşçakal</div>
  <div class="card-content">
    <div class="card-content-inner">
      <p class="color-gray"><?php echo tarih_t($row["yazi_tarih"],"d m Y h:i"); ?></p>
      <p><?=kisalt($row["yazi_icerik"],100);?></p>
    </div>
  </div>
  <div class="card-footer">
    <a href="#" class="link">Paylaş</a>
    <a href="<?=URL?>/blog/<?=$row["yazi_link"]?>.html" class="link">Devamını Oku</a>
  </div>
</div>
<?php }
 } }else{
	echo alert('Henüz hiç yazı eklenmemiş.');
} ?>
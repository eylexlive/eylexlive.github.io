<div class="row">
  <?php
  	@$sayfa = get("s") ? get("s") : 1;
  	$ksayisi = rows(query("SELECT * FROM yazi"));
  	$limit = mset("blog_limit");
  	$ssayisi = ceil($ksayisi/$limit);
  	$baslangic = $sayfa * $limit - $limit;
  	if($ksayisi > $limit){
  		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum='1' ORDER BY yazi_id DESC LIMIT $baslangic,$limit");
  		$oncekiSayfa = $sayfa > 1 ? $sayfa - 1 : 1;
  		$onceki = URL.'/s/'.$oncekiSayfa;
  		$sonrakiSayfa = $sayfa < $ssayisi ? $sayfa + 1 : $ssayisi;
  		$sonraki = URL.'/s/'.$sonrakiSayfa;
  	}else{
  		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum='1' ORDER BY yazi_id DESC");
  	}
  	if(rows($query)){
  		while($row = row($query)){
  ?>
  <div class="col-sm-6">
    <div class="panel panel-post">
      <a href="<?php echo URL."/blog/".$row["yazi_link"]; ?>.html"><img src="<?=$row["yazi_resim"]?>" alt="<?php echo ss($row["yazi_baslik"]); ?>" class="img-responsive"></a>
      <div class="panel-body">
        <a href="<?php echo URL."/blog/".$row["yazi_link"]; ?>.html" class="panel-title"><?php echo ss($row["yazi_baslik"]); ?></a>
        <div class="panel-detay">
          <?php echo ss($row["kategori_baslik"]); ?>  <span style="float: right"><?=tarih_t($row["yazi_tarih"],"d m Y")?></span>
        </div>
      </div>
    </div>
  </div>
  <?php }
  }else{
    echo "<div class='col-md-12'>";
  	echo alert('Henüz hiç yazı eklenmemiş.');
    echo "</div>";
  } ?>
</div>
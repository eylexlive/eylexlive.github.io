<div class="panel panel-default">
  <div class="panel-heading">Duyurular</div>
<?php
	$sayfa = isset($_GET["s"])?get("s"):1;
	$ksayisi = rows(query("SELECT * FROM yazi WHERE yazi_durum=1"));
	$limit = mset("blog_limit");
	$ssayisi = ceil($ksayisi/$limit);
	$baslangic = $sayfa * $limit - $limit;
	if($ksayisi > $limit){
		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum='1' ORDER BY yazi_id DESC LIMIT $baslangic,$limit");
	}else{
		$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_durum='1' ORDER BY yazi_id DESC");
	}
	if(rows($query)){
		while($row = row($query)){
?>
 <div class="panel-body hr">
  <div class="row">
    <div class="col-md-12 text-center">
      <h3 class="mt-none"><a href="<?php echo URL."/blog/".$row["yazi_link"]; ?>.html"><?php echo ss($row["yazi_baslik"]); ?></a></h3>
      <div class="help-block">
        <i class="fa fa-pencil"></i> <strong>Yazan:</strong> <?php echo $row["uye_kadi"]; ?> |
        <i class="fa fa-sitemap"></i> <strong>Kategori:</strong> <?php echo ss($row["kategori_baslik"]); ?> |
        <i class="fa fa-calendar"></i> <strong>Tarih:</strong> <?php echo tarih_t($row["yazi_tarih"],"d m Y h:i"); ?> </div>
    </div>
    <div class="col-md-12">
      <p class="text-center"></p>
      <p><?php echo nl2br($row["yazi_icerik"]); ?></p>
      <p></p>
    </div>
  </div>
</div>
<?php } ?>
</div>
  <?php if(isset($ksayisi) && isset($limit) && $ksayisi > $limit){ ?>
    <div class="panel">
      <div class="panel-body">
          <ul class="pager" style="margin:0;">
          <li <?=($ssayisi>$sayfa)?null:"class='disabled'"?>><a <?=($ssayisi>$sayfa) ? "href=\"".URL."/s/".($sayfa+1)."\"":null?> aria-label="Previous" class="pull-left">
            <span aria-hidden="true"><span aria-hidden="true">&larr;</span> Eski Yazılar</span>
          </a></li>
          <li <?=($sayfa>1)?null:"class='disabled'"?>><a <?=($sayfa>1) ? "href=\"".URL."/s/".($sayfa-1)."\"":null?> aria-label="Next" class="pull-right">
            <span aria-hidden="true">Yeni Yazılar <span aria-hidden="true">&rarr;</span></span>
          </a></li>
          </ul>
      </div>
    </div>
  <?php } ?>
<?php }else{
  echo "<div class='panel-body'>".alert('Henüz hiç yazı eklenmemiş.','warning" style="margin-bottom: 0px;')."</div></div>";
} ?>

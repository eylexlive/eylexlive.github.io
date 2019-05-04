<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
    
	if(isset($_GET['sil'])){
		$sil = get('sil');
		$varmi = query("SELECT * FROM urunler WHERE urun_id = '$sil'");
		if(rows($varmi) < 1){
			go(ADMIN_URL.'/?go=urunler');
			exit;
		}else{
			$delete = query("DELETE FROM urunler WHERE urun_id = '$sil'");
			if($delete){
				echo alert('Ürün başarıyla silindi. Yönlendiriliyorsunuz.','success');
				go(ADMIN_URL.'/?go=urunler',1);
			}else{
				echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
			}
		}
	}
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">Tüm Ürünler</div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>Ürün Adı</th>
          <th class="text-center">Fiyatı</th>
          <th class="text-center">Süresi</th>
          <th class="text-center">Kategori</th>
          <th class="text-center">Sunucu</th>
          <th class="text-center">İşlemler</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "urunler";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM urunler"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM urunler INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY urun_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM urunler INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY urun_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="7">Henüz hiç Ürün eklememişsiniz.</td></tr>';
        }else{
          while($uRow = row($query)){
      ?>
        <tr>
          <td class="text-center"><?php echo $uRow["urun_id"]; ?></td>
          <td><a href="<?=ADMIN_URL?>/?go=urun_duzenle&uid=<?php echo $uRow["urun_id"]; ?>"><?php echo ss($uRow["urun_adi"]); ?></a></td>
          <td class="text-center"><?php echo $uRow["urun_fiyat"]; ?></td>
          <td class="text-center"><?php echo ($uRow["urun_gun"] == "0") ? "Sınırsız" : $uRow["urun_gun"] ; ?></td>
          <td class="text-center"><?php echo ss($uRow["kategori_adi"]); ?></td>
          <td class="text-center"><?php echo ss($uRow["sunucu_adi"]); ?></td>
          <td style="width:110px" class="text-center">
            <a href="<?=URL?>/market/<?=$uRow['sunucu_link']?>/<?=$uRow['kategori_link']?>" class="btn btn-primary btn-xs" target="<?=$uRow['kategori_link']?>_<?=$uRow['urun_id']?>"><i class="fa fa-eye"></i></a>
            <a href="<?=ADMIN_URL?>/?go=urun_duzenle&uid=<?=$uRow['urun_id']?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
            <a onclick="SwSil('urunler','<?=$uRow["urun_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
          </td>
        </tr>
      <?php } } ?>
      </tbody>
    </table>
  </div>
</div>
<?php if ($ksayisi > 0): ?>
    <nav class="text-center">
        <?php
        $sayfa_goster = 6;

        $en_az_orta = ceil($sayfa_goster/2);
        $en_fazla_orta = ($ssayisi+1) - $en_az_orta;

        $sayfa_orta = $sayfa;
        if($sayfa_orta < $en_az_orta) $sayfa_orta = $en_az_orta;
        if($sayfa_orta > $en_fazla_orta) $sayfa_orta = $en_fazla_orta;

        $sol_sayfalar = round($sayfa_orta - (($sayfa_goster-1) / 2));
        $sag_sayfalar = round((($sayfa_goster-1) / 2) + $sayfa_orta);

        if($sol_sayfalar < 1) $sol_sayfalar = 1;
        if($sag_sayfalar > $ssayisi) $sag_sayfalar = $ssayisi;
        ?>
        <ul class="pagination">
            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=1"><i class="fa fa-angle-double-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != 1): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
            <?php endif; ?>

            <?php
            for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
                if($sayfa == $s) {
                    echo '<li class="active"><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                } else {
                    echo '<li><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                }
            }
            ?>
            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
            <?php endif; ?>

            <?php if ($sayfa != $ssayisi): ?>
                <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
            <?php else: ?>
                <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
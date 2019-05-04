<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">Aktif Ürünler</div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>Alan Kişi</th>
          <th>Ürün Adı</th>
          <th class="text-center">Süre</th>
          <th class="text-center">Alım Tarihi</th>
          <th class="text-center">Durumu</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "aktif_urunler";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM market_aktif"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM market_aktif INNER JOIN depo ON depo.depo_id=market_aktif.aktif_market_id INNER JOIN uyeler ON uyeler.uye_id=depo.depo_uye INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY aktif_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM market_aktif INNER JOIN depo ON depo.depo_id=market_aktif.aktif_market_id INNER JOIN uyeler ON uyeler.uye_id=depo.depo_uye INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY aktif_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="6">Henüz hiç aktifleştirilmiş ürün yok.</td></tr>';
        }else{
          while($uRow = row($query)){
        ?>
        <tr>
          <td class="text-center"><?php echo $uRow["depo_id"]; ?></td>
          <td><a href="<?=ADMIN_URL?>/?go=profil&uid=<?php echo $uRow["uye_id"]; ?>"><?php echo $uRow["uye_kadi"]; ?></a></td>
          <td>[<?=$uRow['sunucu_link']?>] <a href="<?=URL?>/market/<?=$uRow['sunucu_link']?>/<?=$uRow['kategori_link']?>"><?=$uRow["urun_adi"]?></a></td>
          <td class="text-center"><?php echo $uRow["aktif_sure"] == 0 ? '<span class="label label-danger">Sınırsız</span>' : $uRow["aktif_sure"]." Gün"; ?></td>
          <td class="text-center"><?=date("d-m-Y H:i",$uRow["aktif_tarih2"])?></td>
          <td class="text-center" style="padding: 11px 10px 13px;">
            <?php $kalan_g = $uRow["aktif_sure"] != 0 ? "Kalan Gün: ".(floor((date($uRow["aktif_tarih2"] + (86400*$uRow["aktif_sure"]))-time()) / 86400)):'Sınırsız'; ?>
            <?php echo $uRow["aktif_durum"] == 1 ? '<span data-toggle="tooltip" data-placement="left" title="" type="button" data-original-title="'.$kalan_g.'" class="label label-success">Devam Ediyor</span>' : null; ?>
            <?php echo $uRow["aktif_durum"] == 0 ? '<span class="label label-danger">Bitti</span>' : null; ?>
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
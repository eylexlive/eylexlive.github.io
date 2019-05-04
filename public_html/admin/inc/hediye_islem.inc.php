<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">Kredi İşlemler</div>
  <div class="table-responsive">
    <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>Depo Ürün & ID</th>
          <th class="text-center">Gönderici</th>
          <th class="text-center">Alıcı</th>
          <th class="text-center">Tarih</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "hediye_islem";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM hediye_gecmis"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT hediye_id,hediye_tarih,urun_adi,depo_id,u1.uye_kadi AS uye_kadi_1,u2.uye_kadi AS uye_kadi_2 FROM hediye_gecmis INNER JOIN uyeler AS u1 ON u1.uye_id=hediye_gecmis.hediye_gonderen_id INNER JOIN uyeler AS u2 ON u2.uye_id=hediye_gecmis.hediye_alan_id INNER JOIN depo ON depo.depo_id=hediye_gecmis.hediye_depo_id INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id ORDER BY hediye_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT hediye_id,hediye_tarih,urun_adi,depo_id,u1.uye_kadi AS uye_kadi_1,u2.uye_kadi AS uye_kadi_2 FROM hediye_gecmis INNER JOIN uyeler AS u1 ON u1.uye_id=hediye_gecmis.hediye_gonderen_id INNER JOIN uyeler AS u2 ON u2.uye_id=hediye_gecmis.hediye_alan_id INNER JOIN depo ON depo.depo_id=hediye_gecmis.hediye_depo_id INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id ORDER BY hediye_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="6">Henüz hiç hediye gönderme işlemi olmamış.</td></tr>';
        }else{
          while($uRow = row($query)){
        ?>
        <tr>
          <td class="text-center"><?php echo $uRow["hediye_id"]; ?></td>
          <td><?=$uRow["urun_adi"]?> [#<?=$uRow["depo_id"]?>]</td>
          <td class="text-center"><?=$uRow["uye_kadi_1"]?></td>
          <td class="text-center"><?=$uRow["uye_kadi_2"]?></td>
          <td class="text-center"><?=date("d-m-Y h:i",$uRow["hediye_tarih"])?></td>
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
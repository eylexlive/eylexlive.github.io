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
          <th>Yükleyen Kişi</th>
          <th>Yükleme Türü</th>
          <th class="text-center">Miktar</th>
          <th class="text-center">Tarih</th>
          <?php if ($user["uye_rutbe"] == "1"): ?>
            <th class="text-center">Detay</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
      <?php
        $link = "kredi_islem";
        @$sayfa = get("sayfa") ? get("sayfa") : 1;
        $ksayisi = rows(query("SELECT * FROM krediler"));
        $limit = 20;
        $ssayisi = ceil($ksayisi/$limit);
        $baslangic = $sayfa * $limit - $limit;
        if($ksayisi > $limit){
          $query = query("SELECT * FROM krediler INNER JOIN odeme ON krediler.odeme_slug=odeme.odeme_slug INNER JOIN uyeler ON uyeler.uye_id=krediler.kredi_ekleyen ORDER BY kredi_id DESC LIMIT $baslangic,$limit");
        }else{
          $query = query("SELECT * FROM krediler INNER JOIN odeme ON krediler.odeme_slug=odeme.odeme_slug INNER JOIN uyeler ON uyeler.uye_id=krediler.kredi_ekleyen ORDER BY kredi_id DESC");
        }
        if(rows($query) < 1){
          echo '<tr><td class="text-center" colspan="6">Henüz hiç kredi yüklenmemiş.</td></tr>';
        }else{
          while($uRow = row($query)){
            $json = json_decode($uRow["odeme_resp"],true);
        ?>
        <tr>
          <td class="text-center"><?php echo $uRow["kredi_id"]; ?></td>
          <td><a href="<?=ADMIN_URL?>/?go=profil&uid=<?php echo $uRow["uye_id"]; ?>"><?php echo $uRow["uye_kadi"]; ?></a></td>
          <td><?php echo $uRow["odeme_adi"]; ?> (<?=$json["baslik"]?>)</td>
          <td class="text-center"><?php echo $uRow["kredi_miktar"]; ?></td>
          <td class="text-center"><?php echo tarih_t($uRow["kredi_tarih"],"d m Y h:i"); ?></td>
          <?php if ($user["uye_rutbe"] == "1"): ?>
            <td class="text-center"><?php echo $uRow["kredi_hesap"]; ?></td>
          <?php endif; ?>
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
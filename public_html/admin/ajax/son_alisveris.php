<?php
  error_reporting(0);
  require_once '../../config.php';
?>
<div class="pull-t pull-r-l">
  <div class="js-slider remove-margin-b">

    <table class="table remove-margin-b font-s13">
        <tbody>
          <?php if (!session('login')): ?>
            <tr><td class="font-w600 text-center" colspan="3">Session Hatası</td></tr>
          <?php else: ?>
            <?php
              $query = query("SELECT * FROM market INNER JOIN uyeler ON uyeler.uye_id=market.market_uye INNER JOIN urunler ON urunler.urun_id=market.market_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY market_id DESC LIMIT 10");
            ?>
            <?php if (rows($query) < 1): ?>
              <tr><td class="text-center" colspan="3">Market geçmişi bulunmamaktadır.</td></tr>
            <?php else: ?>
              <?php while($row = row($query)){ ?>
              <tr>
                <td class="font-w600">[<?=$row['sunucu_link']?>] <a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>"><?=$row["urun_adi"]?></a></td>
                <td class="text-muted text-right"><?=$row["uye_kadi"]?></td>
                <td class="font-w600 text-success text-right">+ <?=$row["market_urun_fiyat"]?> TL</td>
              </tr>
              <?php } ?>
            <?php endif; ?>
          <?php endif; ?>
        </tbody>
    </table>
    
  </div>
</div>      
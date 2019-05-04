<?php $cacheIMG = new ImageCache("content/cache",URL."/content/cache"); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Son Marketi Kullananlar</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-border">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>İsim</th>
          <th class="text-center">Sunucu</th>
          <th class="text-center">Ürün Adı</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $query = query("SELECT * FROM market INNER JOIN uyeler ON uyeler.uye_id=market.market_uye INNER JOIN urunler ON urunler.urun_id=market.market_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY market_id DESC LIMIT 5");
          while($row = row($query)){
        ?>
        <tr>
          <td class="text-center"><img src="<?php $cacheIMG->printUrl('http://cravatar.eu/avatar/'.$row["uye_kadi"].'/20.png',100,20,20,false); ?>"></td>
          <td><?php echo $row["uye_kadi"]; ?></td>
          <td class="text-center"><?php echo ss($row["sunucu_adi"]); ?></td>
          <td class="text-center"><?php echo ss($row["urun_adi"]); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
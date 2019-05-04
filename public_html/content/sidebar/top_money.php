<?php $cacheIMG = new ImageCache("content/cache",URL."/content/cache"); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">En Çok Kredi Alanlar</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-border">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>İsim</th>
          <th class="text-center">Miktar</th>
          <th class="text-center">Toplam</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $tarih = date("Y-m");
          $query = query("SELECT *, SUM(kredi_miktar) as toplam,count( * ) AS tekrar
          FROM krediler INNER JOIN uyeler ON uyeler.uye_id = krediler.kredi_ekleyen
          WHERE kredi_tarih LIKE '%$tarih%'
          GROUP BY kredi_ekleyen
          HAVING tekrar > 0
          ORDER BY toplam DESC LIMIT 5");

          while($row = row($query)){
        ?>
        <tr>
          <td class="text-center"><img src="<?php $cacheIMG->printUrl('http://cravatar.eu/avatar/'.$row["uye_kadi"].'/20.png',100,20,20,false); ?>"></td>
          <td><?php echo $row["uye_kadi"]; ?></td>
          <td class="text-center"><?php echo $row["toplam"]; ?></td>
          <td class="text-center"><?php echo $row["tekrar"]; ?> Kez</td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
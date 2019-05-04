<?php $cacheIMG = new ImageCache("content/cache",URL."/content/cache"); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Kredi Alanlar</h3>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-border">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th>İsim</th>
          <th class="text-center">Miktar</th>
          <th class="text-center">Tür</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $query = query("SELECT * FROM krediler INNER JOIN uyeler ON uyeler.uye_id = krediler.kredi_ekleyen INNER JOIN odeme ON krediler.odeme_slug=odeme.odeme_slug ORDER BY kredi_id DESC LIMIT 5");
          while($row = row($query)){
            $json = json_decode($row["odeme_resp"],true);
        ?>
        <tr>
          <td class="text-center"><img src="<?php $cacheIMG->printUrl('http://cravatar.eu/avatar/'.$row["uye_kadi"].'/20.png',100,20,20,false); ?>"></td>
          <td><?php echo $row["uye_kadi"]; ?></td>
          <td class="text-center"><?php echo $row["kredi_miktar"]; ?></td>
          <td class="text-center"><?=$json["baslik"]?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
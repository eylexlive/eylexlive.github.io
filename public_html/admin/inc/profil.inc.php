<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"] > 0) ? null: go(ADMIN_URL);
  
  if(isset($_GET["uid"])){
		$uid = get("uid");
		$varmi = query("SELECT * FROM uyeler WHERE uye_id = '$uid'");
		if(rows($varmi) < 1){
      go(ADMIN_URL."/?go=uyeler",2);
			exit;
		}else{
			$uyeRow = row($varmi);
		}
	}else{
    go(ADMIN_URL."/?go=uyeler",2);
		exit;
	}
?>
<div class="row">
  <div class="col-md-4">
    <div class="block">
        <div class="block-content block-content-full clearfix">
            <div class="pull-right">
                <img class="img-avatar" src="https://cravatar.eu/avatar/<?=$uyeRow["uye_kadi"]?>/120.png" alt="">
            </div>
            <div class="pull-left push-10-t">
                <div class="font-w600 push-5"><?=$uyeRow["uye_kadi"]?></div>
                <div class="text-muted">
                  <?php if ($uyeRow["uye_rutbe"] == "1"): ?>Yönetici<?php elseif ($uyeRow["uye_rutbe"] == "2"): ?>Moderatör<?php elseif ($uyeRow["uye_rutbe"] == "3"): ?>Ticket Yetkilisi<?php else: ?>Üye<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="block block-bordered">
      <div class="block-header bg-gray-lighter"><h3 class="block-title">Destek Sistemi Engelleme</h3></div>
      <div class="block-content block-content-full">
        <?php $ub = query("SELECT * FROM ticket_engel WHERE uye_id='$uid'"); ?>
        <?php if (rows($ub) > 0): ?>
          <?php 
          
            if (isset($_POST["kaldir"])) {
              $engelle = query("DELETE FROM ticket_engel WHERE uye_id='$uid'");
              if($engelle){
                echo alert("Kaldırma işlemi başarılı.",'success');
                go(ANLIK_URL,1);
              }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
              }
            }
          
          ?>
    			<?php $ubr = row($ub); ?>
          <label>Engel Nedeni:</label>
          <p><?=$ubr["engel_neden"]?></p>
          <form action="" method="post">
            <button type="submit" name="kaldir" value="true" class="btn btn-block btn-success">Engel Kaldır</button>
          </form>
    		<?php else: ?>
          <?php 
          
            if ($_POST) {
              $engel_neden = post("engel_neden");
              if ($engel_neden) {
                $engelle = query("INSERT INTO ticket_engel SET
                  uye_id='$uid',
                  engel_neden='$engel_neden',
                  engel_tarih='".time()."',
                  engel_atan='".$user["uye_id"]."'
                ");
                if($engelle){
  								echo alert("Engelleme işlemi başarılı.",'success');
                  go(ANLIK_URL,1);
  							}else{
  								echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
  							}
              }else{
                echo alert("Lütfen boş alan bırakmayın.","danger");
              }
            }
          
          ?>
          <form action="" method="post">
            <div class="form-group">
              <label for="engel_neden">Engellenme Nedeni</label>
              <input type="text" class="form-control" id="engel_neden" name="engel_neden" placeholder="Kısaca nedenini yazınız.">
            </div>
            <button type="submit" class="btn btn-danger btn-block">Engelle</button>
          </form>
    		<?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    
    <div class="block block-bordered">
      <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs">
          <li class="active">
              <a href="#destek-talepleri">Destek Talepleri</a>
          </li>
          <li>
              <a href="#kredi-gecmis">Kredi Geçmişi</a>
          </li>
          <li>
              <a href="#market-gecmis">Market Geçmişi</a>
          </li>
          <li>
              <a href="#depo">Depo</a>
          </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="destek-talepleri">
          <div class="table-responsive">
            <table class="table table-hover table-vcenter" style="margin-bottom: 0px;">
              <tbody>
                <?php
                  $query = query("SELECT * FROM ticketler INNER JOIN uyeler ON uyeler.uye_id = ticketler.ticket_atan_id INNER JOIN kategoriler_ticket ON kategoriler_ticket.kategori_id = ticket_kategori WHERE uye_id='$uid' AND ticket_durum = 1 ORDER BY ticket_guncelleme DESC,ticket_id DESC");
                  if(rows($query) < 1){
                    echo '<tr><td class="text-center" colspan="4">Destek talebi bulunamadı.</td></tr>';
                  }else{
                    while($row = row($query)){
                ?>
                  <tr id="<?=$row["ticket_id"]?>">
                      <td style="width: 55%;">
                          <a class="font-w600" href="<?=ADMIN_URL?>/?go=destek_duzenle&tid=<?=$row["ticket_id"]?>"><?=$row["ticket_baslik"]?></a>
                          <div class="text-muted">
                              <em><?=tarih_t($row["ticket_tarih"],"d m Y")?></em> &nbsp;/&nbsp; <a href="<?=ADMIN_URL?>"><?=$row["uye_kadi"]?></a>
                          </div>
                      </td>
                      <td class="hidden-xs hidden-sm hidden-md text-muted">
                          <em><?=ss($row["kategori_adi"])?></em>
                      </td>
                      <td class="hidden-xs hidden-sm hidden-md text-center">
                        <?php echo $row["ticket_turu"] == 1 ? '<span class="label label-success label-mini">Açık</span>' : null; ?>
                        <?php echo $row["ticket_turu"] == 2 ? '<span class="label label-default label-mini">Cevaplandı</span>' : null; ?>
                        <?php echo $row["ticket_turu"] == 3 ? '<span class="label label-warning label-mini">Kullanıcı Yanıtı</span>' : null; ?>
                        <?php echo $row["ticket_turu"] == 4 ? '<span class="label label-danger label-mini">Kapandı</span>' : null; ?>
                      </td>
                      <td style="width:100px" class="text-center">
                        <?php if($row["ticket_turu"] != 4){ ?>
                          <a href="<?=ADMIN_URL?>/?go=destek_duzenle&tid=<?=$row["ticket_id"]?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                          <a href="<?=ADMIN_URL?>/?go=destekler&kapat=<?=$row["ticket_id"]?>" class="btn btn-warning btn-xs"><i class="fa fa-times"></i></a>
                          <a href="#" onclick="SwSil('destekler','<?=$row["ticket_id"]?>','uid');" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                        <?php }else{ ?>
                          <a href="<?=ADMIN_URL?>/?go=destek_duzenle&tid=<?=$row["ticket_id"]?>" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                          <a href="#" onclick="SwSil('destekler','<?=$row["ticket_id"]?>','uid');" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                      </td>
                  </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="tab-pane table-responsive" id="kredi-gecmis">
          <table class="table table-striped table-hover" style="margin-bottom: 0px;">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Yükleme Türü</th>
                <th class="text-center">Miktar</th>
                <th class="text-center">Tarih</th>
                <th class="text-center">Detay</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $kredi = query("SELECT * FROM krediler INNER JOIN odeme ON krediler.odeme_slug=odeme.odeme_slug WHERE kredi_ekleyen='$uid' ORDER BY kredi_id DESC");
                if (rows($kredi) < 1) {
                  echo "<td colspan='8'><center>Kredi yükleme geçmişi bulunmamaktadır.</center></td>";
                }else{
                  while($krow = row($kredi)){
                    $json = json_decode($krow["odeme_resp"],true);
              ?>
              <tr>
                <td class="text-center"><?php echo $krow["kredi_id"]; ?></td>
                <td><?php echo $krow["odeme_adi"]; ?> (<?=$json["baslik"]?>)</td>
                <td class="text-center"><?php echo $krow["kredi_miktar"]; ?></td>
                <td class="text-center"><?php echo tarih_t($krow["kredi_tarih"],"d m Y h:i"); ?></td>
                <td class="text-center"><?php echo $krow["kredi_hesap"]; ?></td>
              </tr>
              <?php }} ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="market-gecmis">
          <table class="table table-striped table-hover" style="margin-bottom: 0px;">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Ürün Adı</th>
                <th class="text-center">Süre</th>
                <th class="text-center">Tarih</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $depo = query("SELECT * FROM market INNER JOIN urunler ON urunler.urun_id=market.market_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE market_uye='$uid' ORDER BY market_id DESC");
                if (rows($depo) < 1) {
                  echo "<td colspan='8'><center>Market geçmişi bulunmamaktadır.</center></td>";
                }else{
                  while($row = row($depo)){
              ?>
              <tr>
                <td class="text-center"><?=$row["market_id"]?></td>
                <td>[<?=$row['sunucu_link']?>] <a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>"><?=$row["urun_adi"]?></a></td>
                <td class="text-center"><?php if ($row["market_urun_gun"] != "0"): ?><?=$row["market_urun_gun"]?><?php else: ?><span class="label label-danger">Sınırsız</span><?php endif; ?></td>
                <td class="text-center"><?=date("d-m-Y",$row["market_tarih1"])?></td>
              </tr>
              <?php }} ?>
            </tbody>
          </table>
        </div>
        <div class="tab-pane table-responsive" id="depo">
          <table class="table table-striped table-hover" style="margin-bottom: 0px;">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Ürün Adı</th>
                <th class="text-center">Süre</th>
                <th class="text-center">Tarih</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $depo = query("SELECT * FROM depo INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE depo_uye='$uid' AND depo_durum='0' ORDER BY depo_id DESC");
                if (rows($depo) < 1) {
                  echo "<td colspan='8'><center>Depo içeriği bulunmamaktadır.</center></td>";
                }else{
                  while($row = row($depo)){
              ?>
              <tr>
                <td class="text-center"><?=$row["depo_id"]?></td>
                <td>[<?=$row['sunucu_link']?>] <a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>"><?=$row["urun_adi"]?></a> <?php if ($row["depo_tur"] == "1"): ?><i data-toggle="tooltip" data-placement="right" class="fa fa-gift fa-fw" title="Gönderen: <?=$row["depo_hg_id"]?>"></i><?php endif; ?></td>
                <td class="text-center"><?php if ($row["depo_urun_gun"] != "0"): ?><?=$row["depo_urun_gun"]?><?php else: ?><span class="label label-danger">Sınırsız</span><?php endif; ?></td>
                <td class="text-center"><?=date("d-m-Y",$row["depo_tarih1"])?></td>
              </tr>
              <?php }} ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </div>
</div>
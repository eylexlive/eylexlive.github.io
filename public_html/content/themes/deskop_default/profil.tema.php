<?php
	!session('login') ? go(URL."/kayitol") : null;
  $uye_id = session("uye_id");
	$query = query("SELECT * FROM uyeler WHERE uye_id = '$uye_id'");
	$row = row($query);
?>
<div class="panel panel-default">
  <div class="panel-heading">Profil Detayları</div>
  <div class="panel-body">
    
    <div class="row">
      <div class="col-md-6">
      <?php
        if(isset($_POST["sifre_degistir"])){
          $eski_sifre = post("eski_sifre");
          $yeni_sifre = post("yeni_sifre");
          $yeni_sifre2 = post("yeni_sifre2");
          if(!$eski_sifre || !$yeni_sifre || !$yeni_sifre2){
            echo alert('Şifre bölümlerini doldurun.');
          }else if($yeni_sifre != $yeni_sifre2){
            echo alert('Yeni şifreler uyuşmuyor.');
          }else{
						if (mset("authme_sifre") == "sha256"){
	            $k_sifre = sha256Kontrol($eski_sifre,$row["uye_sifre"]);
	          }else{
	            $k_sifre = ($row["uye_sifre"] != md5($eski_sifre))?false:true;
	          }
	          if ($k_sifre == false) {
	              echo alert('Mevcut şifrenizi yanlış girdiniz.');
	          }else{
							$yeni_sifre = (mset("authme_sifre") == "md5") ? md5($yeni_sifre2) : sha256Olustur($yeni_sifre2);
              $update = query("UPDATE uyeler SET uye_sifre = '$yeni_sifre' WHERE uye_id = '$uye_id'");
              if($update){
                echo alert('Şifreniz güncellendi! Yönlendiriliyorsunuz...','success');
                go(ANLIK_URL,1);
              }else{
                echo alert('Mysql Hatası: '.mysqli_error($baglan));
              }
            }
          }
        }
      ?>
        <form action="" method="post">
          <div class="panel panel-default">
            <div class="panel-heading">Şifre Değiştir</div>
            <div class="panel-body">
              <div class="form-group">
                <label for="">Mevcut Şifreniz:</label>
                <input type="password" name="eski_sifre" id="" class="form-control" />
              </div>
              <div class="form-group">
                <label for="">Yeni Şifreniz:</label>
                <input type="password" name="yeni_sifre" id="" class="form-control" />
              </div>
              <div class="form-group">
                <label for="">Yeni Şifreniz(Tekrar):</label>
                <input type="password" name="yeni_sifre2" id="" class="form-control" />
              </div>
              <button name="sifre_degistir" class="btn btn-success btn-block">Güncelle</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-6">
      <?php
        if(isset($_POST["email_degistir"])){
          $mevcut_sifre = post("mevcut_sifre");
          $uye_email = post("uye_email");
          if(!$mevcut_sifre || !$uye_email){
            echo alert('Email değiştirme bölümünün tamamını doldurun.');
          }else{
						if (mset("authme_sifre") == "sha256"){
	            $k_sifre = sha256Kontrol($mevcut_sifre,$row["uye_sifre"]);
	          }else{
	            $k_sifre = ($row["uye_sifre"] != md5($mevcut_sifre))?false:true;
	          }
	          if ($k_sifre == false) {
	              echo alert('Mevcut şifrenizi yanlış girdiniz.');
	          }else{
              $update = query("UPDATE uyeler SET uye_email = '$uye_email' WHERE uye_id = '".session('uye_id')."'");
              if($update){
                echo alert('Email adresiniz değişti. Yönelndiriliyorsunuz.','success');
                go(ANLIK_URL,1);
              }else{
                echo alert('Mysql Hatası: '.mysqli_error($baglan));
              }
            }
          }
        }
      ?>
        <form action="" method="post">
          <div class="panel panel-default">
            <div class="panel-heading">Email Değiştir</div>
            <div class="panel-body">
              <div class="form-group">
                <label for="">Mevcut Şifreniz:</label>
                <input type="password" name="mevcut_sifre" id="" class="form-control" />
              </div>
              <div class="form-group">
                <label for="">Email Hesabınız:</label>
                <input type="text" name="uye_email" id="" class="form-control" value="<?php echo $row["uye_email"]; ?>" />
              </div>
              <button name="email_degistir" class="btn btn-success btn-block">Güncelle</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
  </div>
  <div class="alert alert-info text-center" style="border-radius: 0;border-left:0;border-right:0;margin-bottom:0;"><strong>Yaptığınız Son 30 işlem gözükür.</strong></div>
  
  <div class="panel-body">
    <div class="panel panel-default">
      <div class="panel-heading">Kredi Geçmişi</div>

      <div class="tab-pane table-responsive">
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
              $kredi = query("SELECT * FROM krediler INNER JOIN odeme ON krediler.odeme_slug=odeme.odeme_slug WHERE kredi_ekleyen='$uye_id' ORDER BY kredi_id DESC LIMIT 30");
              if (rows($kredi) < 1) {
                echo "<td colspan='8'><center>Kredi yükleme geçmişi bulunmamaktadır.</center></td>";
              }else{
                while($krow = row($kredi)){
                  $json = json_decode($krow["odeme_resp"],true);
            ?>
            <tr>
              <td class="text-center"><?php echo $krow["kredi_id"]; ?></td>
              <td><?=$json["baslik"]?></td>
              <td class="text-center"><?php echo $krow["kredi_miktar"]; ?></td>
              <td class="text-center"><?php echo tarih_t($krow["kredi_tarih"],"d m Y h:i"); ?></td>
              <td class="text-center"><?php echo $krow["kredi_hesap"]; ?></td>
            </tr>
            <?php }} ?>
          </tbody>
        </table>
      </div>
        
    </div>
		<div class="panel panel-default">
			<div class="panel-heading">Satın Alma Geçmişi</div>

			<div class="tab-pane table-responsive">
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
							$depo = query("SELECT * FROM market INNER JOIN urunler ON urunler.urun_id=market.market_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE market_uye='$uye_id' ORDER BY market_id DESC LIMIT 30");
							if (rows($depo) < 1) {
								echo "<td colspan='8'><center>Market geçmişi bulunmamaktadır.</center></td>";
							}else{
								while($row = row($depo)){
						?>
						<tr>
							<td class="text-center"><?=$row["market_id"]?></td>
							<td>[<?=$row['sunucu_link']?>] <a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>"><?=$row["urun_adi"]?></a></td>
							<td class="text-center"><?php if ($row["market_urun_gun"] != "0"): ?><?=$row["market_urun_gun"]?><?php else: ?><span class="label label-danger">Sınırsız</span><?php endif; ?></td>
							<td class="text-center"><?php echo tarih_t($row["market_tarih2"],"d m Y h:i"); ?></td>
						</tr>
						<?php }} ?>
					</tbody>
				</table>
			</div>
				
		</div>
		
		<div class="panel panel-default" id="aktif_urunler">
			<div class="panel-heading">Aktif Ürünler</div>
			<div class="tab-pane table-responsive">
				<table class="table table-striped table-hover" style="margin-bottom: 0px;">
					<thead>
						<tr>
							<th class="text-center">#</th>
		          <th>Ürün Adı</th>
		          <th class="text-center">Süre</th>
		          <th class="text-center">Alım Tarihi</th>
		          <th class="text-center">Durumu</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$query = query("SELECT * FROM market_aktif INNER JOIN depo ON depo.depo_id=market_aktif.aktif_market_id INNER JOIN urunler ON urunler.urun_id=depo.depo_urun_id INNER JOIN kategoriler_urun ON kategoriler_urun.kategori_id = urunler.urun_kategori INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu WHERE depo_uye='$uye_id' ORDER BY aktif_id DESC");
							if (rows($query) < 1) {
								echo "<td colspan='8'><center>Aktifleştirilmiş ürün bulunmamaktadır.</center></td>";
							}else{
								while($uRow = row($query)){
						?>
						<tr>
		          <td class="text-center"><?php echo $uRow["depo_id"]; ?></td>
		          <td>[<?=$uRow['sunucu_link']?>] <a href="<?=URL?>/market/<?=$uRow['sunucu_link']?>/<?=$uRow['kategori_link']?>"><?=$uRow["urun_adi"]?></a></td>
		          <td class="text-center"><?php echo $uRow["aktif_sure"] == 0 ? '<span class="label label-danger">Sınırsız</span>' : $uRow["aktif_sure"]." Gün"; ?></td>
		          <td class="text-center"><?=date("d-m-Y H:i",$uRow["aktif_tarih2"])?></td>
		          <td class="text-center">
		            <?php $kalan_g = $uRow["aktif_sure"] != 0 ? "Kalan Gün: ".(floor((date($uRow["aktif_tarih2"] + (86400*$uRow["aktif_sure"]))-time()) / 86400)):'Sınırsız'; ?>
		            <?php echo $uRow["aktif_durum"] == 1 ? '<span data-toggle="tooltip" data-placement="left" title="" type="button" data-original-title="'.$kalan_g.'" class="label label-warning">Devam Ediyor</span>' : null; ?>
		            <?php echo $uRow["aktif_durum"] == 0 ? '<span class="label label-warning">Bitti</span>' : null; ?>
		          </td>
		        </tr>
						<?php }} ?>
					</tbody>
				</table>
			</div>
		</div>
		
	</div>
</div>
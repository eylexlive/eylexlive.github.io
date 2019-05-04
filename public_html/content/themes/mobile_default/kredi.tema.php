<h3 class="ui-bar ui-bar-a ui-corner-all">Kredi Yükle</h3>
<?php if (isset($_GET["link"])): ?>
  <?php $row = query("SELECT * FROM odeme WHERE odeme_slug='".get("link")."' AND odeme_durum='1'"); ?>
  <?php if (rows($row) > 0): ?>
    <?php $row = row($row); ?>
    <?php if ($row["odeme_slug"] == "ininal"): ?>
      <?php $api = json_decode($row["odeme_resp"],true); ?>
      <div class="alert alert-success text-center">
				<strong>Barkod No:</strong>
        <h4><?php if (isset($api["barkod1"])): ?><?=$api["barkod1"]?><?php endif; ?></h4>
        <h4><?php if (isset($api["barkod2"])): ?><?=$api["barkod2"]?><?php endif; ?></h4>
        <h4><?php if (isset($api["barkod3"])): ?><?=$api["barkod3"]?><?php endif; ?></h4>
			</div>
      
      <div class="alert alert-warning">
				<strong>Nasıl Para Gönderilir?</strong>
				<br>1- İninal cep uygulamasına giriş yapın.
				<br>2- Alt menüde bulunan <strong>"Para Gönder"</strong> simgesine tıklayın.
				<br>3- Çıkan iki seçenekten üst taraftaki Para Gönder seçeneğini seçin.
				<br>4- Alıcı Kart Barkodu yazan yere Üst tarafta yazan barkodu girin.
				<br>5- Göndereceğiniz miktarı girin.
				<br>6- <strong>Açıklama kısmına <?=mset("site_adi")?> yazınız. Bu kısmı biz değil bankalar görür.</strong>
				<br>7- Devam yapın ve para gönderin.
				<strong><br>8- Para gönderikten sonra DESTEK BİLDİRİMİ açın.</strong>
				<br>9- Para gönderdikten sonra bunu destek bildirimdekiler hariç kimseyle para gönderdiğinizi paylaşmayın!<br>
			</div>
    <?php else: ?>
      <?php 
        if ($_POST) {
          $fiyat = isset($_POST["fiyat"])?post("fiyat"):"10";
          go_js(str_ireplace("%URL%",URL,$row["odeme_url"])."?fiyat=$fiyat");
        }
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label for="fiyat">Yükleme Miktarı:</label>
          <input type="number" class="form-control" id="fiyat" name="fiyat" placeholder="Yüklemek istediğiniz miktarı yazınız.">
        </div>
        <button type="submit" class="btn btn-block btn-success">Yükle</button>
      </form>
    <?php endif; ?>
  <?php else: ?>
    <?php go(URL."/kredi"); ?>
  <?php endif; ?>
<?php else: ?>
  <?php $s = query("SELECT * FROM odeme WHERE odeme_durum='1'"); ?>
  <?php while($row = row($s)): ?>
    <?php $json = json_decode($row["odeme_resp"],true); ?>
    <a href="<?=URL?>/kredi/<?=$row["odeme_slug"]?>" class="ui-btn ui-btn-b ui-corner-all"><?=$json["baslik"]?></a>
  <?php endwhile; ?>
<?php endif; ?>
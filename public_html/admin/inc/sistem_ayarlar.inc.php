<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<?php 
$cek = [
  "authme_sifre",
  "chrome_eklenti",
  "ssl",
  "hr",
  "send_method",
  "hr",
  "blog_limit",
  "ticket_limit",
  "hr",
  "admin_duyuru"
]; 
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
    <h3 class="block-title">Sistem Ayarlar</h3>
  </div>
  <div class="block-content">
    <?php
      foreach ($cek as $as) { $as .= $as; }$as=md5($as);
      if (isset($_POST[$as])) {
        $hata = "";$basari = "";
        foreach ($cek as $key) {
          if (isset($_POST["$key"])) {
            $varmi = query("SELECT * FROM ayar WHERE ayar_slug='$key'");
            if (rows($varmi) > 0) {
              $guncelle = query("UPDATE ayar SET ayar_deger='".post("$key")."' WHERE ayar_slug='$key'");
              if ($guncelle) {
                $basari = alert("Güncelleme işlemi başarıyla gerçekleşmiştir.","success");
                go(ANLIK_URL,2);
              }else{
                $hata .= alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
              }
            }else{
              $hata .= alert("Hata: $key adlı veri bulunamadı!");
            }
          }elseif ($key == "hr") {
            null;
          }else{
            $hata .= alert("Hata: $key adlı içerik boş bırakılamaz!");
          }
        }
        echo $basari.$hata;
      }
    ?>
    <form action="" method="post">
      <?php foreach ($cek as $key): ?>
        <?php $cek = query("SELECT * FROM ayar WHERE ayar_slug='$key' AND ayar_yapi!='NULL'"); ?>
        <?php if (rows($cek) > 0): ?>
          <?php $row = row($cek); $value = json_decode($row["ayar_yapi"],true); ?>
          <div class="form-group">
            <label for="<?=$key?>"><?=$value['label']?></label>
            
            <?php if ($value["type"] == "text" OR $value["type"] == "number"): ?>
              <input type="<?=$value["type"]?>" class="form-control" id="<?=$key?>" name="<?=$key?>" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>" value="<?=$row["ayar_deger"]?>">
            <?php endif; ?>
            
            <?php if ($value["type"] == "select"): ?>
              <select class="form-control" id="<?=$key?>" name="<?=$key?>" <?php if (isset($value["size"])): ?>size="<?=$value["size"]?>"<?php endif; ?>>
                <?php foreach ($value["select"] as $key2 => $value2): ?>
                  <option <?=($row["ayar_deger"] == $key2) ?'selected=""':null?> value="<?=$key2?>"><?=$value2?></option>
                <?php endforeach; ?>
              </select>
            <?php endif; ?>
            <?php if ($value["type"] == "textarea"): ?>
              <textarea id="<?=$key?>" name="<?=$key?>" rows="<?=isset($value['rows'])?$value['rows']:'4'?>" class="form-control" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>"><?=$row["ayar_deger"]?></textarea>
            <?php endif; ?>
            <?php if (isset($value["help"])): ?><p class="help-block"><?=$value["help"]?></p><?php endif; ?>
          </div>
          
        <?php endif; ?>
        <?php if ($key == "hr"): ?>
          <hr>
        <?php endif; ?>
      <?php endforeach; ?>
      <div class="form-group">
        <button type="submit" name="<?=$as?>" class="btn btn-block btn-success">Ayarları Güncelle</button>
      </div>
    </form>
  </div>
</div>
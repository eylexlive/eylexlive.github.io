<?php 
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<?php if (mset("deskop_theme") != mset("mobile_theme") AND !get("tema")): ?>
  <div class="row">
      <div class="col-sm-6">
          <a class="block block-rounded block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=ozellestir&tema=<?=mset("deskop_theme")?>">
              <div class="block-content block-content-full">
                  <div class="item item-circle push-10">
                      <i class="si si-screen-desktop text-default"></i>
                  </div>
                  <div class="h4">Masaüstü Tema</div>
                  <div class="h5 text-danger">( <?=mset("deskop_theme")?> )</div>
              </div>
          </a>
      </div>
      <div class="col-sm-6">
          <a class="block block-rounded block-link-hover3 text-center" href="<?=ADMIN_URL?>/?go=ozellestir&tema=<?=mset("mobile_theme")?>">
              <div class="block-content block-content-full">
                  <div class="item item-circle push-10">
                      <i class="si si-screen-smartphone text-default"></i>
                  </div>
                  <div class="h4">Mobil Tema</div>
                  <div class="h5 text-danger">( <?=mset("mobile_theme")?> )</div>
              </div>
          </a>
      </div>
  </div>
<?php else: ?>
  <div class="block block-bordered">
    <div class="block-header bg-gray-lighter">
      <h3 class="block-title">Tema Özelleştir</h3>
    </div>
    <div class="block-content">
      <?php 
        $tema = isset($_GET["tema"])?get("tema"):mset("deskop_theme"); $tema_s = $tema."_s";
        $q = query("SELECT * FROM ayar WHERE ayar_slug='$tema_s'");
      ?>
      <?php if (rows($q) < 1): ?>
        <div class="alert alert-warning">
          Bu temaya özel ayar bulunamadı.
        </div>
      <?php else: ?>
        <?php
          $row = row($q);

          $json = json_decode($row["ayar_deger"],true);
          $json_cvp = json_decode($row["ayar_format"],true);

          if (isset($_POST["guncelle"])) {
            foreach ($json as $key => $value) {
              $slug_resp[$value["name"]] = post($value["name"]);
            }
            $js = json_encode($slug_resp,JSON_UNESCAPED_UNICODE);
            $guncelle = query("UPDATE ayar SET ayar_format='$js' WHERE ayar_slug='$tema_s'");

            if ($guncelle) {
              echo alert("Güncelleme işlemi başarıyla gerçekleşmiştir.","success");
              go(ANLIK_URL,2);
            }else{
              echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
          }
        ?>
        <form action="" method="post">
          <?php foreach ($json as $key => $value): ?>
            <?php if ($value["type"] == "text"): ?>
              <div class="form-group">
                <label for="<?=$value['name']?>"><?=$value['label']?></label>
                <input type="text" class="form-control" id="<?=$value['name']?>" name="<?=$value['name']?>" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>" value="<?=$json_cvp[$value['name']]?>">
                <?php if (isset($value["help"])): ?>
                  <p class="help-block"><?=$value["help"]?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if ($value["type"] == "select"): ?>
              <div class="form-group">
                <label for="<?=$value['name']?>"><?=$value['label']?></label>
                <select class="form-control" id="<?=$value['name']?>" name="<?=$value['name']?>" <?php if ($value["size"]): ?>size="<?=$value["size"]?>"<?php endif; ?>>
                  <?php for ($i=0; $i < count($value['select']); $i++) { ?>
                    <option <?=($json_cvp[$value['name']] == $i) ?'selected=""':null?> value="<?=$i?>"><?=$value['select'][$i]?></option>
                  <?php } ?>
                </select>
                <?php if (isset($value["help"])): ?>
                  <p class="help-block"><?=$value["help"]?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if ($value["type"] == "textarea"): ?>
              <div class="form-group">
                <label for="<?=$value['name']?>"><?=$value['label']?></label>
                <textarea id="<?=$value['name']?>" name="<?=$value['name']?>" rows="<?=$value['rows']?>" class="form-control" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>"><?=$json_cvp[$value['name']]?></textarea>
                <?php if (isset($value["help"])): ?>
                  <p class="help-block"><?=$value["help"]?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
          <div class="form-group">
            <button type="submit" name="guncelle" value="guncelle" class="btn btn-block btn-success">Güncelle</button>
          </div>
        </form>
      <?php endif; ?>
      
    </div>
  </div>
<?php endif; ?>
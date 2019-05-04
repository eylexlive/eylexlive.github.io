<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<style>
    .menu_ekle>.block {
        margin-bottom: 0;
        border-bottom: 0;
    }
    .menu_ekle>.block:last-child {
        border-bottom: 1px solid #e9e9e9;
    }
</style>

<?php
if(isset($_GET['sid'])){
    $sid = get('sid');
    $varmi = query("SELECT * FROM menu WHERE menu_id = '$sid'");
    if(rows($varmi) < 1){
        go(ADMIN_URL.'/?go=menu');
        exit;
    }else{
        $delete = query("DELETE FROM menu WHERE menu_id = '$sid'");
        if($delete){
            echo alert('Başarıyla silindi. Yönlendiriliyorsunuz.','success');
            go(ADMIN_URL.'/?go=menu');
        }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
        }
    }
}
?>
<div class="row">
  <div class="col-md-6">
      <div class="block block-bordered">
          <div class="block-header bg-gray-lighter">
              <h3 class="block-title">Menü Düzen</h3>
          </div>
          <div class="block-content">
              <style type="text/css">
                  .dd {
                      position: relative;
                      display: block;
                      margin: 0;
                      padding: 0;
                      width: 100%;
                      list-style: none;
                      font-size: 13px;
                      line-height: 20px;
                      margin-bottom: 20px;
                  }

                  .dd-list {
                      display: block;
                      position: relative;
                      margin: 0;
                      padding: 0;
                      list-style: none;
                  }

                  .dd-list .dd-list {
                      padding-left: 30px;
                  }

                  .dd-collapsed .dd-list {
                      display: none;
                  }

                  .dd-item,
                  .dd-empty,
                  .dd-placeholder {
                      display: block;
                      position: relative;
                      margin: 0;
                      padding: 0;
                      min-height: 20px;
                      font-size: 13px;
                      line-height: 20px;
                  }

                  .dd-handle {
                      display: block;
                      min-height: 20px;
                      margin-top: 5px;
                      padding: 10px 15px;
                      text-decoration: none;
                      font-weight: bold;
                      border: 1px solid #ddd;
                      box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
                      background: #fafafa;
                      color: #23282d;
                  }

                  .dd-handle:hover {
                      border-color: #999;
                  }

                  .dd-placeholder,
                  .dd-empty {
                      margin: 5px 0;
                      padding: 0;
                      min-height: 30px;
                      border: 1px dashed #a5a5a5;
                      box-sizing: border-box;
                      -moz-box-sizing: border-box;
                  }

                  .dd-empty {
                      border: 1px dashed #bbb;
                      min-height: 100px;
                      background-color: #e5e5e5;
                      background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                      background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                      background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                      background-size: 60px 60px;
                      background-position: 0 0, 30px 30px;
                  }

                  .dd-dragel {
                      position: absolute;
                      pointer-events: none;
                      z-index: 9999;
                  }

                  .dd-dragel > .dd-item > .dd-handle {
                      margin-top: 0;
                  }

                  .dd-dragel .dd-handle {
                      -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
                      box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
                  }
                  @media only screen and (min-width: 700px) {
                      .dd + .dd {
                          margin-left: 2%;
                      }
                  }

                  .dd-hover > .dd-handle {
                      background: #2ea8e5 !important;
                  }
                  .dd-edit {
                      display: none;
                      padding: 10px 0 10px 10px;
                      position: relative;
                      z-index: 10;
                      border: 1px solid #e5e5e5;
                      border-top: none;
                      -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
                      box-shadow: 0 1px 1px rgba(0,0,0,.04);
                      background-color: white;
                  }
                  .item_edit {
                      color: black;
                      cursor: pointer;
                  }
                  .dd-type {
                      display: inline-block;
                      padding: 12px 16px;
                      color: #666;
                      font-size: 12px;
                      line-height: 18px;
                  }
                  .dd-controls {
                      font-size: 12px;
                      position: absolute;
                      right: 20px;
                      top: 0px;
                      cursor: default;
                      user-select: none;
                  }
              </style>
              
              <div class="dd">
                  <ol class="dd-list">
                      <?php

                      function menu($menu_name, $id = 0) {
                          $query = query("SELECT * FROM menu WHERE menu_slug='$menu_name' AND menu_ust_id='$id'");
                          if (rows($query)) {
                              while ($row = row($query)) { ?>
                                  <?php $json = json_decode($row["menu_json"],true); ?>
                                  <li class="dd-item" data-id="<?=$row["menu_id"]?>">
                                      <div class="dd-handle" <?php if ($row["menu_id"] == get("duzenle")): ?>style="background: #fef3e5;"<?php elseif ($row["menu_id"] == get("ekle")): ?>style="background: #e0f5e9;"<?php endif; ?>>
                                          <span class="dd-title"><?=$json["title"]?></span>
                                          <div class="dd-controls">
                                              <span class="dd-type"><a href="<?=ADMIN_URL?>/?go=menu&ekle=<?=$row["menu_id"]?>">Ekle</a></span>
                                              <a class="item_edit" href="<?=ADMIN_URL?>/?go=menu&duzenle=<?=$row["menu_id"]?>"><i class="fa fa-edit"></i></a>
                                              <a class="item_edit" onclick="SwSil('menu','<?=$row["menu_id"]?>','sid');"><i class="fa fa-trash-o"></i></a>
                                          </div>
                                      </div>
                                      <?php $sd = query("SELECT * FROM menu WHERE menu_slug='$menu_name' AND menu_ust_id='".$row["menu_id"]."'"); ?>
                                      <?php if (rows($sd)): ?>
                                          <ol class="dd-list">
                                              <?=menu($menu_name,$row["menu_id"]);?>
                                          </ol>
                                      <?php endif; ?>
                                  </li>
                                  <?php

                              }
                          }else{
                              return false;
                          }
                      }

                      menu("menu");
                      ?>
                  </ol>
              </div>

          </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="block block-bordered">
      <div class="block-header bg-gray-lighter">
        <h3 class="block-title">Menü İşlem <?=isset($_GET["duzenle"])?"[Düzenleme]":null?></h3>
      </div>
      <div class="block-content">
        <?php
          if (isset($_POST["gonder"])) {
            $url = post("url");
            $title = post("title");
            $icon = post("icon");
            $blank = isset($_POST["blank"])?"1":"0";
            
            if ($url AND $title) {
              $ust = isset($_GET["ekle"])?get("ekle"):"0";
              $json = json_encode(array("title" => $title,"url" => $url,"icon" => $icon,"blank" => $blank),JSON_UNESCAPED_UNICODE);
              $ekle = query("INSERT INTO menu SET
                menu_slug = 'menu',
                menu_json = '$json',
                menu_ust_id = '$ust'
              ");
              if ($ekle) {
                echo alert("Ekleme işlemi başarılı. Yönlendiriliyorsunuz...","success");
                go(ADMIN_URL."/?go=menu");
              }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
              }
            }else{
              echo alert("Lütfen boş alan bırakmayınız.","danger");
            }
          }
          
          if (isset($_POST["guncelle"])) {
            $url = post("url");
            $title = post("title");
            $icon = post("icon");
            $blank = isset($_POST["blank"])?"1":"0";
            $guncel = get("duzenle");
            
            if ($url AND $title) {
              $json = json_encode(array("title" => $title,"url" => $url,"icon" => $icon,"blank" => $blank),JSON_UNESCAPED_UNICODE);
              $ekle = query("UPDATE menu SET
                menu_slug = 'menu',
                menu_json = '$json'
              WHERE menu_id = '$guncel'");
              if ($ekle) {
                echo alert("Güncelleme işlemi başarılı. Yönlendiriliyorsunuz...","success");
                go(ANLIK_URL,1);
              }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
              }
            }else{
              echo alert("Lütfen boş alan bırakmayınız.","danger");
            }
          }
        ?>
        <?php if(isset($_GET["duzenle"])): ?>
          <?php
            $duzenle = get("duzenle");
            $wg = query("SELECT * FROM menu WHERE menu_id='$duzenle'");
            if (rows($wg) < 1) {
              go(ADMIN_URL."/?go=menu");
            }else{
              $row = row($wg);
              
              $js = json_decode($row["menu_json"],true);
            }
          ?>
          <form action="" method="post">
            <div class="form-group">
              <label for="url">URL (Link)</label>
              <input type="text" class="form-control" name="url" value="<?=$js["url"]?>">
              <div class="help-block">Side üzerinden link için <code>[URL]/link</code> şeklinde yazın.</div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">Bağlantı Metni</label>
                  <input type="text" class="form-control" name="title" value="<?=$js["title"]?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">Icon (Fontawesome)</label>
                  <input type="text" class="form-control" name="icon" value="<?=isset($js["icon"])?$js["icon"]:null?>" placeholder="Örnek: home">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="css-input css-checkbox css-checkbox-primary">
                <input type="checkbox" name="blank" <?php if ($js["blank"] == "1"): ?>checked<?php endif; ?>><span></span> Link Yeni Sekmede Açılsın
              </label>
            </div>
            <div class="form-group">
              <button type="submit" name="guncelle" value="guncelle" class="btn btn-block btn-primary">Güncelle</button>
              <a href="<?=ADMIN_URL?>/?go=menu" class="btn btn-block btn-danger">İptal Et</a>
            </div>
          </form>
        <?php else: ?>
          <form action="" method="post">
            <div class="form-group">
              <label for="url">URL (Link)</label>
              <input type="text" class="form-control" name="url">
              <div class="help-block">Side üzerinden link için <code>[URL]/link</code> şeklinde yazın.</div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">Bağlantı Metni</label>
                  <input type="text" class="form-control" name="title">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">Icon (Fontawesome)</label>
                  <input type="text" class="form-control" name="icon" placeholder="Örnek: home">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="css-input css-checkbox css-checkbox-primary">
                <input type="checkbox" name="blank"><span></span> Link Yeni Sekmede Açılsın
              </label>
            </div>
            <div class="form-group">
              <button type="submit" name="gonder" value="gonder" class="btn btn-block btn-primary">Menüye Ekle</button>
              <?php if (isset($_GET["ekle"])): ?>
                <a href="<?=ADMIN_URL?>/?go=menu" class="btn btn-block btn-danger">İptal Et</a>
              <?php endif; ?>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
    
    $items = json_decode(file_get_contents("assets/items/items.json"), true);
?>
<?php
  if(isset($_POST["urun_ekle"])){
    $urun_adi       = post('urun_adi');
    $urun_fiyat     = post('urun_fiyat');
    $urun_gun       = (post('urun_gun') == "")?"0":post('urun_gun');
    $urun_kategori  = post('urun_kategori');
    $urun_aciklama  = post('urun_aciklama',false);
    $urun_resim     = post("urun_resim");
    $urun_icon      = post("urun_icon");
    $urun_icon_id   = post("urun_icon_id");
    
    $bul = array("'",";","--","\*","#");
    $degistir = array(" "," "," "," "," ");
    $urun_komut = str_ireplace($bul,$degistir,$_POST['urun_komut']);
    $urun_komut_sil = str_ireplace($bul,$degistir,$_POST['urun_komut_sil']);
    
    if(!$urun_adi || !$urun_fiyat || !$urun_kategori || !$urun_icon || !$urun_icon_id){
      echo alert('Lütfen boş alan bırakmayınız.');
    }else if($urun_komut[0]== ''){
      echo alert('Ürünün düzgün bir şekilde çalışabilmesi için lütfen en az <strong>bir</strong> tane komut giriniz.');
    }else{
      $urun_komut = implode($urun_komut,'-/');
      $urun_komut_sil = implode($urun_komut_sil,'-/');
      $varmi = query("SELECT * FROM kategoriler_urun WHERE kategori_id = '$urun_kategori'");
      if(rows($varmi) < 1){
        echo alert('<strong>Kategori Bulunamadı!</strong>','danger');
      }else{
        $insert = query("INSERT INTO urunler SET
        urun_adi = '$urun_adi',
        urun_fiyat = '$urun_fiyat',
        urun_gun = '$urun_gun',
        urun_kategori = '$urun_kategori',
        urun_aciklama = '$urun_aciklama',
        urun_resim = '$urun_resim',
        urun_icon = '$urun_icon',
        urun_icon_id = '$urun_icon_id',
        urun_komut = '$urun_komut',
        urun_komut_sil = '$urun_komut_sil'");
        if($insert){
          echo alert('<strong>Ürün</strong> başarıyla eklendi.','success');
          go(ADMIN_URL."/?go=urunler",1);
        }else{
          echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
        }
      }
    }
  }
?>
<form class="" action="" method="post">
  
  <div class="row">
    <div class="col-md-5">
      <div class="block block-bordered">
        <div class="block-header bg-gray-lighter">Ürün Ekle</div>
        <div class="block-content">
          <div class="form-group">
            <label>Ürün Adı</label>
            <input type="text" name="urun_adi" class="form-control" placeholder="Örnek: Normal Vip" value="<?=isset($urun_adi)?$urun_adi:null;?>">
          </div>
          <div class="form-group">
            <label>Ürün Fiyatı</label>
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-fw fa-try"></i></span>
              <input type="number" name="urun_fiyat" class="form-control" placeholder="Örnek: 30" value="<?=isset($urun_fiyat)?$urun_fiyat:null;?>">
            </div>
          </div>
          <div class="form-group">
            <label>Ürün Süresi:</label>
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></span>
              <input type="number" min="0" name="urun_gun" class="form-control" placeholder="Örnek: 30" value="<?=isset($urun_gun)?$urun_gun:null;?>">
            </div>
            <div class="help-block">Gün cinsinden giriniz. Sınırsız olması için sıfır (0) sayısını girin.</div>
          </div>
          <div class="form-group">
            <label class="control-label">Kategori Seçimi</label>
            <select class="selectpicker form-control" data-live-search="true" name="urun_kategori">
            <?php
              $varmi = query("SELECT * FROM kategoriler_urun INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY kategori_id DESC");
              if(rows($varmi) < 1){
                echo '<option>Ürün için kategori eklenmemiş.</option>';
              }else{
                while($krow = row($varmi)){
                  echo '<option value="'.$krow['kategori_id'].'">'.ss($krow['kategori_adi']).' ['.ss($krow['sunucu_adi']).']</option>';
                }
              }
            ?>
            </select>
          </div>
          <div class="form-group">
            <label for="">Ürün Açıklama</label>
            <textarea name="urun_aciklama" id="summernote"><?=isset($urun_aciklama)?$urun_aciklama:null;?></textarea>
          </div>
          <hr>
          <div class="form-group row">
              <div class="col-xs-6"  style="padding-right: 7px;">
                  <div class="form-group">
                      <label>Ürün Resim</label>
                      <input name="urun_resim" type="text" class="form-control" placeholder="Gözükecek resim." value="<?=isset($urun_resim)?$urun_resim:null;?>">
                  </div>
              </div>
              <div class="col-xs-6"  style="padding-left: 7px;">
                  <div class="form-group">
                      <label>Ürün Icon</label>
                      <button id="sunucu_icon" type="button" data-toggle="modal" data-target="#modal-items" class="btn btn-block btn-default" style="overflow-x: auto;text-align: left"><img src="<?=ADMIN_URL?>/assets/items/1-0.png"> &nbsp;&nbsp;Stone</button>
                      <input type="hidden" id="sunucu_icon_hidden" name="urun_icon" value="stone">
                      <input type="hidden" id="sunucu_icon_id" name="urun_icon_id" value="1:0">
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-7">
        <script src="assets/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript">
          $(function(){
            $("#komutEkle").on('click',function(e){
              e.preventDefault();
              $("#komutlar tbody").append('<tr> <td><input type="text" name="urun_komut[]" class="form-control" /></td> <td><input type="text" name="urun_komut_sil[]" class="form-control" /></td> <td class="text-center" style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td></tr>');
            });
            $(".satirSil").live('click',function(e){
              var cevap = confirm('Bu satırı gerçekten silmek istiyor musunuz?');
              if(cevap){
                $(this).parent().parent().remove();
              }
            });
          })
        </script>
        <div class="block block-bordered">
          <div class="block-content">
            <div class="form-group">
              <a id="komutEkle" class="btn btn-default"><i class="fa fa-fw fa-plus"></i> Komut Ekle</a>
              <button type="submit" name="urun_ekle" value="urun_ekle" class="pull-right btn btn-success"><i class="fa fa-fw fa-check"></i> Kaydet</button>
            </div>
            <i class="fa fa-fw fa-caret-right"></i> Ürün eklerken oyuncu isimleri yerine <kbd>%player%</kbd> kelimesini kullanın.<br>
            <i class="fa fa-fw fa-caret-right"></i> Süre sınırı olmayan ürünlere lütfen süresi bitince çalışacak olan komutları eklemeyin. Boşuna veritabanında yer işgal etmesin.<br>
            <i class="fa fa-fw fa-caret-right"></i> Komutların başına <span class="label label-danger">/</span> (Slash) işareti koymayın. Komut çalışmaz.<br>
            <i class="fa fa-fw fa-caret-right"></i> Komutu yazarken <span class="label label-danger">-/</span> bu iki karakteri birleşik yazmayın. Tüm scripti bozabilirsiniz. <br>
          </div>
          <table id="komutlar" class="table table-striped table-advance table-hover" style="margin-bottom: 0">
            <thead>
              <tr>
                <th>Ürünü alınca Çalışacaklar</th>
                <th>Süresi Bitince Çalışacaklar</th>
                <th class="text-center">Sil</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="urun_komut[]" class="form-control" /></td>
                <td><input type="text" name="urun_komut_sil[]" class="form-control" /></td>
                <td class="text-center" style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
  </div>
</form>

<div class="modal fade" id="modal-items" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-popout mc-items">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary-dark">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Minecraft İtem Seçimi</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <input type="text" class="form-control" onkeyup="searchItems()" id="itemsInput" placeholder="Itemin adını veya ID'sini yazınız">
                    </div>

                    <ul class="list-group" id="myUL">
                        <?php foreach ($items as $key): ?>
                            <a class="list-group-item items-s" onclick="setItems('<?=$key["name"]?>','<?=$key["text_type"]?>','<?=$key["type"]?>','<?=$key["meta"]?>')" href="#" value="<?=$key["text_type"]?>"><img src="<?=ADMIN_URL?>/assets/items/<?=$key["type"]?>-<?=$key["meta"]?>.png"> &nbsp;&nbsp;<?=$key["name"]?> (<?=$key["type"]?>:<?=$key["meta"]?>)</a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <script>
                function searchItems() {
                    var input, filter, ul, li, a, i;
                    input = document.getElementById('itemsInput');
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("myUL");
                    li = ul.getElementsByTagName('a');

                    for (i = 0; i < li.length; i++) {
                        if (li[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                }
                function setItems(name,text_type,type,meta) {
                    jQuery('#modal-items').modal('hide');
                    $("#sunucu_icon").html("<img src='<?=ADMIN_URL?>/assets/items/"+type+"-"+meta+".png'> &nbsp;&nbsp;"+name);
                    $("#sunucu_icon_hidden").val(text_type);
                    $("#sunucu_icon_id").val(type+":"+meta);
                }
            </script>
        </div>
    </div>
</div>
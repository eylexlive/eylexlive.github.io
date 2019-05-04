<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<?php
if(isset($_POST["yazi_ekle"])){
    $yazi_baslik    = post('yazi_baslik');
    $yazi_resim     = post('yazi_resim');
    $yazi_link      = sef_link($yazi_baslik);
    $yazi_icerik    = post('yazi_icerik',false);
    $yazi_kategori  = post('yazi_kategori');
    $yazi_etiket    = post('yazi_etiket');
    $yazi_durum     = post('yazi_durum');

    $yazi_olusturan_id  = session("uye_id");
    $yazi_olusturan_ip  = GetIP();

    if(!$yazi_baslik || !$yazi_icerik){
        echo alert('Yazı için <strong>Yazı Başlığı</strong> ve <strong>Yazı İçeriği</strong> boş bırakılamaz.');
    }else{
        $varmi = query("SELECT * FROM yazi WHERE yazi_link = '$yazi_link' AND yazi_durum != '3'");
        if(rows($varmi)){
            echo alert('<strong>'.ss($yazi_baslik).'</strong> zaten başka bir yazının başlığı olarak kullanılıyor. Lütfen başka bir tane deneyiniz.');
        }else{
            if ($yazi_kategori == "false"){
                echo alert("Yazı için <b>Yazı Kategorisi</b> boş bırakılamaz.");
            }else{
                $insert = query("INSERT INTO yazi SET
                    yazi_baslik = '$yazi_baslik',
                    yazi_link = '$yazi_link',
                    yazi_resim = '$yazi_resim',
                    yazi_icerik = '$yazi_icerik',
                    yazi_kategori_id = '$yazi_kategori',
                    yazi_olusturan_id = '$yazi_olusturan_id',
                    yazi_olusturan_ip = '$yazi_olusturan_ip',
                    yazi_etiket = '$yazi_etiket',
                    yazi_durum = '$yazi_durum'");
                if($insert){
                    echo alert('Yazı başarıyla oluşturuldu. Yönlendiriliyorsunuz...', 'success');
                    go(ADMIN_URL.'?go=yazilar',2);
                }else{
                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                }
            }
        }
    }

}
?>
<form action="" method="post">
    <div class="row">
        <div class="col-md-8">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Yeni Yazı Ekle</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="yazi_baslik">Yazı Başlığı: </label>
                        <input type="text" class="form-control" name="yazi_baslik" id="yazi_baslik" placeholder="Yazı Başlığını Giriniz" value="<?=isset($yazi_baslik)?$yazi_baslik:null;?>">
                    </div>
                    <div class="form-group">
                        <textarea name="yazi_icerik" id="summernote"><?=isset($yazi_icerik)?$yazi_icerik:null;?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Yayınla</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="yazi_durum">Durum:</label>
                        <select name="yazi_durum" id="yazi_durum" class="form-control">
                            <option selected="" value="1">Yayınlanmış</option>
                            <option value="0">Yayınlanmamış (Gizli)</option>
                            <option value="2">Taslak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="yazi_ekle" value="yazi_ekle">Yazıyı Ekle</button>
                    </div>
                </div>
            </div>
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Seçenekler</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="yazi_durum">Kategori:</label>
                        <select name="yazi_kategori" class="form-control">
                            <?php
                            $kquery = query("SELECT * FROM kategoriler_yazi");
                            if(rows($kquery) < 1){
                                echo '<option value="false">Kategori oluşturmamışsınız.</option>';
                            }else{
                                while($krow = row($kquery)){
                                    echo '<option value="'.$krow['kategori_id'].'"';
                                    echo isset($yazi_kategori)?$yazi_kategori == $krow['kategori_id'] ? ' selected' : null:null;
                                    echo '>'.ss($krow["kategori_baslik"]).'</option>';
                                }
                            }
                            ?>
                        </select>
                        <div class="help-block">Yazıyı kategorize edebilirsin. Kategori oluşturmamışsan yazı ekleyemezsin.</div>
                    </div>
                    <div class="form-group">
                        <label for="">Etiket:</label>
                        <input class="form-control" type="text" id="tags-input" name="yazi_etiket" data-role="tagsinput" value="<?=isset($yazi_etiket)?$yazi_etiket:null;?>">
                    </div>
                </div>
            </div>
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Yazı Resmi</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="yazi_baslik">Resim Adresi: </label>
                        <input type="text" class="form-control" name="yazi_resim" id="yazi_resim" placeholder="Resim Linkini Giriniz (.png, .jpg, .gif)" value="<?=isset($yazi_resim)?$yazi_resim:null;?>">
                        <div class="help-block">Yazı Resmi özelliği kullandığınız tema içeriğine göre değişkenlik gösterebilir.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
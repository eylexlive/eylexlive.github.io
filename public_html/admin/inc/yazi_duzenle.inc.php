<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);

if(!isset($_GET['uid'])){
    go(ADMIN_URL.'/?go=yazilar');
    exit;
}else{
    $yid = get('uid');
    if(!$yid){
        go(ADMIN_URL.'/?go=yazilar');
        exit;
    }else{
        $varmi = query("SELECT * FROM yazi WHERE yazi_id = '$yid'");
        if(rows($varmi) < 1){
            go(ADMIN_URL.'/?go=yazilar');
            exit;
        }else{
            null;
        }
    }
}
?>
<?php
if(isset($_POST["yazi_duzenle"])){
    $yazi_baslik    = post('yazi_baslik');
    $yazi_resim     = post('yazi_resim');
    $yazi_link      = sef_link($yazi_baslik);
    $yazi_icerik    = post('yazi_icerik',false);
    $yazi_kategori  = post('yazi_kategori');
    $yazi_etiket    = post('yazi_etiket');
    $yazi_durum     = post('yazi_durum');

    $yazi_guncelleyen_id  = session("uye_id");
    $yazi_guncelleyen_ip  = GetIP();

    if(!$yazi_baslik || !$yazi_icerik){
        echo alert('Yazı için <strong>Yazı Başlığı</strong> ve <strong>Yazı İçeriği</strong> boş bırakılamaz.');
    }else{
        if ($yazi_kategori == "false"){
            echo alert("Yazı için <b>Yazı Kategorisi</b> boş bırakılamaz.");
        }else{
            $varmi = query("SELECT * FROM yazi WHERE yazi_link = '$yazi_link' AND yazi_id != '$yid' AND yazi_durum != '3'");
            if(rows($varmi)){
                echo alert('<strong>'.ss($yazi_baslik).'</strong> zaten başka bir yazının başlığı olarak kullanılıyor. Lütfen başka bir tane deneyiniz.');
            }else{
                $insert = query("UPDATE yazi SET
                    yazi_baslik = '$yazi_baslik',
                    yazi_link = '$yazi_link',
                    yazi_resim = '$yazi_resim',
                    yazi_icerik = '$yazi_icerik',
                    yazi_kategori_id = '$yazi_kategori',
                    yazi_guncelleyen_id = '$yazi_guncelleyen_id',
                    yazi_guncelleyen_ip = '$yazi_guncelleyen_ip',
                    yazi_etiket = '$yazi_etiket',
                    yazi_durum = '$yazi_durum'
                WHERE yazi_id = '$yid'");
                if($insert){
                    echo alert('Yazı başarıyla güncellendi. Yönlendiriliyorsunuz...', 'success');
                    go(ADMIN_URL.'?go=yazilar',2);
                }else{
                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                }
            }
        }
    }

}
$query = query("SELECT * FROM yazi INNER JOIN kategoriler_yazi ON kategoriler_yazi.kategori_id = yazi.yazi_kategori_id INNER JOIN uyeler ON uyeler.uye_id = yazi.yazi_olusturan_id WHERE yazi_id = '$yid'");
$yrow = row($query);
?>
<form action="" method="post">
    <div class="row">
        <div class="col-md-8">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Yazı Düzenle</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="yazi_baslik">Yazı Başlığı: </label>
                        <input type="text" class="form-control" name="yazi_baslik" id="yazi_baslik" placeholder="Yazı Başlığını Giriniz" value="<?php echo ss($yrow['yazi_baslik']); ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="yazi_icerik" id="summernote"><?php echo ss($yrow['yazi_icerik']); ?></textarea>
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
                            <option <?=($yrow["yazi_durum"] == 1)?"selected=''":null;?>  value="1">Yayınlanmış</option>
                            <option <?=($yrow["yazi_durum"] == 0)?"selected=''":null;?>  value="0">Yayınlanmamış (Gizli)</option>
                            <option <?=($yrow["yazi_durum"] == 2)?"selected=''":null;?>  value="2">Taslak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="yazi_duzenle" value="yazi_duzenle">Yazıyı Kaydet</button>
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
                                    echo $yrow['kategori_id'] == $krow['kategori_id'] ? ' selected' : null;
                                    echo '>'.ss($krow["kategori_baslik"]).'</option>';
                                }
                            }
                            ?>
                        </select>
                        <div class="help-block">Yazıyı kategorize edebilirsin. Kategori oluşturmamışsan yazı ekleyemezsin.</div>
                    </div>
                    <div class="form-group">
                        <label for="">Etiket:</label>
                        <input class="form-control" type="text" id="tags-input" name="yazi_etiket" data-role="tagsinput" value="<?=$yrow["yazi_etiket"]?>">
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
                        <input type="text" class="form-control" name="yazi_resim" id="yazi_resim" placeholder="Resim Linkini Giriniz (.png, .jpg, .gif)" value="<?php echo ss($yrow['yazi_resim']); ?>">
                        <div class="help-block">Yazı Resmi özelliği kullandığınız tema içeriğine göre değişkenlik gösterebilir.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);

    if(isset($_GET['sid'])){
        $sid = get('sid');
        if(!$sid){
            go(ADMIN_URL.'/?go=sayfalar');
            exit;
        }else{
            $varmi = query("SELECT * FROM sayfalar WHERE sayfa_id = '$sid'");
            if(rows($varmi) < 1){
                go(ADMIN_URL.'/?go=sayfalar');
                exit;
            }else{
                null;
            }
        }
    }else{
        go(ADMIN_URL.'/?go=sayfalar');
        exit;
    }
?>
<?php
if(isset($_POST["sayfa_duzenle"])){
    $sayfa_baslik = post('sayfa_baslik');
    $sayfa_link = sef_link($sayfa_baslik);
    $sayfa_icerik = post('sayfa_icerik',false);

    if(empty($sayfa_baslik)){
        echo alert('Sayfa için <strong>Sayfa Başlığı</strong> boş bırakılamaz.');
    }else{
        $varmi = query("SELECT * FROM sayfalar WHERE sayfa_link = '$sayfa_link' and sayfa_id != '$sid'");
        if(rows($varmi)){
            echo alert('<strong>'.ss($sayfa_baslik).'</strong> zaten başka bir sayfanın başlığı olarak kullanılıyor. Lütfen başka bir tane deneyiniz.');
        }else{
            $insert = query("UPDATE sayfalar SET
                sayfa_baslik = '$sayfa_baslik',
                sayfa_link = '$sayfa_link',
                sayfa_icerik = '$sayfa_icerik'
            WHERE sayfa_id = '$sid'");
            if($insert){
                echo alert('Sayfa başarıyla güncellendi. Yönlendiriliyorsunuz.', 'success');
                go(ADMIN_URL.'/?go=sayfalar',1);
            }else{
                echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
            }
        }
    }

}
$query = query("SELECT * FROM sayfalar WHERE sayfa_id = '$sid'");
$row = row($query);
?>

<div class="row">
    <form action="" method="post">
        <div class="col-md-8">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Sayfa Düzenle</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="sayfa_baslik">Sayfa Başlığı:</label>
                        <input type="text" class="form-control" name="sayfa_baslik" id="sayfa_baslik" placeholder="Sayfa Başlığını Giriniz" value="<?=$row["sayfa_baslik"]?>">
                    </div>
                    <div class="form-group">
                        <textarea name="sayfa_icerik" id="summernote"><?=$row["sayfa_icerik"]?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Kaydet</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="sayfa_duzenle" value="sayfa_duzenle">Sayfayı Kaydet</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
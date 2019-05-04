<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);

    if(isset($_POST["sayfa_ekle"])){
        $sayfa_baslik = post('sayfa_baslik');
        $sayfa_link = sef_link($sayfa_baslik);
        $sayfa_icerik = post('sayfa_icerik',false);

        if(!$sayfa_baslik){
            echo alert('Sayfa için <strong>Sayfa Başlığı</strong> boş bırakılamaz.');
        }else{
            $varmi = query("SELECT * FROM sayfalar WHERE sayfa_link = '$sayfa_link'");
            if(rows($varmi)){
                echo alert('<strong>'.ss($sayfa_baslik).'</strong> zaten başka bir sayfanın başlığı olarak kullanılıyor. Lütfen başka bir tane deneyiniz.');
            }else{
                $insert = query("INSERT INTO sayfalar SET
                    sayfa_baslik = '$sayfa_baslik',
                    sayfa_link = '$sayfa_link',
                    sayfa_icerik = '$sayfa_icerik'
                ");
                if($insert){
                    echo alert('Sayfa başarıyla eklendi. Yönlendiriliyorsunuz.', 'success');
                    go(ADMIN_URL.'/?go=sayfalar',1);
                }else{
                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                }
            }
        }

    }
?>
<div class="row">
    <form action="" method="post">
        <div class="col-md-8">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Yeni Sayfa Oluştur</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="sayfa_baslik">Sayfa Başlığı:</label>
                        <input type="text" class="form-control" name="sayfa_baslik" id="sayfa_baslik" placeholder="Sayfa Başlığını Giriniz" value="<?=isset($sayfa_baslik)?$sayfa_baslik:null;?>">
                    </div>
                    <div class="form-group">
                        <textarea name="sayfa_icerik" id="summernote"><?=isset($sayfa_icerik)?$sayfa_icerik:null;?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-bordered">
                <div class="block-header bg-gray-lighter">
                    <h3 class="block-title">Oluştur</h3>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="sayfa_ekle" value="sayfa_ekle">Yeni Sayfa Oluştur</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<div class="block block-bordered">
    <div class="block-header" style="border-bottom: 0">
        <h3 class="block-title" style="display: inline;">Temalar</h3>
        <div class="block-options-simple">
            <a href="#" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Ücretli/Ücretsiz Temalar">Tema Marketi</a>
        </div>
    </div>
</div>
<?php
    if (isset($_GET["y"]) AND isset($_GET["d"])) {
        $y = get("y");
        $d = get("d");
        $kaydet = array_update("ayar",array("ayar_deger"=>"$d"),"WHERE ayar_slug='$y'");
        if ($kaydet) {
            echo alert("Tema ayarları başarıyla kaydedilmiştir.","success");
            go(ADMIN_URL."/?go=temalar");
        }else{
            echo alert(mysqli_error($baglan),"danger");
        }
    }
?>
<div class="row">
    <?php
    $path = "../content/themes";
    $dizin = opendir($path);
    $error = "";
    while($dosya = readdir($dizin)) {
        if (is_dir("$path/$dosya") AND $dosya != "." AND $dosya != "..") {
            if (file_exists("$path/$dosya/theme.json")) {
                $file = file_get_contents("$path/$dosya/theme.json");
                $json = json_decode($file,true);
                $img = (file_exists("$path/$dosya/screenshot.png"))?"$path/$dosya/screenshot.png":ADMIN_URL."/assets/img/resim.png";
    ?>
                <div class="col-md-4 col-sm-6">
                    <div class="block block-bordered">
                        <div class="block-content" style="padding: 0;">
                            <img src="<?=$img?>" alt="<?=$json["name"]?>" class="img-responsive">
                        </div>
                        <div class="block-footer">
                            <?=$json["name"]?>

                            <div class="block-options-simple" style="margin: -6px 0 -3px 15px;">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?=ADMIN_URL?>/?go=temalar&y=deskop_theme&d=<?=$dosya?>" class="btn btn-<?=($dosya == mset("deskop_theme"))?"success":"danger";?>" style="width: 35px;" data-toggle="tooltip" data-placement="top" data-original-title="<?=($dosya == mset("deskop_theme"))?"Bilgisayar için Aktif":"Bilgisayar için Aktif Et";?>"><i class="fa fa-laptop"></i></a>
                                    <a href="<?=ADMIN_URL?>/?go=temalar&y=mobile_theme&d=<?=$dosya?>" class="btn btn-<?=($dosya == mset("mobile_theme"))?"success":"danger";?>" style="width: 35px;" data-toggle="tooltip" data-placement="top" data-original-title="<?=($dosya == mset("mobile_theme"))?"Mobil için Aktif":"Mobil için Aktif Et";?>"><i class="fa fa-mobile"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
            }else{
                $error .= "$dosya adlı tema klasöründe theme.json bulunamadı.<br>";
            }
        }
    }
    ?>
</div>
<?=($error != "")?alert($error."Detaylı bilgi ve çözüm için [TIKLAYIN]. "):null;?>
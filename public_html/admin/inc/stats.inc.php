<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<div class="row">
    <div class="col-md-6">
        <?php

        if (isset($_GET["sil"])) {
            $sil = get('sil');
            if(!$sil){
                go(ADMIN_URL.'/?go=stats');
                exit;
            }else{
                $query = query("SELECT * FROM stats WHERE stats_id = '$sil'");
                if(rows($query) < 1){
                    go(ADMIN_URL.'/?go=stats');
                    exit;
                }else{
                    $sil = query("DELETE FROM stats WHERE stats_id = '$sil'");
                    if ($sil) {
                        echo alert("Silme işlemi başarılı.", "success");
                        go(ADMIN_URL.'/?go=stats',1);
                    }else{
                        echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                    }
                }
            }
        }

        ?>
        <div class="block block-bordered">
            <div class="block-header bg-gray-lighter">Stats Listesi</div>
            <table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Stats Adı</th>
                    <th width="100"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $link = "stats";
                @$sayfa = get("sayfa") ? get("sayfa") : 1;
                $ksayisi = rows(query("SELECT * FROM stats"));
                $limit = 10;
                $ssayisi = ceil($ksayisi/$limit);
                $baslangic = $sayfa * $limit - $limit;
                if($ksayisi > $limit){
                    $query = query("SELECT * FROM stats ORDER BY stats_id DESC LIMIT $baslangic,$limit");
                }else{
                    $query = query("SELECT * FROM stats ORDER BY stats_id DESC");
                }
                if(rows($query) < 1){
                    echo '<tr><td class="text-center" colspan="3">Henüz hiç içerik eklememişsiniz.</td></tr>';
                }else{
                    while($row = row($query)){
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $row['stats_id']; ?></td>
                            <td><?php echo ss($row['stats_adi']); ?></td>
                            <td class="text-center">
                                <a href="<?php echo URL.'/stats/'.$row['stats_slug'].'/'; ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                <a href="<?php echo ADMIN_URL."/?go=stats&uid=".$row['stats_id']; ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                <a onclick="SwSil('stats','<?=$row["stats_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
        <?php if ($ksayisi > 0): ?>
            <nav class="text-center">
                <?php
                $sayfa_goster = 6;

                $en_az_orta = ceil($sayfa_goster/2);
                $en_fazla_orta = ($ssayisi+1) - $en_az_orta;

                $sayfa_orta = $sayfa;
                if($sayfa_orta < $en_az_orta) $sayfa_orta = $en_az_orta;
                if($sayfa_orta > $en_fazla_orta) $sayfa_orta = $en_fazla_orta;

                $sol_sayfalar = round($sayfa_orta - (($sayfa_goster-1) / 2));
                $sag_sayfalar = round((($sayfa_goster-1) / 2) + $sayfa_orta);

                if($sol_sayfalar < 1) $sol_sayfalar = 1;
                if($sag_sayfalar > $ssayisi) $sag_sayfalar = $ssayisi;
                ?>
                <ul class="pagination">
                    <?php if ($sayfa != 1): ?>
                        <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=1"><i class="fa fa-angle-double-left"></i></a></li>
                    <?php else: ?>
                        <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-left"></i></a></li>
                    <?php endif; ?>

                    <?php if ($sayfa != 1): ?>
                        <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa-1)?>"><i class="fa fa-angle-left"></i></a></li>
                    <?php else: ?>
                        <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-left"></i></a></li>
                    <?php endif; ?>

                    <?php
                    for($s = $sol_sayfalar; $s <= $sag_sayfalar; $s++) {
                        if($sayfa == $s) {
                            echo '<li class="active"><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                        } else {
                            echo '<li><a href="'.ADMIN_URL.'/?go='.$link.'&sayfa='.$s.'">'.$s.'</a></li>';
                        }
                    }
                    ?>
                    <?php if ($sayfa != $ssayisi): ?>
                        <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=($sayfa+1)?>"><i class="fa fa-angle-right"></i></a></li>
                    <?php else: ?>
                        <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-right"></i></a></li>
                    <?php endif; ?>

                    <?php if ($sayfa != $ssayisi): ?>
                        <li><a href="<?=ADMIN_URL?>/?go=<?=$link?>&sayfa=<?=$ssayisi?>"><i class="fa fa-angle-double-right"></i></a></li>
                    <?php else: ?>
                        <li class="disabled"><a href="javascript:void(0)"><i class="fa fa-angle-double-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <?php if (isset($_GET["uid"])) { ?>
            <?php
            if(isset($_GET['uid']) && !empty($_GET['uid'])){
                $uid = get('uid');
                if(!$uid){
                    go(ADMIN_URL.'/?go=stats');
                    exit;
                }else{
                    $query = query("SELECT * FROM stats WHERE stats_id = '$uid'");
                    if(rows($query) < 1){
                        go(ADMIN_URL.'/?go=stats');
                        exit;
                    }else{
                        if ($_POST) {
                            $stats_adi = post("stats_adi");
                            $stats_link = sef_link($stats_adi);
                            $stats_host = post("stats_host");
                            $stats_user = post("stats_user");
                            $stats_pass = post("stats_pass");
                            $stats_db = post("stats_db");
                            $stats_table = post("stats_table");
                            $stats_limit = post("stats_limit");
                            $stats_siralama = post("stats_siralama");
                            
                            if ($stats_adi && $stats_host && $stats_user && $stats_pass && $stats_db && $stats_table && $stats_limit) {
                                $varmi = query("SELECT * FROM stats WHERE stats_slug = '$stats_link' AND stats_id != '$uid'");
                                if(rows($varmi) > 0){
                                    echo alert('<strong>'.ss($stats_adi).'</strong> adını zaten başka bir tablo kullanıyor. Lütfen başka bir tane dene.');
                                }else {
                                    $title = $_POST["title"];
                                    $slug = $_POST["slug"];
                                    $build = $_POST["build"];
                                    if ($title[0] != "" AND $slug[0] != "") {
                                        $array = array('settings' => array('mysql' => array('host' => $stats_host, 'user' => $stats_user, 'pass' => $stats_pass, 'db' => $stats_db, 'table' => $stats_table), 'limit' => $stats_limit, 'siralama' => $stats_siralama), 'table' => array());

                                        $sayi = 0;
                                        foreach ($slug as $key) {
                                            $build2 = ($build[$sayi] != "") ? $build[$sayi] : "[GET]";
                                            $array["table"][$key] = array(
                                                "title" => $title[$sayi],
                                                "build" => addslashes($build2)
                                            );
                                            $sayi++;
                                        }
                                        $array = json_encode($array, JSON_UNESCAPED_UNICODE);

                                        $insert = query("UPDATE stats SET
                                            stats_adi = '$stats_adi',
                                            stats_slug = '$stats_link',
                                            stats_json = '$array'
                                        WHERE stats_id = '$uid'");
                                        if($insert){
                                            echo alert('<strong>Stats Tablosu</strong> başarıyla güncellendi.', 'success');
                                            go(ADMIN_URL."/?go=stats",1);
                                        }else{
                                            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                        }
                                    } else {
                                        echo alert("Sistemin düzgün çalışması için, lütfen en az bir tane Tablo Satırı giriniz.");
                                    }
                                }
                            }else{
                                echo alert("Lütfen boş alan bırakmayınız!");
                            }
                        }

                        $row = row($query);
                        $jrow = json_decode($row["stats_json"],true);
                        echo alert('<strong>'.ss($row['stats_adi']).'</strong> adlı Stats verilerini düzenliyorsunuz.','info');
                    }
                }
            }
            ?>
            <form action="" method="post">
                <div class="block block-bordered">
                    <div class="block-header bg-gray-lighter">
                        <h3 class="block-title">Düzenle [<?=$row['stats_adi']?>]</h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label>Stats Adı:</label>
                            <input name="stats_adi" type="text" class="form-control" placeholder="Belirleyici olan isim." value="<?=$row['stats_adi']?>">
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mysql Host:</label>
                                    <input name="stats_host" type="text" class="form-control" placeholder="Mysql bağlantısının host adresi." value="<?=$jrow["settings"]["mysql"]["host"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mysql K. Adı:</label>
                                    <input name="stats_user" type="text" class="form-control" placeholder="Mysql username (Kullanıcı Adı)" value="<?=$jrow["settings"]["mysql"]["user"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Mysql Şifre:</label>
                                    <input name="stats_pass" type="text" class="form-control" placeholder="Mysql password (Şifre)" value="<?=$jrow["settings"]["mysql"]["pass"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Mysql Database:</label>
                                    <input name="stats_db" type="text" class="form-control" placeholder="Mysql database (Tablonun olduğu database.)" value="<?=$jrow["settings"]["mysql"]["db"]?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Tablo Adı:</label>
                                    <input name="stats_table" type="text" class="form-control" placeholder="Tablo adı (Örnek: skywarsStats)" value="<?=$jrow["settings"]["mysql"]["table"]?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Satır Limiti:</label>
                                    <input name="stats_limit" type="number" class="form-control" placeholder="Kaç satır veri gösterceği." value="<?=$jrow["settings"]["limit"]?>">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Sıralama:</label>
                            <input name="stats_siralama" type="text" class="form-control" placeholder="Sıralama yapılacak satırın adı (Örnek: wins)" value="<?=isset($jrow["settings"]["siralama"])?$jrow["settings"]["siralama"]:null?>">
                            <div class="help-block">Bu alanı boş bırakabilirsiniz. Boş bırakırsanız rastgele sıralama yapacaktır.</div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modal-tablo" type="button">Tablo Ayarları</button>
                            <button class="btn btn-primary" style="float: right;" type="submit">Kaydet</button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-tablo" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent remove-margin-b">
                                <div class="block-header bg-primary-dark">
                                    <ul class="block-options">
                                        <li>
                                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">Tablo Ayarları & Düzenleme</h3>
                                </div>
                                <div class="block-content">
                                    <button class="btn btn-warning btn-block" id="satirEkle">Satır Ekle</button>
                                </div><hr style="margin-bottom: 0">
                                <script>
                                    $(function(){
                                        $("#satirEkle").on('click',function(e){
                                            e.preventDefault();
                                            $("#veriler tbody").append('<tr><td><input type="text" name="title[]" class="form-control" placeholder="Tabloda gözükecek olan başlık."></td><td><input type="text" name="slug[]" class="form-control" placeholder="Mysqlde yazan sütun adı."></td><td><input type="text" name="build[]" class="form-control" placeholder="Gözükecek satırın düzeni."></td><td class="text-center"  style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td></tr>');
                                        });
                                        $(".satirSil").live('click',function(e){
                                            var cevap = confirm('Bu satırı gerçekten silmek istiyor musunuz?');
                                            if(cevap){
                                                $(this).parent().parent().remove();
                                            }
                                        });
                                    });
                                </script>
                                <table id="veriler" class="table table-striped table-advance table-hover" style="margin-bottom: 0">
                                    <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Sütun Adı</th>
                                        <th>Satır Düzeni</th>
                                        <th class="text-center">Sil</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($jrow["table"] as $key=>$value) { ?>
                                    <tr>
                                        <td><input type="text" name="title[]" class="form-control" placeholder="Tabloda gözükecek olan başlık." value="<?=$value["title"]?>"></td>
                                        <td><input type="text" name="slug[]" class="form-control" placeholder="Mysqlde yazan sütun adı." value="<?=$key?>"></td>
                                        <td><input type="text" name="build[]" class="form-control" placeholder="Gözükecek satırın düzeni." value="<?=htmlspecialchars($value["build"])?>"></td>
                                        <td class="text-center" style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php }else{ ?>
            <?php

                if ($_POST) {
                    $stats_adi = post("stats_adi");
                    $stats_link = sef_link($stats_adi);
                    $stats_host = post("stats_host");
                    $stats_user = post("stats_user");
                    $stats_pass = post("stats_pass");
                    $stats_db = post("stats_db");
                    $stats_table = post("stats_table");
                    $stats_limit = post("stats_limit");
                    $stats_siralama = post("stats_siralama");
                    
                    if ($stats_adi && $stats_host && $stats_user && $stats_pass && $stats_db && $stats_table && $stats_limit) {
                        $varmi = query("SELECT * FROM stats WHERE stats_slug = '$stats_link'");
                        if(rows($varmi) > 0){
                            echo alert('<strong>'.ss($stats_adi).'</strong> adını zaten başka bir tablo kullanıyor. Lütfen başka bir tane dene.');
                        }else {
                            $title = $_POST["title"];
                            $slug = $_POST["slug"];
                            $build = post("build");
                            if ($title[0] != "" AND $slug[0] != "") {
                                $array = array('settings' => array('mysql' => array('host' => $stats_host, 'user' => $stats_user, 'pass' => $stats_pass, 'db' => $stats_db, 'table' => $stats_table), 'limit' => $stats_limit, 'siralama' => $stats_siralama), 'table' => array());

                                $sayi = 0;
                                foreach ($slug as $key) {
                                    $build2 = isset($build[$sayi]) ? $build[$sayi] : "[GET]";
                                    $array["table"][$key] = array(
                                        "title" => $title[$sayi],
                                        "build" => addslashes($build2)
                                    );
                                    $sayi++;
                                }
                                $array = json_encode($array, JSON_UNESCAPED_UNICODE);

                                $insert = query("INSERT INTO stats SET
                                    stats_adi = '$stats_adi',
                                    stats_slug = '$stats_link',
                                    stats_json = '$array'
                                ");
                                if($insert){
                                    echo alert('<strong>Stats Tablosu</strong> başarıyla eklendi.', 'success');
                                    go(ADMIN_URL."/?go=stats",1);
                                }else{
                                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                }
                            } else {
                                echo alert("Sistemin düzgün çalışması için, lütfen en az bir tane Tablo Satırı giriniz.");
                            }
                        }
                    }else{
                        echo alert("Lütfen boş alan bırakmayınız!");
                    }
                }

            ?>
            <form action="" method="post">
                <div class="block block-bordered">
                    <div class="block-header bg-gray-lighter">
                        <h3 class="block-title">Yeni Ekle</h3>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label>Stats Adı:</label>
                            <input name="stats_adi" type="text" class="form-control" placeholder="Belirleyici olan isim.">
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mysql Host:</label>
                                    <input name="stats_host" type="text" class="form-control" placeholder="Mysql bağlantısının host adresi.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mysql K. Adı:</label>
                                    <input name="stats_user" type="text" class="form-control" placeholder="Mysql username (Kullanıcı Adı)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Mysql Şifre:</label>
                                    <input name="stats_pass" type="text" class="form-control" placeholder="Mysql password (Şifre)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Mysql Database:</label>
                                    <input name="stats_db" type="text" class="form-control" placeholder="Mysql database (Tablonun olduğu database.)">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Tablo Adı:</label>
                                    <input name="stats_table" type="text" class="form-control" placeholder="Tablo adı (Örnek: skywarsStats)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label>Satır Limiti:</label>
                                    <input name="stats_limit" type="number" class="form-control" placeholder="Kaç satır veri gösterceği.">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Sıralama:</label>
                            <input name="stats_siralama" type="text" class="form-control" placeholder="Sıralama yapılacak satırın adı (Örnek: wins)">
                            <div class="help-block">Bu alanı boş bırakabilirsiniz. Boş bırakırsanız rastgele sıralama yapacaktır.</div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modal-tablo" type="button">Tablo Ayarları</button>
                            <button class="btn btn-primary" style="float: right;" type="submit">Kaydet</button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-tablo" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent remove-margin-b">
                                <div class="block-header bg-primary-dark">
                                    <ul class="block-options">
                                        <li>
                                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">Tablo Ayarları & Düzenleme</h3>
                                </div>
                                <div class="block-content">
                                    <button class="btn btn-warning btn-block" id="satirEkle">Satır Ekle</button>
                                </div><hr style="margin-bottom: 0">
                                <script>
                                    $(function(){
                                        $("#satirEkle").on('click',function(e){
                                            e.preventDefault();
                                            $("#veriler tbody").append('<tr><td><input type="text" name="title[]" class="form-control" placeholder="Tabloda gözükecek olan başlık."></td><td><input type="text" name="slug[]" class="form-control" placeholder="Mysqlde yazan sütun adı."></td><td><input type="text" name="build[]" class="form-control" placeholder="Gözükecek satırın düzeni."></td><td class="text-center"  style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td></tr>');
                                        });
                                        $(".satirSil").live('click',function(e){
                                            var cevap = confirm('Bu satırı gerçekten silmek istiyor musunuz?');
                                            if(cevap){
                                                $(this).parent().parent().remove();
                                            }
                                        });
                                    });
                                </script>
                                <table id="veriler" class="table table-striped table-advance table-hover" style="margin-bottom: 0">
                                    <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Sütun Adı</th>
                                        <th>Satır Düzeni</th>
                                        <th class="text-center">Sil</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><input type="text" name="title[]" class="form-control" placeholder="Tabloda gözükecek olan başlık."></td>
                                        <td><input type="text" name="slug[]" class="form-control" placeholder="Mysqlde yazan sütun adı."></td>
                                        <td><input type="text" name="build[]" class="form-control" placeholder="Gözükecek satırın düzeni."></td>
                                        <td class="text-center" style="padding: 18px 10px;"><a href="javascript:" class="satirSil btn btn-danger btn-xs"><i class="fa fa-fw fa-trash-o"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</div>
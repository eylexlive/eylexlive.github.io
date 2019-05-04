<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="row">
    <div class="col-md-4">
        <div class="block block-bordered">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Destek Kategori <?php echo isset($_GET['kid']) ? 'Düzenle' : 'Ekle'; ?></h3>
            </div>
            <div class="block-content">
                <form action="" method="post">
                    <?php
                    if(isset($_GET["kid"]) && get('kid') != ""){
                        $kid = get('kid');
                        $query = query("SELECT * FROM kategoriler_ticket WHERE kategori_id = '$kid'");
                        if(rows($query) < 1){
                            echo alert('Seçtiğiniz kategori bulunamadı.','danger');
                        }else{
                            if(isset($_POST['kategori_duzenle'])){
                                $kategori_adi = post('kategori_adi');
                                $kategori_durum = post("kategori_durum");
                                if(!$kategori_adi){
                                    echo alert('Kategori adını lütfen boş bırakmayınız.');
                                }else{
                                    $query = query("SELECT * FROM kategoriler_ticket WHERE kategori_adi = '$kategori_adi' and kategori_id != '$kid'");
                                    if(rows($query) < 1){
                                        $insert = query("UPDATE kategoriler_ticket SET
                      										kategori_adi = '$kategori_adi',
                      										kategori_durum ='$kategori_durum'
                      										WHERE kategori_id = '$kid'");
                                        if($insert){
                                            echo alert('Kategori güncelleme işlemi başarılı. Yönlendiriliyorsunuz.','success');
                                            go(ANLIK_URL,1);
                                        }else{
                                            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                        }
                                    }else{
                                        echo alert('<strong>'.$kategori_adi.'</strong> adlı bir kategori zaten mevcut. Lütfen başka bir tane deneyin.');
                                    }
                                }
                            }
                            $row = row($query);
                            ?>
                            <div class="alert alert-info"><strong><?php echo ss($row['kategori_adi']); ?></strong> adlı kategoriyi düzenliyorsunuz.</div>
                            <div class="form-group">
                                <label>Kategori Adı:</label>
                                <input type="text" name="kategori_adi" id="" class="form-control" placeholder="Kategori adı yazınız." value="<?php echo ss($row['kategori_adi']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="kategori_durum">Kategori Durum:</label>
                                <select class="form-control" name="kategori_durum">
                                  <option <?=($row['kategori_durum'] == "1")?"selected":null?> value="1">Aktif</option>
                                  <option <?=($row['kategori_durum'] == "0")?"selected":null?> value="0">Aktif Değil</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="kategori_duzenle" class="btn btn-primary btn-block">Kategori Güncelle</button>
                                <a href="<?=ADMIN_URL?>/?go=destek_kategori" class="btn btn-danger btn-block">Düzenlemeyi İptal Et</a>
                            </div>
                            <?php
                        }
                    }else{
                        if(isset($_POST['kategori_ekle'])){
                            $kategori_adi = post('kategori_adi');
                            $kategori_durum = post("kategori_durum");
                            if(!$kategori_adi){
                                echo alert('Kategori adını lütfen boş bırakmayınız.');
                            }else{
                                $query_v = query("SELECT * FROM kategoriler_ticket WHERE kategori_adi = '$kategori_adi'");
                                if(rows($query_v) < 1){
                                    $insert = query("INSERT INTO kategoriler_ticket SET
                  										kategori_adi = '$kategori_adi',
                  										kategori_durum = '$kategori_durum'");
                                    if($insert){
                                        echo alert('Kategori ekleme işlemi başarılı.','success');
                                        go(ADMIN_URL."/?go=destek_kategori",1);
                                    }else{
                                        echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                    }
                                }else{
                                    echo alert('<strong>'.$kategori_adi.'</strong> adlı bir kategori zaten mevcut. Lütfen başka bir tane deneyin.');
                                }
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="kategori_adi">Kategori Adı:</label>
                            <input type="text" name="kategori_adi" id="kategori_adi" class="form-control" placeholder="Kategori Adı" />
                        </div>
                        <div class="form-group">
                            <label for="kategori_durum">Kategori Durum:</label>
                            <select class="form-control" name="kategori_durum">
                              <option value="1">Aktif</option>
                              <option value="0">Aktif Değil</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="kategori_ekle" class="btn btn-primary btn-block">Kategori Ekle</button>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?php
        if(isset($_GET['sid'])){
            $sid = get('sid');
            $varmi = query("SELECT * FROM kategoriler_ticket WHERE kategori_id = '$sid'");
            if(rows($varmi) < 1){
                go(ADMIN_URL."/?go=destek_kategori");
                exit;
            }else{
                $delete = query("DELETE FROM kategoriler_ticket WHERE kategori_id = '$sid'");
                $delete_k = query("DELETE FROM ticketler WHERE ticket_kategori = '$sid'");
                if($delete AND $delete_k){
                    echo alert('Kategori başarıyla silindi. Yönlendiriliyorsunuz.','success');
                    go(ADMIN_URL."/?go=destek_kategori",1);
                }else{
                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                }
            }
        }
        ?>
        <div class="block block-bordered" style="margin-bottom: 0">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Kategoriler</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" style="margin-bottom: 0">
                    <thead>
                    <tr>
                        <th>Kategori Adı</th>
                        <th class="text-center">Kategori Durum</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        $link = "destek_kategori";
              					@$sayfa = get("sayfa") ? get("sayfa") : 1;
              					$ksayisi = rows(query("SELECT * FROM kategoriler_ticket"));
              					$limit = 10;
              					$ssayisi = ceil($ksayisi/$limit);
              					$baslangic = $sayfa * $limit - $limit;
              					if($ksayisi > $limit){
              						$query = query("SELECT * FROM kategoriler_ticket ORDER BY kategori_id DESC LIMIT $baslangic,$limit");
              					}else{
              						$query = query("SELECT * FROM kategoriler_ticket ORDER BY kategori_id DESC");
              					}
              					if(rows($query) < 1){
              						echo '<tr><td class="text-center" colspan="4">Henüz hiç Kategori eklememişsiniz.</td></tr>';
              					}else{
              						while($row = row($query)){
              				?>
                        <tr>
                          <td><a href="<?=ADMIN_URL?>/?go=destek_kategori&kid=<?=$row['kategori_id']?>"><?=ss($row['kategori_adi'])?></a></td>
                          
                          <td class="text-center"><?=($row['kategori_durum'] == "1")?'<span class="label label-success">Aktif</span>':'<span class="label label-danger">Deaktif</span>'?></td>
                          <td width="80px">
                              <a href="<?=ADMIN_URL?>/?go=destek_kategori&kid=<?=$row['kategori_id']?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                              <a href="#" onclick="SwSil('destek_kategori','<?=$row["kategori_id"]?>','sid',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php } } ?>
                    </tbody>
                </table>
            </div>
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
</div>
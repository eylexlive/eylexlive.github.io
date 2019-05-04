<?php
  defined("ADMIN") ? null: die('Hacking?');
  (($user["uye_rutbe"] == 1) OR ($user["uye_rutbe"] == 2)) ? null: go(ADMIN_URL);
?>
<div class="row">
    <div class="col-md-6">
        <div class="block block-bordered">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Hazır Cevap <?php echo isset($_GET['kid']) ? 'Düzenle' : 'Ekle'; ?></h3>
            </div>
            <div class="block-content">
                <form action="" method="post">
                    <?php
                    if(isset($_GET["kid"]) && get('kid') != ""){
                        $kid = get('kid');
                        $query = query("SELECT * FROM ticket_cevap WHERE cevap_id = '$kid'");
                        if(rows($query) < 1){
                            echo alert('Seçtiğiniz Hızlı Cevap bulunamadı.','danger');
                        }else{
                            if(isset($_POST['cevap_duzenle'])){
                                $cevap_adi = post('cevap_adi');
                                $cevap_yazi = post("cevap_yazi");
                                if(!$cevap_adi OR !$cevap_yazi){
                                    echo alert('Lütfen boş alan bırakmayınız');
                                }else{
                                  $queryG = query("SELECT * FROM ticket_cevap WHERE cevap_adi = '$cevap_adi' AND cevap_id != '$kid'");
                                  if(rows($queryG) < 1){
                                      $insert = query("UPDATE ticket_cevap SET
                    										cevap_adi = '$cevap_adi',
                    										cevap_yazi ='$cevap_yazi'
                    										WHERE cevap_id = '$kid'");
                                      if($insert){
                                          echo alert('Hızlı Cevap güncelleme işlemi başarılı. Yönlendiriliyorsunuz.','success');
                                          go(ANLIK_URL,1);
                                      }else{
                                          echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                      }
                                  }else{
                                      echo alert('<strong>'.$kategori_adi.'</strong> adlı bir Hızlı Cevap zaten mevcut. Lütfen başka bir tane deneyin.');
                                  }
                                }
                            }
                            $row = row($query);
                            ?>
                            <div class="alert alert-info"><strong><?php echo ss($row['cevap_adi']); ?></strong> adlı Cevabı düzenliyorsunuz.</div>
                            <div class="form-group">
                                <label for="cevap_adi">Cevap Adı:</label>
                                <input type="text" name="cevap_adi" id="cevap_adi" class="form-control" placeholder="Örnek: Hile Bildirim" value="<?php echo ss($row['cevap_adi']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="cevap_yazi">Cevap Yazı:</label>
                                <textarea name="cevap_yazi" id="cevap_yazi" rows="5" class="form-control"><?php echo ss($row['cevap_yazi']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="cevap_duzenle" class="btn btn-primary btn-block">Cevap Güncelle</button>
                                <a href="<?=ADMIN_URL?>/?go=destek_cevap" class="btn btn-danger btn-block">Düzenlemeyi İptal Et</a>
                            </div>
                            <?php
                        }
                    }else{
                        if(isset($_POST['cevap_ekle'])){
                            $cevap_adi = post('cevap_adi');
                            $cevap_yazi = post("cevap_yazi");
                            if(!$cevap_adi OR !$cevap_yazi){
                                echo alert('Lütfen boş alan bırakmayınız');
                            }else{
                                $query_v = query("SELECT * FROM ticket_cevap WHERE cevap_adi = '$cevap_adi'");
                                if(rows($query_v) < 1){
                                    $insert = query("INSERT INTO ticket_cevap SET
                  										cevap_adi = '$cevap_adi',
                  										cevap_yazi = '$cevap_yazi'");
                                    if($insert){
                                        echo alert('Hızlı Cevap ekleme işlemi başarılı.','success');
                                        go(ADMIN_URL."/?go=destek_cevap",1);
                                    }else{
                                        echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                                    }
                                }else{
                                    echo alert('<strong>'.$cevap_adi.'</strong> adlı bir Hızlı Cevap zaten mevcut. Lütfen başka bir tane deneyin.');
                                }
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="cevap_adi">Cevap Adı:</label>
                            <input type="text" name="cevap_adi" id="cevap_adi" class="form-control" placeholder="Örnek: Hile Bildirim">
                        </div>
                        <div class="form-group">
                            <label for="cevap_yazi">Cevap Yazı:</label>
                            <textarea name="cevap_yazi" id="cevap_yazi" rows="5" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="cevap_ekle" class="btn btn-primary btn-block">Cevap Ekle</button>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php
        if(isset($_GET['sid'])){
            $sid = get('sid');
            $varmi = query("SELECT * FROM ticket_cevap WHERE cevap_id = '$sid'");
            if(rows($varmi) < 1){
                go(ADMIN_URL."/?go=destek_cevap");
                exit;
            }else{
                $delete = query("DELETE FROM ticket_cevap WHERE cevap_id = '$sid'");
                if($delete){
                    echo alert('Hızlı Cevap başarıyla silindi. Yönlendiriliyorsunuz.','success');
                    go(ADMIN_URL."/?go=destek_cevap",1);
                }else{
                    echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
                }
            }
        }
        ?>
        <div class="block block-bordered" style="margin-bottom: 0">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Hızlı Cevaplar</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover" style="margin-bottom: 0">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Hızlı Cevap Adı</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        $link = "destek_cevap";
              					@$sayfa = get("sayfa") ? get("sayfa") : 1;
              					$ksayisi = rows(query("SELECT * FROM ticket_cevap"));
              					$limit = 10;
              					$ssayisi = ceil($ksayisi/$limit);
              					$baslangic = $sayfa * $limit - $limit;
              					if($ksayisi > $limit){
              						$query = query("SELECT * FROM ticket_cevap ORDER BY cevap_id DESC LIMIT $baslangic,$limit");
              					}else{
              						$query = query("SELECT * FROM ticket_cevap ORDER BY cevap_id DESC");
              					}
              					if(rows($query) < 1){
              						echo '<tr><td class="text-center" colspan="4">Henüz hiç içerik eklememişsiniz.</td></tr>';
              					}else{
              						while($row = row($query)){
              				?>
                        <tr>
                          <td class="text-center"><?=$row['cevap_id']?></td>
                          <td><a href="<?=ADMIN_URL?>/?go=destek_cevap&kid=<?=$row['cevap_id']?>"><?=ss($row['cevap_adi'])?></a></td>
                          <td width="80px">
                              <a href="<?=ADMIN_URL?>/?go=destek_cevap&kid=<?=$row['cevap_id']?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                              <a href="#" onclick="SwSil('destek_cevap','<?=$row["cevap_id"]?>','sid',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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
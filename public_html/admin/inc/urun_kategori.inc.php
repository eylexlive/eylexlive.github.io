<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);

    $items = json_decode(file_get_contents("assets/items/items.json"), true);
?>
<div class="row">
	<div class="col-md-6">
		<?php if (isset($_GET["suid"])) { ?>
			<?php 
				if(isset($_GET['suid']) && !empty($_GET['suid'])){
					$suid = get('suid');
					if(!$suid){
						go(ADMIN_URL.'/?go=urun_kategori');
						exit;
					}else{
						$query = query("SELECT * FROM kategoriler_urun WHERE kategori_id = '$suid'");
						if(rows($query) < 1){
							go(ADMIN_URL.'/?go=urun_kategori');
							exit;
						}else{
							if(!empty($_POST['sunucu_duzenle'])){
                $kategori_adi = post("kategori_adi");
        				$kategori_link = sef_link($kategori_adi);
        				$kategori_sunucu = post("kategori_sunucu");
                $kategori_resim = post("kategori_resim");
                $kategori_icon = post("kategori_icon");
                $kategori_icon_id = post("kategori_icon_id");

								if(!$kategori_adi || !$kategori_sunucu || !$kategori_resim || !$kategori_icon || !$kategori_icon_id){
									echo alert('<strong>Tüm alanları doldurmanız gerekiyor.</strong>');
								}else{
									$varmi = query("SELECT * FROM kategoriler_urun WHERE kategori_link = '$kategori_link' AND kategori_id != '$suid'");
									if(rows($varmi)){
										echo alert('<strong>'.ss($kategori_adi).'</strong> adını zaten başka bir kategori kullanıyor. Lütfen başka bir tane dene.');
									}else{
										$update = query("UPDATE kategoriler_urun SET
										kategori_adi = '$kategori_adi',
										kategori_link = '$kategori_link',
										kategori_sunucu = '$kategori_sunucu',
										kategori_resim = '$kategori_resim',
										kategori_icon = '$kategori_icon',
                    kategori_icon_id = '$kategori_icon_id' WHERE kategori_id = '$suid'");
										if($update){
											echo alert('<strong>Kategori</strong> başarıyla güncellendi.', 'success');
                      go(ADMIN_URL.'/?go=urun_kategori',1);
										}else{
											echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
										}
									}
								}
							}
							$row = row($query);
							echo alert('<strong>'.ss($row['kategori_adi']).'</strong> adlı sunucu bilgilerini düzenliyorsunuz.','info');
						}
					}
				}
			?>
			<div class="block block-bordered">
				<div class="block-header bg-gray-lighter">Kategori Düzenle [<?=ss($row['kategori_adi'])?>]
					<div style="float: right;margin-top: -2px;">
						<a href="<?=ADMIN_URL?>/?go=urun_kategori" class="btn btn-danger btn-xs">İptal</a>
					</div>
				</div>
				<div class="block-content">
					<form action="" method="post">
            <div class="form-group">
              <label>Kategori Adı</label>
              <input name="kategori_adi" type="text" class="form-control" placeholder="Kategorinin gözükecek adı. (Örnek: Vipler)" value="<?=$row['kategori_adi']?>">
            </div>
            <div class="form-group">
              <label class="control-label">Sunucu Seçimi</label>
              <select class="selectpicker form-control" data-live-search="true" name="kategori_sunucu">
                <?php
                  $squery = query("SELECT * FROM sunucular ORDER BY sunucu_id ASC");
                  if(rows($squery) < 1){
                    echo '<option>Hiç sunucu ekli değil.</option>';
                  }else{
                    while($srow = row($squery)){
                      echo '<option value="'.$srow['sunucu_id'].'"';
                      echo $srow['sunucu_id'] == $row['kategori_sunucu'] ? ' selected' : null;
                      echo '>'.ss($srow['sunucu_adi']).'</option>';
                    }
                  }
                ?>		        
              </select>
            </div><hr>
            <div class="form-group row" style="margin-bottom: 15px;">
                <div class="col-xs-6"  style="padding-right: 7px;">
                    <div class="form-group">
                        <label>Kategori Resim</label>
                        <input name="kategori_resim" type="text" class="form-control" placeholder="Gözükecek resim." value="<?=$row['kategori_resim']?>">
                    </div>
                </div>
                <div class="col-xs-6"  style="padding-left: 7px;">
                    <div class="form-group">
                        <label>Kategori Icon</label>
                        <?php foreach ($items as $key): ?>
                            <?php $id = explode(":", $row['kategori_icon_id']); ?>
                            <?php if ($key["text_type"] == $row['kategori_icon'] AND $key["meta"] == $id[1]): ?>
                                <button id="sunucu_icon" type="button" data-toggle="modal" data-target="#modal-items" class="btn btn-block btn-default" style="overflow-x: auto;text-align: left"><img src="<?=ADMIN_URL?>/assets/items/<?=$key["type"]?>-<?=$key["meta"]?>.png"> &nbsp;&nbsp;<?=$key["name"]?></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" id="sunucu_icon_hidden" name="kategori_icon" value="<?=$row['kategori_icon']?>">
                        <input type="hidden" id="sunucu_icon_id" name="kategori_icon_id" value="<?=$row['kategori_icon_id']?>">
                    </div>
                </div>
            </div>
						<div class="form-group">
								<button name="sunucu_duzenle" value="sunucu_duzenle" type="submit" class="btn btn-info btn-block">Güncelle</button>
						</div>
					</form>
				</div>
			</div>
		<?php }else{ ?>
			<?php 

			if (isset($_POST["kategori_ekle"])) {
				$kategori_adi = post("kategori_adi");
				$kategori_link = sef_link($kategori_adi);
				$kategori_sunucu = post("kategori_sunucu");
        $kategori_resim = post("kategori_resim");
        $kategori_icon = post("kategori_icon");
        $kategori_icon_id = post("kategori_icon_id");

				if (!empty($kategori_adi) && !empty($kategori_sunucu) && !empty($kategori_icon) && !empty($kategori_icon_id)) {
					$varmi = query("SELECT * FROM kategoriler_urun WHERE kategori_link = '$kategori_link'");
					if(rows($varmi)){
						echo alert('<strong>'.ss($kategori_adi).'</strong> adını zaten başka bir kategori kullanıyor. Lütfen başka bir tane dene.');
					}else{
						$insert = query("INSERT INTO kategoriler_urun SET
						kategori_adi = '$kategori_adi',
						kategori_link = '$kategori_link',
						kategori_sunucu = '$kategori_sunucu',
						kategori_resim = '$kategori_resim',
						kategori_icon = '$kategori_icon',
						kategori_icon_id = '$kategori_icon_id'");
						if($insert){
							echo alert('<strong>Kategori</strong> başarıyla eklendi.', 'success');
							go(ADMIN_URL."/?go=urun_kategori",1);
						}else{
							echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
						}
					}

				}else{
					echo alert('<strong>Tüm alanları doldurmanız gerekiyor.</strong>');
				}
			}

			?>
			<div class="block block-bordered">
				<div class="block-header bg-gray-lighter">Kategori Ekle</div>
				<div class="block-content">
					<form action="" method="post">
						<div class="form-group">
							<label>Kategori Adı</label>
							<input name="kategori_adi" type="text" class="form-control" placeholder="Kategorinin gözükecek adı. (Örnek: Vipler)">
						</div>
            <div class="form-group">
              <label class="control-label">Sunucu Seçimi</label>
              <select class="selectpicker form-control" data-live-search="true" name="kategori_sunucu">
                <?php
                  $squery = query("SELECT * FROM sunucular ORDER BY sunucu_id ASC");
                  if(rows($squery) < 1){
                    echo '<option value="0">Hiç sunucu ekli değil.</option>';
                  }else{
                    while($srow = row($squery)){
                      echo '<option value="'.$srow['sunucu_id'].'">'.ss($srow['sunucu_adi']).'</option>';
                    }
                  }
                ?>			        
              </select>
            </div>
            <hr>
            <div class="form-group row" style="margin-bottom: 15px;">
                <div class="col-xs-6"  style="padding-right: 7px;">
                    <div class="form-group">
                        <label>Kategori Resim</label>
                        <input name="kategori_resim" type="text" class="form-control" placeholder="Gözükecek resim.">
                    </div>
                </div>
                <div class="col-xs-6"  style="padding-left: 7px;">
                    <div class="form-group">
                        <label>Kategori Icon</label>
                        <button id="sunucu_icon" type="button" data-toggle="modal" data-target="#modal-items" class="btn btn-block btn-default" style="overflow-x: auto;text-align: left"><img src="<?=ADMIN_URL?>/assets/items/1-0.png"> &nbsp;&nbsp;Stone</button>
                        <input type="hidden" id="sunucu_icon_hidden" name="kategori_icon" value="stone">
                        <input type="hidden" id="sunucu_icon_id" name="kategori_icon_id" value="1:0">
                    </div>
                </div>
            </div>
						<div class="form-group">
								<button name="kategori_ekle" value="kategori_ekle" type="submit" class="btn btn-info btn-block">Ekle</button>
						</div>
					</form>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="col-md-6">
  	<?php 

    if (isset($_GET["sil"])) {
      $sil = get('sil');
      if(!$sil){
        go(ADMIN_URL.'/?go=urun_kategori');
        exit;
      }else{
        $query = query("SELECT * FROM kategoriler_urun WHERE kategori_id = '$sil'");
        if(rows($query) < 1){
          go(ADMIN_URL.'/?go=urun_kategori');
          exit;
        }else{
          $sil1 = query("DELETE FROM kategoriler_urun WHERE kategori_id = '$sil'");
          $sil2 = query("DELETE FROM urunler WHERE urun_kategori = '$sil'");
          if ($sil1) {
            echo alert("Silme işlemi başarılı.", "success");
            go(ADMIN_URL.'/?go=urun_kategori',1);
          }else{
            echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
          }
        }
      }
    }

  	?>
		<div class="block block-bordered">
			<div class="block-header bg-gray-lighter">Tüm Kategoriler</div>
			<table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Kategori Adı</th>
						<th class="text-center">Bağlı Sunucu</th>
						<th width="100"></th>
					</tr>
				</thead>
				<tbody>
				<?php
          $link = "urun_kategori";
					@$sayfa = get("sayfa") ? get("sayfa") : 1;
					$ksayisi = rows(query("SELECT * FROM kategoriler_urun"));
					$limit = 10;
					$ssayisi = ceil($ksayisi/$limit);
					$baslangic = $sayfa * $limit - $limit;
					if($ksayisi > $limit){
						$query = query("SELECT * FROM kategoriler_urun INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY kategori_id DESC LIMIT $baslangic,$limit");
					}else{
						$query = query("SELECT * FROM kategoriler_urun INNER JOIN sunucular ON sunucular.sunucu_id = kategoriler_urun.kategori_sunucu ORDER BY kategori_id DESC");
					}
					if(rows($query) < 1){
						echo '<tr><td class="text-center" colspan="4">Henüz hiç Kategori eklememişsiniz.</td></tr>';
					}else{
						while($row = row($query)){
				?>
					<tr>
						<td class="text-center"><?php echo $row['kategori_id']; ?></td>
						<td><?php echo ss($row['kategori_adi']); ?></td>
						<td class="text-center"><?php echo $row['sunucu_adi']; ?></td>
						<td class="text-center">
						<a href="<?=URL?>/market/<?=$row['sunucu_link']?>/<?=$row['kategori_link']?>" target="_blank_<?=$row['sunucu_link']?>_<?=$row['kategori_link']?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
						<a href="<?php echo ADMIN_URL."/?go=urun_kategori&suid=".$row['kategori_id']; ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
						<a onclick="SwSil('urun_kategori','<?=$row["kategori_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
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
</div>

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

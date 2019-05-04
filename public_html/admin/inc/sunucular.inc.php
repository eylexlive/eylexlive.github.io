<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);

    $items = json_decode(file_get_contents("assets/items/items.json"), true);
    
    $send_method = mset("send_method");
    if ($send_method == "rcon") {$name = "Rcon";}elseif ($send_method == "websender") {$name = "Websender";}else{$name = "Websend";}
?>
<div class="row">
	<div class="col-md-6">
		<?php if (isset($_GET["suid"])) { ?>
			<?php 
				if(isset($_GET['suid']) && !empty($_GET['suid'])){
					$suid = get('suid');
					if(!$suid){
						go(ADMIN_URL.'/?go=sunucular');
						exit;
					}else{
						$query = query("SELECT * FROM sunucular WHERE sunucu_id = '$suid'");
						if(rows($query) < 1){
							go(ADMIN_URL.'/?go=sunucular');
							exit;
						}else{
							if(!empty($_POST['sunucu_duzenle'])){
								$sunucu_adi = post('sunucu_adi');
								$sunucu_link = sef_link($sunucu_adi);
								$sunucu_ip = post('sunucu_ip');
								$sunucu_port = post('sunucu_port');
								$sunucu_sifre = post('sunucu_sifre');
                $sunucu_resim = post("sunucu_resim");
                $sunucu_icon = post("sunucu_icon");
                $sunucu_icon_id = post("sunucu_icon_id");

								if(!$sunucu_adi || !$sunucu_ip || !$sunucu_port || !$sunucu_sifre || !$sunucu_icon || !$sunucu_icon_id){
									echo alert('<strong>Tüm alanları doldurmanız gerekiyor.</strong>');
								}else{
									$varmi = query("SELECT * FROM sunucular WHERE sunucu_link = '$sunucu_link' AND sunucu_id != '$suid'");
									if(rows($varmi)){
										echo alert('<strong>'.ss($sunucu_adi).'</strong> adını zaten başka bir sunucu kullanıyor. Lütfen başka bir tane dene.');
									}else{
										$update = query("UPDATE sunucular SET
										sunucu_adi = '$sunucu_adi',
										sunucu_link = '$sunucu_link',
										sunucu_ip = '$sunucu_ip',
										sunucu_port = '$sunucu_port',
										sunucu_sifre = '$sunucu_sifre',
                    sunucu_resim = '$sunucu_resim',
                    sunucu_icon = '$sunucu_icon',
                    sunucu_icon_id = '$sunucu_icon_id' WHERE sunucu_id = '$suid'");
										if($update){
											echo alert('<strong>Sunucu</strong> başarıyla güncellendi.', 'success');
                      go(ADMIN_URL.'/?go=sunucular',1);
										}else{
											echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
										}
									}
								}
							}
							$row = row($query);
							echo alert('<strong>'.ss($row['sunucu_adi']).'</strong> adlı sunucu bilgilerini düzenliyorsunuz.','info');
						}
					}
				}
			?>
			<div class="block block-bordered">
				<div class="block-header bg-gray-lighter">Sunucu Düzenle [<?=ss($row['sunucu_adi'])?>]
					<div style="float: right;margin-top: -2px;">
						<a href="<?=ADMIN_URL?>/?go=sunucular" class="btn btn-danger btn-xs">İptal</a>
					</div>
				</div>
				<div class="block-content">
					<form action="" method="post">

						<div class="form-group">
							<label>Server Adı</label>
							<input name="sunucu_adi" type="text" class="form-control" placeholder="Serverinizin gözükecek adı. (Örnek: Faction)" value="<?=$row['sunucu_adi']?>">
						</div>
						<div class="form-group">
							<label>Server Ip Adresi</label>
							<input name="sunucu_ip" type="text" class="form-control" placeholder="Websendi kurduğunuz serverin sayısal ip adresi." value="<?=$row['sunucu_ip']?>">
						</div>
						<div class="form-group">
							<label><?=$name?> Portu</label>
							<input name="sunucu_port" type="text" class="form-control" placeholder="Websend configine yazdığınız port." value="<?=$row['sunucu_port']?>">
						</div>
						<div class="form-group">
							<label><?=$name?> Şifresi</label>
							<input name="sunucu_sifre" type="text" class="form-control" placeholder="Websend configine yazdığınız şifre." value="<?=$row['sunucu_sifre']?>">
						</div>
            <hr>
            <div class="form-group row">
                <div class="col-xs-6"  style="padding-right: 7px;">
                    <div class="form-group">
                        <label>Sunucu Resim</label>
                        <input name="sunucu_resim" type="text" class="form-control" placeholder="Gözükecek resim." value="<?=$row['sunucu_resim']?>">
                    </div>
                </div>
                <div class="col-xs-6"  style="padding-left: 7px;">
                    <div class="form-group">
                        <label>Sunucu Icon</label>
                        <?php foreach ($items as $key): ?>
                            <?php $id = explode(":", $row['sunucu_icon_id']); ?>
                            <?php if ($key["text_type"] == $row['sunucu_icon'] AND $key["meta"] == $id[1]): ?>
                                <button id="sunucu_icon" type="button" data-toggle="modal" data-target="#modal-items" class="btn btn-block btn-default" style="overflow-x: auto;text-align: left"><img src="<?=ADMIN_URL?>/assets/items/<?=$key["type"]?>-<?=$key["meta"]?>.png"> &nbsp;&nbsp;<?=$key["name"]?></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" id="sunucu_icon_hidden" name="sunucu_icon" value="<?=$row['sunucu_icon']?>">
                        <input type="hidden" id="sunucu_icon_id" name="sunucu_icon_id" value="<?=$row['sunucu_icon_id']?>">
                    </div>
                </div>
            </div>
						<div class="form-group row">
							<div class="col-xs-6" style="padding-right: 7px;">
								<button name="sunucu_duzenle" value="sunucu_duzenle" type="submit" class="btn btn-info btn-block">Güncelle</button>
							</div>
							<div class="col-xs-6" style="padding-left: 7px;">
								<a id="baglanti_testi" style="cursor: pointer;" class="btn btn-primary btn-block">Bağlantı Testi</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		<?php }else{ ?>
			<?php 

			if (isset($_POST["sunucu_ekle"])) {
				$sunucu_adi = post("sunucu_adi");
				$sunucu_link = sef_link($sunucu_adi);
				$sunucu_ip = post("sunucu_ip");
        $sunucu_port = post("sunucu_port");
        $sunucu_sifre = post("sunucu_sifre");
        $sunucu_resim = post("sunucu_resim");
        $sunucu_icon = post("sunucu_icon");
        $sunucu_icon_id = post("sunucu_icon_id");

				if (!empty($sunucu_adi) && !empty($sunucu_ip) && !empty($sunucu_port) && !empty($sunucu_sifre) && !empty($sunucu_icon) && !empty($sunucu_icon_id)) {

					$varmi = query("SELECT * FROM sunucular WHERE sunucu_link = '$sunucu_link'");
					if(rows($varmi)){
						echo alert('<strong>'.ss($sunucu_adi).'</strong> adını zaten başka bir sunucu kullanıyor. Lütfen başka bir tane dene.');
					}else{
						$insert = query("INSERT INTO sunucular SET
						sunucu_adi = '$sunucu_adi',
						sunucu_link = '$sunucu_link',
						sunucu_ip = '$sunucu_ip',
						sunucu_port = '$sunucu_port',
						sunucu_sifre = '$sunucu_sifre',
						sunucu_resim = '$sunucu_resim',
						sunucu_icon = '$sunucu_icon',
						sunucu_icon_id = '$sunucu_icon_id'");
						if($insert){
							echo alert('<strong>Sunucu</strong> başarıyla eklendi.', 'success');
							go(ADMIN_URL."/?go=sunucular",1);
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
				<div class="block-header bg-gray-lighter">Sunucu Ekle</div>
				<div class="block-content">
					<form action="" method="post">

						<div class="form-group">
							<label>Server Adı</label>
							<input name="sunucu_adi" type="text" class="form-control" placeholder="Serverinizin gözükecek adı. (Örnek: Faction)">
						</div>
						<div class="form-group">
							<label>Server Ip Adresi</label>
							<input name="sunucu_ip" type="text" class="form-control" placeholder="Websendi kurduğunuz serverin sayısal ip adresi.">
						</div>
						<div class="form-group">
							<label><?=$name?> Portu</label>
							<input name="sunucu_port" type="text" class="form-control" placeholder="Websend configine yazdığınız port.">
						</div>
						<div class="form-group">
							<label><?=$name?> Şifresi</label>
							<input name="sunucu_sifre" type="text" class="form-control" placeholder="Websend configine yazdığınız şifre.">
						</div>
            <hr>
            <div class="form-group row">
                <div class="col-xs-6"  style="padding-right: 7px;">
                    <div class="form-group">
                        <label>Sunucu Resim</label>
                        <input name="sunucu_resim" type="text" class="form-control" placeholder="Gözükecek resim.">
                    </div>
                </div>
                <div class="col-xs-6"  style="padding-left: 7px;">
                    <div class="form-group">
                        <label>Sunucu Icon</label>
                        <button id="sunucu_icon" type="button" data-toggle="modal" data-target="#modal-items" class="btn btn-block btn-default" style="overflow-x: auto;text-align: left"><img src="<?=ADMIN_URL?>/assets/items/1-0.png"> &nbsp;&nbsp;Stone</button>
                        <input type="hidden" id="sunucu_icon_hidden" name="sunucu_icon" value="stone">
                        <input type="hidden" id="sunucu_icon_id" name="sunucu_icon_id" value="1:0">
                    </div>
                </div>
            </div>
						<div class="form-group row">
							<div class="col-xs-6" style="padding-right: 7px;">
								<button name="sunucu_ekle" value="sunucu_ekle" type="submit" class="btn btn-info btn-block">Ekle</button>
							</div>
							<div class="col-xs-6" style="padding-left: 7px;">
								<a id="baglanti_testi" style="cursor: pointer;" class="btn btn-primary btn-block">Bağlantı Testi</a>
							</div>
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
				go(ADMIN_URL.'/?go=sunucular');
				exit;
			}else{
				$query = query("SELECT * FROM sunucular WHERE sunucu_id = '$sil'");
				if(rows($query) < 1){
					go(ADMIN_URL.'/?go=sunucular');
					exit;
				}else{
          $sil1 = query("DELETE FROM sunucular WHERE sunucu_id = '$sil'");
          $sil2Query = query("SELECT * FROM kategoriler_urun WHERE kategori_sunucu = '$sil'");
          while($silRow = row($sil2Query)){
            query("DELETE FROM urunler WHERE urun_kategori = '".$silRow["kategori_id"]."'");
          }
          $sil2 = query("DELETE FROM kategoriler_urun WHERE kategori_sunucu = '$sil'");
					if ($sil1 AND $sil2) {
						echo alert("Silme işlemi başarılı.", "success");
            go(ADMIN_URL.'/?go=sunucular',1);
					}else{
						echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
					}
				}
			}
		}

	?>
		<div class="block block-bordered">
			<div class="block-header bg-gray-lighter">Tüm Sunucular</div>
			<table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Sunucu Adı</th>
						<th class="text-center">Sunucu IP'si</th>
						<th width="100"></th>
					</tr>
				</thead>
				<tbody>
				<?php
          $link = "sunucular";
					@$sayfa = get("sayfa") ? get("sayfa") : 1;
					$ksayisi = rows(query("SELECT * FROM sunucular"));
					$limit = 10;
					$ssayisi = ceil($ksayisi/$limit);
					$baslangic = $sayfa * $limit - $limit;
					if($ksayisi > $limit){
						$query = query("SELECT * FROM sunucular ORDER BY sunucu_id DESC LIMIT $baslangic,$limit");
					}else{
						$query = query("SELECT * FROM sunucular ORDER BY sunucu_id DESC");
					}
					if(rows($query) < 1){
						echo '<tr><td class="text-center" colspan="4">Henüz hiç Sunucu eklememişsiniz.</td></tr>';
					}else{
						while($row = row($query)){
				?>
					<tr>
						<td class="text-center"><?php echo $row['sunucu_id']; ?></td>
						<td><?php echo ss($row['sunucu_adi']); ?></td>
						<td class="text-center"><?php echo $row['sunucu_ip']; ?></td>
						<td class="text-center">
						<a href="<?=URL?>/market/<?=$row['sunucu_link']?>" target="_blank_<?=$row['sunucu_id']?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
						<a href="<?=ADMIN_URL?>/?go=sunucular&suid=<?=$row['sunucu_id']?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
						<a onclick="SwSil('sunucular','<?=$row["sunucu_id"]?>','sil',$(this));" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
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

                $("#baglanti_testi").click(function(){
                    var sunucu_ip = $("[name*='sunucu_ip']").val();
                    var sunucu_port = $("[name*='sunucu_port']").val();
                    var sunucu_sifre = $("[name*='sunucu_sifre']").val();

                    $('#baglanti_testi').removeClass('btn-success btn-danger btn-warning').addClass('btn-primary').html('<i class="fa fa-refresh fa-spin"></i>');
                    $.ajax({
                        type: "POST",
                        url: ADMIN_URL+"/ajax/server_test.ajax.php",
                        dataType: "json",
                        data: "sunucu_ip="+sunucu_ip+"&sunucu_port="+sunucu_port+"&sunucu_sifre="+sunucu_sifre,
                        success: function(cevap){
                            $('#baglanti_testi').addClass('btn-'+cevap.button).html('<i class="fa fa-'+cevap.icon+'"></i> '+cevap.mesaj);
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>

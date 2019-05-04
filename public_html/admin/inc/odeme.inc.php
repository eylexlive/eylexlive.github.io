<?php
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
		
	$qu = query("SELECT * FROM odeme ORDER BY odeme_id");
?>

<div class="row">
	<div class="col-lg-6">
		<?php 
		
		if(isset($_GET['kapat'])){
			$kapat = get('kapat');
			$varmi = query("SELECT * FROM odeme WHERE odeme_id = '$kapat'");
			if(rows($varmi) < 1){
				go(ADMIN_URL.'/?go=odeme');
				exit;
			}else{
				$kkapat = query("UPDATE odeme SET odeme_durum='0' WHERE odeme_id = '$kapat'");
				if($kkapat){
					echo alert('Ödeme Mehodu başarıyla kapatıldı. Yönlendiriliyorsunuz.','success');
					go(ADMIN_URL.'/?go=odeme');
				}else{
					echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
				}
			}
		}
		if(isset($_GET['ac'])){
			$ac = get('ac');
			$varmi = query("SELECT * FROM odeme WHERE odeme_id = '$ac'");
			if(rows($varmi) < 1){
				go(ADMIN_URL.'/?go=odeme');
				exit;
			}else{
				$aac = query("UPDATE odeme SET odeme_durum='1' WHERE odeme_id = '$ac'");
				if($aac){
					echo alert('Ödeme Mehodu başarıyla kapatıldı. Yönlendiriliyorsunuz.','success');
					go(ADMIN_URL.'/?go=odeme');
				}else{
					echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
				}
			}
		}
		
		?>
		<div class="block block-bordered">
			<div class="block-header bg-gray-lighter">
				<h3 class="block-title">Ödeme Methodları</h3>
			</div>
			<table class="table table-striped table-advance table-hover" style="margin-bottom: 0">
				<thead>
					<tr>
					  <th>Method Adı</th>
					  <th width="110"></th>
					</tr>
				</thead>
				<tbody>
					<?php if (rows($qu) < 1): ?>
						<tr><td class="text-center" colspan="2">Ödeme methodları ile ilgili bir sorun gerçekleşmiştir.</td></tr>
					<?php else: ?>
						<?php while ($row = row($qu)) { ?>
						<tr class="<?=($row['odeme_durum'] == "1")?"success":"danger"?>">
							<td><?=$row["odeme_adi"]?></td>
							<td>
								<a href="<?=ADMIN_URL?>/?go=odeme&uid=<?php echo $row['odeme_id'] ?>" class="btn btn-<?=($row['odeme_durum'] == "1")?"success":"danger"?> btn-xs">Düzenle</a>
								<?php if ($row['odeme_durum'] == "1"): ?>
									<a href="<?=ADMIN_URL?>/?go=odeme&kapat=<?php echo $row['odeme_id'] ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a>
								<?php else: ?>
									<a href="<?=ADMIN_URL?>/?go=odeme&ac=<?php echo $row['odeme_id'] ?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
								<?php endif; ?>
							</td>
	          </tr>
						<?php } ?>
					<?php endif; ?> 
				</tbody>
			</table>
		</div>
	</div>
<?php 
  $duzenle = get("uid"); 
?>
	<div class="col-lg-6">
		<?php if (!$duzenle) { ?>
			<div class="block block-bordered">
				<div class="block-header bg-gray-lighter">
					<h3 class="block-title">Ödeme Methodları Ayarları</h3>
				</div>
		    <div class="block-content">
		      <p>Herhangi bir methodun sağında bulunan <a class="btn btn-success btn-xs">Düzenle</a> butonuna tıklayarak düzenleyebilirsiniz.</p>
		    </div>
		  </div>
		<?php }else{ ?>
			<?php
			  $wg = query("SELECT * FROM odeme WHERE odeme_id='$duzenle'");
			  if (rows($wg) < 1) {
			  	go(ADMIN_URL."/?go=odeme");
			  }else{
					$row = row($wg);
				}
				
				$json = json_decode($row["odeme_json"],true);
				$json_cvp = json_decode($row["odeme_resp"],true);
			?>
			
			<div class="block block-bordered">
				<div class="block-header bg-gray-lighter">
					<h3 class="block-title">Ayarlar [<?=$row["odeme_adi"]?>]</h3>
				</div>
		    <div class="block-content">
					<?php 
					
					if (isset($_POST["gonder"])) {						
						foreach ($json as $key => $value) {
							if ($value["name"] == "method") {
								$slug_resp[$value["name"]] = implode(',',$_POST[$value["name"]]);
							} else {
								$slug_resp[$value["name"]] = post($value["name"]);
							}
						}
						$js = json_encode($slug_resp,JSON_UNESCAPED_UNICODE);
						$aac = query("UPDATE odeme SET odeme_resp='$js' WHERE odeme_id='$duzenle'");
						if ($aac) {
							echo alert('Güncelleme işlemi başarılı. Yönlendiriliyorsunuz...','success');
							go(ANLIK_URL,1);
						}else{
							echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
						}
					}
					
					?>
					<form action="" method="post">
					  <?php foreach ($json as $key => $value): ?>
					    <?php if ($value["type"] == "text"): ?>
					      <div class="form-group">
					        <label for="<?=$value['name']?>"><?=$value['label']?></label>
					        <input type="text" class="form-control" id="<?=$value['name']?>" name="<?=$value['name']?>" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>" value="<?=isset($json_cvp[$value['name']])?$json_cvp[$value['name']]:null?>">
					        <?php if ($value["help"]): ?>
					          <p class="help-block"><?=$value["help"]?></p>
					        <?php endif; ?>
					      </div>
					    <?php endif; ?>
					    <?php if ($value["type"] == "select"): ?>
					      <div class="form-group">
					        <label for="<?=$value['name']?>"><?=$value['label']?></label>
					        <select class="form-control" id="<?=$value['name']?>" name="<?=$value['name']?>" <?php if ($value["size"]): ?>size="<?=$value["size"]?>"<?php endif; ?>>
					          <?php foreach ($value['select'] as $key => $data): ?>
                      <option <?=($json_cvp["method"] == $key) ?'selected=""':null?> value="<?=$key?>"><?=$data?></option>
                    <?php endforeach; ?>
					        </select>
					        <?php if (isset($value["help"])): ?>
					          <p class="help-block"><?=$value["help"]?></p>
					        <?php endif; ?>
					      </div>
					    <?php endif; ?>
					    <?php if ($value["type"] == "textarea"): ?>
					      <div class="form-group">
					        <label for="<?=$value['name']?>"><?=$value['label']?></label>
					        <textarea id="<?=$value['name']?>" name="<?=$value['name']?>" rows="<?=$value['rows']?>" class="form-control" placeholder="<?=isset($value['placeholder'])?$value['placeholder']:null?>"><?=isset($json_cvp[$value['name']])?$json_cvp[$value['name']]:null?></textarea>
					        <?php if (isset($value["help"])): ?>
					          <p class="help-block"><?=$value["help"]?></p>
					        <?php endif; ?>
					      </div>
                <script type="text/javascript">
                $('textarea').keypress(function(event) {
                  if (event.which == 13) {
                    event.preventDefault();
                    var s = $(this).val();
                    $(this).val(s+"\n");
                  }
                });​
                </script>
					    <?php endif; ?>
							<?php if ($value["type"] == "checkbox"): ?>
					      <div class="form-group">
					        <label for="<?=$value['name']?>"><?=$value['label']?></label>
									<?php foreach ($value['checkbox'] as $kk => $vv): ?>
										<?php 
											$sec = explode(',',$json_cvp[$value['name']]);
										?>
										<div class="checkbox">
	                      <label for="<?=$value['name']?><?=$kk?>">
	                          <input <?=in_array($kk,$sec)?"checked":null?> type="checkbox" id="<?=$value['name']?><?=$kk?>" name="<?=$value['name']?>[]" value="<?=$kk?>"> <?=$vv?>
	                      </label>
	                  </div>
									<?php endforeach; ?>
					        <?php if (isset($value["help"])): ?>
					          <p class="help-block"><?=$value["help"]?></p>
					        <?php endif; ?>
					      </div>
					    <?php endif; ?>
					  <?php endforeach; ?>
					  <div class="form-group">
					    <button type="submit" name="gonder" value="gonder" class="btn btn-block btn-primary">Ayarları Güncelle</button>
					  </div>
					</form>
		    </div>
		  </div>
			
		<?php } ?>
	</div>
</div>
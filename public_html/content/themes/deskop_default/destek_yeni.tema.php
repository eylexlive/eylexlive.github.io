<?php !session('login') ? go(URL."/kayitol") : null; ?>
<?php $uye_id = session("uye_id"); $bak = query("SELECT * FROM ticketler WHERE ticket_atan_id='$uye_id' AND ticket_turu = '1'") ?>
<?php if (rows($bak) < mset("ticket_limit","ayar_deger")): ?>
  <div class="panel panel-default">
    <div class="panel-heading">Destek Bildirimi Oluştur</div>
    <div class="panel-body">
  		<?php
  			$ub = query("SELECT * FROM ticket_engel WHERE uye_id='".session("uye_id")."'");
  		?>
  		<?php if (rows($ub) > 0): ?>
  			<?php $ubr = row($ub); ?>
  			<div class="alert alert-danger" style="margin-bottom: 0;">Destek Sistemiden engellendiniz!<br><b>Engellenme Nedeni:</b><br><?=nl2br($ubr["engel_neden"])?></div>
  		<?php else: ?>
  			<?php
  				if(isset($_POST["CwToken"])){
            $coz = base64_decode(post("CwToken"));
            $coz = explode("||",$coz);
            
  					$ticket_baslik = post($coz[0]);
  					$ticket_kategori = post("ticket_kategori");
            $ticket_icerik = post($coz[1]);
  					$ticket_resim = post("ticket_resim");
  					
  					if(!$ticket_baslik || !$ticket_kategori || !$ticket_icerik){
  						echo alert("Lütfen boş alan bırakmayınız!","danger");
  					}else{

  						$userid = session("uye_id");
  					  $insert = query("INSERT INTO ticketler SET
  						ticket_baslik = '$ticket_baslik',
  						ticket_atan_id = '".session("uye_id")."',
  						ticket_kategori = '$ticket_kategori',
              ticket_guncelleme = '".time()."',
  						ticket_icerik = '$ticket_icerik',
              ticket_resim = '$ticket_resim',
  						ticket_turu = 1,
  						ticket_durum = 1,
  						ticket_ana_id = 0");
  						if($insert){
  							$tid = mysqli_insert_id($baglan);
  							$update = query("UPDATE ticketler SET ticket_ana_id = '$tid' WHERE ticket_id = '$tid'");
  							if($update){
  								echo alert("Destek Talebi açma işleminiz başarılı. En kısa zaman içinde yanıtlanacaktır.",'success');
  								go(URL."/destek/duzenle/".$tid,1);
  							}else{
  								echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
  							}
  						}else{
  							echo alert('<strong>Mysql Hatası: </strong>'.mysqli_error($baglan),'danger');
              }
  					}
  				}
  			?>
  			<?php echo alert("Kanıtlayamadığınız şikayetler hakkında yada bizi ilgilendirmeyen durumlarda lütfen destek bildirimi oluşturmayınız"); ?>
  			<?php 
          $ticket_baslik   = rasgeleSifre("32");
          $ticket_icerik   = rasgeleSifre("32");
          $salt = base64_encode("$ticket_baslik||$ticket_icerik");
        ?>
  			<form action="" method="post" enctype="multipart/form-data">
  				<div class="form-group">
  					<label>Başlık</label>
  					<input type="text" name="<?=$ticket_baslik?>" class="form-control" maxlength="50" value="<?=isset($_POST["ticket_baslik"])?post("ticket_baslik"):null;?>">
  					<p class="help-block">Fazla uzatmadan sorununuz için bir başlık yazınız.</p>
  				</div>
  				<div class="form-group">
  					<label>Kategori</label>
  					<select name="ticket_kategori" class="form-control">
  						<?php
  							$tkatCek = query("SELECT * FROM kategoriler_ticket");
  							if (rows($tkatCek) < 0) {
  								echo '<option value="" disabled selected>Kategori eklenmemiş!</option>';
  							}else{
  								while($tkatRow = row($tkatCek)){
  									echo '<option value="'.$tkatRow["kategori_id"].'">'.ss($tkatRow["kategori_adi"]).'</option>';
  								}
  							}
  						?>
  					</select>
  					<p class="help-block">Sorununuz için kategori seçin.</p>
  				</div>
  				<div class="form-group">
  					<label>İçerik</label>
  					<textarea name="<?=$ticket_icerik?>" id="" cols="30" rows="10" class="form-control"><?=isset($_POST["ticket_icerik"])?post("ticket_icerik"):null;?></textarea>
  					<p class="help-block">Her türlü sorununuzu yazabilirsiniz.</p>
  				</div>
          <style media="screen">
            .resim-onizle {
              max-height: 70px;
              overflow: hidden;
              -webkit-transition: all 300ms linear;
              -moz-transition: all 300ms linear;
              -ms-transition: all 300ms linear;
              -o-transition: all 300ms linear;
              transition: all 300ms linear;
            }
            .resim-onizle:hover {
              max-height: 100%;
            }
          </style>
          <div class="form-group resim">
  					<label class="control-label">Resim</label>
            <div class="resim-onizle"></div>
  					<input type="file" class="filestyle" name="file[]" id="fileupload">
  					<div class="help-block" style="color: #a94442;"></div>
            <div class="progress progress-striped active" style="display: none;">
              <div class="progress-bar progress-bar-success" style="width: 4%">%4</div>
            </div>
  				</div>          
          <input type="hidden" name="ticket_resim" value="">
          <input type="hidden" name="CwToken" value="<?=$salt?>">
  				<button name="ticket_olustur" value="ticket_olustur" type="submit" class="btn btn-success btn-block">Destek Bildirimi Oluştur</button>
  			</form>
        <script src="<?=INC_PATH?>/js/bootstrap-filestyle.min.js"></script>
        <script src="<?=INC_PATH?>/js/jquery-ui.min.js"></script>
        
        <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.2/js/jquery.fileupload.min.js"></script>
        
        <script type="text/javascript">
          $(function() {
            $("#fileupload").fileupload({
              url: "https://vgy.me/upload",
              dataType: "json",
              limitMultiFileUploads: 1,
              add: function (e, data) {
                $(".resim > .progress").css("display","block");
                data.submit();
              },
              done: function(e, data) {
                if(typeof data.result.url != "undefined") {
                  $(".resim .filestyle").hide();
                  $(".resim .bootstrap-filestyle").hide();
                  $(".resim .help-block").hide();
                  $(".resim .progress").hide();
                  $(".resim .resim-onizle").html("<img src='"+data.result.image+"' class='img-responsive img-rounded'>");
                  $("input[name=ticket_resim]").val(data.result.image);
                }
              },
              progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('.resim .progress-bar').css('width', progress + '%').html("%"+progress);
              }
            });
          });
          $(".filestyle").filestyle({
            text: '<i class="fa fa-folder-open"></i> Resim Seç',
            placeholder: "Yüklenilecek resmi seçin...",
            btnClass: "btn-primary",
            onChange: function (files) {
              //console.log(files);
              if (files[0].type == "image/png" || files[0].type == "image/jpeg") {
                $(".resim > .help-block").html("");
                $(".resim").removeClass("has-error").addClass("has-success");
                if (files[0].size <= 9437184) {
                  $(".resim > .help-block").html("");
                  $(".resim").removeClass("has-error").addClass("has-success");
                }else{
                  $(".resim > .help-block").html("Seçtiğiniz resim 9 Mb (9216 Kb)'den büyük olamaz.");
                  $(".resim").addClass("has-error").removeClass("has-success").effect("shake");
                }
              }else{
                $(".resim > .help-block").html("Seçtiğiniz resim türü PNG veya JPEG (jpg) olmalıdır");
                $(".resim").addClass("has-error").removeClass("has-success").effect("shake");
              }
           }
          });
        </script>
  		<?php endif; ?>
    </div>
  </div>
<?php else: ?>
  <?php echo alert("Aynı anda 5'den fazla Destek Talebi açamazsınız! Lütfen birini kapatıp deneyiniz.","danger text-center"); ?>
<?php endif; ?>
<?php
    defined("ADMIN") ? null: die('Hacking?');
    ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<?php
	$get_sun = get("sunucu");

	if (!empty($get_sun)) {
    $squery = query("SELECT * FROM sunucular where sunucu_id='$get_sun'");
		$sunucu_kontrol = row($squery);

		if (rows($squery) < 1) {
			go(ADMIN_URL."/?go=consol");
		}
	}
?>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
      <ul class="block-options">
        <form action="" method="GET" onchange='submit()'>
          <input type="hidden" name="go" value="consol">
          <select name="sunucu" class="form-control input-sm" style="margin-top: -3px;">
            <?php
              $squery = query("SELECT * FROM sunucular ORDER BY sunucu_id ASC");
              if(rows($squery) < 1){
                echo '<option disabled selected>Hiç sunucu ekli değil.</option>';
              }else{
                echo "<option disabled selected>Bir sunucu seçin</option>";
                while($srow = row($squery)){
                  echo '<option value="'.$srow['sunucu_id'].'"';
                  echo $srow['sunucu_id'] == $get_sun ? ' selected' : null;
                  echo '>'.ss($srow['sunucu_adi']).'</option>';
                }
              }
            ?>
          </select>
        </form>
      </ul>
      <h3 class="block-title">Web Konsol</h3>
  </div>
  <div class="block-content">
    <div class="form-group">
      <div class="input-group">
        <?php if (empty($get_sun)): ?>
          <input type="text" disabled="" class="form-control" placeholder="İstediğiniz komudu başında  /  olmadan yazınız.">
          <span class="input-group-btn">
            <button disabled="" class="btn btn-white" type="button">Gönder!</button>
          </span>
        <?php else: ?>
          <form action="" style="display: table-row;" method="post" onsubmit="return false;">
						<input type="text" id="consol_cmd" class="form-control" placeholder="İstediğiniz komudu başında  /  olmadan yazınız.">
						<span class="input-group-btn">
							<input onclick="komut_gonder()" class="btn btn-success" type="submit" value="Gönder!">
						</span>
					</form>
          <script type="text/javascript">
            function komut_gonder() {
              var komut = $("#consol_cmd").val();
              komut = jQuery.trim(komut);
              
              if (komut == "") {
                alert("Lütfen komut yazmadan göndermeye çalışmayın.");
              }else{
                $("#consol_cmd").val("");
                $(".consol-yellow").html("");
                var date = new Date();
                
                $.ajax({
                  type: "POST",
                  url: ADMIN_URL+"/ajax/consol.ajax.php",
                  data: "server_id=<?=$get_sun?>&consol_cmd="+komut,
                  success: function(sonuc){
										if (sonuc == 'ok'){
                      $("#consol_c").prepend('<span class="console-date">'+date.toString("yyyy-MM-dd HH:mm:ss")+'</span> <span class="console-user">[<?=$user["uye_kadi"]?>]</span> : '+komut+"<br>");
										}else{
											$("#consol_c").prepend('<span class="console-date">'+date.toString("yyyy-MM-dd HH:mm:ss")+'</span> <span class="console-user">[Konsol]</span> : <span class="console-danger">Bir hata meydana geldi. ('+sonuc+')</span><br>');
										}
									}
                });
              }
            }
          </script>	
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div style="padding-top: 11px; padding: 15px;" class="bbdt">
			
		<?php

		 if (empty($get_sun)) { ?>
			<div class="console" style="min-height: 20px;">
				<span class="console-danger">Konsolu kullanmak için lütfen server seçiniz</span>
			</div>
		<?php }else{ ?>
			<div class="table-responsive console" id="consol_c" style="height: 380px; overflow-y: scroll;">
			<?php 
				$cmd_komut = query("SELECT * FROM konsol WHERE sunucu_id='$get_sun' ORDER BY komut_id DESC LIMIT 30");

				if(rows($cmd_komut) < 1){
					echo "<span class='consol-yellow'>Bu sunucuya ait komut geçmişi bulunamadı.</span>";
				}else{
					while($komutlar = row($cmd_komut)) { 
					$uye_bilgi = row(query("SELECT * FROM uyeler WHERE uye_id = '".$komutlar["uye_id"]."' "));
			?>
				<span class="console-date"><?=$komutlar["komut_tarih"]?></span> <span class="console-user">[<?=$uye_bilgi["uye_kadi"]?>]</span> : <?=$komutlar["komut_n"]?><br>
			<?php } } ?>
			</div>
		<?php } ?>

	</div>
</div>
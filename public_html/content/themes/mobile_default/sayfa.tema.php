<?php
	@$link = get("link") ? get("link") : false;
	if(!$link){
		go_js(URL);
		exit;
	}
	$query = query("SELECT * FROM sayfalar WHERE sayfa_link = '$link'");
	if(rows($query) > 0){
        $row = row($query);
?>
  <h3 class="ui-bar ui-bar-a ui-corner-all"><?php echo ss($row["sayfa_baslik"]); ?></h3>
  <div class="ui-body ui-body-a ui-corner-all">
      <?php echo nl2br($row["sayfa_icerik"]); ?>
  </div>
<?php }else{ go_js(URL); } ?>
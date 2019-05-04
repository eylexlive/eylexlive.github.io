<?php
	@$link = get("link") ? get("link") : false;
	if(!$link){
		go(URL);
		exit;
	}
	$query = query("SELECT * FROM sayfalar WHERE sayfa_link = '$link'");
	if(rows($query) > 0){
        $row = row($query);
?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <?=$row["sayfa_baslik"]?>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo nl2br($row["sayfa_icerik"]); ?>
                    </div>
                </div>
            </div>
        </div>
<?php }else{ go(URL); } ?>
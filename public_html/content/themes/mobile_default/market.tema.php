<?php !session('login') ? go_js(URL."/kayitol") : null; ?>
<h3 class="ui-bar ui-bar-a ui-corner-all">Market - Sunucular</h3>
<ul data-role="listview" data-inset="true"> 
<?php
  $varmi = query("SELECT * FROM sunucular ORDER BY sunucu_id ASC");
  if(rows($varmi) < 1){
    echo alert('Hiç sunucu eklenmemiş.');
  }else{
    while($row = row($varmi)) {
  ?>   
    <li><a href="<?=URL?>/market/<?=$row["sunucu_link"]?>"><img src="<?=INC_PATH?>/items/<?=str_ireplace(":","-",$row["sunucu_icon_id"])?>.png" class="ui-li-icon" style="top: 0.8em;" alt="<?=$row["sunucu_adi"]?>"><?=$row["sunucu_adi"]?></a></li>
  <?php } ?>
<?php } ?>
</ul>
<?php $s = new Sidebar_Set(__FILE__); ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?=$s->resp("baslik")?></h3>
  </div>
  <div class="panel-body">
    <?=$s->resp("textarea")?>
  </div>
</div>
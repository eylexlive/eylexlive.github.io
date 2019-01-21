<?php

mysql_select_db($database_baglanti, $baglanti);
$query_pagesfot = "SELECT * FROM pages WHERE pageactive = '1' ORDER BY id DESC";
$pagesfot = mysql_query($query_pagesfot, $baglanti) or die(mysql_error());
$row_pagesfot = mysql_fetch_assoc($pagesfot);
$totalRows_pagesfot = mysql_num_rows($pagesfot);
?>
<?php 
// Show IF Conditional region1 
if (@$_SESSION['kt_login_id'] != "") {
?> 
<div class="input-control text full-size" data-role="input">
                <input type="text" placeholder="Site içi Player arama sorgusu.....">
                <button class="button"><span class="mif-search"></span></button>
            </div>
 <?php } 
// endif Conditional region1
?>           
            
<?php if ($totalRows_pagesfot > 0) { // Show if recordset not empty ?>
  <ul class="horizontal-menu compact">
    
    <?php do { ?>
      <li><a href="p<?php echo $row_pagesfot['id']; ?>-<?php echo permalink($row_pagesfot['pagetitle']); ?>-.htm"><i class="fa fa-file-text-o"></i> <?php echo $row_pagesfot['pagetitle']; ?> </a></li>
      <?php } while ($row_pagesfot = mysql_fetch_assoc($pagesfot)); ?>
  </ul>
  <?php } // Show if recordset not empty ?>
<footer style="margin-top:7px; border-top:solid 1px #09C; border-bottom:solid 1px #09C; margin-bottom:7px;">
    <div class="container" style="padding:15px">
          Copyright <strong>&copy;</strong> 2015-<?php echo date("Y"); ?>  <?php echo $row_ayar['copy']; ?>&reg; <small><em><?php echo $row_ayar['title']; ?></em> <b>Tüm Hakları Saklıdır.</b> </small>
        <a href="http://www.cndesing.com/bagis.php" style="float:right" target="_blank" title="Tasarım & Kodlama">CN Game &#8482; <img src="<?php echo $row_ayar['siteurl']; ?>images/arti.gif" width="9" height="9" alt="CNGame" /></a>
    </div>
    
</footer>
<?php
mysql_free_result($pagesfot);
?>

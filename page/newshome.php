<?php
$maxRows_newhme = 5;
$pageNum_newhme = 0;
if (isset($_GET['pageNum_newhme'])) {
  $pageNum_newhme = $_GET['pageNum_newhme'];
}
$startRow_newhme = $pageNum_newhme * $maxRows_newhme;

mysql_select_db($database_baglanti, $baglanti);
$query_newhme = "SELECT * FROM news ORDER BY id DESC";
$query_limit_newhme = sprintf("%s LIMIT %d, %d", $query_newhme, $startRow_newhme, $maxRows_newhme);
$newhme = mysql_query($query_limit_newhme, $baglanti) or die(mysql_error());
$row_newhme = mysql_fetch_assoc($newhme);

if (isset($_GET['totalRows_newhme'])) {
  $totalRows_newhme = $_GET['totalRows_newhme'];
} else {
  $all_newhme = mysql_query($query_newhme);
  $totalRows_newhme = mysql_num_rows($all_newhme);
}
$totalPages_newhme = ceil($totalRows_newhme/$maxRows_newhme)-1;
?>
<div class="panel warning">
    <div class="heading">
        <span class="title"><i class="fa fa-newspaper-o" style="color:#333; font-weight:bold"></i> BİZDEN HABERLER </span>
  </div>
    <div class="content" style="background-color:#FFF">
      <?php if ($totalRows_newhme == 0) { // Show if recordset empty ?>
  <h1>Kayıtlı haber bulunamadı...</h1>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_newhme > 0) { // Show if recordset not empty ?>
  <ul class="step-list">
    <?php do { ?>
      <li>
        <h4 class="no-margin-top"><?php echo $row_newhme['title']; ?></h4>
        <hr  class="bg-blue"/>
        <div> <?php echo $row_newhme['subcontent']; ?><br />
          <form method="post" action="haber-<?php echo $row_newhme['id']; ?>-<?php echo permalink($row_newhme['title']); ?>.htm">
             <button class="button primary" title="<?php echo $row_newhme['title']; ?>">Devamını Oku</button>
          </form>
          </div>
        <?php } while ($row_newhme = mysql_fetch_assoc($newhme)); ?>
    </li>
  </ul>
   
  <?php } // Show if recordset not empty ?>
 
    </div>
</div>
<?php
mysql_free_result($newhme);
?>

<?php
mysql_select_db($database_baglanti, $baglanti);
$query_serv = "SELECT * FROM serverconfig WHERE id = 1";
$serv = mysql_query($query_serv, $baglanti) or die(mysql_error());
$row_serv = mysql_fetch_assoc($serv);
$totalRows_serv = mysql_num_rows($serv);
?>

<table width="100%" border="0" class="table-bordered">
  <tr class="success">
  

    <td>SUNUCU ADRESİ</td>
  </tr>
  <tr>
    <td><?php echo $row_serv['sip']; ?></td>
  </tr>
  <tr class="success">
    <td>ONLİNE PLAYER</td>
  </tr>
  <tr>
    <td><?php
$ipgel = $row_serv['sip'];
$portgel = $row_serv['port'];
$playeronline = file_get_contents('http://minecraft-api.com/api/query/playeronline.php?ip=$ipgel&port=$portgel'); 
echo $playeronline; 
?> /
      <?php
$ipgel = $row_serv['sip'];
$portgel = $row_serv['port'];
$maxplayer  = file_get_contents('http://minecraft-api.com/api/query/playeronline.php?ip=$ipgel&port=$portgel'); 
echo $maxplayer ; 
?> 
      
      </td>
  </tr>
  <tr class="success">
    <td><div class="progress progress-striped active">
      <div class="bar bar-success" style="width:  <?php
$ipgel = $row_serv['sip'];
$portgel = $row_serv['port'];
$maxplayer  = file_get_contents('http://minecraft-api.com/api/query/playeronline.php?ip=$ipgel&port=$portgel'); 
echo $maxplayer ; 
?> %;"></div>
    </div></td>
  </tr>
  
  <tr class="success">
    <td>VERSİYON</td>
  </tr>
  <tr class="success">
    <td><center><?php
$ipgel = $row_serv['sip'];
$portgel = $row_serv['port'];
$version = file_get_contents('http://minecraft-api.com/api/query/version.php?ip=$ipgel&port=$portgel'); 
echo $version; 
?></center></td>
  </tr>
  <tr class="success">
    <td>SERVERDE BULUNAN OYUN TÜRLERİ</td>
  </tr>
  <tr>
    <td><?php 
$parcala = explode(',', $row_serv['soyun']); 
for($i=0; $i<count($parcala); $i++){ 
$kelime = $parcala[$i]; 
echo '<span class="label label-info">'.$kelime.'</span> '; 
} 
?></td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="get" action="Minecraft:<?php echo $row_serv['sip']; ?>?add">
      <input type="submit" onClick="prompt('Sunucuya baglanmak i&ccedil;in bu IP adresi kullanin. Kopyala / Multiplayer altinda Minecraft Client yapistirin:', '<?php echo $row_serv['sip']; ?>'); return false;" name="transfer5" value="OYUNA KATIL" class="btn btn-large btn-block btn-primary" />
    </form></td>
  </tr>
</table>
<?php
mysql_free_result($serv);
?>

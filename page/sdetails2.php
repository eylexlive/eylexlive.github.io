<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "2ff0f61e-ab1c-449e-b79a-dcc0e023e428", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<?php 
$APIkey = $row_serhead['sadi'];
$json = file_get_contents("http://minecraft-mp.com/api/?object=servers&element=detail&key=$APIkey");
$data = json_decode($json);
	
	$serveradi = $data->name;
	$bizeoyver = $data->id;
	$serverip = $data->address;
	$sport = $data->port;
	$ulke = $data->location;
	$durum = $data->is_online;
	$online = $data->players;
	$max = $data->maxplayers;
	$versiyon = $data->version;
	$oylar = $data->votes;
	$rank = $data->score;
	$up = $data->uptime;
	$link = $data->url;
	
?>
<h4><img src="http://cdn.minecraft-mp.com/images/flags/<?php echo $ulke ?>.png" alt="<?php echo $ulke ?>" title="Server <?php echo $ulke ?>'de" border="0"/> <strong title="Minecraft Server IP Adresimiz"><?php echo $serverip; ?>:<?php echo $sport; ?></strong></h4>
<p align="center">
  <button class="button cycle-button success" type="button" title="Şuan online olan kullanıcılar"><?php echo $online; ?></button>
/
<button class="button cycle-button primary" type="button" title="Server toplam player sayısı"><?php echo $max; ?></button>
</p>
<table class="table table-bordered">
  <tbody>
    <tr>
      <td width="40%"><i class="fa fa-signal"></i> <strong>Uptime</strong></td>
      <td><span class="tag success"><?php echo $up; ?>%</span></td>
    </tr>
    <tr>
      <td><i class="fa fa-thumbs-o-up"></i> <strong>Vote(s)</strong></td>
      <td><span class="tag info"><?php echo $oylar; ?></span></td>
    </tr>
    <tr>
      <td><i class="fa fa-cog"></i> <strong>Versiyon</strong></td>
      <td><span class="tag info"><?php echo $versiyon; ?></span></td>
    </tr>
    <tr>
      <td><i class="fa fa-power-off"></i> <strong>Durum</strong></td>
      <td><?php 

if ($durum == '1') 
{ 
    echo ' <span class="tag success">Online</span> '; 
} 
elseif ($durum == '0') 
{ 
    echo '<span class="tag alert">Offline</span>'; 
} 
else 
{ 
    echo 'Hata Olustu'; 
} 

?></td>
    </tr>
    <tr>
      <td colspan="2"><script src="http://minecraft-mp.com/embed.js?id=<?php echo $bizeoyver ?>&type=votes&size=normal"></script></td>
    </tr>
  </tbody>
</table>
<p>
<?php 
$parcala = explode(',', $row_serhead['soyun']); 
for($i=0; $i<count($parcala); $i++){ 
$kelime = $parcala[$i]; 
echo '<span class="tag info">'.$kelime.'</span> '; 
} 
?>
</p>
<p>
<span class='st_facebook_large' displayText='Facebook'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
<span class='st_linkedin_large' displayText='LinkedIn'></span>
<span class='st_blogger_large' displayText='Blogger'></span>
<span class='st_email_large' displayText='Email'></span>
</p>
<form  name="form1" method="get" action="Minecraft:<?php echo $serverip; ?>:<?php echo $sport; ?>?add">
      <button type="submit" onClick="prompt('Sunucuya baglanmak i&ccedil;in bu IP adresi kullanin. Kopyala / Multiplayer altinda Minecraft Client yapistirin:', '<?php echo $serverip; ?>:<?php echo $sport; ?>'); return false;" name="transfer5" class="button primary lighten primary loading-pulse rounded"> OYUNA KATIL <span class="icon mif-share"></span></button>
</form>
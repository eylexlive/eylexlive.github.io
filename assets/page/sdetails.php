<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "2ff0f61e-ab1c-449e-b79a-dcc0e023e428", doNotHash: false, doNotCopy: false, hashAddressBar: true});</script>
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
<table width="100%" border="0" class="table-bordered">
  <tr class="success">
  

    <td><i class="fa fa-play"></i> SUNUCU ADRESI</td>
  </tr>
  <tr>
    <td><img src="http://cdn.minecraft-mp.com/images/flags/Turkey.png" alt="Turkey" title="Turkey" border="0"/> <strong><?php echo $serverip; ?>:<?php echo $sport; ?></strong></td>
  </tr>
  <tr class="success">
    <td><i class="fa fa-group"></i> ONLINE PLAYER</td>
  </tr>
  <tr>
    <td>
   <center><button class="btn btn-small btn-success" type="button"><?php echo $online; ?></button> / <button class="btn btn-small btn-warning" type="button"><?php echo $max; ?></button></center>
    </td>
  </tr>
  <tr>
    <td>
    <table class="table table-bordered">
			<tbody>
				<tr>
					<td width="40%"><i class="fa fa-signal"></i> <strong>Uptime</strong></td>
					<td><span class="badge badge-success"><?php echo $up; ?>%</span></td>
				</tr>
				<tr>
					<td><i class="fa fa-thumbs-o-up"></i> <strong>Vote(s)</strong></td>
					<td><?php echo $oylar; ?></td>
				</tr>
				<tr>
				  <td><i class="fa fa-cog"></i> <strong>Versiyon</strong></td>
				  <td><a class="label label-info"><?php echo $versiyon; ?></a></td>
			  </tr>
				<tr>
				  <td><i class="fa fa-power-off"></i> <strong>Durum</strong></td>
				  <td>
                  <?php 

if ($durum == '1') 
{ 
    echo ' <span class="badge badge-success">Online</span> '; 
} 
elseif ($durum == '0') 
{ 
    echo '<span class="badge badge-important">Offline</span>'; 
} 
else 
{ 
    echo 'Hata Olustu'; 
} 

?>
                  </td>
			  </tr>
				<tr>
				  <td colspan="2">
                  <script src="http://minecraft-mp.com/embed.js?id=<?php echo $bizeoyver; ?>&type=votes&size=normal"></script>
                  </td>
			  </tr>
			</tbody>
		</table>
      </td>
  </tr>
  
  <tr>
    <td></td>
  </tr>
  <tr class="success">
    <td>SERVERDE BULUNAN OYUN T&Uuml;RLERI</td>
  </tr>
  <tr>
    <td><?php 
$parcala = explode(',', $row_serhead['soyun']); 
for($i=0; $i<count($parcala); $i++){ 
$kelime = $parcala[$i]; 
echo '<span class="label label-info">'.$kelime.'</span> '; 
} 
?></td>
  </tr>
  <tr>
    <td>
<span class='st_facebook_large' displayText='Facebook'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
<span class='st_linkedin_large' displayText='LinkedIn'></span>
<span class='st_blogger_large' displayText='Blogger'></span>
<span class='st_email_large' displayText='Email'></span>
</td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="get" action="Minecraft:<?php echo $serverip; ?>:<?php echo $sport; ?>?add">
      <input type="submit" onClick="prompt('Sunucuya baglanmak i&ccedil;in bu IP adresi kullanin. Kopyala / Multiplayer altinda Minecraft Client yapistirin:', '<?php echo $serverip; ?>:<?php echo $sport; ?>'); return false;" name="transfer5" value="OYUNA KATIL" class="btn btn-small btn-block btn-primary" />
    </form></td>
  </tr>
</table>
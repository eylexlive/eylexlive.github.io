<!DOCTYPE html PUBLIC>
<html lang="tr-TR">
<head>

	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	
	<title>Vitoll Bilişim</title>
	
	<link rel="shortcut icon" href="dosya/resimler/vitoll-logo.png"> <!-- Burdan Sitenin İconunu Ayarla! -->
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	
	<!-- Normal CSS -->
	<link rel="stylesheet" href="dosya/css/ayar" />
	
	
</head>
<body>
	
	<div class="resim">
	
		<div class="container">
			<div class="row">
			
				<div class="col-md-12">
					
					<center>
						
						<br /><img src="dosya/resimler/vitoll-logo.png" alt="Vitoll Bilişim - Minecraft Site Skripti" height="200" /><br /><br /> <!-- Burasını Videoda Anlattım -->
						
						<font size="6" face="Fantasy" color="red">------ </font><font size="6" face="Fantasy" color="#fff"> Vitoll Bilişim </font><font size="6" face="Fantasy" color="red"> ------</font> <!-- Burdan Sunucu isimini bul Ayarla -->
						
						<hr />
					</center>
					
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="container">
	
		<div class="row">
		
			<div class="col-md-4">
			
				<br /><div class="panel panel-success">
					<div class="panel-heading"><center><font size="5" face="" color=""><b>Sunucu</b></font></center></div>
						<div class="panel-body">
						
							<center>
							
								<font size="4" face="" color=""><b>Sunucu İsmi</b></font><br />
								<font size="4" face="" color="">Hypixel</font><br /><br /> <!-- Burdan Sunucu Adını Ayarla -->
								
								<font size="4" face="" color=""><b>Oyuncu Sayısı</b></font><br />
							
								
								<?php 
							
									require "Query.php";
									header('Content-type: text/html; charset=utf8;');
									$query = new Query('play.hypixel.net', 25565); #Burdan Sunucu IPsini Değiştir. Bukadar!
									$server = @$query->get()['general'];

									if($server['online']) {
										echo '<font size="4" face="" color="">' . $server['numplayers'] . '</font><font size="4" face="" color="gray"> / </font><font size="4" face="" color="">' . $server['maxplayers'] . '</font>';
										
									} else {
										echo '<font size="5" face="" color="red">0</font>';
									}
							
								?> <br /><br />
								
								<font size="4" face="" color=""><b>Sunucu Sürümü</b></font><br /> 
								<font size="4" face="" color="">1.8/1.9/1.10/1.11/1.12</font><br /><br /> <!-- Burdan Sunucu Sürümlerini Ayarla -->
								
								<font size="4" face="" color=""><b>Sunucu Ip</b></font><br />
								<font size="4" face="" color="green">Play.Hypixel.Net</font> <!-- Burdan Sunucu IPsini Ayarla -->
								
							</center>
						
						</div>
						
				</div>
			
			</div>
			
			<div class="col-md-4">
			
				<br /><div class="panel panel-danger">
					<div class="panel-heading"><center><font size="5" face="" color=""><b>Haberler</b></font></center></div>
						<div class="panel-body"> 
						
							<div class="haber"> <!-- Bu Kısmı Kopyala Yapışır Yaparak Sitenize Haber Ekleyebilirsiniz! -->
							
								<center>
								
									<font size="4" ><b>Sunucumuz Yeni Açılmıştır!</b></font><br /> <!-- Başlık -->
									
									<font size="" face="" color=""> <!-- Buraya Yazacağınız Yazı -->
									
										Merhaba Arkaşlar Bu Skript İle Sitesi Olmayan <b>MineCraft Sunucularını</b> Siteli Hale Getirdik!
									
									</font>
									
									<hr />
								
								</center>
							
							</div> <!-- Buraya Kadar Kopyalanacak! -->
							
							<div class="haber"> <!-- Bu Kısmı Kopyala Yapışır Yaparak Sitenize Haber Ekleyebilirsiniz! -->
							
								<center>
								
									<font size="4" ><b>Vitoll Bilişim Yapımı Skript</b></font><br />
									
									<font size="" face="" color=""> <!-- Buraya Yazacağınız Yazı -->
									
										Bu Skript <b>Vitoll Bilişim</b> Tarafından Hazırlanıp <b>Sadettin Yaycı</b> Tarafından Kodlanmıştır!
 									
									</font>
									
									<hr />
								
								</center>
							
							</div> <!-- Buraya Kadar Kopyalanacak! -->
							
						
						</div>
					</div>	
			</div>
			
			<div class="col-md-4">
			
				<br /><div class="panel panel-info">
					<div class="panel-heading"><center><font size="5" face="" color=""><b>Yetkililer</b></font></center></div>
						<div class="panel-body"> 
						
							<table class="table table-striped table-bordered"> 
								<thead>
									<tr>
									  <th class="text-center">#</th>
									  <th>İsim</th>
									  <th class="text-center">Yetkisi</th>
									</tr>
								</thead>
								  
								<tbody>
									
									<tr>
										<td class="text-center"><img src="http://cravatar.eu/avatar/VitalYT/20.png"></td>
										<td>VitalYT</td>
										<td class="text-center"><b>Kurucu</b></td>
									</tr>
									
									<tr>
										<td class="text-center"><img src="http://cravatar.eu/avatar/AtakanYumru/20.png"></td>
										<td>AtakanYumru</td>
										<td class="text-center"><b>Kurucu</b></td>
									</tr>
									
									<tr>
										<td class="text-center"><img src="http://cravatar.eu/avatar/MrSemo/20.png"></td>
										<td>MrSemo</td>
										<td class="text-center"><b>Kurucu</b></td>
									</tr>
									
								</tbody>
							</table>
						
						</div>
					</div>	
			</div>
		
		</div>
	
	</div>
	
	<br /><br /><br /><br /><br /><br />
	
	<div class="container-fluid footer-f">
	
		<div class="row">
		
			<div class="col-md-12">
			
				<div class="panel panel-default">
				
					<div class="panel-body">
					
						Tüm Hakları Saklıdır. Copyright © 2017 <b>Vitoll Bilişim Farkıyla!</b>
					
						<span class="pull-right">
						
							<a data-toggle="tooltip" data-placement="top" title href="http://vitoll.ml/" target="_Blank" data-orginal-title="Vitoll Bilişim Minecraft Site">
							
								<strong>Minecraft Site</strong>
							
							</a>
							<strong> / </strong>
							<b>Vitoll Bilişim</b>
						</span>
					</div>
					
				
				</div>
			
			</div>
		
		</div>
	
	</div>
	
</body>
</html>
<?php
error_reporting(0);
/*
---------------------- CNGame Minecraft Server Portali ---------------------------------

@@@@@@@@   @@@@@@@@      @@@@@@@@     @@@@@     @@@@@   @@@@@   @@@@@@@@
@@@@@@@@   @@@@@@@@      @@@@@@@@   @@@@ @@@@   @@@@@@ @@@@@@   @@@@@@@@
@@         @@@  @@@      @@@        @@@   @@@   @@@@ @@@ @@@@   @@@
@@         @@@  @@@      @@@  @@@   @@@@@@@@@   @@@@  @  @@@@   @@@@@@@@
@@         @@@  @@@      @@@   @@   @@@@@@@@@   @@@@     @@@@   @@@
@@@@@@@@   @@@  @@@      @@@@@@@@   @@@   @@@   @@@@     @@@@   @@@@@@@@
@@@@@@@@   @@@  @@@      @@@@@@@@   @@@   @@@   @@@@     @@@@   @@@@@@@@


Bu Minecraft portal yazilimi CNGame tarafindan olusturulmus ve kodlanmistir.
Script'in alt tarafindaki tasarim ve kodlama yazisini silmezseniz sevinirim. :)
  ----------------------- http://www.cngame.enjin.com -----------------------
*/

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_baglanti = "localhost";
$database_baglanti = "mcregister";
$username_baglanti = "root";
$password_baglanti = "mysql";
$baglanti = mysql_pconnect($hostname_baglanti, $username_baglanti, $password_baglanti) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET CHARACTER SET UTF-8");

$dosya = md5("site").".html";
$cache = "cache/".$dosya;
$sure = 1600;
if (time() - $sure < filemtime($cache)){
	readfile($cache);
	}else{
		
		}

$inj = array ('select', 'insert', 'delete', 'update', 'drop table', 'union', 'null', 'SELECT', 'INSERT', 'DELETE', 'UPDATE', 'DROP TABLE', 'UNION', 'NULL','order by','order  by'); 
for ($i = 0; $i < sizeof ($_GET); ++$i){ for ($j = 0; $j < sizeof ($inj); ++$j){ foreach($_GET as $gets){ if(preg_match ('/' . $inj[$j] . '/', $gets)){ $temp = key ($_GET); $_GET[$temp] = ''; 
exit; 
continue; 
} 
} 
} 
}
?>
<?php 
	  function GetIP(){
	if(getenv("HTTP_CLIENT_IP")) {
 		$ip = getenv("HTTP_CLIENT_IP");
 	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
 		$ip = getenv("HTTP_X_FORWARDED_FOR");
 		if (strstr($ip, ',')) {
 			$tmp = explode (',', $ip);
 			$ip = trim($tmp[0]);
 		}
 	} else {
 	$ip = getenv("REMOTE_ADDR");
 	}
	return $ip;
}

function permalink($string)
{
$find = array('Ç', 'S', 'G', 'Ü', 'I', 'Ö', 'ç', 's', 'g', 'ü', 'ö', 'i', '+', '#');
$replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
$string = strtolower(str_replace($find, $replace, $string));
$string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
$string = trim(preg_replace('/\s+/', ' ', $string));
$string = str_replace(' ', '-', $string);
return $string;
}
	  ?>
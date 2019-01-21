<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_baglanti, $baglanti);
$query_sergels = "SELECT * FROM serverconfig WHERE id = 1";
$sergels = mysql_query($query_sergels, $baglanti) or die(mysql_error());
$row_sergels = mysql_fetch_assoc($sergels);
$totalRows_sergels = mysql_num_rows($sergels);
 

$APIkey = $row_sergels['sadi'];
$json = file_get_contents("http://minecraft-mp.com/api/?object=servers&element=detail&key=$APIkey");
$data = json_decode($json);
	
	$serveradi = $data->name;
	$serverid = $data->id;
	
?>
<style type="text/css">
<!--
.collapse{display:none}textarea {
	height: 50px;
	width: 70%;
}
.collapse.in{display:block}tr.collapse.in{display:table-row}tbody.collapse.in{display:table-row-group}
.panel .content .siteneekleic {
	padding: 20px;
	background-color: #EFEFEF;
	margin-top: 0px;
	margin-right: 15px;
	margin-bottom: 15px;
	margin-left: 15px;
}
-->
</style>

<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Default -->
<div class="panel" >
    <div class="heading">
        <span class="title"><i class="fa fa-code"></i> SİTENE BANNERLARIMIZI EKLE</span>
  </div>
    <div class="content">
    <div class="siteneekleic">
<h2>Vote buttons</h2>
<hr />

<script src="http://minecraft-mp.com/embed.js?id=<?php echo $serverid ?>&type=votes&size=normal"></script>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#button_code1">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="button_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea cols="60" rows="3" class="form-control"><script src="http://minecraft-mp.com/embed.js?id=<?php echo $serverid ?>&type=votes&size=normal"></script></textarea></div></div>
</div>

<div style="height: 20px;"></div>

<script src="http://minecraft-mp.com/embed.js?id=<?php echo $serverid ?>&type=votes&size=small"></script>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#button_code2">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="button_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" cols="60" rows="3"><script src="http://minecraft-mp.com/embed.js?id=<?php echo $serverid ?>&type=votes&size=small"></script></textarea></div></div>
</div>


<div style="height: 30px;"></div>


<h2>Banners (600x100)</h2>
<hr />


<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code1">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" cols="60" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $bizeoyver ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $bizeoyver ?>.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code1">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" cols="60" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>
<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-2.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code2">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-2.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code2">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>-2.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>
<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-3.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code3">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-3.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code3">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>-3.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>
<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-4.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code4">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-4.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code4">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>-4.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>
<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-5.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code5">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-5.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code5">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>-5.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>
<div><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-6.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_html_code6">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_html_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/banner-<?php echo $serverid ?>-6.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#banner_bbcode_code6">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="banner_bbcode_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/banner-<?php echo $serverid ?>-6.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>


<div style="height: 20px;"></div>

<h2>Leaderboard (728x90)</h2>
<hr>


<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code1">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code1">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-2.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code2">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-2.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code2">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-2.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-3.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code3">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-3.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code3">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-3.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-4.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code4">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-4.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code4">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-4.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-5.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code5">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-5.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code5">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-5.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-6.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_html_code6">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_html_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-6.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#leaderboard_bbcode_code6">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="leaderboard_bbcode_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/leaderboard-<?php echo $serverid ?>-6.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>


<div style="height: 20px;"></div>

<h2>Classic banners (468x60)</h2>
<hr>
<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code1">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code1">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-2.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code2">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-2.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code2">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-2.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-3.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code3">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-3.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code3">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-3.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-4.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code4">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-4.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code4">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code4" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-4.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-5.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code5">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-5.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code5">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code5" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-5.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-6.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_html_code6">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_html_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-6.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#regular-banner_bbcode_code6">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="regular-banner_bbcode_code6" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/regular-banner-<?php echo $serverid ?>-6.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div style="height: 20px;"></div>

<h2>Half-Banners (234x60)</h2>
<hr>


<div><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_html_code1">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_html_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_bbcode_code1">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_bbcode_code1" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/half-banner-<?php echo $serverid ?>.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-2.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_html_code2">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_html_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-2.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_bbcode_code2">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_bbcode_code2" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-2.png[/img][/url]</textarea></div></div>
</div>

<div style="height: 10px;"></div>

<div><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-3.png" alt="<?php echo $serveradi ?>" title="<?php echo $serveradi ?>"></div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_html_code3">HTML Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_html_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3"><a href="http://minecraft-mp.com/server-s<?php echo $serverid ?>" target="_blank"><img src="http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-3.png" border="0"></a></textarea></div></div>
</div>

<div style="height: 5px;"></div>
<div><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#half-banner_bbcode_code3">BBCODE Kodunu Göster  <i class="fa fa-info-circle"></i></button></div>
<div style="height: 5px;"></div>
<div id="half-banner_bbcode_code3" class="collapse">
<div class="row"><div class="col-xs-6"><textarea class="form-control" rows="3">[url=http://minecraft-mp.com/server-s<?php echo $serverid ?>][img]http://minecraft-mp.com/half-banner-<?php echo $serverid ?>-3.png[/img][/url]</textarea></div></div>
</div></div>
</div></div>
<?php
mysql_free_result($sergels);
?>

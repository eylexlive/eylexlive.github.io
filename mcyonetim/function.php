<?php 

	function p($par, $st = false){
		if ($st){
			return htmlspecialchars(addslashes(trim($_POST[$par])));
			}else{
				return addslashes(trim($_POST[$par]));
				}
		}
		
	function g($par){
		return strip_tags(trim(addslashes($_GET[$par])));
		}
		
	function query($query){
		return mysql_query($query);
		}
		
	function row($query){
		return mysql_fetch_array($query);
	
		}
		
	function rows($query){
		return mysql_num_rows($query);
	
		}
		
	function go($par, $time = 0){
		if($time == 0){
			header("Location: {$par}");
			}else{
				header("Refresh: url={$par}");
				}
	
		}
?>
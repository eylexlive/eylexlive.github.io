<?php 

/**
 * Sidebar Class System
 * @author CraftWeb Software Team
 * @version 1.0
 * @copyright 2017 CraftWeb Software
 */
 
class sidebar {
  
  function get2($path,$id=1) {
    $path = THEMES_SET."/".$path;
    if (file_exists($path)) {
      $sb_json = mset("sidebar");
      $sb_array = json_decode($sb_json,true);
      $sb_id = "sb".$id;
      foreach ($sb_array[$sb_id] as $key) {
        if (!is_dir("$path/$key.php")) {
          require_once "$path/$key.php";
        }
      }
    }
  }
  
  function get($path,$id=1){
    $path = "content/sidebar";
    if (file_exists($path)) {
      
      $sb_array = json_decode(mset("sidebar"),true);
      $sb_id = "sb".$id;
      foreach ($sb_array[$sb_id] as $key) {
        if (file_exists(THEMES_SET."/sidebar/$key.php")) {
          require_once THEMES_SET."/sidebar/$key.php";
        }else{
          if (file_exists("$path/$key.php")) {
            require_once "$path/$key.php";
          }  
        }
      }
      
    }
    
  }
  /* function r($path) {
    $path = THEMES_SET."/".$path;
    if (file_exists($path)) {
      $dir = opendir($path);
      while (($dosya = readdir($dir)) != false) {
        if (!is_dir($dosya)) {
          require_once "$path/$dosya";
        }
      }
      closedir($dir);
    }
  } */
}
class Sidebar_Set extends sidebar {
  function __construct($path=__FILE__) {
    $this->path = $path;
  }
  
  function resp($get,$doc=".php"){
    $path = $this->path;
    $slug = basename($path, $doc);
    $row =  row(query("SELECT * FROM sidebar WHERE sidebar_name='$slug'"));
    $json_cvp = json_decode($row["sidebar_slug_resp"],true);
    return $json_cvp[$get];
  }
}
?>
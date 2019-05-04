<?php 

/**
 * Update Class System
 * @author CraftWeb Software Team
 * @version 1.0
 * @copyright 2017 CraftWeb Software
 */
 
class CwU {
  function download($link,$name=null,$dizin=null) {
    $link = base64_decode($link);
    $link_info = pathinfo($link);
    $uzanti = strtolower($link_info['extension']);
    $file = ($name) ? $name.'.'.$uzanti : $link_info['basename'];
    $yolcuk = $dizin.$file;
     
    $curl = curl_init($link);
    $fopen = fopen($yolcuk,'w');
     
    curl_setopt($curl, CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_FILE, $fopen);
     
    curl_exec($curl);
    curl_close($curl);
    fclose($fopen);
  }
}


?>
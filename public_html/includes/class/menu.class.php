<?php 
/**
 * Menu Class System
 * @author CraftWeb Software Team
 * @version 1.0
 * @copyright 2017 CraftWeb Software
 */
 
class Menu {
  public function get($menu_slug='',$setting=true,$id=0) {
    $get_query = query("SELECT * FROM menu WHERE menu_slug='$menu_slug' AND menu_ust_id='$id' ORDER BY menu_id ASC");
    if (rows($get_query)) {
      while ($row = row($get_query)) {
        $menu_id = $row["menu_id"];
        $get_json = json_decode($row["menu_json"],true);
        $get_query_sub = query("SELECT * FROM menu WHERE menu_slug='$menu_slug' AND menu_ust_id='$menu_id' ORDER BY menu_id ASC");
        if (rows($get_query_sub)) {
          echo "<li class='dropdown'>";
            echo "<a class='dropdown-toggle' data-toggle='dropdown' href='#' aria-expanded='true'>";
            if ($setting == true) {
              if ($get_json['icon']) {
                echo "<i class='fa fa-fw fa-".$get_json['icon']."'></i> ";
              }
            }
            echo $get_json['title']." <span class='caret'></span></a>";
            echo "<ul class='dropdown-menu'>";
              $this->get($menu_slug,$setting,$row["menu_id"]);
            echo "</ul>";
          echo "</li>";
        }else{
          $get_url = str_ireplace("[URL]",URL,$get_json["url"]);
          $get_target = $get_json["blank"] == "1" ? "target='_blank'":null;
          echo "<li><a href='$get_url' $get_target>";
          if ($setting == true) {
            if ($get_json['icon']) {
              echo "<i class='fa fa-fw fa-".$get_json['icon']."'></i> ";
            }
          }
          echo $get_json['title']."</a></li>";
        }
      }
    }
  }
}

?>
<?php 

/**
 * Menu Class System
 * @author CraftWeb Software Team
 *         Hacii_
 * @version 1.0
 * @copyright 2017 CraftWeb Software
 */
 
class HBM {
  private $b = null;
  
  function __construct($host='localhost',$username='root',$password,$database="",$port='3306',$charset='utf-8') {
    $this->b = mysqli_connect($host,$username,$password,$database,$port);
    mysqli_query($this->b, "SET CHARACTER NAME $charset");
    mysqli_query($this->b, "SET NAME $charset");
  }
  
  function __destruct() {
    if ($this->b) {
      mysqli_close($this->b);
    }
  }
  
  /**
   * Query (query)
   * $q = Mysql Query Kodu
   */
  public function query($q) {
    return mysqli_query($this->b, $q);
  }
  
  /**
   * Array SELECT Query (array_select)
   * $q = Tablo Adı
   * $k = Tablo Stün Adı
   */
  public function array_select($q,$k) {
    $q = $this->query("SELECT * FROM $q");
    foreach ($q as $s) {
      $c[] = $s[$k];
    }
    return $c;
  }
  
  /**
   * Array INSERT (array_insert)
   * $q = Tablo Adı
   * $v = Insert Array
   */
  public function array_insert($q, $v) {
    $col = ""; $val = "";
    foreach ($v as $k => $i) {
      $col .= "$k,";
      $val .= "'$i',";
    }
    $col = substr($col, 0,-1);
    $val = substr($val, 0,-1);
    return $this->query("INSERT INTO $q ($col) VALUES ($val)");
  }
  
  /**
   * Delete Table (delete_table)
   * $q = Tablo Adı
   */
  public function delete_table($q) {
    return $this->query("DROP TABLE $q");
  }
  
  /**
   * Array UPDATE (array_update)
   * $q = Tablo Adı
   * $v = Insert Array
   */
  public function array_update($q, $v, $w="") {
    $col = ""; $r = "";
    foreach ($v as $k => $i) {
      $col .= "$k='$i',";
    }
    $col = substr($col, 0,-1);
    return $this->query("UPDATE $q SET $col $w");
  }
  
}

?>
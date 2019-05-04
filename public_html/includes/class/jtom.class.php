<?php 

  /**
   * Json To Mysql (JtoM)
   */
  class jtom {
    function __construct($json_doc) {
      $this->json = json_decode(file_get_contents($json_doc),true);
    }
    function insert($var,$key) {
      $col = ""; $val = "";
      foreach ($var as $k => $i) {
        $col .= "$k,";
        $val .= "'$i',";
      }
      $col = substr($col, 0,-1);
      $val = substr($val, 0,-1);
      query("INSERT INTO $key ($col) VALUES ($val)");
    }
    
    function update($var,$key,$where) {
      $col = ""; $r = "";
      foreach ($var as $k => $i) {
        $col .= "$k='$i',";
      }
      $col = substr($col, 0,-1);
      query("UPDATE $key SET $col $where");
    }
    
    public function mTo() {
      foreach ($this->json as $key => $value) {
        foreach ($value as $k1 => $v) {
          switch ($v["method"]) {
            case 'insert':
              $this->insert($v["var"],$key);
              break;
            case 'update':
              $this->update($v["var"],$key,$v["where"]);
              break;
            default:
              $this->insert($v["var"],$key);
              break;
          }
        }
      }
    }
  }

?>
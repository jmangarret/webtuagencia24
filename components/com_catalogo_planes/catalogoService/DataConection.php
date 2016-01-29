<?php

require_once RAIZ_ROOT.'configuration.php';
/**
 *
 * This class conect with mysql for execute queries
 * @author andres.ramirez
 *
 */
class DataConection {


  /**
   *
   * Conection var
   * @var Object
   */
  private $conexion;
  /**
   *
   * Contains the response
   * @var Object
   */
  private $resource;
  /**
   *
   * Contains the query
   * @var string
   */
  private $sql;
  /**
   *
   * Contains the number of queries
   * @var int
   */
  public static $queries;

  /**
   *
   * This function conect whit the database
   */
  public function conectarbd(){
    if(!isset($this->conexion)){
      $obj = new JConfig();
      //Conexion con la base de datos, para el funcionamiento de los store procedure
      //en versiones antiguas de php se agregan los 2 ultimos parametros a la funcion de conexion
      	
      $this->conexion = mysql_connect($obj->host, $obj->user, $obj->password, false,65536) or die(mysql_error());
      mysql_select_db($obj->db, $this->conexion) or die(mysql_error());
      mysql_set_charset('utf8');
    }
    $this->queries = 0;
    $this->resource = null;
  }

  /**
   *
   * This function execute the query
   */
  public function execute(){
    if(!($this->resource = mysql_query($this->sql, $this->conexion))){
      return null;
    }
    $this->queries++;
    return $this->resource;
  }

  /**
   *
   * Enter description here ...
   */
  public function alter(){
    if(!($this->resource = mysql_query($this->sql, $this->conexion))){
      return false;
    }
    return true;
  }

  /**
   *
   * Return the objectList
   */
  public function loadObjectList(){
    $this->conectarbd();
    if (!($cur = $this->execute())){
      return null;
    }
    $array = array();
    while ($row = @mysql_fetch_object($cur)){
      $array[] = $row;
    }
    $this->cerrarbd();
    return $array;
  }

  /**
   *
   * Return the objectList
   */
  public function loadAssocList(){
    $this->conectarbd();
    if (!($cur = $this->execute())){
      return null;
    }
    $array = array();
    while ($row = @mysql_fetch_assoc($cur)){
      $array[] = $row;
    }
    $this->cerrarbd();
    return $array;
  }

  /**
   *
   * Set the query
   * @param string $sql
   */
  public function setQuery($sql){
    if(empty($sql)){
      return false;
    }

    $obj = new JConfig();
    $sqlcam = str_replace('#__', $obj->dbprefix, $sql);

    $this->sql = $sqlcam;
    return true;
  }

  /**
   *
   *  Free all memory associated with the result identifier
   */
  public function freeResults(){
    @mysql_free_result($this->resource);
    return true;
  }

  /**
   *
   * Return an object with the result query
   */
  public function loadObject(){
    $this->conectarbd();
    if ($cur = $this->execute()){
      if ($object = mysql_fetch_object($cur)){
        $this->cerrarbd();
        return $object;
      }
      else {
        return null;
      }
    }
    else {
      	
      return false;
    }
  }

  /**
   *
   * Close the conection
   */
  function cerrarbd(){
    @mysql_free_result($this->resource);
    @mysql_close($this->conexion);
  }
}
?>
<?php

/* GMap_Map: class for map objects
 * Accessors:
 * -----------
 * id, map_group_id, title, center_glat, center_glng, zoom, width, height, switch_zoom_level
 * 
 * CRUD methods:
 * -------------
 * new(), , save(), delete()
 *
 * Utility (static) Methods:
 * ----------------
 * load($id)
 * loadByMapGroupId($mg_id) -> static method, takes map group id as arg, returns array of maps keyed by map id
 *
 */

include_once(dirname(__FILE__).'/../defines.inc.php');

class GMap_Map {

  public $id;
  public $map_group_id;
  public $title;
  public $center_glat;
  public $center_glng;
  public $zoom;
  public $width;
  public $height;
  public $switch_zoom_level;

  public function __constructor () {
  }

  public static function loadBySQL( $sql ) {
    global $wpdb;

    $results = $wpdb->get_results( $sql, ARRAY_A );
    $maps = array();
    if ( $results === FALSE ) {
      error_log( "Could not load maps: " . mysql_error(), 0 );
    } elseif ( $results === 0 ) {
      error_log( "No maps to load", 0 );
    } else {
      if ( is_array($results) && count($results) > 0 ) {
        foreach ( $results as $result ) {
          $m = new GMap_Map ();
          $m->id = $result['map_id'];
          $m->map_group_id = $result['map_group_id'];
          $m->title = $result['title'];
          $m->center_glat = $result['center_glat'];
          $m->center_glng = $result['center_glng'];
          $m->zoom = $result['zoom'];
          $m->width = $result['width'];
          $m->height = $result['height'];
          $m->switch_zoom_level = $result['switch_zoom_level'];
          
          // add to the maps
          $maps[$m->id] = $m;
        }
      }
    }
    return $maps;
  }

  public static function load ($id) {
    global $wpdb;

    $safe_id = $wpdb->escape($id);
    $sql = self::_selectClause() . " WHERE map_id = '$safe_id'";
    $result = GMap_Map::loadBySQL( $sql );
    return array_key_exists($safe_id, $result) ? $result[$safe_id] : FALSE;
  }

  public static function loadByMapGroupId($group_id) {
    global $wpdb;
    $safe_group_id = $wpdb->escape($group_id);
    $sql = self::_selectClause() . " WHERE map_group_id = '$safe_group_id'";
    return GMap_Map::loadBySQL($sql);
  }

  public static function loadAll() {
    global $wpdb;
    $safe_group_id = $wpdb->escape($group_id);
    $sql = self::_selectClause();
    return GMap_Map::loadBySQL($sql);
  }


  /* CRUD */
  public static function _selectClause () {
    return 'SELECT map_id, map_group_id, title, center_glat, center_glng, zoom, width, height, switch_zoom_level FROM ' . GM_TABLE_MAPS;
  }

  public function save () {
    if ( $this->id ) {
      $this->update();
    } else {
      $this->insert();
    }
  }

  private function insert () {
    global $wpdb;

    $map_group_id      = $wpdb->escape( $this->map_group_id );
    $title             = $wpdb->escape( $this->title );
    $center_glat       = $wpdb->escape( $this->center_glat );
    $center_glng       = $wpdb->escape( $this->center_glng );
    $zoom              = $wpdb->escape( $this->zoom );
    $width             = $wpdb->escape( $this->width );
    $height            = $wpdb->escape( $this->height );
    $switch_zoom_level = $wpdb->escape( $this->switch_zoom_level );

    $ins_result = $wpdb->query("INSERT INTO " . GM_TABLE_MAPS .
                               " (map_id, map_group_id, title, center_glat, center_glng, zoom, width, height, switch_zoom_level) " .
                               " VALUES (NULL,'$map_group_id','$title','$center_glat','$center_glng','$zoom','$width','$height','$switch_zoom_level')");

    if ( $ins_result === FALSE ) {
      error_log( "Could not insert map into database: " . mysql_error(), 0 );
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  private function update () {
    global $wpdb;

    $id                = $wpdb->escape( $this->id );
    $map_group_id      = $wpdb->escape( $this->map_group_id );
    $title             = $wpdb->escape( $this->title );
    $center_glat       = $wpdb->escape( $this->center_glat );
    $center_glng       = $wpdb->escape( $this->center_glng );
    $zoom              = $wpdb->escape( $this->zoom );
    $width             = $wpdb->escape( $this->width );
    $height            = $wpdb->escape( $this->height );
    $switch_zoom_level = $wpdb->escape( $this->switch_zoom_level );
    $up_result = $wpdb->query('UPDATE ' . GM_TABLE_MAPS . " SET " .
                               "map_group_id = '$map_group_id', title = '$title', " .
                               "center_glat = '$center_glat', center_glng = '$center_glng', zoom = '$zoom', " . 
                               "width = '$width', height = '$height', switch_zoom_level = '$switch_zoom_level'" . 
                               "WHERE map_id = '$id'");
    if ( $up_result === FALSE ) {
      error_log( "Could not update map in database: " . mysql__error(), 0 );
    }
  }

  public function delete () {
    if ( $this->id ) {
      global $wpdb;
      $id = $wpdb->escape( $this->id );
      $del_result = $wpdb->query("DELETE FROM " . GM_TABLE_MAPS . " WHERE map_id = '$id'");
      if ( $del_result === FALSE ) {
        error_log( "Could not delete map from database: " . mysql_error(), 0 );
      } else {
        $this->id = FALSE;
      }
    }
  }

}

?>
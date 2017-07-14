<?php

/* GMap_Marker - class for marker objects
 * 
 * Accessors:
 * -----------
 * id, marker_type_id, marker_type, map_id, title, glat, glng, blurb, lg_icon_id, sm_icon_id, info_window
 *
 * CRUD:
 * -----
 * new([$id]), load($id), save(), delete()
 * 
 * Utility:
 * ------------
 * loadByMapId($map_id): loads an array of markers by map id, takes map id, returns array keyed by marker id
 * loadByMarkerTypeId($marker_type_id): load an array of markers by $marker_type_id, return array keyed by marker id
 */

include_once(dirname(__FILE__).'/../defines.inc.php');

class GMap_Marker {

  // load from GM_TABLE_MARKERS
  public $id;
  public $marker_type_id;
  public $map_id;
  public $title;
  public $glat;
  public $glng;
  public $info_window;

  public function __constructor () {
  }

  public static function loadBySQL( $sql ) {
    global $wpdb;

    $markers = array();
    $results = $wpdb->get_results($sql, ARRAY_A );

    if ( $results === FALSE ) {
      error_log( "Could not load markers: " . mysql_error(), 0 );
      return FALSE;
    } elseif ( $results === 0 ) {
      error_log( "No markers to load", 0 );
      return FALSE;
    } else {
      if ( is_array($results) && count($results) > 0 ) {
        foreach ( $results as $result ) {
          $m = new GMap_Marker ();
          $m->id             = $result['marker_id'];
          $m->marker_type_id = $result['marker_type_id'];
          $m->map_id         = $result['map_id'];
          $m->title          = $result['title'];
          $m->glat           = $result['glat'];
          $m->glng           = $result['glng'];
          $m->info_window    = $result['info_window'];
          
          // add to the markers
          $markers[$m->id] = $m;
        }
      }
    }
    return $markers;
    
  }
  public static function load ($id) {
    global $wpdb;

    $safe_id = $wpdb->escape($id);
    $sql = self::_selectClause() . " WHERE marker_id = $safe_id";
    $results = self::loadBySQL( $sql );
    return array_key_exists($safe_id, $results) ? $results[$safe_id] : FALSE;
  }


  public static function loadByMapId($map_id) {
    global $wpdb;

    $safe_map_id = $wpdb->escape($map_id);
    $sql = self::_selectClause() . " WHERE map_id = $safe_map_id";
    return self::loadBySQL( $sql );
  }

  public static function loadByMarkerTypeId($marker_type_id) {
    global $wpdb;

    $safe_marker_type_id = $wpdb->escape($marker_type_id);
    $sql = self::_selectClause() . " WHERE marker_type_id = $safe_marker_type_id";
    return self::loadBySQL( $sql );
  }

  /* CRUD */
  public static function _selectClause () {
    return 'SELECT marker_id, marker_type_id, map_id, title, glat, glng, info_window FROM ' . GM_TABLE_MARKERS;
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

    $marker_type_id   = $wpdb->escape( $this->marker_type_id );
    $map_id           = $wpdb->escape( $this->map_id );
    $title            = $wpdb->escape( $this->title );
    $glat             = $wpdb->escape( $this->glat );
    $glng             = $wpdb->escape( $this->glng );
    $info_window      = $wpdb->escape( $this->info_window );

    $ins_result = $wpdb->query("INSERT INTO " . GM_TABLE_MARKERS .
                               " (marker_id, marker_type_id, map_id, title, glat, glng, info_window) " .
                               " VALUES (NULL,'$marker_type_id', '$map_id', '$title','$glat','$glng','$info_window')");

    if ( $ins_result === FALSE ) {
      error_log( "Could not insert marker into database: " . mysql_error(), 0 );
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  private function update () {
    global $wpdb;

    $id                = $wpdb->escape( $this->id );
    $marker_type_id    = $wpdb->escape( $this->marker_type_id );
    $map_id            = $wpdb->escape( $this->map_id );
    $title             = $wpdb->escape( $this->title );
    $glat              = $wpdb->escape( $this->glat );
    $glng              = $wpdb->escape( $this->glng );
    $info_window       = $wpdb->escape( $this->info_window);

    $up_result = $wpdb->query('UPDATE ' . GM_TABLE_MARKERS . " SET " .
                              "marker_type_id = '$marker_type_id', map_id = '$map_id', title = '$title', " .
                              "glat = '$glat', glng = '$glng',".
                              "info_window = '$info_window'" .
                              "WHERE marker_id = '$id'");

    if ( $up_result === FALSE ) {
      error_log( "Could not update marker in database: " . mysql_error(), 0 );
    }
  }

  public function delete () {
    if ( $this->id ) {
      global $wpdb;
      $id = $wpdb->escape( $this->id );
      $del_result = $wpdb->query("DELETE FROM " . GM_TABLE_MARKERS . " WHERE marker_id = '$id'");
      if ( $del_result === FALSE ) {
        error_log( "Could not delete marker from database: " . mysql_error(), 0 );
      } else {
        $this->id = FALSE;
      }
    }
  }

}

?>
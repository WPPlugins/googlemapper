<?php

/* GMap_MarkerType - class for marker types
 * 
 * Accessors:
 * -----------
 * id, name, sm_icon_id, lg_icon_id, attributes (array of attribute names keyed by marker_atttribute_id)
 *
 * CRUD:
 * -----
 * new([$id]), load($id), save(), delete()
 * 
 * Utility:
 * ------------
 * loadByMapGroupId($map_group_id): loads an array of marker types by map group id. takes $map_group_id, returns array keyed by marker_type_id
 */

include_once(dirname(__FILE__).'/../defines.inc.php');

class GMap_MarkerType {

  // load from GM_TABLE_MARKER_TYPES
  public $id;
  public $title;
  public $sm_icon_id;
  public $lg_icon_id;

  public function __constructor () {
  }

  public static function loadBySQL ($sql) {

    global $wpdb;

    $marker_types = array();
    $results = $wpdb->get_results($sql, ARRAY_A );

    if ( $results === FALSE ) {
      error_log( "Could not load marker_types: " . mysql_error(), 0 );
      return FALSE;
    } elseif ( $results === 0 ) {
      error_log( "No marker types to load", 0 );
      return FALSE;
    } else {
      if ( is_array($results) && count($results) > 0 ) {
        foreach ( $results as $result ) {
          $mt = new GMap_MarkerType ();
          $mt->id            = $result['marker_type_id'];
          $mt->title         = $result['title'];
          $mt->sm_icon_id    = $result['sm_icon_id'];
          $mt->lg_icon_id    = $result['lg_icon_id'];   
          // add to the marker types
          $marker_types[$mt->id] = $mt;
        }
      }
    }
    return $marker_types;
  }

  public static function load ($id) {
    global $wpdb;

    $safe_id = $wpdb->escape($id);
    
    $sql = self::_selectClause() . " WHERE marker_type_id = '$safe_id'";
    $results = GMap_MarkerType::loadBySQL( $sql );
    return array_key_exists($safe_id,$results) ? $results[$safe_id] : FALSE;
  }

  public static function loadAll () {
    $sql = GMap_MarkerType::_selectClause();
    return GMap_MarkerType::loadBySQL($sql);
  }

  public static function loadByMapGroupId($map_group_id) {
    if ( !empty($map_group_id) ) {
      global $wpdb;
      $safe_map_group_id = $wpdb->escape( $map_group_id );
      $sql = 'SELECT mt.marker_type_id as marker_type_id, mt.title as title, mt.sm_icon_id as sm_icon_id, mt.lg_icon_id as lg_icon_id '.
        'FROM ' . GM_TABLE_MARKER_TYPES . ' mt LEFT JOIN ' . GM_TABLE_MAP_GROUP_MARKER_TYPES . ' mgmt on (mgmt.marker_type_id = mt.marker_type_id) ' .
        "WHERE mgmt.map_group_id = $safe_map_group_id";
      return GMap_MarkerType::loadBySQL( $sql );
    } else {
      error_log('GMap_MarkerType::loadByMapGroupId: No map group id provided', 0);
      return array();
    }
  }

  /* CRUD */
  public static function _selectClause () {
    return 'SELECT marker_type_id, title, sm_icon_id, lg_icon_id FROM ' . GM_TABLE_MARKER_TYPES;
  }

  public function save () {
    if ( $this->id ) {
      $this->update();
    } else {
      $this->insert();
    }
  }

  private function update () {
    global $wpdb;

    $id                = $wpdb->escape( $this->id );
    $title              = $wpdb->escape( $this->title );
    $sm_icon_id        = $wpdb->escape( $this->sm_icon_id );
    $lg_icon_id        = $wpdb->escape( $this->lg_icon_id );

    $up_result = $wpdb->query('UPDATE ' . GM_TABLE_MARKER_TYPES . " SET " .
                              "marker_type_id = '$id', title = '$title', sm_icon_id = '$sm_icon_id', lg_icon_id = '$lg_icon_id' ".
                              "WHERE marker_type_id = '$id'");
    if ( $up_result === FALSE ) {
      error_log( "Could not insert marker_type into database: " . mysql_error(), 0 );
    }
  }

  private function insert () {
    global $wpdb;

    $title      = $wpdb->escape( $this->title      );
    $sm_icon_id = $wpdb->escape( $this->sm_icon_id );
    $lg_icon_id = $wpdb->escape( $this->lg_icon_id );

    $ins_result = $wpdb->query("INSERT INTO " . GM_TABLE_MARKER_TYPES .
                               " (marker_type_id, title, sm_icon_id, lg_icon_id) " .
                               " VALUES (NULL,'$title','$sm_icon_id','$lg_icon_id' )");

    if ( $ins_result === FALSE ) {
      error_log( "Could not insert marker_type into database: " . mysql_error(), 0 );
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  public function delete () {
    if ( $this->id ) {
      global $wpdb;
      $id = $wpdb->escape( $this->id );
      $del_result = $wpdb->query("DELETE FROM " . GM_TABLE_MARKER_TYPES . " WHERE marker_type_id = '$id'");
      if ( $del_result === FALSE ) {
        error_log( "Could not delete marker_type from database: " . mysql_error(), 0 );
      } else {
        $this->id = FALSE;
      }
    }
  }

}

?>
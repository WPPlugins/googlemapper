<?php

/*
 * GMap_Factory - class for managing GMap_* objects
 */

include_once(dirname(__FILE__).'/../defines.inc.php');
// will need this one for the others to work
include_once('GMap_Model_Base.inc.php');
include_once('GMap_MapGroup.inc.php');
include_once('GMap_Map.inc.php');
include_once('GMap_MarkerType.inc.php');
include_once('GMap_Marker.inc.php');
include_once('GMap_Icon.inc.php');

class GMap_Factory {

  /* Get methods */
  // this is where caching should happen if it's going to happen...

  // map groups
  public static function getMapGroup($id) {
    return GMap_MapGroup::load($id);
  }
  public static function getAllMapGroups() {
    return GMap_MapGroup::loadAll();
  }
  public static function saveMapGroupMarkerTypes($mg_id, $mt_ids) {
    // delete all associations where map_group_id = $id, then reload the ones we have here
    if ( $mg_id && isset($mt_ids) ) {
      global $wpdb;

      $safe_mg_id = $wpdb->escape( $mg_id );
      $remove_sql = 'DELETE FROM ' . GM_TABLE_MAP_GROUP_MARKER_TYPES . ' WHERE map_group_id = ' . $safe_mg_id;
      $del_result = $wpdb->query($remove_sql);
      if ( $del_result === FALSE ) {
        error_log('Could not delete marker type set from map group marker types: ' . mysql_error(), 0);
      } else {
        if ( is_array($mt_ids) && count($mt_ids) > 0 ) {
          $insert_sql = 'INSERT INTO ' . GM_TABLE_MAP_GROUP_MARKER_TYPES . ' (map_group_id, marker_type_id) VALUES ';
          $inserts = array();
          foreach ( $mt_ids as $mt_id ) {
            $inserts[] = ' (' . $mg_id . ', ' . $mt_id . ')';
          }
          $insert_sql .= implode(', ', $inserts);
          // FIRE!
          $ins_result = $wpdb->query($insert_sql);
          if ( $ins_result === FALSE ) {
            error_log('Could not insert marker type set from map group marker types: ' . mysql_error(), 0);
          }
        }
      }
    }
    return TRUE;
  }

  // maps
  public static function getMap($id) {
    return GMap_Map::load($id);
  }
  public static function getAllMaps() {
    return GMap_Map::loadAll();
  }
  public static function getMapsByMapGroup($mg_id) {
    return GMap_Map::loadByMapGroupId($mg_id);
  }

  // marker types
  public static function getMarkerType($id) {
    return GMap_MarkerType::load($id);
  }
  public static function getAllMarkerTypes() {
    return GMap_MarkerType::loadAll();
  }
  public static function getMarkerTypesByMapGroup($mg_id) {
    return GMap_MarkerType::loadByMapGroupId($mg_id);
  }
  public static function getMarkerTypes( $mt_ids = array() ) {
    $mts = array();
    if ( $mt_ids ) {
      $sql_mt_ids = implode(',', self::quoter($mt_ids));
      $sql = GMap_MarkerType::_selectClause() . ' WHERE marker_type_id IN ('.$sql_mt_ids.')';
      $mts = GMap_MarkerType::loadBySQL($sql);
    }
    return $mts;
  }
  
  // markers
  public static function getMarker($id) {
    return GMap_Marker::load($id);
  }
  public static function getMarkersByMap($map_id) {
    return GMap_Marker::loadByMapId( $map_id );
  }

  // icons
  public static function getIcon($id) {
    return GMap_Icon::load($id);
  }
  public static function getIcons($ids = array()) {
    $icons = array();
    if ( $ids ) {
      $sql_ids = implode(', ', self::quoter($ids));
      $sql = GMap_Icon::_selectClause() . ' WHERE icon_id IN ('.$sql_ids.')';
      $icons = GMap_Icon::loadBySQL($sql);
    }
    return $icons;
  }
  public static function getAllIcons() {
    return GMap_Icon::loadAll();
  }

  private static function quoter ($elms = array()) {
    $_ugh = array();
    if ( is_array($elms) && count($elms) > 0 ) {
      foreach ( $elms as $elm ) { $_ugh[] = "'$elm'"; };
    }
    return $_ugh;
  }

}

?>
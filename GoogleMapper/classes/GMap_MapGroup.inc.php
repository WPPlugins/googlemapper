<?php
/* GMap_MapGroup - class for map groups
 * 
 * Attributes:
 * ------------
 * id, title, default_width, default_height, default_zoom, default_use_ctl_directions, default_use_ctl_zoom, icon_path
 
 * CRUD:
 * -----
 * save(), delete()
 * 
 * Utility:
 * --------
 * load($id): returns a map group or FALSE
 * loadAll(): returns an array of map groups keyed by map_group_id
 *
 */

include_once(dirname(__FILE__).'/../defines.inc.php');

class GMap_MapGroup {

  public $id;
  public $title;
  public $default_width;
  public $default_height;
  public $default_zoom;
  public $default_use_ctl_directions;
  public $default_use_ctl_zoom;

  public function __constructor () {
  }

  public static function loadBySQL ($sql) {
    global $wpdb;

    $results = $wpdb->get_results( $sql, ARRAY_A );
    $map_groups = array();

    if ( $results === FALSE ) {
      error_log( "Could not load map groups: " . mysql_error(), 0 );
      return FALSE;
    } elseif ( $results === 0 ) {
      error_log( "No map groups to load", 0 );
      return FALSE;
    } elseif (is_array($results) && count($results) > 0) {
      foreach ( $results as $result ) {
        $mg = new GMap_MapGroup ();
        $mg->id             = $result['map_group_id'];
        $mg->title          = $result['title'];
        $mg->default_width  = $result['default_width'];
        $mg->default_height = $result['default_height'];
        $mg->default_zoom   = $result['default_zoom'];
        $mg->default_use_ctl_directions = $result['default_use_ctl_directions'];
        $mg->default_use_ctl_zoom = $result['default_use_ctl_zoom'];

        $map_groups[$mg->id] = $mg;
      }
    }
    return $map_groups;

  }

  public static function load($id) {
    global $wpdb;
    $safe_id = $wpdb->escape($id);
    $sql = self::_selectClause() . "
            WHERE
                   map_group_id = $safe_id";
    $result = self::loadBySQL($sql);
    return array_key_exists($safe_id, $result) ? $result[$safe_id] : FALSE;
  }

  public static function loadAll () {
    return self::loadBySQL( self::_selectClause() );
  }

  /*
   * individual CRUD operations
   */

  public static function _selectClause () {
    return "
            SELECT
                   map_group_id, title,
                   default_width, default_height, default_zoom,
                   default_use_ctl_directions, default_use_ctl_zoom
            FROM 
                   " . GM_TABLE_MAP_GROUPS;
  }

  public function save () {
    if ( isset($this->id) && !empty($this->id) ) {
      $this->update();
    } else {
      $this->insert();
    }
  }
  private function insert () {
    global $wpdb;

    $title                      = $wpdb->escape( $this->title          );
    $default_width              = $wpdb->escape( $this->default_width  );
    $default_height             = $wpdb->escape( $this->default_height );
    $default_zoom               = $wpdb->escape( $this->default_zoom   );
    $default_use_ctl_directions = $wpdb->escape( $this->default_use_ctl_directions );
    $default_use_ctl_zoom       = $wpdb->escape( $this->default_use_ctl_zoom );

    $ins_result = $wpdb->query("INSERT INTO " . GM_TABLE_MAP_GROUPS . 
                               "(map_group_id, title, default_width, default_height, default_zoom," .
                               "default_use_ctl_directions, default_use_ctl_zoom)" . 
                               "VALUES (NULL, '$title', '$default_width', '$default_height', '$default_zoom',".
                               "'$default_use_ctl_directions', '$default_use_ctl_zoom' )");

    if ( $ins_result === FALSE ) {
      global $EZSQL_ERRORS;
      error_log( "Could not insert map group into database\n  query: ". mysql_error(), 0 );
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  private function update () {
    global $wpdb;
    $id                         = $wpdb->escape( $this->id             );
    $title                      = $wpdb->escape( $this->title          );
    $default_width              = $wpdb->escape( $this->default_width  );
    $default_height             = $wpdb->escape( $this->default_height );
    $default_zoom               = $wpdb->escape( $this->default_zoom   );
    $default_use_ctl_directions = $wpdb->escape( $this->default_use_ctl_directions );
    $default_use_ctl_zoom       = $wpdb->escape( $this->default_use_ctl_zoom );
    
    $update_result = $wpdb->query("UPDATE " . GM_TABLE_MAP_GROUPS . " SET " .
                                  "title = '$title', default_width = '$default_width', default_height = '$default_height', default_zoom = '$default_zoom', " . 
                                  "default_use_ctl_directions = '$default_use_ctl_directions', default_use_ctl_zoom = '$default_use_ctl_zoom' " . 
                                  "WHERE map_group_id = '$id'");

    if ( $update_result === 0 ) {
      global $EZSQL_ERRORS;
      error_log( "Could not update map group (id: $id) in database\n  query: ".mysql_error(), 0 );
    }
  }
  
  public function delete () {
    global $wpdb;
    $wpdb->show_errors();
    if ( $this->id ) {
      $id = $wpdb->escape( $this->id );
      $result = $wpdb->query('DELETE FROM ' . GM_TABLE_MAP_GROUPS . " WHERE map_group_id = '$id'");
      if ( $ins_result === 0 ) {
        error_log( "Could not delete map group (id: $id) in database: " . mysql_error(), 0 );
      } else {
        $this->id = FALSE;
      }
    }
  }

}
?>
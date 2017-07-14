<?php

/* GMap_Icon - class for icon objects
 * 
 * Accessors:
 * -----------
 * id, image_path, image_width, image_height, shadow_path, shadow_width, shadow_height, anchor_point, info_anchor_point
 *
 * CRUD:
 * -----
 * save(), delete()
 * 
 * Utility:
 * ------------
 * load($id)
 * loadBySQL($sql) => array of icons keyed by id
 * loadAll(): returns an array of all icons keyed by id
 *
 */

include_once(dirname(__FILE__).'/../defines.inc.php');

class GMap_Icon {

  // load from GMAP_TABLE_ICONS
  public $id;
  public $title;
  public $image_path;
  public $image_width;
  public $image_height;
  public $shadow_path;
  public $shadow_width;
  public $shadow_height;
  public $anchor_point;
  public $info_anchor_point;

  public function __constructor () {
  }

  public static function loadBySQL( $sql ) {
    global $wpdb;

    $icons = array();
    $results = $wpdb->get_results($sql, ARRAY_A );

    if ( $results === FALSE ) {
      error_log( "Could not load icons: " . mysql_error(), 0 );
    } elseif ( $results === 0 ) {
      error_log( "No icons to load", 0 );
    } else {
      if ( is_array($results) && count($results) > 0 ) {
        foreach ( $results as $result ) {
          $i = new GMap_Icon ();
          $i->id                  = $result['icon_id'];
          $i->title               = $result['title'];
          $i->image_path          = $result['image_path'];
          $i->image_width         = $result['image_width'];
          $i->image_height        = $result['image_height'];
          $i->shadow_path         = $result['shadow_path'];
          $i->shadow_width        = $result['shadow_width'];
          $i->shadow_height       = $result['shadow_height'];
          $i->anchor_point        = $result['anchor_point'];
          $i->info_anchor_point   = $result['info_anchor_point']; 
          // add to the icons
          $icons[$i->id] = $i;
        }
      }
    }
    return $icons;
    
  }

  public static function load ($id) {
    global $wpdb;
    $wpdb->show_errors();

    $safe_id = $wpdb->escape($id);
    $sql = self::_selectClause() . " WHERE icon_id = $safe_id";
    $results = self::loadBySQL( $sql );
    return array_key_exists($safe_id, $results) ? $results[$safe_id] : FALSE;
  }

  public static function loadAll() {
    $sql = self::_selectClause();
    return self::loadBySQL($sql);
  }

  /* CRUD */
  public static function _selectClause () {
    return 'SELECT icon_id, title, image_path, image_width, image_height, shadow_path, shadow_width, shadow_height, anchor_point, info_anchor_point FROM ' . GM_TABLE_ICONS;
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
    $wpdb->show_errors();
    $title               = $wpdb->escape( $this->title );
    $image_path          = $wpdb->escape( $this->image_path );
    $image_width         = $wpdb->escape( $this->image_width );
    $image_height        = $wpdb->escape( $this->image_height );
    $shadow_path         = $wpdb->escape( $this->shadow_path );
    $shadow_width        = $wpdb->escape( $this->shadow_width );
    $shadow_height       = $wpdb->escape( $this->shadow_height );
    $anchor_point        = $wpdb->escape( $this->anchor_point );
    $info_anchor_point   = $wpdb->escape( $this->info_anchor_point );

    $ins_result = $wpdb->query("INSERT INTO " . GM_TABLE_ICONS .
                               " (icon_id, title, image_path, image_width, image_height, shadow_path, shadow_width, shadow_height, anchor_point, info_anchor_point) " .
                               " VALUES (NULL,'$title', '$image_path', '$image_width', '$image_height','$shadow_path', '$shadow_width', '$shadow_height', '$anchor_point', '$info_anchor_point')");

    if ( $ins_result === FALSE ) {
      error_log( "Could not insert icon into database: " . mysql_error(), 0 );
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  private function update () {
    global $wpdb;
    $wpdb->show_errors();

    $id                  = $wpdb->escape( $this->id );
    $title               = $wpdb->escape( $this->title );
    $image_path          = $wpdb->escape( $this->image_path );
    $image_width         = $wpdb->escape( $this->image_width );
    $image_height        = $wpdb->escape( $this->image_height );
    $shadow_path         = $wpdb->escape( $this->shadow_path );
    $shadow_width        = $wpdb->escape( $this->shadow_width );
    $shadow_height       = $wpdb->escape( $this->shadow_height );
    $anchor_point        = $wpdb->escape( $this->anchor_point );
    $info_anchor_point   = $wpdb->escape( $this->info_anchor_point );

    $up_result = $wpdb->query('UPDATE ' . GM_TABLE_ICONS . " SET " .
                              "title = '$title', image_path = '$image_path', image_width = '$image_width', image_height = '$image_height'," .
                              "shadow_path = '$shadow_path', shadow_width = '$shadow_width', shadow_height = '$shadow_height'," .
                              "anchor_point = '$anchor_point', info_anchor_point = '$info_anchor_point'" . 
                              "WHERE icon_id = '$id'");

    if ( $up_result === FALSE ) {
      error_log( "Could not update icon in database: " . mysql_error(), 0 );
    }
  }

  public function delete () {
    if ( $this->id ) {
      global $wpdb;
      $id = $wpdb->escape( $this->id );
      $del_result = $wpdb->query("DELETE FROM " . GM_TABLE_ICONS . " WHERE icon_id = '$id'");
      if ( $del_result === FALSE ) {
        error_log( "Could not delete icon from database: " . mysql_error(), 0 );
      } else {
        $this->id = FALSE;
      }
    }
  }
}

?>
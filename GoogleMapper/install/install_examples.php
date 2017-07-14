<?php 
/*
 * install some basic items: 
 */

include_once(dirname(__FILE__).'/../classes/GMap_Factory.inc.php');

function gm_install_examples () {
  global $wpdb;
  

  /*
   * icons
   */

  // check to see if they're already there
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_ICONS . ' WHERE title = "Large Red Teardrop"') == 0 ) {  
    $red_lg_icon = new GMap_Icon ();
    $red_lg_icon->title = 'Large Red Teardrop';
    $red_lg_icon->image_path = 'general_icon.png';
    $red_lg_icon->image_width = 20;
    $red_lg_icon->image_height = 28;
    $red_lg_icon->shadow_path = 'general_icon_shadow.png';
    $red_lg_icon->shadow_width = 40;
    $red_lg_icon->shadow_height = 28;
    $red_lg_icon->anchor_point = '10,26';
    $red_lg_icon->info_anchor_point = '10,3';
    $red_lg_icon->save();
  }

  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_ICONS . ' WHERE title = "Small Red Teardrop"') == 0 ) {  
    $red_sm_icon = new GMap_Icon ();
    $red_sm_icon->title = 'Small Red Teardrop';
    $red_sm_icon->image_path = 'general_icon_sm.png';
    $red_sm_icon->image_width = 15;
    $red_sm_icon->image_height = 21;
    $red_sm_icon->shadow_path = 'general_icon_shadow_sm.png';
    $red_sm_icon->shadow_width = 30;
    $red_sm_icon->shadow_height = 21;
    $red_sm_icon->anchor_point = '7,21';
    $red_sm_icon->info_anchor_point = '7,2';
    $red_sm_icon->save();
  }

  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_ICONS . ' WHERE title = "Large Blue Teardrop"') == 0 ) {  
    $blue_lg_icon = new GMap_Icon ();
    $blue_lg_icon->title = 'Large Blue Teardrop';
    $blue_lg_icon->image_path = 'general_icon_blue.png';
    $blue_lg_icon->image_width = 20;
    $blue_lg_icon->image_height = 28;
    $blue_lg_icon->shadow_path = 'general_icon_shadow.png';
    $blue_lg_icon->shadow_width = 40;
    $blue_lg_icon->shadow_height = 28;
    $blue_lg_icon->anchor_point = '10,26';
    $blue_lg_icon->info_anchor_point = '10,3';
    $blue_lg_icon->save();
  }

  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_ICONS . ' WHERE title = "Small Blue Teardrop"') == 0 ) {  
    $blue_sm_icon = new GMap_Icon ();
    $blue_sm_icon->title = 'Small Blue Teardrop';
    $blue_sm_icon->image_path = 'general_icon_blue_sm.png';
    $blue_sm_icon->image_width = 15;
    $blue_sm_icon->image_height = 21;
    $blue_sm_icon->shadow_path = 'general_icon_shadow_sm.png';
    $blue_sm_icon->shadow_width = 30;
    $blue_sm_icon->shadow_height = 21;
    $blue_sm_icon->anchor_point = '7,21';
    $blue_sm_icon->info_anchor_point = '7,2';
    $blue_sm_icon->save();
  }

  // marker type
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_MARKER_TYPES . ' WHERE title = "Red Teardrop"') == 0 ) {  
    $red_mt = new GMap_MarkerType ();
    $red_mt->title = 'Red Teardrop';
    $red_mt->sm_icon_id = $red_sm_icon->id;
    $red_mt->lg_icon_id = $red_lg_icon->id;
    $red_mt->save();
  }
  // marker type
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_MARKER_TYPES . ' WHERE title = "Blue Teardrop"') == 0 ) {  
    $blue_mt = new GMap_MarkerType ();
    $blue_mt->title = 'Blue Teardrop';
    $blue_mt->sm_icon_id = $blue_sm_icon->id;
    $blue_mt->lg_icon_id = $blue_lg_icon->id;
    $blue_mt->save();
  }

  // map group
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_MAP_GROUPS . ' WHERE title = "General Maps"') == 0 ) {  
    $new_mg = new GMap_MapGroup ();
    $new_mg->title = "General Maps";
    $new_mg->default_width = '400';
    $new_mg->default_height = '300';
    $new_mg->default_zoom   = '5';
    $new_mg->default_use_ctl_directions = 'on';
    $new_mg->default_use_ctl_zoom = 'on';
    $new_mg->save();

    // need to get the marker types for this map group just in case this is an upgrade...
    $all_mg_mts = GMap_Factory::getMarkerTypesByMapGroup($new_mg->id);
    $all_mg_mts[] = $red_mt;
    $all_mg_mt_ids = array_map( create_function('$mt', 'return $mt->id;'), $all_mg_mts );
    $all_mg_mt_ids = array_unique( $all_mg_mt_ids );
    GMap_Factory::saveMapGroupMarkerTypes( $new_mg->id, $all_mg_mt_ids );
  }

  // make a map
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_MAPS . ' WHERE title = "Example Map"') == 0 ) {  
    $new_map = new GMap_Map ();
    $new_map->map_group_id = $new_mg->id;
    $new_map->title = 'Example Map';
    $new_map->center_glat = '38.94348';
    $new_map->center_glng = '-76.985643';
    $new_map->zoom = 6;
    $new_map->width = 450;
    $new_map->height = 300;
    $new_map->switch_zoom_level = 9;
    $new_map->save();
  }
  
  // add a point
  if ( $wpdb->get_var('SELECT count(*) FROM ' . GM_TABLE_MARKERS . ' WHERE title = "Druid Hill Park"') == 0 ) {  
    $new_marker = new GMap_Marker ();
    $new_marker->title  = 'Druid Hill Park';
    $new_marker->map_id = $new_map->id;
    $new_marker->marker_type_id = $red_mt->id;
    $new_marker->glat   = '39.3264833333';
    $new_marker->glng   = '-76.653683333';
    $new_marker->info_window = "Hello World from Druid Hill Park!";
    $new_marker->save();
  }

  return TRUE;
}

?>

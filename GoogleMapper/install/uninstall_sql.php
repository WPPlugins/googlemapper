<?php

include_once(dirname(__FILE__).'/../defines.inc.php');

function gm_uninstall_tables () {
  gm_uninstall_map_groups();
  gm_uninstall_map_group_marker_types();
  gm_uninstall_maps();
  gm_uninstall_marker_types();
  gm_uninstall_markers();
  gm_uninstall_icons();
}

function gm_uninstall_table ($sql) {
  global $wpdb;
  $wpdb->show_errors();
  $res = $wpdb->query($sql);
  if ( $res === FALSE ) {
    error_log("could not drop table: " . $wpdb->print_error(), 0);
  }
}

function gm_uninstall_map_groups () {
  gm_uninstall_table(
 'DROP TABLE IF EXISTS ' . GM_TABLE_MAP_GROUPS);

}

function gm_uninstall_map_group_marker_types () {
  gm_uninstall_table('DROP TABLE IF EXISTS ' . GM_TABLE_MAP_GROUP_MARKER_TYPES);
}

function gm_uninstall_maps () {
  gm_uninstall_table('DROP TABLE IF EXISTS ' . GM_TABLE_MAPS);
}

// this creates a marker type
function gm_uninstall_marker_types () {
  gm_uninstall_table('DROP TABLE IF EXISTS ' . GM_TABLE_MARKER_TYPES);
}

function gm_uninstall_markers () {
  gm_uninstall_table('DROP TABLE IF EXISTS ' . GM_TABLE_MARKERS);
}


function gm_uninstall_icons () {
  gm_uninstall_table('DROP TABLE IF EXISTS ' . GM_TABLE_ICONS);
}

?>
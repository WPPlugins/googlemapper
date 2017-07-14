<?php

include_once(dirname(__FILE__).'/../defines.inc.php');

function gm_install_tables () {
  gm_install_map_groups();
  gm_install_map_group_marker_types();
  gm_install_maps();
  gm_install_marker_types();
  gm_install_markers();
  gm_install_icons();
}

function gm_install_table ($sql) {
  global $wpdb;
  $wpdb->show_errors();
  require_once(ABSPATH . '/wp-admin/upgrade-functions.php');
  dbDelta($sql);
}

function gm_install_map_groups () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_MAP_GROUPS . ' (
  map_group_id   SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title          VARCHAR(100),
  default_width  VARCHAR(4),
  default_height VARCHAR(4),
  default_zoom   VARCHAR(2),
  default_use_ctl_directions VARCHAR(3),
  default_use_ctl_zoom       VARCHAR(3)
)');

}

function gm_install_map_group_marker_types () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_MAP_GROUP_MARKER_TYPES . ' (
  map_group_id   SMALLINT UNSIGNED NOT NULL,
  marker_type_id   SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (map_group_id, marker_type_id)
)');
}

function gm_install_maps () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_MAPS . ' (
  map_id            SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  map_group_id      SMALLINT UNSIGNED,
  title             VARCHAR(255),
  center_glat       VARCHAR(13),
  center_glng       VARCHAR(13),
  zoom              VARCHAR(2),
  width             VARCHAR(4),
  height            VARCHAR(4),
  switch_zoom_level VARCHAR(2)
)');
}

// this creates a marker type
function gm_install_marker_types () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_MARKER_TYPES . ' (
  marker_type_id    SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(100),
  sm_icon_id        SMALLINT UNSIGNED,
  lg_icon_id        SMALLINT UNSIGNED
  )');
}

function gm_install_markers () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_MARKERS . ' (
  marker_id         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  marker_type_id    SMALLINT UNSIGNED NOT NULL,
  map_id            SMALLINT UNSIGNED NOT NULL,
  title             VARCHAR(255),
  glat              VARCHAR(13),
  glng              VARCHAR(13),
  info_window       TEXT
  )');
}


function gm_install_icons () {
  gm_install_table(
 'CREATE TABLE ' . GM_TABLE_ICONS . ' (
  icon_id           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title             VARCHAR(100),
  image_path        VARCHAR(255),
  image_width       VARCHAR(3),
  image_height      VARCHAR(3),
  shadow_path       VARCHAR(255),
  shadow_width      VARCHAR(3),
  shadow_height     VARCHAR(3),
  anchor_point      VARCHAR(7),
  info_anchor_point VARCHAR(7)
)');
}

?>
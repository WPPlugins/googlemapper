<?php
/*
Plugin Name: GoogleMapper
Plugin URI:  http://blog.chiggins.com/?p=35
Description: plugin for creating custom google maps
Version:     0.9.10
Author:      Creighton Higgins
Author URI:  http://chiggins.com
*/

/*  Copyright 2007 Creighton Higgins (email : chiggins@chiggins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


include_once('defines.inc.php');
include_once('classes/GMap_Factory.inc.php');

// first thing's first: install
function gmapper_install () {

  // global variables
  update_option(GM_OPTION_VERSION, '0.9.10');
  add_option(GM_OPTION_API_KEY,'');
  add_option(GM_OPTION_WIPEALL, 'on');
  update_option(GM_OPTION_ICON_PATH, '/wp-content/plugins/GoogleMapper/icons');

  // install the tables
  include_once('install/install_sql.php');
  gm_install_tables();

  // install the examples
  include_once('install/install_examples.php');
  gm_install_examples();

}
function gmapper_uninstall () {
  // uninstall the tables
  include_once('install/uninstall_sql.php');
  if (get_option(GM_OPTION_WIPEALL) == 'on') {
    error_log('Deactivation: uninstalling tables (wipe option == "on")', 0);
    gm_uninstall_tables();
  } else {
    error_log('Deactivation: not uninstalling tables (wipe option == false)', 0);
  }
}

add_action('activate_GoogleMapper/GoogleMapper.php', 'gmapper_install');
add_action('deactivate_GoogleMapper/GoogleMapper.php', 'gmapper_uninstall');

/* set up admin menus */
function gmapper_add_menu_pages () {


  //  add_submenu_page(__FILE__, 'Map Groups',  'Map Groups',  8, 'GoogleMapper/admin/mapgroups.inc.php' );
  add_submenu_page(__FILE__, 'Maps',                 'Maps',                 8, 'GoogleMapper/admin/Maps.inc.php'      );
  add_submenu_page(__FILE__, 'Map Groups',           'Map Groups',           8, 'GoogleMapper/admin/MapGroups.inc.php' );
  add_submenu_page(__FILE__, 'Icons & Marker Types', 'Icons & Marker Types', 8, 'GoogleMapper/admin/Icons.inc.php'     );
  add_submenu_page(__FILE__, 'Configuration',        'Configuration',        8, 'GoogleMapper/admin/Config.inc.php'    );
  
  add_menu_page('Google Mapper', 'Google Mapper', 8, __FILE__, 'gmapper_menu_toplevel');
}

function gmapper_menu_toplevel () {

}

add_action('admin_menu', 'gmapper_add_menu_pages');

/* and finally! the ACTION! */
function gmapper_render_single_gmap ($matches,$showtitle=true) {

  $map_id = $matches[1];
  $map = GMap_Factory::getMap($map_id);
  $map_group = GMap_Factory::getMapGroup($map->map_group_id);
  $map_width  = ( $map->width ) ? $map->width : $map_group->default_width;
  $map_height  = ( $map->height ) ? $map->height : $map_group->default_height;
  $map_src = GM_CONFIG_SINGLE_MAP_SRC;
  if ($showtitle)
	$output = '<h3>'.$map->title."</h3>";
  $output .= "<p><iframe src=\"${map_src}?map_id=$map_id\" frameborder=\"0\" height=\"$map_height\" scrolling=\"no\" width=\"$map_width\"></iframe></p>";
  return $output;
}

function gmapper_add_map ($content, $widget=false) { // widget doesn't want to show title.
  $callback = $widget ? 'gmapper_render_widget_gmap' : 'gmapper_render_single_gmap';
  $content = preg_replace_callback('/\[gmap\s+map:(\d+)\]/', $callback, $content);
  return $content;
}

function gmapper_render_widget_gmap($matches){	// there's probably a more elegant solution than creating this function, but whatever.
	return gmapper_render_single_gmap($matches,false);
}

add_filter('the_content', 'gmapper_add_map');
include_once('GoogleMapperWidget.php');
?>

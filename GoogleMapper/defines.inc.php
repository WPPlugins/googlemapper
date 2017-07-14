<?php
// database
define('GM_TABLE_MAP_GROUPS',             $wpdb->prefix.'gmapperMapGroups');
define('GM_TABLE_MAP_GROUP_MARKER_TYPES', $wpdb->prefix.'gmapperMapGroupMarkerTypes');
define('GM_TABLE_MAPS',                   $wpdb->prefix.'gmapperMaps');
define('GM_TABLE_MARKERS',                $wpdb->prefix.'gmapperMarkers');
define('GM_TABLE_MARKER_TYPES',           $wpdb->prefix.'gmapperMarkerTypes');
define('GM_TABLE_ICONS',                  $wpdb->prefix.'gmapperIcons');
// options
define('GM_OPTION_VERSION',   'gmapper_version');
define('GM_OPTION_API_KEY',   'gmapper_api_key');
define('GM_OPTION_WIPEALL',   'gmapper_wipeall');
define('GM_OPTION_ICON_PATH', 'gmapper_icon_path');
// and a couple configs...
define('GM_CONFIG_SINGLE_MAP_SRC', get_option('home').'/wp-content/plugins/GoogleMapper/gmaps/single_map.php');

?>
<?php

include_once(dirname(__FILE__).'/../classes/GMap_Factory.inc.php');

// maps
define('MANAGE_MAPS', 'state_manage_maps');
define('ADD_MAP_SUBMIT', 'state_add_map_submit');
define('EDIT_MAP', 'state_edit_map');
define('EDIT_MAP_SUBMIT', 'state_edit_map_submit');
define('DELETE_MAP', 'state_delete_map');

// map markers
define('MANAGE_MAP_MARKERS', 'state_manage_map_markers');
define('ADD_MAP_MARKER_SUBMIT', 'state_add_map_marker_submit');
define('EDIT_MAP_MARKER', 'state_edit_map_marker');
define('EDIT_MAP_MARKER_SUBMIT', 'state_edit_map_marker_submit');
define('DELETE_MAP_MARKER', 'state_delete_map_marker');

// pages to load
define('PAGE_MANAGE_MAPS', 'Maps/manage_maps.inc.php');
define('PAGE_EDIT_MAP',    'Maps/edit_map.inc.php');

define('PAGE_MANAGE_MAP_MARKERS', 'Maps/manage_map_markers.inc.php');
define('PAGE_EDIT_MAP_MARKER',    'Maps/edit_map_marker.inc.php');

// operations - maps
function add_update_map () {
  if ( $_POST['submit'] ) {
    $new_map = new GMap_Map ();
    $new_map->id            = $_REQUEST['map_id'];
    $new_map->map_group_id  = $_REQUEST['map_group_id'];
    $new_map->title         = $_REQUEST['title'];
    $new_map->center_glat   = $_REQUEST['center_glat'];
    $new_map->center_glng   = $_REQUEST['center_glng'];
    $new_map->zoom          = $_REQUEST['zoom'];
    $new_map->width         = $_REQUEST['width'];
    $new_map->height        = $_REQUEST['height'];
    $new_map->switch_zoom_level = $_REQUEST['switch_zoom_level'];

    $new_map->save();
  }
}
function delete_map () {
  $m_id = $_GET['map_id'];
  if ( !empty($m_id) ) {
    $del_m = GMap_Factory::getMap($m_id);
    if ( !empty($del_m->id) ) $del_m->delete();
  }   
}

// operations - map_markers
function add_update_map_marker () {
  if ( $_POST['submit'] ) {
    $new_mm = new GMap_Marker ();
    $new_mm->id                         = $_REQUEST['marker_id'];
    $new_mm->marker_type_id             = $_REQUEST['marker_type_id'];
    $new_mm->map_id                     = $_REQUEST['map_id'];
    $new_mm->title                      = $_REQUEST['title'];
    $new_mm->glat                       = $_REQUEST['glat'];
    $new_mm->glng                       = $_REQUEST['glng'];
    $new_mm->info_window                = $_REQUEST['info_window'];
    $new_mm->save();
  }  
}
function delete_map_marker () {
  $mm_id = $_GET['marker_id'];
  if ( !empty($mm_id) ) {
    $del_mm = GMap_Factory::getMarker($mm_id);
    if ( !empty($del_mm->id) ) $del_mm->delete();
  }  
}

$page_section_title = 'Manage Maps';
$load_page = PAGE_MANAGE_MAPS;
$status_message = FALSE;

switch ($_REQUEST['state']) {
  
 case EDIT_MAP:
   $page_section_title = 'Edit Map';
   $load_page = PAGE_EDIT_MAP;
   break;
 case ADD_MAP_SUBMIT:
   add_update_map();
   $status_message = 'Map Added';
   break;
 case EDIT_MAP_SUBMIT:
   add_update_map();
   $status_message = 'Map Updated';
   break;
 case DELETE_MAP:
   delete_map();
   $status_message = 'Map Deleted';
   break;
   
 case MANAGE_MAP_MARKERS:
   $page_section_title = 'Manage Markers';
   $load_page = PAGE_MANAGE_MAP_MARKERS;
   break;
 case EDIT_MAP_MARKER:
   $page_section_title = 'Edit Marker';
   $load_page = PAGE_EDIT_MAP_MARKER;
   break;
 case ADD_MAP_MARKER_SUBMIT:
   add_update_map_marker();
   $status_message = 'Marker Added';
   $page_section_title = 'Manage Markers';
   $load_page = PAGE_MANAGE_MAP_MARKERS;
   break;
 case EDIT_MAP_MARKER_SUBMIT:
   add_update_map_marker();
   $status_message = 'Marker Updated';
   $page_section_title = 'Manage Markers';
   $load_page = PAGE_MANAGE_MAP_MARKERS;
   break;
 case DELETE_MAP_MARKER:
   delete_map_marker();
   $status_message = 'Marker Deleted';
   $page_section_title = 'Manage Markers';
   $load_page = PAGE_MANAGE_MAP_MARKERS;
   break;

 case MANAGE_MAPS:
 default:
   $page_section_title = 'Manage Maps';
   break;
}
   
// need a clean url, but with the page param set
$parsed_url = parse_url($_SERVER['REQUEST_URI']);
$page_path = $parsed_url['path'];
$page_url  = $parsed_url['path'] . '?page=' . $_GET['page'];

?>

<style type="text/css">
fieldset {
  border: 1px solid #ccc;
  padding: 5px;
  margin-bottom: 15px;
}
legend {
  font-family: "Lucida Sans", "Arial", "Trebuchet", "Tahoma", "Helvetica";
  font-size: 14px;
  font-weight: bold;
}
.smallbold {
  font-size: 12px;
  font-weight:bold;
  font-family:'Lucida Sans', 'Arial','Helvetica';
}
.small {
  font-size: 12px;
  font-weight: normal;
  font-family:'Lucida Sans', 'Arial','Helvetica';
}
.configDesc {
  margin-bottom: 12px;
  border-bottom: 1px solid #CCC;
}
</style>

<div class="wrap">
<h2><?php echo $page_section_title ?></h2>
<?php if ( $status_message !== FALSE  ) { ?>
<fieldset><div id="message" class="update fade"><strong><?php echo $status_message ?></strong></div></fieldset>
<?php } ?>
<?php include($load_page); ?>
</div>
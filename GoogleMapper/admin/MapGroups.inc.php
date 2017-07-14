<?php 

include_once(dirname(__FILE__).'/../classes/GMap_Factory.inc.php');
include_once(dirname(__FILE__).'/../util/icons.inc.php');

/*
 * admin/MapGroups.inc.php - manage map groups and marker types
 */

// map groups
define('MANAGE_MAP_GROUPS', 'state_manage_map_groups');
define('EDIT_MAP_GROUP', 'state_edit_map_group');
define('EDIT_MAP_GROUP_SUBMIT', 'state_edit_map_group_submit');
define('ADD_MAP_GROUP_SUBMIT', 'state_add_map_group_submit');
define('DELETE_MAP_GROUP', 'state_delete_map_group');


// pages to load
define('PAGE_MANAGE_MAP_GROUPS', 'MapGroups/manage_map_groups.inc.php');
define('PAGE_EDIT_MAP_GROUP', 'MapGroups/edit_map_group.inc.php');

// operations - map groups
function add_update_map_group () {
  
  if ( $_POST['submit'] ) {
    $new_mg = new GMap_MapGroup ();
    $new_mg->id                         = $_REQUEST['map_group_id'];
    $new_mg->title                      = $_REQUEST['title'];
    $new_mg->default_width              = $_REQUEST['default_width'];
    $new_mg->default_height             = $_REQUEST['default_height'];
    $new_mg->default_zoom               = $_REQUEST['default_zoom'];
    $new_mg->default_use_ctl_directions = $_REQUEST['default_use_ctl_directions'];
    $new_mg->default_use_ctl_zoom       = $_REQUEST['default_use_ctl_zoom'];
    $new_mg->icon_path                  = $_REQUEST['icon_path'];
    $new_mg->save();

    // do the marker types
    $mg_marker_type_keys = preg_grep( '/^mt_id_\d+$/', array_keys($_REQUEST));
    $mg_marker_types = array();
    if ( is_array($mg_marker_type_keys) && count($mg_marker_type_keys) > 0 ) {
      foreach ($mg_marker_type_keys as $key) {
        $id = substr_replace($key, '', 0, strlen('mt_id_') );
        error_log("Key: $key => id: $id", 0);
        if ( $_REQUEST[$key] == 'on' ) $mg_marker_types[] = $id;
      }
    }
    if ( count($mg_marker_types > 0) ) GMap_Factory::saveMapGroupMarkerTypes($new_mg->id, $mg_marker_types);

  }
  
}
function _findMarkerTypeIds ($e) {
  
}

function delete_map_group () {
  $mg_id = $_GET['map_group_id'];
  if ( !empty($mg_id) ) {
    $del_mg = GMap_Factory::getMapGroup($mg_id);
    if ( !empty($del_mg->id) ) $del_mg->delete();
  } 
}


/* 
 * route the request to the right state-page
 */
$status_message = FALSE;
$page_section_title = 'Manage Map Groups';
$load_page = PAGE_MANAGE_MAP_GROUPS;
switch ($_REQUEST['state']) {
  
 case ADD_MAP_GROUP_SUBMIT:
   add_update_map_group();
   $status_message = 'Map Group Added';
 case EDIT_MAP_GROUP_SUBMIT:
   add_update_map_group();
   $status_message = 'Map Group Updated';
   break;
 case EDIT_MAP_GROUP:
   $page_section_title = 'Edit Map Group';
   $load_page = PAGE_EDIT_MAP_GROUP;
   break;
 case DELETE_MAP_GROUP:
   delete_map_group();
   $status_message = 'Map Group Deleted';
   break;
   
 case MANAGE_MAP_GROUPS:
 default:
   $page_section_title = 'Manage Map Groups';
   $load_page = PAGE_MANAGE_MAP_GROUPS;
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

<?php

include_once(dirname(__FILE__).'/../classes/GMap_Factory.inc.php');
include_once(dirname(__FILE__).'/../util/icons.inc.php');

// icons
define('MANAGE_ICONS',     'state_manage_icons');
define('ADD_ICON_SUBMIT',  'state_add_icon_submit');
define('EDIT_ICON',        'state_edit_icon');
define('EDIT_ICON_SUBMIT', 'state_edit_icon_submit');
define('EDIT_ICON_CANCEL', 'state_edit_icon_cancel');
define('DELETE_ICON',      'state_delete_icon');

// marker_types
define('ADD_MARKER_TYPE_SUBMIT',  'state_add_marker_type_submit');
define('EDIT_MARKER_TYPE',        'state_edit_marker_type');
define('EDIT_MARKER_TYPE_SUBMIT', 'state_edit_marker_type_submit');
define('DELETE_MARKER_TYPE',      'state_delete_marker_type');

define('PAGE_MANAGE_ICONS',     'Icons/manage_icons_and_marker_types.inc.php');
define('PAGE_EDIT_ICON',        'Icons/edit_icon.inc.php');
define('PAGE_EDIT_MARKER_TYPE', 'Icons/edit_marker_type.inc.php');


// operations - icons
function add_update_icon () {
  // check for safety

  $image_path = $_REQUEST['image_path'];
  $shadow_path = $_REQUEST['shadow_path'];
  $reg = '/\.\./';
  if ( preg_match($reg,$image_path) == 1 && preg_match($reg,$shadow_path) == 1 ) return FALSE;

  if ( $_POST['submit'] ) {
    $new_icon = new GMap_Icon ();
    $new_icon->id                   = $_REQUEST['icon_id'];
    $new_icon->title                = $_REQUEST['title'];
    $new_icon->image_path           = $_REQUEST['image_path'];
    $new_icon->image_width          = $_REQUEST['image_width'];
    $new_icon->image_height         = $_REQUEST['image_height'];
    $new_icon->shadow_path          = $_REQUEST['shadow_path'];
    $new_icon->shadow_width         = $_REQUEST['shadow_width'];
    $new_icon->shadow_height        = $_REQUEST['shadow_height'];
    $new_icon->anchor_point         = $_REQUEST['anchor_point_ol'].','.$_REQUEST['anchor_point_dt'];
    $new_icon->info_anchor_point    = $_REQUEST['info_anchor_point_ol'].','.$_REQUEST['info_anchor_point_dt'];
    $new_icon->save();
  }
}
function delete_icon () {
  $i_id = $_REQUEST['icon_id'];
  if ( !empty($i_id) ) {
    // do a check to see if this icon is being used by a marker type:
    global $wpdb;
    $i_id = $wpdb->escape($i_id);
    $del_i = GMap_Factory::getIcon($i_id);
    if ( !empty($del_i->id) ) {
      $in_use_sql = 'SELECT count(*) FROM ' . GM_TABLE_MARKER_TYPES . " WHERE sm_icon_id = '$i_id' OR lg_icon_id = '$i_id'";
      $in_use = $wpdb->get_var($in_use_sql);
      // returns: FALSE for sql error, status message if there was an error, or TRUE if it deleted
      if ( $in_use === FALSE ) {
        error_log("Could not check to see if icon in use: " . mysql_error(), 0);
        return FALSE;
      } elseif ( $in_use > 0 ) {
        return 'Can not delete Icon '.$del_i->title.' (id:'.$del_i->id.'): In use by '.$in_use.' Marker Types';
      } else {
        $del_i->delete();
        return TRUE;
      }
    }
    return 'Could not load Icon (id:'.$i_id.')';
  }
  return 'No ID provided to delete Icon';
}

// operations - marker types
function add_update_marker_type () {
  if ( $_POST['submit'] ) {
    $new_mt = new GMap_MarkerType ();
    $new_mt->id                         = $_REQUEST['marker_type_id'];
    $new_mt->title                      = $_REQUEST['title'];
    $new_mt->sm_icon_id                 = $_REQUEST['sm_icon_id'];
    $new_mt->lg_icon_id                 = $_REQUEST['lg_icon_id'];
    $new_mt->save();
  }
}

function delete_marker_type () {
  $mt_id = $_REQUEST['marker_type_id'];
  if ( !empty($mt_id) ) {
    $del_mt = GMap_Factory::getMarkerType($mt_id);
    if ( !empty($del_mt->id) ) $del_mt->delete();
  }   
}

/* 
 * route the request to the right state-page
 */
$page_section_title = 'Manage Icons & Marker Types';
$status_message = FALSE;
$load_page = PAGE_MANAGE_ICONS;
switch ($_REQUEST['state']) {
 case EDIT_ICON:
   $load_page = PAGE_EDIT_ICON;
   break;
 case ADD_ICON_SUBMIT:
   add_update_icon();
   $status_message = 'Icon Added';
   break;
 case EDIT_ICON_SUBMIT:
   add_update_icon();
   $status_message = 'Icon Updated';
   break;
 case DELETE_ICON:
   $retval = delete_icon();
   switch (TRUE) {
   case ($retval === FALSE):
     $status_message = 'Error deleting icon (see error log)';
     break;
   case ($retval === TRUE):
     $status_message = 'Icon Deleted';
     break;
   default:
     $status_message = $retval;
   }
   break;

 case EDIT_MARKER_TYPE:
   $page_section_title = 'Edit Marker Type';
   $load_page = PAGE_EDIT_MARKER_TYPE;
   break;

 case ADD_MARKER_TYPE_SUBMIT:
   add_update_marker_type();
   $status_message = 'Marker Type Added';
   break;
 case EDIT_MARKER_TYPE_SUBMIT:
   add_update_marker_type();
   $status_message = 'Marker Type Updated';
   break;
 case DELETE_MARKER_TYPE:
   delete_marker_type();
   $status_message = 'Marker Type Deleted';
   break;

 case MANAGE_ICONS:
 default:
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
.helpItems {
 margin: 14px 0px 0px 0px;
 background-color: #EEE;
 border 1px solid #CCC;
}
div.helpItems div {
 padding: 6px 0px;
}
div.helpItems div .bold {
  font-weight: bold;
}
</style>

<div class="wrap">
<h2><?php echo $page_section_title ?></h2>
<?php if ( $status_message !== FALSE  ) { ?>
<fieldset><div id="message" class="update fade"><strong><?php echo $status_message ?></strong></div></fieldset>
<?php } ?>
<?php include($load_page); ?>
</div>
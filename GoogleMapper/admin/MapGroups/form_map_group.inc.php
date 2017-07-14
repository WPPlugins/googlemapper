<?php
/*
 * form_map_group.inc.php - add/edit map groups
 * 
 * - requires $map_group_id or 'map_group_id' param in request
 *
 */
$map_group_id = $_REQUEST['map_group_id'];
$form_type = ( $_REQUEST['state'] == EDIT_MAP_GROUP ) ? 'edit' : 'add';

$mg_attributes = array(
                       'map_group_id', 'title',
                       'default_width', 'default_height', 'default_zoom',
                       'default_use_ctl_directions', 'default_use_ctl_zoom'
                       );
$mg_values = array();
$mg_marker_types = array();
foreach ($mg_attributes as $att_name ) {
  $mg_values[$att_name] = FALSE;
}

if ( !empty($map_group_id) && $form_type == 'edit' ) {
  // get the map group
  $map_group = GMap_Factory::getMapGroup($map_group_id);
  $mg_values['map_group_id']               = $map_group->id;
  $mg_values['title']                      = $map_group->title;
  $mg_values['default_width']              = $map_group->default_width;
  $mg_values['default_height']             = $map_group->default_height;
  $mg_values['default_zoom']               = $map_group->default_zoom;
  $mg_values['default_use_ctl_directions'] = $map_group->default_use_ctl_directions;
  $mg_values['default_use_ctl_zoom']       = $map_group->default_use_ctl_zoom;

  // get the marker types for this map group
  $mg_marker_types = GMap_Factory::getMarkerTypesByMapGroup($map_group->id);
  // get the icons for rendering
}

// need all the marker types to build out the marker type option menu
$mg_all_marker_types = GMap_Factory::getAllMarkerTypes();
$mg_all_icons        = GMap_Factory::getAllIcons();

// takes array of all marker types, array of this map groups marker types, and array of all icons.
// all arrays of objects are keyed by their id's
function build_mt_table ($mts, $mg_mts, $mg_is) {
  $icon_path = get_option(GM_OPTION_ICON_PATH);
  $checkboxes = array();


  if (is_array($mts) && count($mts) > 0) {
    foreach ( $mts as $mtid => $mt ) {
      $cb = '<input type="checkbox" name="mt_id_' . $mtid . '"';
      $cb .= ( array_key_exists($mtid, $mg_mts) ) ? ' checked="checked"' : '';
      $cb .= '> ';
      $cb .= gm_admin_render_marker_type($mt, $mg_is);
      $cb .= $mt->title;
      $checkboxes[] = $cb;
    }
  }
  // make a table with rows of 3
  $num_rows = intval(count($mts) / 3);
  if (count($mts) % 3) $num_rows++;
  $table = "<table width=\"100%\">\n";
  for ($i = 0; $i < $num_rows; $i++) {
    $row = "  <tr style=\"vertical-align:bottom\">\n";
    for ($j = 0; $j < 3; $j++) {
      $row .= "    <td>".array_shift($checkboxes)."</td>\n";
    }
    $row .= "  </tr>\n";
    $table .= $row;
  }
  $table .= "</table>";
  return $table;
}

if ( $form_type != 'edit' || ( !empty($map_group) && ($map_group !== FALSE) ) ) {
?>

<form name="map_group" action="<?php $page_url ?>" method="POST">
<input type="hidden" name="map_group_id" value="<?php echo $mg_values['map_group_id'] ?>" />
<input type="hidden" name="state" value="<?php echo ($form_type == 'edit') ? EDIT_MAP_GROUP_SUBMIT : ADD_MAP_GROUP_SUBMIT ?>" />
<table width="100%">

  <!-- title -->
  <tr>
    <td>Title</td>
    <td><input type="text" size="30" maxlength="255" name="title" value="<?php echo $mg_values['title']?>" /></td>
  </tr>

  <tr>
    <td>Default Map Width</td>
    <td><input type="text" size="4" maxlength="4" name="default_width" value="<?php echo $mg_values['default_width']?>" /></td>
  </tr>

  <tr>
    <td>Default Map Height</td>
    <td><input type="text" size="4" maxlength="4" name="default_height" value="<?php echo $mg_values['default_height']?>" /></td>
  </tr>

  <tr>
    <td>Default Zoom Level (0-17)</td>
    <td>
      <select name="default_zoom">
<?php for ($i = 0; $i < 18; $i++) {
  $opt = '        <option value="'.$i.'"';
  if ( $mg_values['default_zoom'] == $i ) $opt.= ' selected="selected" ';
  $opt .= ">$i</option>\n";
  echo $opt;
} ?>
  </tr>

  <tr>
    <td>Use Zoom/Directional Controls</td>
    <td><input type="checkbox" name="default_use_ctl_directions" <?php echo ( $mg_values['default_use_ctl_directions'] == 'on' ) ? 'CHECKED' : '' ?> /></td>
  </tr>

  <tr>
    <td>Use Map-Type Controls</td>
    <td><input type="checkbox" name="default_use_ctl_zoom" <?php echo ( $mg_values['default_use_ctl_zoom'] == 'on' ) ? 'CHECKED' : '' ?> /></td>
  </tr>

  <!-- marker types -->
  <tr><td colspan="2"><div style="height:16px"></div></td></tr>
  <tr>
    <td colspan="2" style="border-top: solid 1px #CCC"><strong>Marker Types:</strong></td>
  </tr>
  <tr>
    <td colspan="2" style="border-bottom: solid 1px #CCC">
      <?php echo build_mt_table($mg_all_marker_types, $mg_marker_types, $mg_all_icons) ?>
    </td>
  </tr>

  <tr>
    <td colspan="2"><div class="submit">
      <input type="submit" name="submit" value="<?php echo ($form_type == 'edit') ? 'Update' : 'Add' ?> Map Group" />
      <input type="reset"  name="cancel" value="Cancel" <?php if ($form_type == 'edit') echo ' onclick="window.history.back()"' ?>/>
    </td>
  </tr>

</table></form>
<?php
      } else {
?>
<div id="message" class="update fade">No Map Group to Edit!</div>
<?php
   }
?>

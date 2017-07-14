<?php
/*
 * form_map.inc.php - form to add/edit maps
 * 
 * - requires the constants defined in admin/Maps.inc.php
 * - requires classes/GMap_Factory.inc.php (included from admin/Maps.inc.php)
 * - populate $mm_groups from GMap_Factory::getAllGroups() to populate the map_group dropdown
 * 
 */

$form_type = ( $_REQUEST['state'] == EDIT_MAP ) ? 'edit' : 'add';

$map_attributes = array(
                       'map_id', 'map_group_id', 'title',
                       'center_glat', 'center_glng',
                       'zoom', 'width', 'height', 'switch_zoom_level'
                       );
$map_values = array();
foreach ($map_attributes as $att_name ) {
  $map_values[$att_name] = FALSE;
}

if ( $form_type == 'edit' ) {
  $map = GMap_Factory::getMap( $_REQUEST['map_id'] );
  $map_values['map_id']                     = $map->id;
  $map_values['map_group_id']               = $map->map_group_id;
  $map_values['title']                      = $map->title;
  $map_values['center_glat']                = $map->center_glat;
  $map_values['center_glng']                = $map->center_glng;
  $map_values['zoom']                       = $map->zoom;
  $map_values['width']                      = $map->width;
  $map_values['height']                     = $map->height;
  $map_values['switch_zoom_level']          = $map->switch_zoom_level;
}

$map_markers = array();
if ($map) {
  $map_markers = GMap_Factory::getMarkersByMap( $map->id );
}
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo get_option(GM_OPTION_API_KEY) ?>" type="text/javascript"></script>
<script language="javascript">
// <![[CDATA
var geocoder = new GClientGeocoder();
function geoLookup() {
  var address = document.getElementById('geocode_lookup').value;
  var lat_field = document.getElementById('center_glat');
  var lng_field = document.getElementById('center_glng');
  var set_lat = function(lat) { lat_field.disabled = false; lat_field.value = lat; };
  var set_lng = function(lng) { lng_field.disabled = false; lng_field.value = lng; };
  var geo_callback = function(point) {
    if (!point) {
      alert(address + " not found");
    } else {
      set_lat( point.lat() );
      set_lng( point.lng() );
    }
  };

  if (address != '') {
    lat_field.disabled = true;
    lng_field.disabled = true;
    geocoder.getLatLng( address, geo_callback );
  }
  return true;
}

// ]]>
</script>

<form name="map" id="mapForm" action="<?php $page_url ?>" method="POST">
<input type="hidden" name="map_id" value="<?php echo $map_values['map_id'] ?>" />
<input type="hidden" name="state" value="<?php echo ($form_type == 'edit') ? EDIT_MAP_SUBMIT : ADD_MAP_SUBMIT ?>" />
<table width="100%">

  <!-- title -->
  <tr>
    <td>Title</td>
    <td><input type="text" size="30" maxlength="255" name="title" value="<?php echo $map_values['title']?>" /></td>
  </tr>
  <!-- map group -->
  <tr>
    <td>Map Group</td>
    <td>
      <select name="map_group_id">
      <?php
if ( is_array($mm_groups) && count($mm_groups) > 0 ) {
  foreach ($mm_groups as $mg_id => $mg) {
    $opt = '        <option value=' . $mg_id;
    if ( $map_values['map_id'] == $mg_id ) $opt .= ' selected="selected"';
    $opt .= '>' . $mg->title . '</option>';
    echo $opt;
  } 
}
?>
      </select>
    </td>
  </tr>
 
  <!-- center glat, glng -->
  <tr>
    <td>Center Lat and Long</td>
    <td>
      Lat: <input type="text" size="13" maxlength="13" id="center_glat" name="center_glat" value="<?php echo $map_values['center_glat'] ?>" />
      Long: <input type="text" size="13" maxlength="13" name="center_glng" id="center_glng" value="<?php echo $map_values['center_glng'] ?>" />
    </td>
  </tr>
  <!-- look up geocode -->
  <tr>
    <td></td>
    <td>
      <input type="text" size="40" maxlength="255" name="geocode_lookup" id="geocode_lookup" value=""/>
      <input type="button" name="geocode_button" value="Look Up Address" onClick="geoLookup()" />
    </td>
  </tr>

  <!-- width, height -->
  <tr>
    <td>Map Width and Height</td>
    <td>
      <input type="text" size="4" maxlength="4" name="width" value="<?php echo $map_values['width'] ?>" />w x
      <input type="text" size="4" maxlength="4" name="height" value="<?php echo $map_values['height'] ?>" />h
    </td>
  </tr>

  <tr>
    <td>Zoom Level (0-17)</td>
    <td>
      <select name="zoom">
<?php for ($i = 0; $i < 18; $i++) {
  $opt = '        <option value="'.$i.'"';
  if ( $map_values['zoom'] == $i ) $opt.= ' selected="selected" ';
  $opt .= ">$i</option>\n";
  echo $opt;
} ?>
      </select>
    </td>
  </tr>

  <tr>
    <td>Switch to large icons at Zoom Level (0-17):</td>
    <td>
      <select name="switch_zoom_level">
        <option value="">--</option>
<?php for ($i = 0; $i < 18; $i++) {
  $opt = '        <option value="'.$i.'"';
  if ( $map_values['switch_zoom_level'] == $i ) $opt.= ' selected="selected" ';
  $opt .= ">$i</option>\n";
  echo $opt;
} ?>
      </select>
    </td>
  </tr>

  <tr>
    <td colspan="2"><div class="submit">
      <input type="submit" name="submit" value="<?php echo ($form_type == 'edit') ? 'Update' : 'Add' ?> Map" />
      <input type="reset"  name="cancel" value="Cancel" <?php if ($form_type == 'edit') echo ' onclick="window.history.back()"' ?>/>
    </td>
  </tr>

</table></form>

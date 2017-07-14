<?php
/*
 * form_marker.inc.php - form to add/edit map markers
 * 
 * - requires the constants defined in admin/Maps.inc.php
 * - requires classes/GMap_Factory.inc.php (included from admin/Maps.inc.php)
 * - to populate the marker type dropdown: $map_id -> $map->map_group_id -> $map_group, getMarkerTypesByMapGroup
 * 
 */

// 0. i do declare
$marker_id = FALSE;
$marker    = FALSE;
$map_id    = FALSE;
$map       = FALSE;
$map_group = FALSE;
$map_group_marker_types = FALSE;

// get the marker
if ( !$marker_id ) $marker_id = $_REQUEST['marker_id'];
if ( $marker_id)   $marker = GMap_Factory::getMarker( $marker_id );

// try getting map id from wherever, 
// hopefully that'll get us the map, map group, and map group marker types
$map_id = ( $marker ) ? $marker->map_id : $_REQUEST['map_id'];
if ($map_id) {
  $map                                    = GMap_Factory::getMap($map_id);
  if ($map)       $map_group              = GMap_Factory::getMapGroup($map->map_group_id);
  if ($map_group) $map_group_marker_types = GMap_Factory::getMarkerTypesByMapGroup( $map_group->id );
}

$form_type = ( $_REQUEST['state'] == EDIT_MAP_MARKER ) ? 'edit' : 'add';

$marker_attributes = array(
                           'marker_id', 'marker_type_id', 'map_id', 'title',
                           'glat', 'glng', 'info_window'
                           );
$marker_values = array();
foreach ($marker_attributes as $att_name ) {
  $marker_values[$att_name] = FALSE;
}

if ( $form_type == 'edit' && $marker ) {
  $marker_values['marker_id']               = $marker->id;
  $marker_values['marker_type_id']          = $marker->marker_type_id;
  $marker_values['map_id']                  = $marker->map_id;
  $marker_values['title']                   = $marker->title;
  $marker_values['glat']                    = $marker->glat;
  $marker_values['glng']                    = $marker->glng;
  $marker_values['info_window']             = $marker->info_window;
}

if ( count($map_group_marker_types) > 0 ) {
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo get_option(GM_OPTION_API_KEY) ?>" type="text/javascript"></script>
<script language="javascript">
// <![[CDATA
var geocoder = new GClientGeocoder();
function geoLookup() {
  var address = document.getElementById('geocode_lookup').value;
  var lat_field = document.getElementById('glat');
  var lng_field = document.getElementById('glng');
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

<form name="map" action="<?php $page_url ?>" method="POST">
<input type="hidden" name="map_id"    value="<?php echo $map_id ?>" />
<input type="hidden" name="marker_id" value="<?php echo $marker_values['marker_id'] ?>" />
<input type="hidden" name="state"     value="<?php echo ($form_type == 'edit') ? EDIT_MAP_MARKER_SUBMIT : ADD_MAP_MARKER_SUBMIT ?>" />
<table width="100%">

  <!-- title -->
  <tr>
    <td>Title</td>
    <td><input type="text" size="30" maxlength="255" name="title" value="<?php echo $marker_values['title']?>" /></td>
  </tr>
  <!-- marker type -->
  <tr>
    <td>Marker Type</td>
    <td>
      <select name="marker_type_id">
<?php
    if ( is_array($map_group_marker_types) && count($map_group_marker_types) > 0 ) {
      foreach ($map_group_marker_types as $mt_id => $mt) {
        $opt = '        <option value="' . $mt_id . '"';
        if ( $marker_values['marker_type_id'] == $mt_id ) $opt .= ' selected="selected"';
        $opt .= '>' . $mt->title . '</option>';
        echo $opt;
      }
    }
  ?>
      </select>
    </td>
  </tr>
 
  <!-- glat, glng -->
  <tr>
    <td>Lat and Long</td>
    <td>
      Lat:  <input type="text" size="13" maxlength="13" name="glat" id="glat" value="<?php echo $marker_values['glat'] ?>" />
      Long: <input type="text" size="13" maxlength="13" name="glng" id="glng" value="<?php echo $marker_values['glng'] ?>" />
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


  <tr>
    <td>Info Window</td>
    <td><textarea rows="6" cols="30" name="info_window" wrap="soft"><?php echo $marker_values['info_window'] ?></textarea></td>
  </tr>

  <tr>
    <td colspan="2"><div class="submit">
      <input type="submit" name="submit" value="<?php echo ($form_type == 'edit') ? 'Update' : 'Add' ?> Marker" />
      <input type="reset"  name="cancel" value="Cancel"<?php if ($form_type == 'edit') echo ' onclick="window.history.back()"'?>/>
    </td>
  </tr>

</table></form>
<?php
     } else {
?>
 <div class="update fade" id="message">No Marker Types have been defined for this Map Group! (Go to Map Groups -> Marker Types)</div>
 <div><input type="reset"  name="return" value="Return" onclick="window.history.back()"/></div>
<?php
     }
?>